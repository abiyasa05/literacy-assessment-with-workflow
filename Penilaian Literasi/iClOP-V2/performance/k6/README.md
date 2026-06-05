Performance tests using k6

Requirements
- Install k6: https://k6.io/docs/getting-started/installation/
- Run these tests from a machine that can reach your app (and N8N/FastAPI if needed).

Environment variables
- BASE_URL: base URL of the Laravel app (default http://localhost:8000)
- TEACHER_EMAIL, TEACHER_PASS: credentials for a teacher account
- MATERIAL_ID: material id used for question generation
- STUDENT_EMAIL, STUDENT_PASS: credentials for a student account
- ASSESSMENT_ID: assessment id to submit for essay scoring
- K6_VUS, K6_DURATION: override default VUs and duration

Example: run question generation test

```bash
BASE_URL=http://localhost:8000 TEACHER_EMAIL=teacher@example.com TEACHER_PASS=secret MATERIAL_ID=1 K6_VUS=10 K6_DURATION=30s \
  k6 run generate_questions_test.js
```

Example: run essay assessment test

```bash
BASE_URL=http://localhost:8000 STUDENT_EMAIL=student@example.com STUDENT_PASS=secret ASSESSMENT_ID=123 K6_VUS=5 K6_DURATION=30s \
  k6 run essay_assessment_test.js
```

Notes
- `generate_questions_test.js` hits `/literacy/teacher/generate_questions/ai` which in turn calls N8N; ensure N8N and model services are available.
- `essay_assessment_test.js` hits `/literacy/student/assessment/submit/{id}` which triggers essay scoring and N8N interactions.
- The scripts assume session-based authentication via `/login` returning Laravel session cookie.
- For CI, run k6 in a dedicated job and collect the JSON summary for artifact storage.
