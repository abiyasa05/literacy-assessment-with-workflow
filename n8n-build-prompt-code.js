// Node Build Prompt - Feedback and Score
// Updated code untuk n8n
const body = $json;

// Ambil data dari request body (bukan nested dalam 'body')
const questionText = body.question_text;
const userAnswer = body.user_answer;
const expectedAnswer = body.expected_answer;

/* ===============================
   VALIDASI INPUT
================================ */
if (!questionText || !userAnswer || !expectedAnswer) {
  console.error('Data tidak lengkap:', {
    has_question_text: !!questionText,
    has_user_answer: !!userAnswer,
    has_expected_answer: !!expectedAnswer
  });
  throw new Error("Data penilaian esai tidak lengkap");
}

/* ===============================
   BUILD PROMPT GABUNGAN
================================ */
const prompt = `
Kamu adalah sistem penilai jawaban esai siswa SD kelas 3–5.

==================================================
TUGAS:
==================================================
1. Nilai jawaban siswa berdasarkan 3 aspek:
   - Relevansi (0–100)
   - Kelengkapan (0–100)
   - Kejelasan (0–100)

2. Berikan juga feedback singkat yang membantu siswa memahami jawabannya.

==================================================
ATURAN WAJIB:
==================================================
- Nilai harus bilangan bulat 0–100.
- Feedback maksimal 2–3 kalimat.
- Bahasa sederhana dan ramah anak.
- Jangan gunakan markdown atau simbol khusus.
- Jangan menambahkan teks di luar JSON.
- Output HARUS JSON VALID saja.

==================================================
FORMAT OUTPUT WAJIB:
==================================================
{
  "relevansi": 0-100,
  "kelengkapan": 0-100,
  "kejelasan": 0-100,
  "feedback": "teks feedback singkat"
}

==================================================
DATA PENILAIAN:
==================================================
Soal:
${questionText}

Jawaban siswa:
${userAnswer}

Jawaban ideal:
${expectedAnswer}

INGAT:
Jika output bukan JSON valid, maka dianggap salah.
`.trim();

return {
  prompt
};
