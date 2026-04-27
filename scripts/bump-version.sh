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

if ! grep -Eq "^= ${version} =$" readme.txt; then
	tmp="$(mktemp)"
	{
		awk '
			/^== Changelog ==/ {
				print
				print ""
				printf "= %s =\n\n", version
				print "- Updated release metadata and package artifacts."
				print ""
				next
			}
			{ print }
		' version="$version" readme.txt
	} > "$tmp"
	mv "$tmp" readme.txt
fi

previous_version="$(
	grep -E '^## \[[0-9]+\.[0-9]+\.[0-9]+\]' CHANGELOG.md \
		| sed -E 's/^## \[([^]]+)\].*/\1/' \
		| awk -v current="$version" '$0 != current { print; exit }'
)"

perl -0pi -e 's/\n\[Unreleased\]: .*\n(?:\[[0-9]+\.[0-9]+\.[0-9]+\]: .*\n)*/\n/s' CHANGELOG.md
{
	echo "[Unreleased]: https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/compare/${version}...HEAD"
	echo "[${version}]: https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/releases/tag/${version}"
	if [ -n "$previous_version" ]; then
		echo "[${previous_version}]: https://github.com/MatthiasReinholz/hungry-flamingo-blog-wordpress-theme/releases/tag/${previous_version}"
	fi
} >> CHANGELOG.md

echo "Updated release metadata to $version"
