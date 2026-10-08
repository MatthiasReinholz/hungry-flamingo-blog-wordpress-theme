import assert from 'node:assert/strict';
import { isDeepStrictEqual } from 'node:util';
import { createHash } from 'node:crypto';
import { lstatSync, readFileSync, readdirSync, realpathSync, existsSync } from 'node:fs';
import nativePath, { basename, join, resolve, isAbsolute } from 'node:path';
import { spawnSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';

export const advisory = 'GHSA-vfj7-8cjw-p6xm';
const hash = path => createHash('sha256').update(readFileSync(path)).digest('hex');
const readJson = path => JSON.parse(readFileSync(path, 'utf8'));
export function within(root, path, implementation = nativePath) {
	const part = implementation.relative(root, path);
	return part !== '..' && !part.startsWith(`..${implementation.sep}`) && !implementation.isAbsolute(part);
}
export function npmRelative(root, path, implementation = nativePath) {
	assert(within(root, path, implementation), 'Relative npm path escapes root');
	return implementation.relative(root, path).split(implementation.sep).join('/');
}

export function isMainModule(moduleURL, entry = process.argv[1]) {
	return Boolean(entry) && existsSync(entry)
		&& nativePath.relative(realpathSync(resolve(entry)), realpathSync(fileURLToPath(moduleURL))) === '';
}

export function run(command, args, cwd) {
	const result = spawnSync(command, args, { cwd, encoding: 'utf8', timeout: 120000, maxBuffer: 32 * 1024 * 1024 });
	assert(!result.error && !result.signal, `${command} did not complete`);
	return result;
}

function fileHashes(root) {
	const result = {};
	function visit(dir) {
		for (const entry of readdirSync(dir, { withFileTypes: true })) {
			const path = join(dir, entry.name);
			assert(!entry.isSymbolicLink(), 'Unexpected symlink inside braces');
			if (entry.isDirectory()) visit(path);
			else {
				assert(entry.isFile(), 'Unexpected braces file type');
				result[npmRelative(root, path)] = hash(path);
			}
		}
	}
	visit(root);
	return result;
}

export function installedBraces(root) {
	const instances = [];
	function modules(dir) {
		if (!existsSync(dir)) return;
		assert(lstatSync(dir).isDirectory() && within(root, realpathSync(dir)), 'Invalid node_modules directory');
		function inspect(path) {
			assert(lstatSync(path).isDirectory(), `Linked dependencies are unsupported: ${path}`);
			const pkg = readJson(join(path, 'package.json'));
			if (basename(path) === 'braces' || pkg.name === 'braces') {
				assert.equal(pkg.name, 'braces', 'Unexpected braces package identity');
				assert.equal(pkg.version, '3.0.3', 'Unreviewed braces version');
				instances.push(path);
			}
			modules(join(path, 'node_modules'));
		}
		for (const entry of readdirSync(dir, { withFileTypes: true })) {
			if (entry.name.startsWith('.')) continue;
			const path = join(dir, entry.name);
			if (entry.name.startsWith('@')) {
				assert(lstatSync(path).isDirectory(), 'Invalid package scope');
				for (const name of readdirSync(path)) inspect(join(path, name));
			} else inspect(path);
		}
	}
	modules(join(root, 'node_modules'));
	assert(instances.length > 0, 'No installed braces instances');
	return instances.sort();
}

export function provenance(root) {
	const metadataPath = join(root, 'docs/braces-security-provenance.json');
	assert(lstatSync(metadataPath).isFile() && within(root, realpathSync(metadataPath)), 'Invalid provenance metadata');
	const metadata = readJson(metadataPath);
	assert.equal(metadata.schemaVersion, 1);
	assert.equal(metadata.advisory, advisory);
	assert.equal(metadata.upstreamVersion, '3.0.3');
	for (const [path, digest] of [
		['package-lock.json', metadata.lockfileSha256],
		[metadata.patchPath, metadata.patchSha256],
		[metadata.regressionScript, metadata.regressionSha256]
	]) {
		assert(typeof path === 'string' && !isAbsolute(path) && within(root, resolve(root, path)), 'Invalid provenance path');
		assert(lstatSync(join(root, path)).isFile() && within(root, realpathSync(join(root, path))), 'Invalid provenance file');
		assert.match(digest, /^[a-f0-9]{64}$/);
		assert.equal(hash(join(root, path)), digest, `Reviewed hash changed: ${path}`);
	}
	const lock = readJson(join(root, 'package-lock.json'));
	assert.equal(lock.lockfileVersion, 3, 'Unsupported npm lock format');
	assert(!readJson(join(root, 'package.json')).workspaces, 'Workspace installs require separate qualification');
	const instances = installedBraces(root);
	for (const path of instances) {
		const entry = lock.packages[npmRelative(root, path)];
		assert(entry && entry.version === '3.0.3' && entry.integrity === metadata.upstreamIntegrity && entry.resolved === metadata.upstreamTarball, 'Unreviewed braces lock entry');
	}
	return { metadata, instances };
}

export function verifyRemediation(root) {
	const { metadata, instances } = provenance(root);
	for (const path of instances) {
		assert.deepEqual(fileHashes(path), metadata.installedFiles, `Installed braces bytes differ: ${path}`);
		const result = run(process.execPath, [join(root, metadata.regressionScript), path], root);
		assert.equal(result.status, 0, `Braces regression failed: ${result.stderr}`);
	}
	return instances.map(path => npmRelative(root, path));
}

export function applyRemediation(root) {
	const { metadata, instances } = provenance(root);
	const pending = [];
	// Inspect every instance before the first write; drift is never overwritten.
	for (const path of instances) {
		const actual = fileHashes(path);
		if (isDeepStrictEqual(actual, metadata.installedFiles)) continue;
		assert.deepEqual(actual, metadata.pristineFiles, `Pristine braces bytes differ: ${path}`);
		const args = ['apply', `--directory=${npmRelative(root, path)}`, metadata.patchPath];
		const check = run('git', [...args, '--check'], root);
		assert.equal(check.status, 0, `Cannot apply reviewed patch: ${check.stderr}`);
		pending.push(args);
	}
	for (const args of pending) {
		const result = run('git', args, root);
		assert.equal(result.status, 0, `Patch failed: ${result.stderr}`);
	}
	return verifyRemediation(root);
}

if (isMainModule(import.meta.url)) {
	try {
		console.log('Verified local braces remediation:', applyRemediation(resolve(fileURLToPath(new URL('..', import.meta.url)))));
	} catch (error) {
		console.error(error.message);
		process.exitCode = 1;
	}
}
