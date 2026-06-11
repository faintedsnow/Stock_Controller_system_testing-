import { expect, test } from '@playwright/test';

const password = 'password123';

async function createUser(request, suffix) {
    const email = `e2e.${suffix}@example.com`;

    await request.post('/api/v1/register', {
        data: {
            name: `E2E Tester ${suffix}`,
            email,
            password,
            password_confirmation: password,
        },
    });

    return email;
}

async function login(page, email) {
    await page.goto('/login');
    await page.locator('input[name="email"]').fill(email);
    await page.locator('input[name="password"]').fill(password);
    await page.locator('button[type="submit"]').click();
    await expect(page).toHaveURL(/\/dashboard$/);
}

test('E2E-001 user can log in and reach the dashboard', async ({ page, request }) => {
    const suffix = Date.now();
    const email = await createUser(request, suffix);

    await login(page, email);

    await expect(page.locator('body')).toContainText('Stock');
});

test('E2E-002 user can create a supplier and inventory item', async ({ page, request }) => {
    const suffix = Date.now();
    const email = await createUser(request, suffix);
    const supplierName = `E2E Supplier ${suffix}`;
    const itemName = `E2E USB Cable ${suffix}`;
    const sku = `E2E-${suffix}`;

    await login(page, email);

    await page.goto('/suppliers/create');
    await page.locator('input[name="name"]').fill(supplierName);
    await page.locator('input[name="contact_person"]').fill('Dara QA');
    await page.locator('input[name="email"]').fill(`supplier.${suffix}@example.com`);
    await page.locator('input[name="phone"]').fill('012345678');
    await page.locator('textarea[name="address"]').fill('Phnom Penh');
    await page.locator('form[action$="/suppliers"] button[type="submit"]').click();
    await expect(page).toHaveURL(/\/suppliers$/);
    await expect(page.locator('body')).toContainText(supplierName);

    await page.goto('/inventory/create');
    await page.locator('input[name="name"]').fill(itemName);
    await page.locator('input[name="sku"]').fill(sku);
    await page.locator('textarea[name="description"]').fill('Created by UI automation');
    await page.locator('input[name="current_stock"]').fill('20');
    await page.locator('input[name="minimum_stock"]').fill('5');
    await page.locator('input[name="price"]').fill('3.50');
    const supplierValue = await page.locator('select[name="supplier_id"] option', { hasText: supplierName }).getAttribute('value');
    await page.locator('select[name="supplier_id"]').selectOption(supplierValue);
    await page.locator('form[action$="/inventory"] button[type="submit"]').click();

    await expect(page).toHaveURL(/\/inventory$/);
    await expect(page.locator('body')).toContainText(itemName);
    await expect(page.locator('body')).toContainText(sku);
});
