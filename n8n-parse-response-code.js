// Node Parse Response - untuk parsing respons LLM
// Updated code untuk n8n

const body = $json;
const answer_id = $('Switch').item.json.answer_id;

let result;
try {
  // Parse response dari LLM
  const response = body.choices[0].message.content;
  result = JSON.parse(response);
  
  // Validasi bahwa semua field yang diperlukan ada
  if (!result.relevansi || !result.kelengkapan || !result.kejelasan) {
    throw new Error('Response tidak memiliki semua field yang diperlukan');
  }
  
  // Pastikan feedback ada, jika tidak berikan default
  if (!result.feedback) {
    result.feedback = 'Jawaban sudah cukup baik.';
  }
  
} catch (e) {
  console.error('Error parsing LLM response:', e);
  // Jika parsing gagal, berikan nilai default
  result = {
    relevansi: 50,
    kelengkapan: 50,
    kejelasan: 50,
    feedback: 'Maaf, terjadi kesalahan dalam penilaian. Silakan coba lagi.'
  };
}

// Return hasil tanpa final_score (akan dihitung di Laravel)
return {
  answer_id: answer_id,
  relevansi: parseInt(result.relevansi) || 0,
  kelengkapan: parseInt(result.kelengkapan) || 0,
  kejelasan: parseInt(result.kejelasan) || 0,
  feedback: result.feedback || ''
};
