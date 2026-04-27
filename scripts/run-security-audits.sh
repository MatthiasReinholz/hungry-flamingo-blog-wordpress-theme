#!/usr/bin/env bash

set -euo pipefail

run_with_retries() {
	local label="$1"
	shift

	local attempt=1
	local max_attempts="${HFB_AUDIT_MAX_ATTEMPTS:-3}"
	local delay="${HFB_AUDIT_RETRY_DELAY:-15}"

	while true; do
		if "$@"; then
			return 0
		fi

		local status="$?"
		if [ "$attempt" -ge "$max_attempts" ]; then
			echo "${label} failed after ${attempt} attempt(s)." >&2
			return "$status"
		fi

		echo "::warning::${label} failed on attempt ${attempt}; retrying in ${delay}s."
		sleep "$delay"
		attempt=$(( attempt + 1 ))
	done
}

run_with_retries "Composer audit" composer audit
run_with_retries "npm audit" npm audit --audit-level=high
