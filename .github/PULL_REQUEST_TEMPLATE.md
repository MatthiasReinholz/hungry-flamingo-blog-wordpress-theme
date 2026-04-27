## Summary

- 

## Test Plan

- [ ] `composer test:php`
- [ ] `npm test`
- [ ] `npm run dist`
- [ ] `HFB_VERIFY_SKIP_BUILD=1 npm run verify:dist`
- [ ] Visual/a11y smoke test, if templates, styles, or scripts changed

## Checklist

- [ ] Theme changes stay in presentation/template scope.
- [ ] User-facing strings are translatable or intentionally core-owned block text.
- [ ] Privacy docs were updated for persistence, external requests, or data changes.
- [ ] Release metadata/docs remain aligned when version or package behavior changes.
