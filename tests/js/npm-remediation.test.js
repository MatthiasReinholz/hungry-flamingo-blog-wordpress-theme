// @vitest-environment node
import { afterEach, describe, expect, it } from 'vitest';
import { chmodSync, cpSync, mkdirSync, mkdtempSync, readFileSync, rmSync, symlinkSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import process from 'node:process';
import { delimiter, join, relative, resolve, posix, win32 } from 'node:path';
import { spawnSync } from 'node:child_process';
import { classifyAudit } from '../../scripts/audit-npm.mjs';
import { applyRemediation, verifyRemediation, run, within, npmRelative } from '../../scripts/braces-remediation.mjs';

const raw = readFileSync(new URL('../fixtures/npm-braces-audit.json', import.meta.url), 'utf8');
const instance = ['node_modules/braces'];
const fixtures = [];
afterEach(() => { for (const path of fixtures.splice(0)) rmSync(path, { recursive: true, force: true }); });

function fixture() {
	const root = mkdtempSync(join(tmpdir(), 'theme-braces-'));
	fixtures.push(root);
	for (const file of ['package.json', 'package-lock.json', 'patches/braces@3.0.3.patch', 'scripts/test-braces-depth.cjs', 'docs/braces-security-provenance.json']) {
		const target = join(root, file);
		mkdirSync(resolve(target, '..'), { recursive: true });
		cpSync(resolve(file), target);
	}
	mkdirSync(join(root, 'node_modules'));
	for (const name of ['braces', 'fill-range', 'to-regex-range', 'is-number']) cpSync(resolve('node_modules', name), join(root, 'node_modules', name), { recursive: true });
	return root;
}

function mutate(callback) {
	const report = JSON.parse(raw);
	callback(report);
	return JSON.stringify(report);
}

describe('npm advisory qualification', () => {
	it('qualifies the exact reviewed advisory and every dependent graph edge', () => {
		const result = classifyAudit(raw, 1, instance);
		expect(result.blocked).toEqual([]);
		expect(result.locallyRemediated).toHaveLength(7);
	});
	it('blocks an unrelated advisory even on an otherwise remediated package', () => {
		const changed = mutate(report => report.vulnerabilities.braces.via.push({ name: 'braces', severity: 'high', url: 'https://github.com/advisories/GHSA-unrelated' }));
		expect(classifyAudit(changed, 1, instance).blocked).toHaveLength(7);
	});
	it.each([
		['omitted finding', report => { delete report.vulnerabilities.braces; }],
		['lowered severity', report => { report.vulnerabilities.braces.severity = 'low'; report.metadata.vulnerabilities.high--; report.metadata.vulnerabilities.low++; }],
		['cycle', report => { report.vulnerabilities.braces.via.push('micromatch'); report.vulnerabilities.micromatch.effects.push('braces'); }],
		['wrong count', report => { report.metadata.vulnerabilities.high--; }],
		['unknown schema', report => { report.auditReportVersion = 3; }],
		['changed advisory', report => { report.vulnerabilities.braces.via[0].range = '*'; }],
		['unverified node', report => { report.vulnerabilities.braces.nodes.push('node_modules/other/node_modules/braces'); }],
		['missing edge', report => { report.vulnerabilities.braces.effects = []; }]
	])('rejects %s', (_name, change) => {
		expect(() => classifyAudit(mutate(change), 1, instance)).toThrow();
	});
	it('rejects audit process failure, contradictory success and unverified installs', () => {
		for (const status of [0, 2, null]) expect(() => classifyAudit(raw, status, instance)).toThrow();
		expect(() => classifyAudit(raw, 1, [])).toThrow();
	});
});

describe('maintained braces source patch', () => {
	it('reapplies to exact pristine bytes, is idempotent, and passes real attack and compatibility regressions', () => {
		const root = fixture();
		expect(run('git', ['apply', '--reverse', '--directory=node_modules/braces', 'patches/braces@3.0.3.patch'], root).status).toBe(0);
		expect(() => verifyRemediation(root)).toThrow();
		expect(applyRemediation(root)).toEqual(instance);
		expect(applyRemediation(root)).toEqual(instance);
	});
	it('removes inherited Git environment keys irrespective of casing', () => {
		const root = fixture();
		cpSync(resolve('scripts/braces-remediation.mjs'), join(root, 'scripts/braces-remediation.mjs'));
		const result = spawnSync(process.execPath, ['--input-type=module', '-e', `import { run } from './scripts/braces-remediation.mjs'; const result = run(process.execPath, ['-e', 'console.log(JSON.stringify(Object.keys(process.env).filter(key => /^git_/i.test(key))))'], process.cwd()); process.stdout.write(result.stdout); process.exit(result.status);`], {
			cwd: root, encoding: 'utf8', timeout: 120000,
			env: { ...process.env, Git_Dir: 'untrusted-directory', git_work_tree: 'untrusted-work-tree', GIT_INDEX_FILE: 'untrusted-index' }
		});
		expect(result.status, result.stderr).toBe(0);
		expect(JSON.parse(result.stdout)).toEqual([]);
	});
	it('patches a real nested linked-worktree project despite inherited Git context', () => {
		const baseline = fixture();
		expect(run('git', ['apply', '--reverse', '--directory=node_modules/braces', 'patches/braces@3.0.3.patch'], baseline).status).toBe(0);
		const repository = mkdtempSync(join(tmpdir(), 'theme-patch-repository-'));
		fixtures.push(repository);
		const worktree = join(repository, 'linked');
		for (const args of [
			['init'],
			['-c', 'user.name=Fixture', '-c', 'user.email=fixture@example.invalid', 'commit', '--allow-empty', '-m', 'Fixture'],
			['worktree', 'add', '--detach', worktree]
		]) expect(run('git', ['-c', 'commit.gpgsign=false', '-c', `core.hooksPath=${join(repository, 'no-hooks')}`, ...args], repository).status).toBe(0);
		const root = join(worktree, 'nested-theme');
		cpSync(baseline, root, { recursive: true });
		cpSync(resolve('scripts/braces-remediation.mjs'), join(root, 'scripts/braces-remediation.mjs'));
		expect(() => verifyRemediation(root)).toThrow();
		const result = spawnSync(process.execPath, [join(root, 'scripts/braces-remediation.mjs')], {
			cwd: root, encoding: 'utf8', timeout: 120000,
			env: { ...process.env, GIT_DIR: join(repository, 'missing-git-dir'), GIT_WORK_TREE: repository }
		});
		expect(result.status, result.stderr + result.stdout).toBe(0);
		expect(result.stdout).toContain('Verified local braces remediation');
		expect(verifyRemediation(root)).toEqual(instance);
		expect(applyRemediation(root)).toEqual(instance);
		expect(run('git', [`--work-tree=${root}`, 'apply', '--reverse', '--directory=node_modules/braces', 'patches/braces@3.0.3.patch'], root).status).toBe(0);
		expect(() => verifyRemediation(root)).toThrow();
		expect(applyRemediation(relative(process.cwd(), root))).toEqual(instance);
	});
	it('rejects modified installed code before writing anything', () => {
		const root = fixture();
		const target = join(root, 'node_modules/braces/index.js');
		writeFileSync(target, 'unreviewed source');
		expect(() => applyRemediation(root)).toThrow();
		expect(readFileSync(target, 'utf8')).toBe('unreviewed source');
	});
	it.each(['package-lock.json', 'patches/braces@3.0.3.patch', 'scripts/test-braces-depth.cjs'])('rejects drift in %s', file => {
		const root = fixture();
		writeFileSync(join(root, file), 'unreviewed source');
		expect(() => verifyRemediation(root)).toThrow();
	});
	it('rejects an additional unreviewed installed file', () => {
		const root = fixture();
		writeFileSync(join(root, 'node_modules/braces/extra.js'), 'extra');
		expect(() => verifyRemediation(root)).toThrow();
	});
});

describe('platform-aware remediation paths', () => {
	it('rejects POSIX, Windows parent, drive and UNC escapes and normalizes npm keys', () => {
		for (const [implementation, root, inside, outside] of [
			[posix, '/project', '/project/node_modules/braces', ['/', '/outside', '/project-escape/file', '/project/../outside']],
			[win32, 'C:/project', 'C:/project/node_modules/braces', ['C:/', 'C:/outside', 'C:/project-escape/file', 'C:/project/../outside', 'D:/project/file', '//server/share/file']],
			[win32, 'C:/Project', 'c:/project/node_modules/braces', ['c:/outside']],
			[win32, '//server/share/project', '//server/share/project/node_modules/braces', ['//server/share/outside', '//other/share/project/file', '//server/other/project/file']],
		]) {
			expect(within(root, inside, implementation)).toBe(true);
			expect(npmRelative(root, inside, implementation)).toBe('node_modules/braces');
			for (const path of outside) {
				expect(within(root, path, implementation)).toBe(false);
				expect(() => npmRelative(root, path, implementation)).toThrow(/escapes root/);
			}
		}
		expect(within('C:\\project', 'C:\\project\\..\\outside', win32)).toBe(false);
		expect(npmRelative('C:\\project', 'C:\\project\\node_modules\\braces\\lib\\parse.js', win32)).toBe('node_modules/braces/lib/parse.js');
	});
});

describe('physical CLI identity', () => {
	it('executes symlinked application and audit entrypoints', () => {
		const root = mkdtempSync(join(tmpdir(), 'theme-remediation-entry-'));
		fixtures.push(root);
		const npm = join(root, 'npm');
		writeFileSync(npm, '#!/usr/bin/env node\nprocess.stdout.write(' + JSON.stringify(raw) + '); process.exitCode = 1;\n');
		chmodSync(npm, 0o700);
		for (const script of ['braces-remediation.mjs', 'audit-npm.mjs']) {
			const alias = join(root, script);
			symlinkSync(resolve('scripts', script), alias);
			const result = spawnSync(process.execPath, [alias], { cwd: process.cwd(), encoding: 'utf8', timeout: 120000,
				env: { ...process.env, PATH: root + delimiter + process.env.PATH } });
			expect(result.status, result.stderr).toBe(0);
			expect(result.stdout).toMatch(script === 'audit-npm.mjs' ? /locallyRemediated/ : /Verified local braces remediation/);
		}
	});
});
