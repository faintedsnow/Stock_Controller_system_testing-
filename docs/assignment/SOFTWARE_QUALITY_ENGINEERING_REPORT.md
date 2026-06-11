# Software Quality Engineering Report

## Title Page

| Field | Value |
|---|---|
| Project Name | Stock Controller |
| Assignment | Quality Engineering Group Assignment |
| Project Path | Path B - The Creator |
| Group Name | Pending group input |
| Group Members | Pending group input |
| Lecturer | Pending group input |
| Course | Pending group input |
| Submission Date | Pending group input |
| GitHub Repository | https://github.com/faintedsnow/Stock_Controller_system_testing- |

## 1. Executive Summary

This report documents the Quality Engineering lifecycle applied to the Stock Controller Laravel application. The project verifies software quality through black-box functional testing, exploratory testing charters, formal bug documentation, backend unit testing, API validation with Postman, UI automation, and performance testing.

The selected project follows **Path B: The Creator** because it is a custom web application with a frontend, backend, and database.

Current progress as of 2026-06-11: environment verification, black-box acceptance testing, exploratory testing documentation, Laravel automated tests, API collection validation, UI automation, and K6 performance testing have been completed. The current evidence shows 32 Laravel tests passing, 12 black-box acceptance cases passing, 12 API requests passing with 24 Postman/Newman assertions, 2 Playwright E2E flows passing, and a K6 load test reaching 50 concurrent users.

## 2. Application Under Test

| Item | Details |
|---|---|
| Application Name | Stock Controller |
| Application Type | Inventory and restock management web application |
| Frontend | Laravel Blade views, Vite assets |
| Backend | Laravel 12, PHP 8.2 |
| Database | SQLite locally or MySQL if configured |
| Authentication | Laravel session login for web, bearer token API for Postman |
| Main Modules | Authentication, Dashboard, Suppliers, Inventory, Stock Updates, Restock Orders |

### 2.1 Main Functional Areas

| Module | Main Functions |
|---|---|
| Authentication | Register, login, logout, protected pages |
| Suppliers | Create, read, update, delete suppliers |
| Inventory | Create, read, update, delete inventory items |
| Stock Updates | Add stock, remove stock, prevent negative final stock |
| Restock Orders | Create pending restock orders, receive stock, update inventory |
| Dashboard | Show operational summary and navigation |

## 3. Test Strategy

The testing strategy follows the assignment phases:

| Phase | Focus | Evidence |
|---|---|---|
| Phase 1 | Black-box testing, exploratory testing, bug reports | Test case suite, charter logs, bug log |
| Phase 2 | Unit tests, API validation, UI automation | PHPUnit results, Postman collection run, E2E results |
| Phase 3 | Load and stress testing | Performance report and graph |
| Phase 4 | Presentation and demo | Slides, screenshots, demo checklist |

Testing principles used:

- Compare expected result against actual result.
- Include happy path and sad path scenarios.
- Use boundary values for numeric fields.
- Use error guessing for duplicate and invalid data.
- Use exploratory charters to find hidden risks.
- Collect screenshots, terminal output, Postman results, and performance graphs as evidence.

## 4. Environment

| Item | Value |
|---|---|
| OS | Windows 11 |
| Browser | Google Chrome 149.0.7827.54; Chromium through Playwright |
| PHP | PHP 8.2.12 |
| Laravel | Laravel Framework 12.18.0 |
| Database | SQLite local database |
| Test Runner | PHPUnit through `php artisan test` |
| API Tool | Postman collection, executed through Newman CLI |
| UI Automation Tool | Playwright |
| Performance Tool | K6 v2.0.0 |

Setup commands:

```powershell
cd "C:\Users\asus\Desktop\ST GROUP\Stock_Controller-main"
composer install
npm install
php artisan migrate --force
npm.cmd run build
php artisan test
php artisan serve --host=127.0.0.1 --port=8000
```

Setup and environment evidence:

```text
docs/assignment/evidence/environment.txt
docs/assignment/evidence/migration-output.txt
docs/assignment/evidence/full-test-output.txt
```

## 5. Phase 1A - Black-Box Test Case Suite

Project Name: Stock Controller  
Tester Name: Codex assisted execution; replace with group member name if required  
Execution Date: 2026-06-11  

| Test Case ID | Module | Test Title | Technique Used | Pre-conditions | Test Steps | Test Data | Expected Result | Actual Result | Status | Evidence |
|---|---|---|---|---|---|---|---|---|---|---|
| TC-AUTH-001 | Authentication | Register with valid data | Equivalence Partitioning | User is on Register page | 1. Open `/register`. 2. Enter valid name, email, password, confirmation. 3. Submit form. | New email, password >= 8 chars | Account is created and user can log in | User was created and redirected to login | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-AUTH-002 | Authentication | Register with duplicate email | Error Guessing | Existing user email exists | 1. Open `/register`. 2. Enter an existing email. 3. Submit form. | Existing email | Validation error is shown and duplicate account is not created | Duplicate email was rejected with validation error | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-AUTH-003 | Authentication | Login with valid credentials | Happy Path | Registered user exists | 1. Open `/login`. 2. Enter valid email/password. 3. Submit form. | Valid account | User is redirected to dashboard | User was redirected to dashboard | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-AUTH-004 | Authentication | Login with wrong password | Sad Path | Registered user exists | 1. Open `/login`. 2. Enter valid email and wrong password. 3. Submit form. | Wrong password | Login is rejected with an error message | Login was rejected with an email error | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-INV-001 | Inventory | Create inventory item with valid fields | Equivalence Partitioning | User is logged in | 1. Open inventory create page. 2. Fill valid fields. 3. Submit form. | Name, unique SKU, stock 20, minimum 5, price 3.50 | Item appears in inventory list | Item was created in database and redirected to inventory | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-INV-002 | Inventory | Reject duplicate SKU | Error Guessing | Existing item SKU exists | 1. Create item with existing SKU. 2. Submit form. | Duplicate SKU | Validation error is shown | Duplicate SKU was rejected | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-INV-003 | Inventory | Reject negative current stock | Boundary Value Analysis | User is logged in | 1. Open inventory create page. 2. Enter `-1` for current stock. 3. Submit form. | current_stock = -1 | Validation error is shown | Negative stock was rejected | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-INV-004 | Inventory | Remove stock below zero | Boundary Value Analysis | Item has current stock 4 | 1. Open stock update action. 2. Remove quantity 99. 3. Submit. | quantity = 99 | Stock does not become negative | Stock was clamped to 0 | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-SUP-001 | Suppliers | Create supplier with valid data | Happy Path | User is logged in | 1. Open supplier create page. 2. Fill valid data. 3. Submit. | Supplier name and email | Supplier appears in supplier list | Supplier was created in database | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-SUP-002 | Suppliers | Reject invalid supplier email | Equivalence Partitioning | User is logged in | 1. Open supplier create page. 2. Enter invalid email. 3. Submit. | email = `abc` | Validation error is shown | Invalid email was rejected | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-RESTOCK-001 | Restock | Create valid restock order | Happy Path | Supplier and item exist | 1. Open restock create page. 2. Select item and supplier. 3. Enter valid quantity and cost. 4. Submit. | Quantity 10, future expected date | Pending restock order is created | Pending restock order was created | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| TC-RESTOCK-002 | Restock | Reject receive quantity above ordered quantity | Boundary Value Analysis | Restock order quantity is 10 | 1. Open receive stock action. 2. Enter received quantity 11. 3. Submit. | quantity_received = 11 | Validation error is shown | Validation error was shown and order stayed pending after fix | Pass | `docs/assignment/evidence/black-box-acceptance-output.txt` |

### 5.1 Black-Box Summary

| Metric | Result |
|---|---:|
| Planned test cases | 12 |
| Executed test cases | 12 |
| Passed | 12 |
| Failed | 0 |
| Blocked | 0 |

All 12 planned black-box acceptance scenarios passed after fixing the restock receive validation rollback defect. The suite covers authentication, supplier management, inventory validation, stock boundary behavior, and restock order workflow behavior.

## 6. Phase 1B - Exploratory Testing Charters

### CH-001 - Authentication Reliability

| Field | Value |
|---|---|
| Mission | Explore authentication reliability |
| Areas | Register, login, logout, protected pages |
| Time Box | 30 minutes |
| Tester | Codex assisted execution |
| Date/Time | 2026-06-11 |
| Risks To Hunt | Duplicate users, weak validation, session problems, unauthorized page access |
| Test Data | Valid user, duplicate email, wrong password |
| Notes | Authentication was checked through web acceptance tests and Playwright login automation. |
| Findings | Valid login works; duplicate registration and wrong password are rejected. Protected API routes reject missing bearer tokens. |
| Bugs Created | None |
| Evidence | `black-box-acceptance-output.txt`, `playwright-output.txt`, `api-unauthenticated-check.txt` |

### CH-002 - Inventory Data Quality

| Field | Value |
|---|---|
| Mission | Explore inventory data quality |
| Areas | Create, edit, delete inventory |
| Time Box | 30 minutes |
| Tester | Codex assisted execution |
| Date/Time | 2026-06-11 |
| Risks To Hunt | Duplicate SKU, negative values, price validation, missing supplier relationship |
| Test Data | Unique SKU, duplicate SKU, negative current stock |
| Notes | Inventory validation was tested through feature acceptance tests, API tests, and UI automation. |
| Findings | Unique item creation succeeds; duplicate SKU and negative stock are rejected. |
| Bugs Created | None |
| Evidence | `black-box-acceptance-output.txt`, `newman-run-output.txt`, `playwright-output.txt` |

### CH-003 - Stock Update Behavior

| Field | Value |
|---|---|
| Mission | Explore stock update behavior |
| Areas | Add stock, remove stock, low stock state |
| Time Box | 30 minutes |
| Tester | Codex assisted execution |
| Date/Time | 2026-06-11 |
| Risks To Hunt | Stock below zero, incorrect status, repeated submit, data corruption |
| Test Data | Item with current stock 4, remove quantity 99 |
| Notes | Stock removal was tested at the boundary where requested removal is greater than available stock. |
| Findings | Current stock is clamped to 0 instead of becoming negative. This behavior is safe for stock integrity. |
| Bugs Created | None |
| Evidence | `black-box-acceptance-output.txt`, `newman-run-output.txt` |

### CH-004 - Supplier Relationships

| Field | Value |
|---|---|
| Mission | Explore supplier relationships |
| Areas | Supplier CRUD, supplier deletion, inventory relationship |
| Time Box | 30 minutes |
| Tester | Codex assisted execution |
| Date/Time | 2026-06-11 |
| Risks To Hunt | Broken relationships, orphaned items, invalid email, deleted supplier behavior |
| Test Data | Valid supplier, invalid email `abc`, supplier used by inventory item |
| Notes | Supplier creation and validation were tested through web acceptance, API, and UI automation flows. |
| Findings | Supplier creation succeeds; invalid email is rejected; supplier relationship works during inventory item creation. |
| Bugs Created | None |
| Evidence | `black-box-acceptance-output.txt`, `playwright-output.txt`, `newman-run-output.txt` |

### CH-005 - Restock Workflow

| Field | Value |
|---|---|
| Mission | Explore restock workflow |
| Areas | Create order, partial receive, full receive |
| Time Box | 30 minutes |
| Tester | Codex assisted execution |
| Date/Time | 2026-06-11 |
| Risks To Hunt | Wrong totals, invalid dates, over-receiving, stock not updated |
| Test Data | Quantity ordered 10, invalid received quantity 11, valid future expected date |
| Notes | Restock creation and receive validation were tested at normal and boundary paths. |
| Findings | A validation rollback defect was found in the receive flow and fixed. After the fix, over-receiving is rejected and the order remains pending. |
| Bugs Created | BUG-001 |
| Evidence | `black-box-acceptance-output.txt`, `full-test-output.txt` |

### 6.1 Exploratory Testing Summary

The exploratory sessions found that the main functional workflows are testable and mostly stable. The highest-risk area was the restock receive workflow because invalid received quantity triggered unsafe rollback handling before the fix. Performance testing also showed that the local Laravel server responds correctly under 50 users but becomes slow as concurrency increases.

Top 2 interesting bugs for presentation:

1. BUG-001: Restock receive validation error path had unsafe rollback behavior. It matters because invalid input could destabilize transaction handling instead of simply returning a validation error. Evidence: `docs/assignment/evidence/black-box-acceptance-output.txt`.
2. PERF-001: Response time degraded strongly under load. It matters because p95 response time reached 12.08s at 50 concurrent users even with 0% HTTP failure. Evidence: `docs/assignment/evidence/response-time-vs-user-load.svg`.

## 7. Phase 1C - Bug Tracking Log

| Bug ID | Bug Title | Severity | Priority | Environment | Steps to Reproduce | Expected Result | Actual Result | Evidence | Status |
|---|---|---|---|---|---|---|---|---|---|
| BUG-001 | Restock receive invalid quantity used unsafe rollback handling | Major | P2 | Laravel local, PHPUnit feature acceptance test | 1. Create restock order with quantity ordered 10. 2. Submit receive request with quantity received 11. 3. Observe validation/error handling. | Validation error is returned and restock order remains pending. | Initial test exposed unsafe rollback behavior; controller was fixed to return validation errors and rollback only when a transaction is active. | `docs/assignment/evidence/black-box-acceptance-output.txt` | Fixed / Verified |
| PERF-001 | Response time increases sharply under concurrent load | Major | P2 | K6 v2.0.0, Laravel local server, 1-50 VUs | 1. Run K6 constant-load tests at 1, 10, 25, and 50 users. 2. Compare p95 response time. | Application should remain within acceptable response time under required 50-user load. | No HTTP failures, but p95 rose from 503.91ms at 1 user to 12.08s at 50 users. | `docs/assignment/evidence/k6-performance-table.md`, `docs/assignment/evidence/response-time-vs-user-load.svg` | Open / Performance bottleneck |

Severity guide:

- Critical: crash, data loss, security problem.
- Major: main feature broken or incorrect business result.
- Minor: UI glitch, typo, small usability issue.

Priority guide:

- P1: fix immediately.
- P2: fix before release.
- P3: low priority.

## 8. Phase 2A - White-Box Unit Testing

### 8.1 Unit Test Scope

Backend business logic selected for unit testing:

- Out-of-stock logic.
- Low-stock detection.
- In-stock detection.
- Restock threshold boundary.
- Stock value calculation.
- Restock order unit cost calculation.

Unit test file:

```text
tests/Unit/InventoryLogicTest.php
```

### 8.2 Unit Test Result

Command:

```powershell
php artisan test --filter=InventoryLogicTest
```

| Metric | Result |
|---|---:|
| Unit tests planned | 10 |
| Unit tests passed | 10 |
| Assertions | 10 |
| Evidence screenshot/path | `docs/assignment/evidence/unit-test-output.txt` |

Unit test execution result:

```text
Tests: 10 passed (10 assertions)
```

Full Laravel test suite evidence:

```text
docs/assignment/evidence/full-test-output.txt
Tests: 32 passed (86 assertions)
```

## 9. Phase 2B - API Validation With Postman

### 9.1 API Scope

API base URL:

```text
http://127.0.0.1:8000/api/v1
```

Postman files:

| Item | Path |
|---|---|
| Collection | `docs/postman/Stock_Controller_API.postman_collection.json` |
| Environment | `docs/postman/Stock_Controller_API.postman_environment.json` |

### 9.2 API Request Coverage

| # | Request | Purpose | Assertions |
|---:|---|---|---|
| 1 | `POST /register` | Register API user | Status code, JSON token/user validation |
| 2 | `POST /login` | Login and save token | Status code, JSON token validation |
| 3 | `POST /suppliers` | Create supplier | Status code, JSON supplier ID validation |
| 4 | `GET /suppliers` | Read suppliers | Status code, JSON array validation |
| 5 | `POST /inventory` | Create inventory item | Status code, JSON item ID validation |
| 6 | `GET /inventory` | Read inventory | Status code, JSON array validation |
| 7 | `PUT /inventory/{id}` | Update inventory item | Status code, JSON updated name validation |
| 8 | `POST /inventory/{id}/stock` | Remove stock | Status code, JSON stock validation |
| 9 | `POST /restock-orders` | Create restock order | Status code, JSON status validation |
| 10 | `POST /restock-orders/{id}/receive` | Receive restock order | Status code, JSON completed status validation |
| 11 | `DELETE /inventory/{id}` | Delete inventory item | Status code, JSON message validation |
| 12 | `DELETE /suppliers/{id}` | Delete supplier | Status code, JSON message validation |

### 9.3 Postman Result

| Metric | Result |
|---|---:|
| Requests executed | 12 |
| Requests passed | 12 |
| Requests failed | 0 |
| Assertions passed | 24 |
| Evidence screenshot/path | `docs/assignment/evidence/newman-run-output.txt` |

API validation was executed through Newman using the Postman collection and environment files. The collection covered registration, login, supplier CRUD, inventory CRUD, stock update, restock order creation, restock receiving, and cleanup.

Postman/Newman result:

```text
iterations: 1 executed, 0 failed
requests: 12 executed, 0 failed
assertions: 24 executed, 0 failed
average response time: 372ms
```

Additional machine-readable evidence:

```text
docs/assignment/evidence/newman-run.json
```

## 10. Phase 2C - UI Automation

Minimum requirement: 2 end-to-end flows.

| Flow ID | Flow Name | Steps | Expected Result | Actual Result | Status | Evidence |
|---|---|---|---|---|---|---|
| E2E-001 | Login Flow | 1. Create test user through API. 2. Open `/login`. 3. Enter valid credentials. 4. Submit. | User reaches dashboard | User reached dashboard | Pass | `docs/assignment/evidence/playwright-output.txt` |
| E2E-002 | Inventory Creation Flow | 1. Create test user through API. 2. Login. 3. Create supplier. 4. Create inventory item. 5. Verify item appears. | Inventory item is visible in list | Supplier and inventory item were created and visible | Pass | `docs/assignment/evidence/playwright-output.txt` |

Automation tool:

```text
Playwright
```

UI automation files:

```text
playwright.config.js
tests/e2e/stock-controller.spec.js
```

Command:

```powershell
npm.cmd run test:e2e
```

Result:

```text
2 passed (10.3s)
```

Evidence:

```text
docs/assignment/evidence/playwright-output.txt
docs/assignment/evidence/playwright-report.json
```

## 11. Phase 3 - Performance Analysis

### 11.1 Performance Objective

The objective is to identify the operational limit and breaking point of the Stock Controller application under load.

Tool:

```text
K6 v2.0.0
```

Target endpoints:

| Endpoint | Purpose |
|---|---|
| `GET /login` | Public web baseline |
| `POST /api/v1/login` | Authentication API load |
| `GET /api/v1/inventory` | Authenticated read load |
| `POST /api/v1/inventory` | Authenticated CRUD stress |

Prepared K6 script:

```text
docs/performance/stock-controller.k6.js
docs/performance/stock-controller-constant.k6.js
```

Command used for staged 50-user run:

```powershell
& "C:\Program Files\k6\k6.exe" run docs/performance/stock-controller.k6.js --summary-export docs/assignment/evidence/k6-summary.json
```

Commands used for constant-load measurement:

```powershell
& "C:\Program Files\k6\k6.exe" run -e VUS=1 -e DURATION=20s docs/performance/stock-controller-constant.k6.js --summary-export docs/assignment/evidence/k6-constant-1.json
& "C:\Program Files\k6\k6.exe" run -e VUS=10 -e DURATION=20s docs/performance/stock-controller-constant.k6.js --summary-export docs/assignment/evidence/k6-constant-10.json
& "C:\Program Files\k6\k6.exe" run -e VUS=25 -e DURATION=20s docs/performance/stock-controller-constant.k6.js --summary-export docs/assignment/evidence/k6-constant-25.json
& "C:\Program Files\k6\k6.exe" run -e VUS=50 -e DURATION=20s docs/performance/stock-controller-constant.k6.js --summary-export docs/assignment/evidence/k6-constant-50.json
```

### 11.2 Load Test Results

| Scenario | Concurrent Users | Avg Response Time | 95th Percentile | Error Rate | Notes |
|---|---:|---:|---:|---:|---|
| Baseline | 1 | 269.60 ms | 503.91 ms | 0% | Stable baseline |
| Small load | 10 | 2039.84 ms | 2583.69 ms | 0% | Response time crossed 2s p95 target |
| Medium load | 25 | 5035.60 ms | 7076.85 ms | 0% | System remained available but slow |
| Required load | 50 | 8867.94 ms | 12082.70 ms | 0% | Required 50-user load completed, but response time was unacceptable |
| Stress load | 50 staged run | 5140 ms | 10530 ms | 0% | Full ramp test crossed the `p(95)<2000` threshold |

### 11.3 Breaking Point Analysis

| Question | Answer |
|---|---|
| When did response time start slowing down? | At 10 concurrent users, p95 response time increased to 2583.69 ms. |
| At what user load did the system fail or become unacceptable? | The system did not return HTTP failures up to 50 users, but became operationally unacceptable from 10 users onward because p95 exceeded 2 seconds. |
| What was the primary bottleneck? | Local Laravel development server throughput and request processing under concurrent load; the app remained correct but response time increased sharply. |
| Evidence path for graph | `docs/assignment/evidence/response-time-vs-user-load.svg` |

Required graph:

```text
docs/assignment/evidence/response-time-vs-user-load.svg
docs/assignment/evidence/k6-output.txt
docs/assignment/evidence/k6-performance-table.md
```

## 12. Optional Observability

| Item | Result |
|---|---|
| Monitoring tool | K6 metrics and Laravel logs |
| What was monitored | HTTP error rate, p95 response time, request duration, and application availability |
| Error/performance spike captured | No HTTP error spike; response-time spike captured from 10 to 50 users |
| Evidence | `docs/assignment/evidence/k6-output.txt`, `docs/assignment/evidence/response-time-vs-user-load.svg` |

## 13. Presentation And Demo Checklist

### 13.1 Slide Plan

| Slide | Required Content | Status |
|---|---|---|
| 1 | Group members, Path B, Laravel tech stack | Content prepared; add group names |
| 2 | Strategy and modeling, 4 missions | Content prepared |
| 3 | 2 interesting bugs with screenshots | Findings selected; add screenshots if required |
| 4 | Postman collection running, unit tests, UI automation | Evidence collected; add screenshots to slide |
| 5 | JMeter/K6 Response Time vs Load graph, breaking point, bottleneck | Done; use `response-time-vs-user-load.svg` |

### 13.2 Demo Checklist

| Demo Item | Command or Action | Status | Evidence |
|---|---|---|---|
| Unit tests | `php artisan test` | Done | `docs/assignment/evidence/full-test-output.txt` |
| Postman collection | Run collection runner or Newman | Done through Newman | `docs/assignment/evidence/newman-run-output.txt` |
| UI automation | `npm.cmd run test:e2e` | Done | `docs/assignment/evidence/playwright-output.txt` |
| Performance test | `& "C:\Program Files\k6\k6.exe" run docs/performance/stock-controller.k6.js` | Done | `docs/assignment/evidence/k6-output.txt` |

## 14. Final Submission Checklist

| Required Item | Status | Evidence |
|---|---|---|
| Black-box test suite | Done | `docs/assignment/evidence/black-box-acceptance-output.txt` |
| 5 SBTM charters | Done | Section 6 of this report |
| Bug tracking log | Done | Section 7 of this report |
| Unit test evidence | Done | `docs/assignment/evidence/unit-test-output.txt` and `docs/assignment/evidence/full-test-output.txt` |
| Postman API collection evidence | Done | `docs/assignment/evidence/newman-run-output.txt` |
| UI automation evidence | Done | `docs/assignment/evidence/playwright-output.txt` |
| Performance analysis report | Done | `docs/assignment/evidence/k6-output.txt`, `docs/assignment/evidence/response-time-vs-user-load.svg` |
| GitHub repository link | Done | https://github.com/faintedsnow/Stock_Controller_system_testing- |
| 10-minute presentation slides | Draft content prepared in report; final visual slide deck still needs group names/screenshots |

## 15. Report Update Log

Use this table every time the report is updated.

| Date | Updated By | Section Updated | Summary |
|---|---|---|---|
| 2026-06-11 | Codex | Initial report draft | Created living report structure with evidence slots |
| 2026-06-11 | Codex | Engineering evidence | Added environment, PHPUnit, Newman/Postman, Playwright E2E, and K6 script status |
| 2026-06-11 | Codex | Functional and performance completion | Added black-box acceptance results, charter logs, bug log, K6 performance results, and graph evidence |
