# Stock Controller Quality Engineering Presentation Outline

## Slide 1 - Title And Introduction

Title: Stock Controller Quality Engineering Project

Include:

- Group name: pending group input
- Group members: pending group input
- Project Path: Path B - The Creator
- Application: Laravel Stock Controller
- Tech stack: Laravel 12, PHP 8.2, Blade, Vite, SQLite, Postman/Newman, Playwright, K6

Speaker notes:

This project tests a Laravel inventory and restock management system as a professional quality engineering lifecycle. The application includes frontend pages, backend logic, API routes, and database migrations.

## Slide 2 - Strategy And Modeling

Main testing missions:

- Authentication reliability
- Inventory and stock data quality
- Supplier relationship behavior
- Restock order workflow
- Performance limits under load

Evidence to show:

- `docs/assignment/evidence/black-box-acceptance-output.txt`
- `docs/assignment/evidence/full-test-output.txt`

Speaker notes:

Testing used both scripted test cases and exploratory charters. The team used equivalence partitioning, boundary value analysis, error guessing, happy path testing, and sad path testing.

## Slide 3 - Investigative Findings

Finding 1: Restock receive validation rollback defect

- Invalid receive quantity above ordered quantity exposed unsafe rollback handling.
- Fix: validation exceptions now return proper validation errors, and rollback only happens when a transaction is active.
- Evidence: `docs/assignment/evidence/black-box-acceptance-output.txt`

Finding 2: Performance bottleneck under concurrent load

- System stayed available at 50 users, but p95 response time became unacceptable.
- p95 response time increased from 503.91 ms at 1 user to 12082.70 ms at 50 users.
- Evidence: `docs/assignment/evidence/response-time-vs-user-load.svg`

Speaker notes:

Show the difference between expected result and actual result. Explain that the first issue was fixed and verified, while the second is a performance limitation.

## Slide 4 - Engineering Depth

Show:

- Unit and feature tests: `php artisan test`
- Result: 32 tests passed, 86 assertions
- API validation: Newman/Postman, 12 requests, 24 assertions, 0 failures
- UI automation: Playwright, 2 E2E flows passed

Evidence:

- `docs/assignment/evidence/full-test-output.txt`
- `docs/assignment/evidence/newman-run-output.txt`
- `docs/assignment/evidence/playwright-output.txt`

Demo commands:

```powershell
php artisan test
npx.cmd newman run docs/postman/Stock_Controller_API.postman_collection.json -e docs/postman/Stock_Controller_API.postman_environment.json
npm.cmd run test:e2e
```

Speaker notes:

Explain that backend logic, API communication, and end-to-end user workflows are all covered.

## Slide 5 - Performance Limits

Show graph:

```text
docs/assignment/evidence/response-time-vs-user-load.svg
```

Performance summary:

| Users | Avg Response Time | P95 Response Time | Error Rate |
|---:|---:|---:|---:|
| 1 | 269.60 ms | 503.91 ms | 0% |
| 10 | 2039.84 ms | 2583.69 ms | 0% |
| 25 | 5035.60 ms | 7076.85 ms | 0% |
| 50 | 8867.94 ms | 12082.70 ms | 0% |

Conclusion:

- The application did not produce HTTP failures at 50 users.
- The operational limit is response time, not correctness.
- Bottleneck is likely the local Laravel development server and request processing under concurrent load.

Demo command:

```powershell
& "C:\Program Files\k6\k6.exe" run docs/performance/stock-controller.k6.js
```

## Demo Checklist

| Demo | Command / Action | Evidence |
|---|---|---|
| Unit tests | `php artisan test` | `docs/assignment/evidence/full-test-output.txt` |
| Postman/API | Run collection or Newman command | `docs/assignment/evidence/newman-run-output.txt` |
| UI automation | `npm.cmd run test:e2e` | `docs/assignment/evidence/playwright-output.txt` |
| Performance | K6 command | `docs/assignment/evidence/k6-output.txt` |

