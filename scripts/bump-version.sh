#!/usr/bin/env bash

set -euo pipefail

version="${1:-}"

if ! [[ "$version" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
	echo "Usage: $0 x.y.z" >&2
	exit 1
fi

perl -0pi -e "s/^Version: .*/Version: $version/m" style.css
perl -0pi -e "s/^Stable tag: .*/Stable tag: $version/m" readme.txt
npm version --no-git-tag-version --allow-same-version "$version" >/dev/null

if ! grep -Eq "^## ${version}( - |$)" CHANGELOG.md; then
	tmp="$(mktemp)"
	{
		sed -n '1,/^## /p' CHANGELOG.md | sed '$d'
		printf '\n## %s - Unreleased\n\n- Update release metadata and package artifacts.\n' "$version"
		sed -n '/^## /,$p' CHANGELOG.md
	} > "$tmp"
	mv "$tmp" CHANGELOG.md
fi

echo "Updated release metadata to $version"
