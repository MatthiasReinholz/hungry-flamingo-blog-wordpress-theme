#!/usr/bin/env bash

set -euo pipefail

DEST_DIR="${1:-}"
SYFT_VERSION="1.43.0"
COSIGN_VERSION="3.0.6"

if [ -z "$DEST_DIR" ]; then
	echo "Usage: $0 <destination-dir>" >&2
	exit 1
fi

case "$(uname -s):$(uname -m)" in
	Linux:x86_64)
		syft_archive="syft_${SYFT_VERSION}_linux_amd64.tar.gz"
		syft_sha256="7b98251d2d08926bb5d4639b56b1f0996a58ef6667c5830e3fe3cd3ad5f4214a"
		cosign_asset="cosign-linux-amd64"
		cosign_sha256="c956e5dfcac53d52bcf058360d579472f0c1d2d9b69f55209e256fe7783f4c74"
		;;
	*)
		echo "Release security tool installation is supported only on Linux/x86_64 runners." >&2
		exit 1
		;;
esac

mkdir -p "$DEST_DIR"
tmp_dir="$(mktemp -d)"
trap 'rm -rf "$tmp_dir"' EXIT

sha256_check() {
	local expected="$1"
	local file="$2"
	printf '%s  %s\n' "$expected" "$file" | sha256sum -c -
}

curl -fsSLo "$tmp_dir/$syft_archive" "https://github.com/anchore/syft/releases/download/v${SYFT_VERSION}/${syft_archive}"
sha256_check "$syft_sha256" "$tmp_dir/$syft_archive"
tar -xzf "$tmp_dir/$syft_archive" -C "$tmp_dir"
install "$tmp_dir/syft" "$DEST_DIR/syft"

curl -fsSLo "$tmp_dir/$cosign_asset" "https://github.com/sigstore/cosign/releases/download/v${COSIGN_VERSION}/${cosign_asset}"
sha256_check "$cosign_sha256" "$tmp_dir/$cosign_asset"
install "$tmp_dir/$cosign_asset" "$DEST_DIR/cosign"

echo "Installed release security tools into $DEST_DIR"
