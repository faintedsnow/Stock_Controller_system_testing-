import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
    stages: [
        { duration: '30s', target: 1 },
        { duration: '1m', target: 10 },
        { duration: '1m', target: 25 },
        { duration: '1m', target: 50 },
        { duration: '30s', target: 0 },
    ],
    thresholds: {
        http_req_failed: ['rate<0.05'],
        http_req_duration: ['p(95)<2000'],
    },
};

const baseUrl = __ENV.BASE_URL || 'http://127.0.0.1:8000';

export function setup() {
    const unique = `${Date.now()}`;
    const email = `k6.${unique}@example.com`;
    const password = 'password123';

    http.post(`${baseUrl}/api/v1/register`, JSON.stringify({
        name: `K6 Tester ${unique}`,
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

    return {
        token: login.json('token'),
    };
}

export default function (data) {
    const webLogin = http.get(`${baseUrl}/login`);
    check(webLogin, {
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
