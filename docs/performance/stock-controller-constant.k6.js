import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    vus: Number(__ENV.VUS || 1),
    duration: __ENV.DURATION || '20s',
};

const baseUrl = __ENV.BASE_URL || 'http://127.0.0.1:8000';

export function setup() {
    const unique = `${Date.now()}-${__ENV.VUS || 1}`;
    const email = `k6.constant.${unique}@example.com`;
    const password = 'password123';

    http.post(`${baseUrl}/api/v1/register`, JSON.stringify({
        name: `K6 Constant Tester ${unique}`,
        email,
        password,
        password_confirmation: password,
    }), {
        headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
    });

    const login = http.post(`${baseUrl}/api/v1/login`, JSON.stringify({
        email,
        password,
    }), {
        headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
    });

    return { token: login.json('token') };
}

export default function (data) {
    const loginPage = http.get(`${baseUrl}/login`);
    check(loginPage, {
        'login page returns 200': (res) => res.status === 200,
    });

    const inventory = http.get(`${baseUrl}/api/v1/inventory`, {
        headers: {
            Accept: 'application/json',
            Authorization: `Bearer ${data.token}`,
        },
    });

    check(inventory, {
        'inventory API returns 200': (res) => res.status === 200,
        'inventory API returns data array': (res) => Array.isArray(res.json('data')),
    });

    sleep(1);
}
