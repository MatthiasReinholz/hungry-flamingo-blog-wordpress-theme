// @vitest-environment node
import { afterEach, beforeEach, describe, expect, it } from 'vitest';
import { mkdtempSync, readFileSync, rmSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join, resolve } from 'node:path';
import { spawnSync } from 'node:child_process';
import process from 'node:process';

let fixture;
beforeEach(() => {
	fixture = mkdtempSync(join(tmpdir(), 'theme-audit-'));
	const stub = `#!/usr/bin/env bash
set -eu
command_name="\${0##*/}"
printf '%s\\n' "$command_name" >> "$AUDIT_CALLS"
case "$AUDIT_CASE:$command_name" in
	composer-fail:composer) exit 23 ;;
	npm-fail:npm) exit 19 ;;
	recover:composer)
		if [ "$(wc -l < "$AUDIT_CALLS")" -lt 3 ]; then exit 23; fi
		;;
esac
`;
	for (const command of ['composer', 'npm']) {
		writeFileSync(join(fixture, command), stub, { mode: 0o755 });
	}
});
afterEach(() => rmSync(fixture, { recursive: true, force: true }));

function runAudit(testCase) {
	const calls = join(fixture, 'calls');
	const result = spawnSync('bash', [resolve('scripts/run-security-audits.sh')], {
		encoding: 'utf8',
		env: {
			...process.env,
			PATH: `${fixture}:${process.env.PATH}`,
			AUDIT_CASE: testCase,
			AUDIT_CALLS: calls,
			HFB_AUDIT_MAX_ATTEMPTS: '3',
			HFB_AUDIT_RETRY_DELAY: '0'
		}
	});
	return { ...result, calls: readFileSync(calls, 'utf8').trim().split('\n') };
}

describe('security audit process exit status', () => {
	it('stops after exhausted Composer failures and preserves the failure status', () => {
		const result = runAudit('composer-fail');
		expect(result.status).toBe(23);
		expect(result.calls).toEqual(['composer', 'composer', 'composer']);
	});

	it('preserves npm audit failure after a successful Composer audit', () => {
		const result = runAudit('npm-fail');
		expect(result.status).toBe(19);
		expect(result.calls).toEqual(['composer', 'npm', 'npm', 'npm']);
	});

	it('accepts a successful retry without skipping the remaining audit', () => {
		const result = runAudit('recover');
		expect(result.status).toBe(0);
		expect(result.calls).toEqual(['composer', 'composer', 'composer', 'npm']);
	});
});
