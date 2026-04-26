#!/usr/bin/env bash

set -euo pipefail

release_type="${1:-patch}"
current="$(awk -F': *' '/^Version:/ { print $2; exit }' style.css)"

if ! [[ "$current" =~ ^([0-9]+)\.([0-9]+)\.([0-9]+)$ ]]; then
	echo "Current style.css version must use x.y.z format." >&2
	exit 1
fi

major="${BASH_REMATCH[1]}"
minor="${BASH_REMATCH[2]}"
patch="${BASH_REMATCH[3]}"

case "$release_type" in
	major)
		major=$((major + 1))
		minor=0
		patch=0
		;;
	minor)
		minor=$((minor + 1))
		patch=0
		;;
	patch)
		patch=$((patch + 1))
		;;
	*)
		echo "Unsupported release type: $release_type" >&2
		exit 1
		;;
esac

printf '%s.%s.%s\n' "$major" "$minor" "$patch"
