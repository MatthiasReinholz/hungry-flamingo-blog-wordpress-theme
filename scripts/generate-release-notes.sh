#!/usr/bin/env bash

set -euo pipefail

version="${1:-}"

if ! [[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
	echo "Usage: $0 x.y.z" >&2
	exit 1
fi

awk -v version="$version" '
	$0 ~ "^## \\[" version "\\]" || $0 ~ "^## " version "([ -]|$)" { in_section = 1; next }
	in_section && /^## / { exit }
	in_section && /^\[[^]]+\]:/ { exit }
	in_section { print }
' CHANGELOG.md | sed '/^[[:space:]]*$/d' > /tmp/hfb-release-notes-body.txt

printf '# Hungry Flamingo Blog %s\n\n' "$version"
if [ -s /tmp/hfb-release-notes-body.txt ]; then
	cat /tmp/hfb-release-notes-body.txt
else
	printf 'See CHANGELOG.md for release details.\n'
fi
