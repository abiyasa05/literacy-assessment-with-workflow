import http from 'k6/http';
import { check } from 'k6';
import { Trend } from 'k6/metrics';

const generationTime = new Trend('question_generation_time');

let testResults = [];

export const options = {

    scenarios: {

        default: {

            executor: 'shared-iterations',

            vus: 1,

            iterations: 1,

            maxDuration: '90m'
        }
    }
};

export default function () {

    const baseUrl = 'http://localhost:8000';

    /*
    ==================================
    CONFIG
    ==================================
    */

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
    GET CSRF TOKEN
    ==================================
    */

    const csrfPage = http.get(
        `${baseUrl}/signup`
    );

    const csrfMatch = csrfPage.body.match(
        /name="_token"\s+value="([^"]+)"/
    );

    if (!csrfMatch) {

        console.log(
            'CSRF TOKEN TIDAK DITEMUKAN'
        );

        return;
    }

    const csrfToken = csrfMatch[1];

    console.log(
        `CSRF TOKEN : ${csrfToken}`
    );

    /*
    ==================================
    LOGIN
    ==================================
    */

    const loginPayload = {
        _token: csrfToken,
        email: 'hani@gmail.com',
        password: '123456',
    };

    const loginRes = http.post(
        `${baseUrl}/login`,
        loginPayload,
        {
            redirects: 0,
        }
    );

    console.log(
        `LOGIN STATUS : ${loginRes.status}`
    );

    console.log(
        `LOGIN REDIRECT : ${
            loginRes.headers.Location || '-'
        }`
    );

    check(loginRes, {
        'login success': (r) =>
            r.status === 302,
    });

    /*
    ==================================
    SESSION LOGIN
    ==================================
    */

    const loginSession =
        loginRes.cookies.laravel_session
            ? loginRes.cookies.laravel_session[0].value
            : '';

    if (!loginSession) {

        console.log(
            'SESSION LOGIN TIDAK DITEMUKAN'
        );

        return;
    }

    const jar = http.cookieJar();

    jar.set(
        baseUrl,
        'laravel_session',
        loginSession
    );

    /*
    ==================================
    TEST HALAMAN TEACHER
    ==================================
    */

    const teacherPage = http.get(
        `${baseUrl}/literacy/teacher/generate_questions`
    );

    console.log(
        `TEACHER PAGE STATUS : ${teacherPage.status}`
    );

    /*
    ==================================
    GENERATE QUESTION
    ==================================
    */

    const payload = JSON.stringify({
        material_id: materialId,
        question_type: questionType,
        question_count: questionCount,
        start_page: startPage,
        max_chars: maxChars,
    });

    console.log(
        `MATERIAL ID : ${materialId}`
    );

    console.log(
        `QUESTION TYPE : ${questionType}`
    );

    console.log(
        `QUESTION COUNT : ${questionCount}`
    );

    const params = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        timeout: '90m',
    };

    const start = Date.now();

    const res = http.post(
        `${baseUrl}/literacy/teacher/generate_questions/ai`,
        payload,
        params
    );

    const responseTime =
        (Date.now() - start) / 1000;

    generationTime.add(
        res.timings.duration
    );

    /*
    ==================================
    PARSE RESPONSE
    ==================================
    */

    const generateStatus =
        res.status === 200
            ? 'success'
            : 'failed';

    const generatedQuestions =
        res.body || '';

    testResults.push({

        generated_at:
            new Date().toISOString(),

        material_id:
            materialId,

        question_type:
            questionType,

        question_count:
            questionCount,

        status:
            generateStatus,

        generation_time_seconds:
            responseTime,

        generated_questions:
            generatedQuestions
    });

    /*
    ==================================
    OUTPUT
    ==================================
    */

    console.log(`
========================================
GENERATE STATUS : ${res.status}
GENERATE TIME   : ${responseTime} sec
CONTENT TYPE    : ${res.headers['Content-Type']}
========================================
`);

    console.log(`
========================================
GENERATED QUESTIONS
========================================
`);

    console.log(
        generatedQuestions.substring(0, 10000)
    );

    /*
    ==================================
    VALIDATION
    ==================================
    */

    check(res, {

        'status 200': (r) =>
            r.status === 200,

        'response not empty': (r) =>
            r.body &&
            r.body.length > 0,
    });
}

/*
==================================
SAVE REPORT
==================================
*/

export function handleSummary(data) {

    const timestamp = new Date()
        .toISOString()
        .replace(/[:.]/g, '-');

    const questionType =
        __ENV.QUESTION_TYPE || 'multiple_choice';

    const materialId =
        Number(__ENV.MATERIAL_ID || 21);

    const report = {

        generated_at:
            new Date().toISOString(),

        material_id:
            materialId,

        question_type:
            questionType,

        question_count:
            Number(__ENV.QUESTION_COUNT || 5),

        test_results:
            testResults,

        metrics: {

            avg_duration_ms:
                data.metrics.http_req_duration?.values?.avg || 0,

            max_duration_ms:
                data.metrics.http_req_duration?.values?.max || 0,

            min_duration_ms:
                data.metrics.http_req_duration?.values?.min || 0,

            p95_duration_ms:
                data.metrics.http_req_duration?.values?.['p(95)'] || 0,

            total_requests:
                data.metrics.http_reqs?.values?.count || 0,

            failed_request_rate:
                data.metrics.http_req_failed?.values?.rate || 0
        }
    };

    return {

        [`testing_results/generate_questions/${questionType}_material_${materialId}_${timestamp}.json`]:
            JSON.stringify(report, null, 4),

        stdout:
            JSON.stringify(report, null, 4)
    };
}