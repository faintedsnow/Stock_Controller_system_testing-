# Stock Controller Quality Engineering Project Execution Plan

## 1. Project Overview

This project uses **Path B: The Creator** because the selected software is a custom Laravel web application.

| Item | Decision |
|---|---|
| Project | Stock Controller |
| Assignment Path | Path B - The Creator |
| Main Stack | Laravel 12, PHP 8.2, Blade, Vite, SQLite or MySQL |
| Main Modules | Authentication, Dashboard, Suppliers, Inventory, Stock Updates, Restock Orders |
| Main Evidence Goal | Prove the app was tested through black-box testing, exploratory testing, unit testing, API testing, UI automation, and performance testing |

Grading priority:

| Phase | Max Score | Priority |
|---|---:|---|
| Phase 1: Exploration | 14 | Highest |
| Phase 2: Engineering | 14 | Highest |
| Phase 3: Infrastructure | 7 | Medium |
| Phase 4: Presentation | 7 | Medium |

Important assignment rule: the guideline says 3 charters in one place, but the final checklist says 5 charters. Use **5 completed charters** to be safe.

## 2. Current Project Assets

Already available in this project:

- Laravel web app with frontend, backend, and database migrations.
- API routes under `/api/v1`.
- Postman collection and environment in `docs/postman`.
- Unit and feature tests in `tests/Unit` and `tests/Feature`.
- Playwright UI automation in `tests/e2e`.
- K6 performance test script in `docs/performance`.
- Assignment workbook in `docs/assignment/Assignment_Workbook.md`.

Important files:

| Purpose | Path |
|---|---|
| Web routes | `routes/web.php` |
| API routes | `routes/api.php` |
| API controller | `app/Http/Controllers/Api/AssignmentApiController.php` |
| Unit tests | `tests/Unit/InventoryLogicTest.php` |
| API feature tests | `tests/Feature/AssignmentApiTest.php` |
| UI automation tests | `tests/e2e/stock-controller.spec.js` |
| Postman collection | `docs/postman/Stock_Controller_API.postman_collection.json` |
| Postman environment | `docs/postman/Stock_Controller_API.postman_environment.json` |
| K6 performance script | `docs/performance/stock-controller.k6.js` |
| Living report | `docs/assignment/SOFTWARE_QUALITY_ENGINEERING_REPORT.md` |

## 3. Setup And Verification Commands

Run these from the project folder:

```powershell
cd "C:\Users\asus\Desktop\ST GROUP\Stock_Controller-main"
composer install
npm install
php artisan migrate --force
npm.cmd run build
php artisan test
npm.cmd run test:e2e
php artisan serve --host=127.0.0.1 --port=8000
```

If using development assets instead of built assets:

```powershell
npm.cmd run dev
```

Expected test evidence:

```text
Tests: 20 passed (43 assertions)
```

Next AI agent task:

```text
Verify the Laravel app setup, run php artisan test, and update SOFTWARE_QUALITY_ENGINEERING_REPORT.md with the exact test output and screenshot/evidence location.
```

Completion criteria:

- App opens at `http://127.0.0.1:8000`.
- `php artisan test` passes.
- Report contains setup status and test result evidence.

## 4. Phase 1A - Black-Box Functional Test Cases

Goal: create and execute at least 10 formal black-box test cases. This project will use 12 planned test cases to exceed the minimum.

Required techniques:

- Equivalence Partitioning
- Boundary Value Analysis
- Error Guessing
- Happy Path Testing
- Sad Path Testing

Test modules:

- Authentication
- Supplier Management
- Inventory Management
- Stock Update
- Restock Orders

Execution steps:

1. Start Laravel server.
2. Open the app in Chrome.
3. Execute each test case in the living report.
4. Fill `Actual Result`.
5. Mark each case `Pass`, `Fail`, or `Blocked`.
6. Create bug reports for failures.
7. Capture screenshots for important pass/fail evidence.

Recommended test data:

| Data Type | Value |
|---|---|
| Valid name | `API Tester` |
| Valid email | `api.tester@example.com` |
| Valid password | `password123` |
| Invalid email | `abc` |
| Valid supplier | `Acme Supply` |
| Valid SKU | `USB-001` |
| Duplicate SKU | Reuse an existing SKU |
| Negative stock | `-1` |
| Remove stock stress value | `99` |

Next AI agent task:

```text
Execute the 12 black-box test cases in SOFTWARE_QUALITY_ENGINEERING_REPORT.md, fill Actual Result and Status, and add bug reports for every failed case.
```

Completion criteria:

- Minimum 10 test cases are executed.
- This project should execute all 12 planned test cases.
- Every failure has a bug report.
- Evidence screenshots are referenced in the report.

## 5. Phase 1B - Exploratory Testing Charters

Goal: complete 5 Session-Based Test Management charters.

Use these missions:

| Charter | Mission | Time Box |
|---|---|---:|
| CH-001 | Explore authentication reliability | 30 minutes |
| CH-002 | Explore inventory data quality | 30 minutes |
| CH-003 | Explore stock update behavior | 30 minutes |
| CH-004 | Explore supplier relationships | 30 minutes |
| CH-005 | Explore restock workflow | 30 minutes |

For each charter, record:

- Mission
- Areas tested
- Start and end time
- Test data used
- Notes and observations
- Bugs found
- Evidence path
- Follow-up risks

Risk ideas to hunt:

- Duplicate data
- Bad validation
- Negative quantities
- Stock corruption
- Supplier deletion side effects
- Invalid dates
- Partial receive behavior
- Unauthorized access
- Concurrency or repeated-submit problems

Next AI agent task:

```text
Run the 5 exploratory testing charters, write session notes into SOFTWARE_QUALITY_ENGINEERING_REPORT.md, and select the 2 most interesting bugs for the presentation.
```

Completion criteria:

- 5 charters are completed.
- Each charter includes time spent and findings.
- At least 2 strong findings or risk stories are ready for presentation.

## 6. Phase 1C - Bug Reports

Goal: document every failure found during scripted or exploratory testing.

Each bug report must include:

- Bug ID
- Bug title
- Severity
- Priority
- Environment
- Steps to reproduce
- Expected result
- Actual result
- Evidence
- Status

Severity guide:

| Severity | Meaning |
|---|---|
| Critical | Crash, data loss, security issue |
| Major | Main feature broken or wrong business result |
| Minor | UI issue, typo, small inconvenience |

Priority guide:

| Priority | Meaning |
|---|---|
| P1 | Must fix immediately |
| P2 | Should fix before release |
| P3 | Low priority |

Next AI agent task:

```text
Review all failed test cases and charter findings, then create formal bug reports in SOFTWARE_QUALITY_ENGINEERING_REPORT.md with evidence paths.
```

Completion criteria:

- Every failure has a bug report.
- Expected vs actual result is clear.
- Evidence is linked or described.

## 7. Phase 2A - White-Box Unit Testing

Goal: prove internal backend logic works correctly with at least 10 unit tests.

Current unit test focus:

- `InventoryItem::isOutOfStock`
- `InventoryItem::needsRestock`
- `InventoryItem::getStockStatus`
- `InventoryItem::stock_value`
- `RestockOrder::unit_cost`

Run:

```powershell
php artisan test --filter=InventoryLogicTest
```

Run the full suite:

```powershell
php artisan test
```

Report evidence to collect:

- Screenshot of terminal output.
- Number of tests passed.
- Number of assertions.
- Short explanation of the backend logic covered.

Next AI agent task:

```text
Run php artisan test and update the Unit Test Evidence section in SOFTWARE_QUALITY_ENGINEERING_REPORT.md with the exact pass count, assertion count, and screenshot path.
```

Completion criteria:

- At least 10 backend logic tests pass.
- Report explains which business rules are verified.
- Evidence is recorded.

## 8. Phase 2B - Postman API Validation

Goal: test backend endpoints with a Postman collection using variables, tokens, and assertions.

Import these files into Postman:

- `docs/postman/Stock_Controller_API.postman_collection.json`
- `docs/postman/Stock_Controller_API.postman_environment.json`

Postman flow:

1. Register user.
2. Login user and save token.
3. Create supplier.
4. List suppliers.
5. Create inventory item.
6. List inventory.
7. Update inventory item.
8. Remove stock.
9. Create restock order.
10. Receive restock order.
11. Delete inventory item.
12. Delete supplier.

Assertions required by assignment:

- Status code assertion.
- JSON response validation.

These are already included in the collection. During execution, capture:

- Collection run summary screenshot.
- Any failed request details.
- Final status of environment variables.

Next AI agent task:

```text
Run the Postman collection using the Stock Controller environment, capture the collection runner result, and update the API Test Evidence section in SOFTWARE_QUALITY_ENGINEERING_REPORT.md.
```

Completion criteria:

- Minimum 10 API requests executed.
- CRUD flow is covered.
- Each request has at least 2 assertions.
- Report includes collection result evidence.

## 9. Phase 2C - UI Automation

Goal: create at least 2 end-to-end user flows.

Recommended tool: Playwright.

Recommended flows:

| Flow | Name | Steps |
|---|---|---|
| E2E-001 | Login Flow | Visit `/login`, enter valid credentials, submit, verify dashboard |
| E2E-002 | Inventory Creation Flow | Login, create supplier, create inventory item, verify item appears |

Suggested evidence:

- Test code path.
- Terminal output.
- Screenshot or video from automation run.

Potential implementation path:

```text
tests/e2e/login.spec.js
tests/e2e/inventory.spec.js
```

Next AI agent task:

```text
Add Playwright UI automation for login and inventory creation, run the tests, and update SOFTWARE_QUALITY_ENGINEERING_REPORT.md with result evidence.
```

Completion criteria:

- 2 E2E flows exist.
- Both flows pass locally.
- Report includes screenshots, videos, or terminal output.

## 10. Phase 3 - Performance And Stress Testing

Goal: find the breaking point of the Laravel Stock Controller app.

Recommended tool: K6 or JMeter. Use K6 if you want script-based results; use JMeter if your lecturer expects the JMeter graph.

Minimum traffic plan:

| Stage | Users | Purpose |
|---|---:|---|
| Baseline | 1 | Establish normal response time |
| Small load | 10 | Check early degradation |
| Medium load | 25 | Check stability |
| Required load | 50 | Meet assignment requirement |
| Stress | 75+ | Increase until failure or unacceptable response |

Recommended targets:

- `GET /login`
- `POST /api/v1/login`
- `GET /api/v1/inventory` with bearer token
- `POST /api/v1/inventory` with unique SKUs for CRUD stress

Metrics to collect:

- Average response time.
- 95th percentile response time.
- Error rate.
- Requests per second.
- CPU or memory notes if available.
- First point where response time becomes unacceptable.
- Failure point.
- Bottleneck explanation.

Graph required:

```text
Response Time vs User Load
```

Next AI agent task:

```text
Create and run a K6 or JMeter performance test with 1, 10, 25, and 50 users, then update SOFTWARE_QUALITY_ENGINEERING_REPORT.md with the table, graph path, breaking point, and bottleneck explanation.
```

Completion criteria:

- Baseline with 1 user is recorded.
- At least 50 concurrent users are tested.
- Breaking point or operational limit is explained.
- Graph is included or referenced.

## 11. Optional Extra Credit - Observability

Goal: show live monitoring during performance testing.

Possible lightweight options:

- Laravel logs in `storage/logs/laravel.log`.
- Windows Task Manager CPU and memory screenshots.
- Logz.io or another dashboard if available.

Demo idea:

1. Open monitoring dashboard or logs.
2. Run K6/JMeter test.
3. Show errors or performance spike.
4. Capture screenshot.

Next AI agent task:

```text
If time allows, add a simple observability section using Laravel logs or a monitoring dashboard and update the report with evidence.
```

Completion criteria:

- Optional monitoring evidence is available.
- The demo can show an error or performance spike.

## 12. Final Report Completion

Before submission, update `SOFTWARE_QUALITY_ENGINEERING_REPORT.md` with:

- Group members.
- Lecturer/course details.
- GitHub repository link.
- Final black-box test results.
- Completed 5 charters.
- Bug report log.
- Unit test evidence.
- Postman evidence.
- UI automation evidence.
- Performance report and graph.
- Presentation demo checklist.

Next AI agent task:

```text
Review SOFTWARE_QUALITY_ENGINEERING_REPORT.md for all TODO markers, replace completed TODOs with evidence, and leave only TODOs for work that genuinely remains.
```

Completion criteria:

- Report is complete enough to submit.
- Evidence paths are filled.
- No important section is empty.

## 13. Presentation Plan

The presentation is 10 minutes and should follow the lecturer's "show, do not just tell" advice.

| Slide | Focus |
|---|---|
| 1 | Title, group members, Path B, Laravel tech stack |
| 2 | Strategy and modeling: modules tested and 4 main missions |
| 3 | Investigative findings: 2 interesting bugs with screenshots |
| 4 | Engineering depth: Postman run, unit tests, UI automation |
| 5 | Performance limits: Response Time vs Load graph and bottleneck |

Demo after presentation:

- Unit tests.
- Postman collection.
- UI automation.
- Performance test.

Next AI agent task:

```text
Create a 5-slide presentation outline from SOFTWARE_QUALITY_ENGINEERING_REPORT.md and prepare exact demo steps for unit tests, Postman, UI automation, and performance testing.
```

Completion criteria:

- 5 slides are ready.
- 2 bugs are shown with evidence.
- Demo commands are known.
- Performance graph is ready.

## 14. Final AI Agent Workflow

Use this sequence when telling another AI agent "next":

1. Verify setup and update report evidence.
2. Execute black-box tests and update test results.
3. Run exploratory charters and update charter logs.
4. Write bug reports for all failures.
5. Run unit tests and update technical evidence.
6. Run Postman collection and update API evidence.
7. Add/run UI automation and update E2E evidence.
8. Run performance testing and update performance report.
9. Prepare presentation slides and demo script.
10. Final review: remove stale TODOs and check all evidence links.
