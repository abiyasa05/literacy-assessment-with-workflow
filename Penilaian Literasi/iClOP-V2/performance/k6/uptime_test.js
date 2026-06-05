import http from 'k6/http';
import { check } from 'k6';

let uptimeResults = [];

export const options = {

    scenarios: {

        uptime_test: {

            executor: 'constant-arrival-rate',

            rate: 1,

            timeUnit: '30s',

            duration: '1h',

            preAllocatedVUs: 1,

            maxVUs: 5
        }
    }
};

export default function () {

    const start = Date.now();

    const res = http.get(
        'http://localhost:8000/health'
    );

    const responseTime =
        (Date.now() - start) / 1000;

    const success =
        res.status === 200;

    uptimeResults.push({

        checked_at:
            new Date().toISOString(),

        status_code:
            res.status,

        response_time_seconds:
            responseTime,

        success:
            success
    });

    check(res, {
        'status 200': (r) =>
            r.status === 200
    });
}

export function handleSummary(data) {

    const timestamp = new Date()
        .toISOString()
        .replace(/[:.]/g, '-');

    const totalRequests =
        data.metrics.http_reqs?.values?.count || 0;

    const failedRate =
        data.metrics.http_req_failed?.values?.rate || 0;

    const successRate =
        (1 - failedRate) * 100;

    const report = {

        generated_at:
            new Date().toISOString(),

        uptime_percentage:
            successRate,

        total_requests:
            totalRequests,

        failed_request_rate:
            failedRate,

        avg_response_time_ms:
            data.metrics.http_req_duration?.values?.avg || 0,

        max_response_time_ms:
            data.metrics.http_req_duration?.values?.max || 0,

        min_response_time_ms:
            data.metrics.http_req_duration?.values?.min || 0,

        p95_response_time_ms:
            data.metrics.http_req_duration?.values?.['p(95)'] || 0,

        checks:
            uptimeResults
    };

    return {

        [`testing_results/uptime/uptime_${timestamp}.json`]:
            JSON.stringify(report, null, 4),

        stdout:
            JSON.stringify(report, null, 4)
    };
}