# Development dependency security

The audit retry wrapper preserves a failing command's actual exit status. After
three unsuccessful attempts the security gate fails, and a failed Composer audit
prevents the npm audit from being skipped through an apparent success. Tests run
the real wrapper with failing and recovering command fixtures.

The October 2026 compatible lock refresh resolves the available npm and Composer
updates without migrating the theme's toolchain majors. The remaining npm advisory,
`GHSA-vfj7-8cjw-p6xm`, affects `braces` 3.0.3 and has no published compatible fix.
The seven raw npm findings are the same advisory propagated through Stylelint's
dependency graph. Downgrading Stylelint to an obsolete major is not a remedy.

## Maintained local backport

`patches/braces@3.0.3.patch` and `scripts/test-braces-depth.cjs` are copied unchanged
from the reviewed app-base maintenance source identified by its full commit in
`braces-security-provenance.json`. The provenance also records the upstream
proposal, tarball integrity, exact patch/regression hashes, complete pristine and
patched file inventories, and this repository's npm lock hash. Package identity
remains `braces` 3.0.3; the repository does not claim an upstream patched release.

The explicit `postinstall` script applies this patch during `npm ci`/`npm install`.
It validates every installed instance and every pristine file before the first
write, checks the patch with Git, applies it, and then verifies complete installed
bytes and executes the real attack/compatibility regressions. Already patched
installs are verified without rewriting them. Unknown versions, changed bytes,
linked packages, unsupported workspaces or lock changes fail closed. Git and Node
are required development tools. Using `--ignore-scripts` leaves pristine vulnerable
code installed; the subsequent audit rejects that state.

`npm run audit:npm` retains the complete raw npm audit JSON and separately reports
local remediation. Its npm-v2 adapter validates process status, schema, counts and
both directions of dependency edges. It qualifies ancestors only when every path
terminates in the exact reviewed braces advisory and all installed braces copies
pass integrity and behavioral verification. An unrelated high/critical advisory
still fails the existing threshold, including one added to an otherwise qualified
package. Malformed, missing or contradictory scanner evidence fails closed.

For any lock update, review the new dependency graph before updating the recorded
lock hash. Changes to the patch or regression require a new source review and new
provenance. When an official compatible fix is available, replace the backport,
remove its installer and qualification, restore ordinary npm auditing, and retain
coverage that audit failures cannot become successful CI results. None of these
development files are included in the WordPress theme release package.

## Required CI check contract

The protected main branch requires the exact GitHub Actions context `lint`. CI
therefore provides a dedicated job that installs the pinned tooling and executes
the real JavaScript/CSS linters, including the security helper modules. The full
`Validate theme` job continues package, PHP, unit, WordPress/WooCommerce, visual,
accessibility and Theme Check qualification. Change required check names together
with repository protection settings; a renamed job cannot satisfy an older
required context.
