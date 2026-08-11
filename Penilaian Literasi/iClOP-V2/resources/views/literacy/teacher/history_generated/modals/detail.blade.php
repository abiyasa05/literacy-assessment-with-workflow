{{--
    =====================================================================
    PARTIAL: Detail Hasil Generated (versi widget)
    =====================================================================
    Cara pakai:
    1. Taruh blok <style> ini di halaman histori Anda (kalau halaman
       histori ini beda file dari halaman "Generate Questions" yang
       sudah diperbaiki sebelumnya). Kalau satu file, boleh dihapus
       karena sudah ada.
    2. Ganti modal lama Anda dengan modal @foreach di bawah ini
       (letakkan di dalam @foreach($generatedHistories as $history_generated_text) ... @endforeach
       yang sudah ada di halaman Anda).
    3. Taruh blok <script> di bagian bawah halaman (sekali saja,
       di luar @foreach), tidak perlu diulang per item.
    ===================================================================== --}}


{{-- ================= CSS WIDGET (taruh di <head> / sebelum </body>) ================= --}}
<style>
    .generated-questions-wrapper {
        margin-top: 20px;
    }

    .story-widget {
        background: #ffffff;
        border: 1px solid #e2e6ea;
        border-radius: 12px;
        margin-bottom: 18px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .story-widget-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 13px 18px;
        background: #f8f9fa;
        border-bottom: 1px solid #e2e6ea;
        font-size: 15px;
        font-weight: 600;
        color: #34364A;
    }

    .story-widget-header i {
        color: #1A79E3;
        font-size: 18px;
    }

    .story-content {
        padding: 18px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        line-height: 1.8;
        color: #444;
        white-space: pre-wrap;
        max-height: 220px;
        overflow-y: auto;
    }

    .generated-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 5px 5px 12px 5px;
    }

    .generated-header h5 {
        font-size: 18px;
        font-weight: 600;
        color: #34364A;
        margin: 0;
    }

    .generated-header h5 i {
        color: #1A79E3;
        margin-right: 5px;
    }

    .questions-scroll-area {
        max-height: 400px;
        overflow-y: auto;
        padding: 5px 8px 15px 5px;
        border-radius: 12px;
    }

    .questions-scroll-area::-webkit-scrollbar,
    .story-content::-webkit-scrollbar {
        width: 7px;
    }

    .questions-scroll-area::-webkit-scrollbar-track,
    .story-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .questions-scroll-area::-webkit-scrollbar-thumb,
    .story-content::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 10px;
    }

    .questions-scroll-area::-webkit-scrollbar-thumb:hover,
    .story-content::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    .question-widget {
        background: #ffffff;
        border: 1px solid #e2e6ea;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 14px;
        box-shadow: 0 2px 7px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .question-widget:hover {
        border-color: #b9d7f7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .question-widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .question-header-tags {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .question-number {
        font-size: 14px;
        font-weight: 600;
        color: #1A79E3;
        background: #eef6ff;
        padding: 6px 11px;
        border-radius: 20px;
    }

    .question-weight {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #9a6b00;
        background: #fff6e0;
        padding: 5px 10px;
        border-radius: 15px;
    }

    .question-aspect {
        font-size: 12px;
        color: #6c757d;
        background: #f5f5f5;
        padding: 5px 10px;
        border-radius: 15px;
    }

    .question-text {
        font-size: 15px;
        font-weight: 500;
        line-height: 1.7;
        color: #34364A;
        margin-bottom: 14px;
    }

    .answer-option {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 12px;
        margin-bottom: 8px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        background: #fafafa;
        font-size: 14px;
        line-height: 1.5;
    }

    .answer-option.correct {
        background: #f0fff4;
        border-color: #9ed8ad;
    }

    .option-letter {
        min-width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e9ecef;
        font-size: 12px;
        font-weight: 600;
    }

    .answer-option.correct .option-letter {
        background: #198754;
        color: white;
    }

    .correct-answer {
        margin-top: 12px;
        padding: 10px 12px;
        background: #f0fff4;
        border-radius: 8px;
        color: #198754;
        font-size: 13px;
        font-weight: 500;
    }

    .reference-answer {
        margin-top: 12px;
        padding: 12px;
        background: #f8f9fa;
        border-left: 4px solid #1A79E3;
        border-radius: 6px;
        font-size: 13px;
        line-height: 1.6;
    }

    .reference-answer strong {
        color: #34364A;
    }

    .empty-question-state {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 150px;
        color: #999;
        text-align: center;
    }

    .empty-question-state i {
        font-size: 36px;
        margin-bottom: 10px;
        color: #b8c2cc;
    }

    .empty-question-state p {
        margin: 0;
        font-size: 14px;
    }

    .widget-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12.5px;
        font-weight: 500;
        color: #34364A;
        background: #ffffff;
        border: 1px solid #dbe1e8;
        border-radius: 7px;
        padding: 5px 10px;
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1.2;
    }

    .widget-action-btn:hover {
        background: #eef6ff;
        border-color: #1A79E3;
        color: #1A79E3;
    }

    .widget-action-btn.copied {
        background: #e6f7ec;
        border-color: #2fb866;
        color: #1e9e56;
    }

    .story-widget-header .widget-action-btn {
        margin-left: auto;
    }

    .generated-header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .plain-text-view {
        display: none;
        background: #f8f9fa;
        border: 1px solid #e2e6ea;
        border-radius: 12px;
        padding: 16px;
        margin-top: 5px;
    }

    .plain-text-view.active {
        display: block;
    }

    .plain-text-view textarea {
        width: 100%;
        min-height: 220px;
        max-height: 400px;
        border: none;
        background: transparent;
        resize: vertical;
        font-family: 'Poppins', sans-serif;
        font-size: 13.5px;
        line-height: 1.7;
        color: #444;
    }

    .plain-text-view textarea:focus {
        outline: none;
    }

    .questions-scroll-area.hidden-view {
        display: none;
    }

    .raw-text-source {
        display: none;
    }
</style>


{{-- ================= MODAL (taruh di dalam @foreach histori Anda) ================= --}}
<div class="modal fade" id="detailGeneratedTextModal{{ $history_generated_text->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generated Result Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                {{-- Teks mentah disimpan tersembunyi, dipakai JS untuk parsing & tombol salin --}}
                <textarea class="raw-text-source"
                    id="rawText{{ $history_generated_text->id }}">{{ $history_generated_text->generate_text }}</textarea>

                <div class="generated-questions-wrapper">

                    {{-- Reading Text --}}
                    <div class="story-widget">
                        <div class="story-widget-header">
                            <i class="bi bi-book"></i>
                            <span>Reading Text</span>
                            <button type="button" class="widget-action-btn"
                                data-copy-story="{{ $history_generated_text->id }}">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <div id="storyContent{{ $history_generated_text->id }}" class="story-content"></div>
                    </div>

                    {{-- Header Generated Questions --}}
                    <div class="generated-header">
                        <div>
                            <h5 class="mb-1">
                                <i class="bi bi-stars"></i>
                                Generated Questions
                            </h5>
                            <small id="questionCountLabel{{ $history_generated_text->id }}" class="text-muted"></small>
                        </div>

                        <div class="generated-header-actions">
                            <button type="button" class="widget-action-btn"
                                data-copy-all="{{ $history_generated_text->id }}">
                                <i class="bi bi-clipboard-check"></i> Copy All
                            </button>
                        </div>
                    </div>

                    {{-- Widget View --}}
                    <div id="questionsScrollArea{{ $history_generated_text->id }}" class="questions-scroll-area"></div>

                    {{-- Plain Text View --}}
                    <div id="plainTextView{{ $history_generated_text->id }}" class="plain-text-view">
                        <textarea id="plainTextArea{{ $history_generated_text->id }}" readonly></textarea>
                    </div>

                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Question Type:</label>
                    <div class="form-control bg-light">
                        {{ $history_generated_text->question_type == 'multiple_choice' ? 'Multiple Choice' : 'Short Answer' }}
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Number of Questions:</label>
                    <div class="form-control bg-light">{{ $history_generated_text->question_count }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


{{-- ================= SCRIPT (taruh SEKALI SAJA di bawah halaman, di luar @foreach) ================= --}}
<script>
    (function () {

        function escapeHtml(text) {
            if (!text) return "";
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        /*
        * ==========================================
        * Salin ke clipboard (dengan fallback)
        * ==========================================
        */
        function copyToClipboard(text, buttonEl) {
            if (!text) return;

            const finishFeedback = () => {
                if (!buttonEl) return;
                const originalHtml = buttonEl.innerHTML;
                buttonEl.classList.add('copied');
                buttonEl.innerHTML = '<i class="bi bi-check2"></i> Copied!';
                setTimeout(() => {
                    buttonEl.classList.remove('copied');
                    buttonEl.innerHTML = originalHtml;
                }, 1800);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(finishFeedback).catch(() => {
                    fallbackCopy(text, finishFeedback);
                });
            } else {
                fallbackCopy(text, finishFeedback);
            }
        }

        function fallbackCopy(text, onDone) {
            const tempArea = document.createElement('textarea');
            tempArea.value = text;
            tempArea.style.position = 'fixed';
            tempArea.style.opacity = '0';
            document.body.appendChild(tempArea);
            tempArea.focus();
            tempArea.select();
            try {
                document.execCommand('copy');
                if (onDone) onDone();
            } catch (err) {
                console.error('Gagal menyalin teks:', err);
            }
            document.body.removeChild(tempArea);
        }

        /*
        * ==========================================
        * Parsing & render (dipakai per-item histori, id unik)
        * ==========================================
        */
        function renderHistoryDetail(id) {
            const rawTextEl = document.getElementById('rawText' + id);
            const storyContent = document.getElementById('storyContent' + id);
            const contentEl = document.getElementById('questionsScrollArea' + id);
            const countLabel = document.getElementById('questionCountLabel' + id);
            const plainTextArea = document.getElementById('plainTextArea' + id);
            const copyAllBtn = document.querySelector('[data-copy-all="' + id + '"]');
            const togglePlainTextBtn = document.querySelector('[data-toggle-plaintext="' + id + '"]');

            if (!rawTextEl || !contentEl) return;

            // Sudah pernah dirender sebelumnya (modal dibuka lagi) -> tidak perlu ulang
            if (contentEl.dataset.rendered === '1') return;

            const rawText = rawTextEl.value;

            plainTextArea.value = rawText;
            if (copyAllBtn) copyAllBtn.disabled = !rawText || !rawText.trim();
            if (togglePlainTextBtn) togglePlainTextBtn.disabled = !rawText || !rawText.trim();

            // Pastikan tampilan awal: widget terlihat, teks polos tersembunyi
            // (di-set langsung lewat inline style supaya tidak bergantung
            // pada CSS eksternal yang mungkin belum ter-include di halaman ini)
            const plainViewEl = document.getElementById('plainTextView' + id);
            if (plainViewEl) plainViewEl.style.display = 'none';
            contentEl.style.display = '';

            if (!rawText || !rawText.trim()) {
                storyContent.innerHTML = `<p class="text-muted mb-0">Story text not found in the generated result.</p>`;
                contentEl.innerHTML = `
                    <div class="empty-question-state">
                        <i class="bi bi-exclamation-circle"></i>
                        <p>Generate result is empty.</p>
                    </div>
                `;
                countLabel.innerText = "Tidak ada soal";
                contentEl.dataset.rendered = '1';
                return;
            }

            /*
            * 1. Ambil bagian Paragraf / cerita
            */
            let story = "";

            const paragraphMatch = rawText.match(
                /\*\*Paragra(?:f|ph):\*\*\s*([\s\S]*?)(?=\*\*Soal|\*\*Pertanyaan|$)/i
            );
            if (paragraphMatch) story = paragraphMatch[1].trim();

            if (!story) {
                const paragraphMatch2 = rawText.match(
                    /Paragra(?:f|ph):\s*([\s\S]*?)(?=Soal Pilihan Ganda:|Soal Isian:|$)/i
                );
                if (paragraphMatch2) story = paragraphMatch2[1].trim();
            }

            storyContent.innerHTML = story
                ? escapeHtml(story)
                : `<p class="text-muted mb-0">Story text not found in the generated result.</p>`;

            /*
            * 2. Pisahkan soal
            */
            const questionRegex = /(?:^|\n)\s*(\d+)\.\s+([\s\S]*?)(?=\n\s*\d+\.\s+|$)/g;
            const questions = [];
            let match;

            while ((match = questionRegex.exec(rawText)) !== null) {
                questions.push({ number: match[1], text: match[2].trim() });
            }

            if (questions.length === 0) {
                contentEl.innerHTML = `
                    <div class="question-widget">
                        <div class="question-widget-header">
                            <span class="question-number">Generate Result</span>
                        </div>
                        <div class="question-text" style="white-space: pre-wrap;">
                            ${escapeHtml(rawText)}
                        </div>
                    </div>
                `;
                countLabel.innerText = "Generate Result";
                contentEl.dataset.rendered = '1';
                return;
            }

            /*
            * 3. Render setiap soal
            */
            contentEl.innerHTML = "";

            questions.forEach((question) => {
                const block = question.text;

                let aspect = "";
                const aspectMatch = block.match(/Aspek:\s*(.*?)(?=\n|$)/i);
                if (aspectMatch) aspect = aspectMatch[1].trim();

                let weight = "";
                const weightMatch = block.match(/Bobot:\s*(\d+)/i);
                if (weightMatch) weight = weightMatch[1].trim();

                let answer = "";
                const answerMatch = block.match(/Jawaban:\s*([A-D])/i);
                if (answerMatch) answer = answerMatch[1].toUpperCase();

                let questionText = block
                    .replace(/Aspek:\s*.*?(?=\n|$)/i, "")
                    .replace(/Bobot:\s*\d+.*?(?=\n|$)/i, "")
                    .replace(/Jawaban:\s*[A-D].*?(?=\n|$)/i, "")
                    .trim();

                const optionRegex =
                    /(?:^|\n)\s*([A-D])\.\s*(.*?)(?=\n\s*[A-D]\.\s*|\n\s*Jawaban:|$)/g;

                const options = [];
                let optionMatch;

                while ((optionMatch = optionRegex.exec(questionText)) !== null) {
                    options.push({ letter: optionMatch[1], text: optionMatch[2].trim() });
                }

                questionText = questionText
                    .replace(/(?:^|\n)\s*[A-D]\.\s*[\s\S]*?(?=\n\s*Jawaban:|$)/i, "")
                    .trim();

                const card = document.createElement("div");
                card.className = "question-widget";

                let optionsHtml = "";

                if (options.length > 0) {
                    optionsHtml = `
                        <div class="answer-options">
                            ${options.map(option => {
                                const isCorrect = answer === option.letter;
                                return `
                                    <div class="answer-option ${isCorrect ? 'correct' : ''}">
                                        <span class="option-letter">${option.letter}</span>
                                        <span>${escapeHtml(option.text)}</span>
                                    </div>
                                `;
                            }).join("")}
                        </div>
                    `;
                } else {
                    const referenceMatch = block.match(
                        /Jawaban:\s*([\s\S]*?)(?=\n\s*Bobot:|$)/i
                    );
                    let referenceAnswer = referenceMatch ? referenceMatch[1].trim() : "";

                    optionsHtml = referenceAnswer
                        ? `
                            <div class="reference-answer">
                                <strong>Jawaban Referensi:</strong>
                                <div class="mt-1">${escapeHtml(referenceAnswer)}</div>
                            </div>
                        `
                        : "";
                }

                card.innerHTML = `
                    <div class="question-widget-header">
                        <span class="question-number">Soal ${question.number}</span>

                        <div class="question-header-tags">
                            ${weight ? `<span class="question-weight"><i class="bi bi-award"></i> Bobot ${escapeHtml(weight)}</span>` : ""}
                            ${aspect ? `<span class="question-aspect">${escapeHtml(aspect)}</span>` : ""}
                        </div>
                    </div>

                    <div class="question-text">${escapeHtml(questionText)}</div>

                    ${optionsHtml}

                    ${answer && options.length > 0 ? `
                        <div class="correct-answer">
                            <i class="bi bi-check-circle-fill"></i>
                            Jawaban benar: <strong>${answer}</strong>
                        </div>
                    ` : ""}
                `;

                contentEl.appendChild(card);
            });

            countLabel.innerText = `${questions.length} question was successfully made`;
            contentEl.dataset.rendered = '1';
        }

        /*
        * ==========================================
        * Trigger render saat modal dibuka (lazy render)
        * ==========================================
        */

        // Bootstrap 5 (native event, bubbles ke document)
        document.addEventListener('shown.bs.modal', function (e) {
            const modal = e.target;
            if (modal && modal.id && modal.id.indexOf('detailGeneratedTextModal') === 0) {
                renderHistoryDetail(modal.id.replace('detailGeneratedTextModal', ''));
            }
        });

        // Bootstrap 4 / jQuery (kalau modal ini masih pakai jQuery Modal plugin)
        if (window.jQuery) {
            jQuery(document).on('shown.bs.modal', '.modal', function () {
                if (this.id && this.id.indexOf('detailGeneratedTextModal') === 0) {
                    renderHistoryDetail(this.id.replace('detailGeneratedTextModal', ''));
                }
            });
        }

        /*
        * ==========================================
        * Tombol salin & toggle teks polos (event delegation)
        * ==========================================
        */
        document.addEventListener('click', function (e) {

            const copyAllBtn = e.target.closest('[data-copy-all]');
            if (copyAllBtn) {
                const id = copyAllBtn.getAttribute('data-copy-all');
                const rawTextEl = document.getElementById('rawText' + id);
                copyToClipboard(rawTextEl ? rawTextEl.value : '', copyAllBtn);
                return;
            }

            const copyStoryBtn = e.target.closest('[data-copy-story]');
            if (copyStoryBtn) {
                const id = copyStoryBtn.getAttribute('data-copy-story');
                const storyContent = document.getElementById('storyContent' + id);
                copyToClipboard(storyContent ? storyContent.innerText.trim() : '', copyStoryBtn);
                return;
            }

            const toggleBtn = e.target.closest('[data-toggle-plaintext]');
            if (toggleBtn) {
                const id = toggleBtn.getAttribute('data-toggle-plaintext');
                const scrollArea = document.getElementById('questionsScrollArea' + id);
                const plainView = document.getElementById('plainTextView' + id);
                if (!scrollArea || !plainView) return;

                // Anggap "aktif" (sedang tampil teks polos) kalau elemen
                // sedang terlihat, baik lewat class 'active' maupun inline style
                const isActive = plainView.classList.contains('active') || plainView.style.display === 'block';

                if (isActive) {
                    // Kembali ke tampilan widget
                    scrollArea.classList.remove('hidden-view');
                    scrollArea.style.display = '';
                    plainView.classList.remove('active');
                    plainView.style.display = 'none';
                    toggleBtn.innerHTML = '<i class="bi bi-file-text"></i> Plain Text';
                } else {
                    // Tampilkan teks polos
                    scrollArea.classList.add('hidden-view');
                    scrollArea.style.display = 'none';
                    plainView.classList.add('active');
                    plainView.style.display = 'block';
                    toggleBtn.innerHTML = '<i class="bi bi-grid"></i> Widget View';
                }
            }
        });

    })();
</script>
