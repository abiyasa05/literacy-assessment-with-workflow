import http from 'k6/http';
import { check } from 'k6';

let testResults = [];

export const options = {

    scenarios: {

        concurrent_generate_question: {

            executor: 'per-vu-iterations',

            vus:
                Number(__ENV.VUS || 5),

            iterations: 1,

            maxDuration: '90m'
        }
    }
};

export default function () {

    const baseUrl =
        'http://localhost:8000';

    const materialId =
        Number(__ENV.MATERIAL_ID || 20);

    const questionType =
        __ENV.QUESTION_TYPE || 'essay';

    const questionCount =
        Number(__ENV.QUESTION_COUNT || 5);

    const startPage =
        Number(__ENV.START_PAGE || 25);

    const maxChars =
        Number(__ENV.MAX_CHARS || 5000);

    /*
    ==================================
    GET CSRF
    ==================================
    */

    const csrfPage = http.get(
        `${baseUrl}/signup`
    );

    const csrfMatch =
        csrfPage.body.match(
            /name="_token"\s+value="([^"]+)"/
        );

    if (!csrfMatch) {

        console.log(
            'CSRF TOKEN TIDAK DITEMUKAN'
        );

        return;
    }

    const csrfToken =
        csrfMatch[1];

    /*
    ==================================
    LOGIN
    ==================================
    */

    const loginRes =
        http.post(
            `${baseUrl}/login`,
            {
                _token:
                    csrfToken,

                email:
                    'hani@gmail.com',

                password:
                    '123456'
            },
            {
                redirects: 0
            }
        );

    check(loginRes, {
        'login success':
            (r) =>
                r.status === 302
    });

    const sessionCookie =
        loginRes.cookies
            .laravel_session
            ? loginRes.cookies
                .laravel_session[0]
                .value
            : '';

    if (!sessionCookie) {

        console.log(
            'SESSION LOGIN GAGAL'
        );

        return;
    }

    const jar =
        http.cookieJar();

    jar.set(
        baseUrl,
        'laravel_session',
        sessionCookie
    );

    /*
    ==================================
    GENERATE QUESTION
    ==================================
    */

    const payload =
        JSON.stringify({

            material_id:
                materialId,

            question_type:
                questionType,

            question_count:
                questionCount,

            start_page:
                startPage,

            max_chars:
                maxChars
        });

    const params = {

        headers: {

            'Content-Type':
                'application/json',

            'Accept':
                'application/json',

            'X-CSRF-TOKEN':
                csrfToken,

            'X-Requested-With':
                'XMLHttpRequest'
        },

        timeout:
            '90m'
    };

    const startTime =
        Date.now();

    console.log(
        `VU ${__VU} START GENERATE`
    );

    const res =
        http.post(
            `${baseUrl}/literacy/teacher/generate_questions/ai`,
            payload,
            params
        );

    const duration =
        (Date.now() - startTime)
        / 1000;

    console.log(
        `VU ${__VU} FINISH (${duration} sec)`
    );

    testResults.push({

        vu:
            __VU,

        status_code:
            res.status,

        success:
            res.status === 200,

        response_time_seconds:
            duration,

        generated_at:
            new Date()
                .toISOString()
    });

    check(res, {

        'generate success':
            (r) =>
                r.status === 200
    });
}

/*
==================================
SAVE REPORT
==================================
*/

export function handleSummary(data) {

    const timestamp =
        new Date()
            .toISOString()
            .replace(
                /[:.]/g,
                '-'
            );

    const vus =
        Number(
            __ENV.VUS || 5
        );

    const report = {

        generated_at:
            new Date()
                .toISOString(),

        concurrent_users:
            vus,

        material_id:
            Number(
                __ENV.MATERIAL_ID || 20
            ),

        question_type:
            __ENV.QUESTION_TYPE
            || 'essay',

        question_count:
            Number(
                __ENV.QUESTION_COUNT || 5
            ),

        total_requests:
            data.metrics.http_reqs
                ?.values?.count || 0,

        avg_response_time_ms:
            data.metrics.http_req_duration
                ?.values?.avg || 0,

        max_response_time_ms:
            data.metrics.http_req_duration
                ?.values?.max || 0,

        p95_response_time_ms:
            data.metrics.http_req_duration
                ?.values?.['p(95)'] || 0,

        failed_request_rate:
            data.metrics.http_req_failed
                ?.values?.rate || 0,

        success_rate:
            (
                1 -
                (
                    data.metrics.http_req_failed
                        ?.values?.rate || 0
                )
            ) * 100,

        test_results:
            testResults
    };

    return {

        [`testing_results/concurrent_users/concurrent_users_${vus}_users_${timestamp}.json`]:
            JSON.stringify(
                report,
                null,
                4
            ),

        stdout:
            JSON.stringify(
                report,
                null,
                4
            )
    };
}