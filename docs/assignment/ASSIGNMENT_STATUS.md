# Assignment Status

## Current Status

The Stock Controller Quality Engineering assignment package is complete from the technical/testing side.

Remaining human-only items:

- Add group name.
- Add group member names and IDs.
- Add lecturer and course details.
- Add final submission date.
- Add final screenshots to presentation slides if your lecturer requires visual screenshots instead of text evidence files.

## Completed Work

| Area | Status | Evidence |
|---|---|---|
| Project path selection | Done | Path B - The Creator |
| Black-box test suite | Done | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| 5 exploratory charters | Done | `docs/assignment/SOFTWARE_QUALITY_ENGINEERING_REPORT.md` |
| Bug tracking log | Done | `docs/assignment/SOFTWARE_QUALITY_ENGINEERING_REPORT.md` |
| Unit tests | Done | `docs/assignment/evidence/unit-test-output.txt` |
| Full Laravel tests | Done | `docs/assignment/evidence/full-test-output.txt` |
| API/Postman collection | Done | `docs/postman/Stock_Controller_API.postman_collection.json` |
| API evidence | Done | `docs/assignment/evidence/newman-run-output.txt` |
| UI automation | Done | `tests/e2e/stock-controller.spec.js` |
| UI automation evidence | Done | `docs/assignment/evidence/playwright-output.txt` |
| Performance test | Done | `docs/assignment/evidence/k6-output.txt` |
| Performance graph | Done | `docs/assignment/evidence/response-time-vs-user-load.svg` |
| GitHub repository | Done | https://github.com/faintedsnow/Stock_Controller_system_testing- |
| Presentation outline | Done | `docs/assignment/PRESENTATION_SLIDES_OUTLINE.md` |

## Latest Verification

Commands verified successfully:

```powershell
php artisan test
npx.cmd --yes newman run docs/postman/Stock_Controller_API.postman_collection.json -e docs/postman/Stock_Controller_API.postman_environment.json
npm.cmd run test:e2e
npm.cmd run build
```

Latest results:

```text
Laravel: 32 tests passed, 86 assertions
Newman/Postman: 12 requests, 24 assertions, 0 failures
Playwright: 2 E2E tests passed
Vite: production build passed
K6: 50 concurrent users reached, 0% HTTP failure, p95 response time 12.08s at 50 users
```

## Main Findings

- Functional behavior is covered and passing after fixing one restock validation rollback issue.
- API CRUD flow is covered with environment variables and bearer token authentication.
- UI automation covers login and inventory creation workflow.
- Performance testing shows availability at 50 concurrent users but slow response time under load.
