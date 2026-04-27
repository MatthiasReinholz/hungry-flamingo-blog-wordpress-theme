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

if ! grep -Eq "^## \\[${version}\\]( - |$)" CHANGELOG.md; then
	tmp="$(mktemp)"
	release_date="${HFB_RELEASE_DATE:-$(date +%F)}"
	{
		awk '
			/^## \[Unreleased\]/ {
				print
				print ""
				printf "## [%s] - %s\n\n", version, release_date
				print "### Changed"
				print ""
				print "- Update release metadata and package artifacts."
				print ""
				next
			}
			{ print }
		' version="$version" release_date="$release_date" CHANGELOG.md
	} > "$tmp"
	mv "$tmp" CHANGELOG.md
fi

echo "Updated release metadata to $version"
