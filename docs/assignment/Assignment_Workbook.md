# Stock Controller Quality Engineering Assignment Workbook

## Project Choice

- Project Path: Path B - The Creator
- Application: Laravel Stock Controller
- Tech Stack: Laravel 12, Blade, MySQL or SQLite, PHP 8.2, Vite
- Core Modules: Authentication, Dashboard, Suppliers, Inventory, Stock Updates, Restock Orders

## Recommended Demo Setup

1. Install dependencies: `composer install` and `npm install`
2. Create `.env`: copy `.env.example` to `.env`
3. Generate key: `php artisan key:generate`
4. Configure database in `.env`
5. Run migrations: `php artisan migrate`
6. Start backend: `php artisan serve`
7. Start frontend assets: `npm run dev`
8. Run tests: `php artisan test`
9. Import the Postman collection and environment from `docs/postman`

## Black-Box Test Suite

| Test Case ID | Module | Test Title | Technique Used | Pre-conditions | Test Steps | Test Data | Expected Result | Actual Result | Status |
|---|---|---|---|---|---|---|---|---|---|
| TC-AUTH-001 | Authentication | Register with valid data | Equivalence Partitioning | User is on Register page | Enter valid name, email, password, confirmation. Submit. | New email, password >= 8 chars | Account is created and user can log in | User was created and redirected to login | Pass |
| TC-AUTH-002 | Authentication | Register with duplicate email | Error Guessing | Existing user email exists | Register using the same email again | Existing email | Validation error is shown | Duplicate email was rejected with validation error | Pass |
| TC-AUTH-003 | Authentication | Login with valid credentials | Happy Path | Registered user exists | Open login, enter valid email/password, submit | Valid account | Dashboard is displayed | User was redirected to dashboard | Pass |
| TC-AUTH-004 | Authentication | Login with wrong password | Sad Path | Registered user exists | Enter valid email and wrong password | Wrong password | Login is rejected with error | Login was rejected with an email error | Pass |
| TC-INV-001 | Inventory | Create inventory item with valid fields | Equivalence Partitioning | User is logged in | Open create inventory, fill fields, submit | Name, unique SKU, stock 20, minimum 5, price 3.50 | Item appears in inventory list | Item was created in database and redirected to inventory | Pass |
| TC-INV-002 | Inventory | Reject duplicate SKU | Error Guessing | Existing item SKU exists | Create another item using same SKU | Duplicate SKU | Validation error is shown | Duplicate SKU was rejected | Pass |
| TC-INV-003 | Inventory | Reject negative stock | Boundary Value Analysis | User is logged in | Create item with current stock -1 | current_stock = -1 | Validation error is shown | Negative stock was rejected | Pass |
| TC-INV-004 | Inventory | Remove stock below zero | Boundary Value Analysis | Item has current stock 4 | Remove quantity 99 | quantity = 99 | Stock does not become negative | Stock was clamped to 0 | Pass |
| TC-SUP-001 | Suppliers | Create supplier with valid data | Happy Path | User is logged in | Open supplier create form, submit valid data | Supplier name and email | Supplier appears in supplier list | Supplier was created in database | Pass |
| TC-SUP-002 | Suppliers | Reject invalid supplier email | Equivalence Partitioning | User is logged in | Create supplier with invalid email | email = abc | Validation error is shown | Invalid email was rejected | Pass |
| TC-RESTOCK-001 | Restock | Create valid restock order | Happy Path | Supplier and item exist | Create restock order | Quantity 10, future expected date | Pending restock order is created | Pending restock order was created | Pass |
| TC-RESTOCK-002 | Restock | Reject receive quantity above ordered quantity | Boundary Value Analysis | Restock order quantity is 10 | Receive quantity 11 | quantity_received = 11 | Validation error is shown | Validation error was shown and order stayed pending after fix | Pass |

## Exploratory Test Charters

| Charter ID | Mission | Areas | Time Box | Risks To Hunt | Findings |
|---|---|---|---|---|---|
| CH-001 | Explore authentication reliability | Register, login, logout, protected pages | 30 minutes | Duplicate users, weak validation, session problems | Valid login works; duplicate registration and wrong password are rejected; protected API routes reject missing bearer tokens |
| CH-002 | Explore inventory data quality | Create, edit, delete inventory | 30 minutes | Duplicate SKU, negative values, decimal price problems | Unique item creation succeeds; duplicate SKU and negative stock are rejected |
| CH-003 | Explore stock update behavior | Add stock, remove stock, low stock state | 30 minutes | Stock below zero, incorrect status, race conditions | Removing more stock than available clamps current stock to 0 instead of making it negative |
| CH-004 | Explore supplier relationships | Supplier CRUD, supplier deletion with inventory | 30 minutes | Broken relationships, orphaned items, validation gaps | Supplier creation succeeds; invalid email is rejected; supplier relationship works during inventory creation |
| CH-005 | Explore restock workflow | Create order, partial receive, full receive | 30 minutes | Wrong totals, invalid dates, stock not updated | Validation rollback defect was found and fixed; over-receiving is now rejected and order remains pending |

## Bug Report Log

| Bug ID | Title | Severity | Priority | Environment | Steps To Reproduce | Expected Result | Actual Result | Evidence | Status |
|---|---|---|---|---|---|---|---|---|---|
| BUG-001 | Restock receive invalid quantity used unsafe rollback handling | Major | P2 | Chrome, Windows, Laravel local | 1. Create restock order with quantity ordered 10. 2. Submit receive request with quantity received 11. | Validation error is returned and restock order remains pending. | Initial test exposed unsafe rollback behavior; controller was fixed and verified. | `evidence/black-box-acceptance-output.txt` | Fixed / Verified |
| PERF-001 | Response time increases sharply under concurrent load | Major | P2 | K6 v2.0.0, Laravel local server, 1-50 VUs | Run K6 constant-load tests at 1, 10, 25, and 50 users. | System remains within acceptable response time under 50 users. | No HTTP failures, but p95 rose to 12.08s at 50 users. | `evidence/k6-performance-table.md`, `evidence/response-time-vs-user-load.svg` | Open / Performance bottleneck |

## Unit Test Evidence

Unit tests are in `tests/Unit/InventoryLogicTest.php`. They cover:

- Out-of-stock logic
- Low-stock logic
- In-stock logic
- Restock threshold boundaries
- Stock value calculation
- Restock unit cost calculation

## API Test Evidence

API routes are available under `/api/v1`. Import:

- `docs/postman/Stock_Controller_API.postman_collection.json`
- `docs/postman/Stock_Controller_API.postman_environment.json`

Run the collection in order. It covers authentication, supplier CRUD, inventory CRUD, stock updates, and restock receiving.

## UI Automation Flow Ideas

1. Login flow: visit `/login`, enter credentials, verify dashboard appears.
2. Inventory flow: login, create supplier, create inventory item, verify the item appears in inventory list.

## Performance Test Plan

Use JMeter or K6 against these URLs:

- Baseline: `GET /login` with 1 user
- Authenticated API load: `GET /api/v1/inventory` using a bearer token
- CRUD stress: repeated inventory creation with unique SKUs

Run with:

- 1 user baseline
- 10 users
- 25 users
- 50 users
- Increase until errors or unacceptable response time appear

Graph to include in the report: Response Time vs. User Load.

## Presentation Outline

| Slide | Content |
|---|---|
| 1 | Group members, Path B, Laravel Stock Controller tech stack |
| 2 | Testing strategy and 4 missions: auth, inventory, suppliers, restock |
| 3 | Two most interesting bugs with screenshots |
| 4 | Unit test result, Postman collection run, UI automation demo |
| 5 | Performance graph, breaking point, bottleneck explanation |
