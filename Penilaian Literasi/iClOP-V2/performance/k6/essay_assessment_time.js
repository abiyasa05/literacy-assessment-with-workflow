import http from 'k6/http';
import { check } from 'k6';

let testResults = [];

export const options = {
    scenarios: {
        essay_assessment_time: {
            executor: 'shared-iterations',
            vus: 1,
            iterations: 5,
            maxDuration: '60m'
        }
    }
};

const essayData = [

    {
        action: 'score',
        question_text: 'Mengapa Banjir dapat membuat kehidupan manusia menjadi kesulitan?',
        user_answer: 'Banjir membuat kehidupan menjadi sulit karena merusak banyak fasilitas dan rumah warga.',
        expected_answer: 'Banjir dapat membuat kehidupan manusia menjadi kesulitan karena menyebabkan kerusakan properti dan pertanian, serta menimpa kendaraan dan sarana transportasi.'
    },

    {
        action: 'score',
        question_text: 'Bagaimana orang-orang di daerah yang tertimpa banjir bisa membantu satu sama lain?',
        user_answer: 'Orang-orang dapat membantu dengan memberikan bantuan dan bekerja sama.',
        expected_answer: 'Orang-orang di daerah yang tertimpa banjir bisa membantu satu sama lain dengan bekerja bersama untuk membongkar tanah, mendirikan tenda-tenda pengungsian, dan berbagi makanan serta air.'
    },

    {
        action: 'score',
        question_text: 'Jelaskan bagaimana keadaan alam sekitar berubah selama banjir?',
        user_answer: 'Daerah sekitar menjadi tergenang air sehingga aktivitas terganggu.',
        expected_answer: 'Ketika terjadi banjir, keadaan alam sekitar berubah dengan cepat menjadi daerah yang terendam air, tanah longsor, dan pohon-pohon tertimbun lumpur.'
    },

    {
        action: 'score',
        question_text: 'Mengapa cerita tentang banjir sering menginspirasi orang untuk bekerja sama?',
        user_answer: 'Karena menunjukkan pentingnya saling membantu ketika terjadi bencana.',
        expected_answer: 'Cerita tentang banjir sering menginspirasi orang untuk bekerja sama karena mengungkapkan kekuatan kolektif dan kebersamaan dalam menghadapi musibah.'
    },

    {
        action: 'score',
        question_text: 'Apa yang menyebabkan banjir biasanya terjadi di daerah datar?',
        user_answer: 'Karena air tidak dapat mengalir dengan cepat ke tempat yang lebih rendah.',
        expected_answer: 'Banjir biasanya terjadi di daerah datar karena air tidak dapat mengalir dengan cepat ke tempat-tempat lebih rendah, sehingga air menggenang dan menimpa wilayah sekitar.'
    }
];

export default function () {

    const data =
        essayData[__ITER % essayData.length];

    const start =
        Date.now();

    const res =
        http.post(
            'http://localhost:5678/webhook/ai-gateway-rag',
            JSON.stringify(data),
            {
                headers: {
                    'Content-Type': 'application/json'
                },
                timeout: '30m'
            }
        );

    const duration =
        (Date.now() - start) / 1000;

    testResults.push({

        question:
            data.question_text,

        status:
            res.status,

        response_time_seconds:
            duration,

        response:
            res.body,

        tested_at:
            new Date().toISOString()
    });

    console.log(`
=================================
QUESTION:
${data.question_text}

STATUS:
${res.status}

TIME:
${duration} sec
=================================
`);

    check(res, {
        'status 200': (r) => r.status === 200
    });
}

export function handleSummary(data) {

    const timestamp =
        new Date()
            .toISOString()
            .replace(/[:.]/g, '-');

    const report = {

        generated_at:
            new Date().toISOString(),

        total_essay_questions:
            testResults.length,

        average_response_time_ms:
            data.metrics.http_req_duration.values.avg,

        max_response_time_ms:
            data.metrics.http_req_duration.values.max,

        min_response_time_ms:
            data.metrics.http_req_duration.values.min,

        p95_response_time_ms:
            data.metrics.http_req_duration.values['p(95)'],

        failed_request_rate:
            data.metrics.http_req_failed.values.rate,

        results:
            testResults
    };

    return {

        [`testing_results/essay_assessment_time/essay_assessment_time_${timestamp}.json`]:
            JSON.stringify(report, null, 4),

        stdout:
            JSON.stringify(report, null, 4)
    };
}