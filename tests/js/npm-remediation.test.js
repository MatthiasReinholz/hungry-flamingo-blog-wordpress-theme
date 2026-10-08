// @vitest-environment node
import { afterEach, describe, expect, it } from 'vitest';
import { cpSync, mkdirSync, mkdtempSync, readFileSync, rmSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join, resolve } from 'node:path';
import { classifyAudit } from '../../scripts/audit-npm.mjs';
import { applyRemediation, verifyRemediation, run } from '../../scripts/braces-remediation.mjs';

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
