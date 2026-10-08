import assert from 'node:assert/strict';
import { resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { advisory, run, verifyRemediation } from './braces-remediation.mjs';

const severities = ['info', 'low', 'moderate', 'high', 'critical'];
const record = value => value !== null && typeof value === 'object' && !Array.isArray(value);

export function classifyAudit(raw, status, verifiedInstances) {
	assert(status === 0 || status === 1, 'npm audit execution failed');
	const report = JSON.parse(raw);
	assert(report.auditReportVersion === 2 && !report.error && record(report.vulnerabilities), 'Unsupported npm audit report');
	const counts = Object.fromEntries(severities.map(severity => [severity, 0]));
	const entries = report.vulnerabilities;
	for (const [name, item] of Object.entries(entries)) {
		assert(record(item) && item.name === name && severities.includes(item.severity), 'Invalid vulnerability record');
		assert(Array.isArray(item.via) && item.via.length > 0 && Array.isArray(item.effects), 'Missing dependency graph');
		assert(Array.isArray(item.nodes) && item.nodes.length > 0 && item.nodes.every(path => typeof path === 'string'), 'Missing installed dependency paths');
		for (const via of item.via) {
			if (typeof via === 'string') {
				assert(entries[via]?.effects.includes(name), 'Incomplete dependency advisory edge');
			} else {
				assert(record(via) && typeof via.url === 'string' && typeof via.name === 'string' && severities.includes(via.severity), 'Invalid advisory');
			}
		}
		for (const effect of item.effects) assert(entries[effect]?.via.includes(name), 'Incomplete reverse advisory edge');
		const sourceSeverity = Math.max(...item.via.map(via => severities.indexOf(typeof via === 'string' ? entries[via].severity : via.severity)));
		assert(severities.indexOf(item.severity) >= sourceSeverity, 'Dependency severity hides a source advisory');
		counts[item.severity]++;
	}
	const total = Object.keys(entries).length;
	assert(record(report.metadata?.vulnerabilities), 'Missing advisory counts');
	assert.deepEqual(report.metadata.vulnerabilities, { ...counts, total }, 'Advisory count mismatch');
	assert.equal(status, total ? 1 : 0, 'Audit exit status contradicts findings');

	const cache = new Map();
	function qualified(name, visiting = new Set()) {
		if (cache.has(name)) return cache.get(name);
		assert(!visiting.has(name), 'Cyclic advisory graph');
		const item = entries[name];
		const next = new Set([...visiting, name]);
		// Evaluate every edge, even after an unrelated finding, to reject malformed graphs.
		const results = item.via.map(via => {
			if (typeof via === 'string') return qualified(via, next);
			if (via.url !== `https://github.com/advisories/${advisory}`) return false;
			assert(name === 'braces' && via.name === 'braces' && via.dependency === 'braces' && via.range === '<=3.0.3' && via.severity === 'high' && item.severity === 'high', 'Reviewed advisory contract changed');
			assert(Array.isArray(verifiedInstances) && verifiedInstances.length > 0 && item.nodes.every(path => verifiedInstances.includes(path)), 'Unverified braces instance');
			return true;
		});
		const result = results.every(Boolean);
		cache.set(name, result);
		return result;
	}
	const locallyRemediated = [];
	const blocked = [];
	for (const [name, item] of Object.entries(entries)) {
		if (qualified(name)) locallyRemediated.push(name);
		else if (severities.indexOf(item.severity) >= 3) blocked.push(name);
	}
	return { locallyRemediated, blocked };
}

if (process.argv[1] && resolve(process.argv[1]) === fileURLToPath(import.meta.url)) {
	try {
		const root = resolve(fileURLToPath(new URL('..', import.meta.url)));
		const result = run('npm', ['audit', '--json', '--audit-level=info', '--registry=https://registry.npmjs.org'], root);
		process.stdout.write(result.stdout);
		process.stderr.write(result.stderr);
		const instances = verifyRemediation(root);
		const decision = classifyAudit(result.stdout, result.status, instances);
		console.log(JSON.stringify({ advisory, instances, ...decision }, null, 2));
		assert.equal(decision.blocked.length, 0, 'Unremediated high-or-critical dependencies');
	} catch (error) {
		console.error(`npm audit qualification failed: ${error.message}`);
		process.exitCode = 1;
	}
}
