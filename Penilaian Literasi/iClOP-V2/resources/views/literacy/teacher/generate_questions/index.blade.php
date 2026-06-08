<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* Additional styles */
        .sidebar {
            background-color: #FFFFFF;
            width: 245px;
        }

        .content {
            min-height: 400px;
            background-color: #FFFFFF;
            padding: 20px;
        }
        
        @media (max-width: 768px) {
            #sidebarMenu {
                position: fixed;
                top: 0;
                left: 0;
                width: 245px;
                height: 100vh;
                background-color: #fff;
                z-index: 1040;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                display: block !important;
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
                overflow-y: auto;
                padding-top: 60px;
            }

            #sidebarMenu.active {
                transform: translateX(0);
            }

            #toggleSidebar {
                position: relative;
                z-index: 1051;
            }
        }

        /* NAV LINK */
        .nav-link {
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            color: blue !important;
        }

        .nav-link .icon {
            margin-right: 5px;
        }

        .custom-button {
            color: #A0A0A0;
            /* Warna teks saat tombol normal */
            transition: background-color 0.3s, color 0.3s;
            /* Efek transisi ketika hover */
            /* outline: none; */
        }

        .custom-button:hover {
            background-color: #007BFF;
            /* Warna latar belakang saat tombol dihover */
            color: white;
            /* Warna teks saat tombol dihover menjadi putih */
        }

        .custom-card {
            padding: 30px;
            width: 395px;
            height: 280px;
            background-color: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .circle-image {
            width: 79px;
            height: 79px;
            border-radius: 50%;
        }

        .custom-title {
            font-weight: 600;
            font-size: 25px;
            color: #252525;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .custom-subtitle {
            font-weight: 400;
            font-size: 20px;
            color: #898989;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .custom-button {
            width: 335px;
            height: 43px;
            border-radius: 10px;
            background-color: #EAEAEA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            outline: none;
        }

        .custom-button-detail {
            width: 180px;
            height: 45px;
            border-radius: 10px;
            background-color: #EAEAEA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            margin-left: auto;
            color: #A0A0A0;
            /* Warna teks saat tombol normal */
            transition: background-color 0.3s, color 0.3s;
            /* Efek transisi ketika hover */
        }

        .custom-button-detail:hover {
            background-color: #007BFF;
            /* Warna latar belakang saat tombol dihover */
            color: white;
            /* Warna teks saat tombol dihover menjadi putih */
        }

        .button-text {
            font-weight: 500;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            margin-left: 10px;
            margin-right: 10px;
            text-decoration: none;
            color: #A0A0A0;
        }

        .button-text:hover {
            text-decoration: none;
            color: #fff;
        }

        .text {
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-right-shadow {
            box-shadow: 1px 0px 8px rgba(0, 0, 0, 0.1);
            /* Menambahkan bayangan ke sisi kanan */
        }
    </style>

    <title>Tab Example</title>

    <!-- ======  CSS  ====== -->
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous">

    <!-- Bootstrap Icons (untuk bi bi-check-circle-fill, dll.) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome (jika masih dipakai di tempat lain) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <!-- Google Font – Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom style -->
    <link href="style.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">


    <!-- ======  JavaScript  ====== -->
    <!-- jQuery (dibutuhkan DataTables) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap 5 JS bundle (sudah termasuk Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables core -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <!-- Ekstensi DataTables Buttons + export -->
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script>

        // Define the font files in base64
        var robotoRegularBase64 = 'BASE64_STRING_OF_ROBOTO_REGULAR';
        var robotoBoldBase64 = 'BASE64_STRING_OF_ROBOTO_BOLD';
        var robotoItalicBase64 = 'BASE64_STRING_OF_ROBOTO_ITALIC';
        var robotoBoldItalicBase64 = 'BASE64_STRING_OF_ROBOTO_BOLDITALIC';

        // Prepare the virtual file system object
        var vfs = {
            "Roboto-Regular.ttf": robotoRegularBase64,
            "Roboto-Bold.ttf": robotoBoldBase64,
            "Roboto-Italic.ttf": robotoItalicBase64,
            "Roboto-BoldItalic.ttf": robotoBoldItalicBase64
        };
    </script>
    <link rel="icon" href="./images/logo.png" type="image/png">
</head>

<body style="padding-bottom: 45px;">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg" style="background-color: #FEFEFE;">
        <div class="container-fluid d-flex justify-content-between align-items-center px-3 px-md-4 position-relative">
            <!-- Toggle Sidebar Kiri (mobile only) -->
            <button id="toggleSidebar" class="btn btn-primary d-md-none">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Dashboard Student - Tampil di tengah pada mobile -->
            <div class="d-md-none position-absolute start-50 translate-middle-x text-center">
                <a href="/dashboard-student" class="nav-link active text-dark fw-bold d-flex flex-column">
                    <span>Dashboard</span>
                    <span>Teacher</span>
                </a>
            </div>

            <!-- Logo (di kanan pada mobile, tengah di desktop) -->
            <img src="{{ asset('./images/logo.png') }}" alt="logo" width="104" height="65" class="order-md-1 order-2 ms-auto" id="logoResponsive">

            <!-- Menu Desktop -->
            <div class="collapse navbar-collapse order-md-2 order-3 d-none d-md-flex" id="navbarSupportedContent">
                <div class="mx-auto">
                    <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/dashboard-student">Dashboard Teacher</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown">
                    <p style="margin-top: 10px; margin-right: 10px;">
                        {{ auth()->user()->name }}
                        <img src="{{ asset('./images/Group.png') }}" alt="Group" style="height: 50px; margin-right: 10px;">
                        <i class="fas fa-chevron-down" style="color: #0079FF;"></i>
                        <div class="dropdown-content" id="dropdownContent">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </form>
                        </div>
                    </p>
                </div>
            </div>
        </div>
    </nav>
    <!-- ------------------------------------------------------------------------------------------ -->

    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <nav id="sidebarMenu" class="col-md-2 d-none d-md-block sidebar sidebar-right-shadow">
                <div class="sidebar-sticky" style="margin-top: 20px;">
                    <ul class="nav flex-column">
                        <li class="nav-item" style="margin-bottom: 40px;">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p style="font-weight: 600; font-size: 14px; color: #34364A; margin-left: 15px;">
                                        STUDENT WEBAPPS
                                    </p>
                                </div>
                                <div class="col">
                                    <img src="{{asset('./images/literacy/literacy.png')}}" alt="learning-logo"
                                        style="height: 45px;">
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-user"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link active" href="{{ route('literacy_teacher_users') }}"
                                        style="color: #34364A;" id="manageUsersLink">Manage Users</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-book"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link active" href="{{ route('literacy_teacher_materials') }}"
                                        style="color: #34364A;" id="manageMaterialsLink">Manage Materials</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-robot"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="{{ route('literacy_teacher_generate_questions') }}"
                                        style="color: #34364A;">AI Generated Questions</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-history"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="{{ route('literacy_teacher_generate_questions_history') }}"
                                        style="color: #34364A;">Generated History</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-question-circle"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link active" href="{{ route('literacy_teacher_questions') }}"
                                        style="color: #34364A;" id="manageQuestionsLink">Manage Questions</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-chart-bar"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="{{ route('literacy_teacher_assessment_results') }}"
                                        style="color: #34364A;">Assessment Results</a>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-sign-out-alt"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <a class="nav-link" href="#" style="color: #34364A;"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- ------------------------------------------------------------------------------------------ -->

            <!-- CONTENT -->
            <main class="col-md-9">
                <div class="content">
                    <p style="font-size: 24px; font-weight: 500; color: #34364A;">AI Generated Questions</p>
                    <div class="container mt-4">

                        <!-- Form untuk generate question -->
                        <form id="aiForm" class="mt-4">
                            @csrf
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="material_id" class="form-label">Select Material</label>
                                        <select class="form-select" id="material_id" name="material_id" required>
                                            <option value="">-- Select Material --</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}">{{ $material->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="question_type" class="form-label">Question Type</label>
                                        <select class="form-select" id="question_type" name="question_type" required>
                                            <option value="">-- Select Question Type --</option>
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="essay">Essay</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="question_count" class="form-label">Number of Questions</label>
                                        <input type="number" class="form-control" id="question_count"
                                            name="question_count" min="1" max="20" value="5" required>
                                    </div>

                                    <div
                                        style="border: 1px solid #ccc; border-radius: 8px; padding: 1rem; background: #f8f9fa; max-height: 300px; overflow-y: auto;">
                                    <pre style="margin: 0; white-space: pre-wrap; text-align: left;"><code id="content">{{ $latestGeneratedText->generate_text ?? 'The generated questions will appear here...' }}</code></pre>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Generate</button>
                                        <button type="button" class="btn btn-secondary" id="resetButton">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <!-- Form untuk menyimpan pertanyaan -->
                        <form action="{{ route('literacy_questions_store') }}" method="POST" id="questionForm"
                            class="mt-4">
                            @csrf

                            <!-- Pilihan Materi -->
                            <div class="mb-3">
                                <label for="material_id" class="form-label">Material</label>
                                <select class="form-select" name="material_id" class="form-control" id="materialSelect" required>
                                    <option value="">-- Choose Material --</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">{{ $material->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pilihan Teks Bacaan -->
                            <div class="mb-3">
                                <label for="story_text_display" class="form-label">Reading Text</label>
                                <textarea id="story_text_display" class="form-control" rows="6" readonly></textarea>
                                <input type="hidden" name="story_text_id" id="story_text_id">
                                <button type="button" class="btn btn-secondary mt-2" id="btnPilihTeks">Select Reading Text</button>
                            </div>

                            <!-- Teks Pertanyaan -->
                            <div class="mb-3">
                                <label for="question_text" class="form-label">Question Text</label>
                                <textarea name="question_text" id="question_text" class="form-control"
                                    required></textarea>
                            </div>

                            <!-- Tipe Pertanyaan -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Question Type</label>
                                <select name="type" class="form-control" id="questionType" required>
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </div>

                            <!-- Opsi Jawaban (Untuk Pilihan Ganda) -->
                            <div id="multipleChoiceOptions" class="mb-3">
                                <label class="form-label">Answer Options</label>
                                <div id="answerOptions"></div>
                            </div>

                            <!-- Skor untuk pertanyaan Isian -->
                            <div id="essayScoreField" class="mb-3">
                                <label for="essay_score" class="form-label">Score for Essay Question</label>
                                <input type="number" name="essay_score" id="essay_score" class="form-control" min="0"
                                    max="100" placeholder="Enter score">
                            </div>

                            <!-- Jawaban untuk Isian -->
                            <div id="essayReferenceAnswerField" class="mb-3">
                                <label for="essay_answer" class="form-label">Reference Answer</label>
                                <textarea name="essay_answer" id="essay_answer" class="form-control"
                                    placeholder="Enter reference answer"></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-2 mt-3">
                                <button type="button" class="btn btn-success btn-sm" id="addOption">+ Add Answer Option</button>
                                <button type="submit" class="btn btn-primary" id="saveButton" disabled>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                @include('literacy.teacher.generate_questions.modals.loading')
            </main>
        </div>
    </div>

    <!-- Modal Pilih Teks Bacaan -->
    <div class="modal fade" id="modalPilihTeks" tabindex="-1" aria-labelledby="modalPilihTeksLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihTeksLabel">Select Reading Text</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="listTeksBacaan">
                    <p>Loading reading text...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const aiForm = document.getElementById('aiForm');
            const contentElement = document.getElementById('content');
            const resetButton = document.getElementById('resetButton');

            const loadingModalEl = document.getElementById('loadingModal');
            const loadingModal = new bootstrap.Modal(loadingModalEl, {
                backdrop: 'static',
                keyboard: false
            });

            const loadingIcon = document.getElementById('loadingIcon');
            const successIcon = document.getElementById('successIcon');
            const errorIcon = document.getElementById('errorIcon');
            const loadingText = document.getElementById('loadingText');

            function showLoading() {
                loadingIcon.style.display = 'inline-block';
                successIcon.style.display = 'none';
                errorIcon.style.display = 'none';
                loadingText.innerText = "Please wait, generating questions...";
                loadingModal.show();
            }

            function showSuccess() {
                loadingIcon.style.display = 'none';
                successIcon.style.display = 'block';
                errorIcon.style.display = 'none';
                loadingText.innerText = "Questions generated successfully!";
            }

            function showError(message) {
                loadingIcon.style.display = 'none';
                successIcon.style.display = 'none';
                errorIcon.style.display = 'block';
                loadingText.innerText = message || "An error occurred while generating questions.";
            }

            aiForm.addEventListener('submit', async function (event) {
                event.preventDefault();
                showLoading();

                try {
                    let payload = {
                        material_id: document.getElementById('material_id').value,
                        question_type: document.getElementById('question_type').value,
                        question_count: parseInt(document.getElementById('question_count').value)
                    };

                    let response = await fetch("{{ route('literacy_teacher_generate_from_ai') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                            "Accept": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    let data = await response.json();

                    if (response.ok) {
                        let formattedText = data.generated_questions;
                        if (formattedText) {
                            formattedText = formattedText.replace(/Soal Pilihan Ganda:/g, '**Soal Pilihan Ganda:**');
                            formattedText = formattedText.replace(/Soal Isian:/g, '**Soal Isian:**');
                        }
                        contentElement.innerText = formattedText || "Soal berhasil dibuat, tapi tidak ada data.";
                        showSuccess();
                        setTimeout(() => loadingModal.hide(), 2000);
                    } else {
                        contentElement.innerText = "Error: " + (data.message || "Terjadi kesalahan.");
                        showError(data.message);
                        setTimeout(() => loadingModal.hide(), 2500);
                    }
                } catch (error) {
                    contentElement.innerText = "Gagal menghubungi server!";
                    console.error("Error:", error);
                    showError("Gagal menghubungi server!");
                    setTimeout(() => loadingModal.hide(), 2500);
                }
            });

            resetButton.addEventListener('click', function () {
                contentElement.innerText = 'Hasil generate soal akan muncul di sini...';
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const materialSelect = document.getElementById("materialSelect");
            const storyTextDisplay = document.getElementById("story_text_display");
            const storyTextIdInput = document.getElementById("story_text_id");
            const btnPilihTeks = document.getElementById("btnPilihTeks");
            const listTeksBacaan = document.getElementById("listTeksBacaan");

            const questionType = document.getElementById("questionType");
            const answerOptions = document.getElementById("answerOptions");
            const addOptionBtn = document.getElementById("addOption");
            const essayScore = document.getElementById("essay_score");
            const essayAnswer = document.getElementById("essay_answer");
            const saveButton = document.getElementById("saveButton");
            const multipleChoiceOptions = document.getElementById("multipleChoiceOptions");
            const essayScoreField = document.getElementById("essayScoreField");
            const essayReferenceAnswerField = document.getElementById("essayReferenceAnswerField");

            btnPilihTeks.addEventListener("click", function () {
                const materialId = materialSelect.value;
                if (!materialId) {
                    alert("Pilih materi terlebih dahulu.");
                    return;
                }

                listTeksBacaan.innerHTML = "<p>Memuat teks bacaan...</p>";
                $('#modalPilihTeks').modal('show');

                fetch(`/literacy/teacher/materials/${materialId}/story-texts`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            listTeksBacaan.innerHTML = "<p>Tidak ada teks bacaan.</p>";
                            return;
                        }

                        listTeksBacaan.innerHTML = "";
                        data.forEach(text => {
                            const div = document.createElement("div");
                            div.classList.add("mb-3", "p-2", "border", "rounded");
                            div.innerHTML = `
                                <div style="max-height: 150px; overflow-y: auto; white-space: pre-wrap;">${text.story_text}</div>
                                <button type="button" class="btn btn-sm btn-primary mt-2 pilih-teks" 
                                    data-id="${text.id}" 
                                    data-teks="${text.story_text.replace(/"/g, '&quot;')}">
                                    Pilih Teks Ini
                                </button>
                            `;
                            listTeksBacaan.appendChild(div);
                        });

                        document.querySelectorAll(".pilih-teks").forEach(btn => {
                            btn.addEventListener("click", function () {
                                const id = this.getAttribute("data-id");
                                const teks = this.getAttribute("data-teks");

                                storyTextDisplay.textContent = teks;
                                storyTextIdInput.value = id;

                                $('#modalPilihTeks').modal('hide');
                            });
                        });
                    });
            });

            function toggleFields() {
                const isMultipleChoice = questionType.value === "multiple_choice";

                if (isMultipleChoice) {
                    multipleChoiceOptions.style.display = "block";
                    essayScoreField.style.display = "none";
                    essayReferenceAnswerField.style.display = "none";

                    essayScore.removeAttribute("required");
                    essayAnswer.removeAttribute("required");

                    document.querySelectorAll(".option-text, .option-score").forEach(input => {
                        input.setAttribute("required", "required");
                    });

                    if (answerOptions.children.length === 0) addNewOption();
                } else {
                    multipleChoiceOptions.style.display = "none";
                    essayScoreField.style.display = "block";
                    essayReferenceAnswerField.style.display = "block";

                    essayScore.setAttribute("required", "required");
                    essayAnswer.setAttribute("required", "required");

                    document.querySelectorAll(".option-text, .option-score").forEach(input => {
                        input.removeAttribute("required");
                    });
                }

                validateForm();
            }

            function addNewOption() {
                let optionsCount = document.querySelectorAll(".option-group").length;
                let div = document.createElement("div");
                div.classList.add("option-group", "mb-2", "d-flex", "align-items-center", "gap-2");
                div.innerHTML = `
                    <input type="text" name="options[${optionsCount}][text]" class="form-control option-text" style="width: 40%;" placeholder="Option ${optionsCount + 1}" required>
                    <input type="number" name="options[${optionsCount}][score]" class="form-control option-score" style="width: 20%;" placeholder="Score" min="0" max="100" required>
                    <label class="d-flex align-items-center ms-2">
                        <input type="checkbox" name="options[${optionsCount}][is_correct]" value="1" class="correct-answer me-1">
                        <span>Correct</span>
                    </label>
                    <button type="button" class="btn btn-danger btn-sm ms-2 remove-option">X</button>
                `;
                answerOptions.appendChild(div);

                div.querySelector(".remove-option").addEventListener("click", function () {
                    div.remove();
                    updateOptionIndexes();
                    validateForm();
                });
            }

            function updateOptionIndexes() {
                document.querySelectorAll(".option-group").forEach((option, index) => {
                    option.querySelector(".option-text").name = `options[${index}][text]`;
                    option.querySelector(".option-score").name = `options[${index}][score]`;
                    option.querySelector(".correct-answer").name = `options[${index}][is_correct]`;
                    option.querySelector(".option-text").placeholder = `Opsi ${index + 1}`;
                });
            }

            function validateForm() {
                let isValid = false;

                if (questionType.value === "multiple_choice") {
                    let options = document.querySelectorAll(".option-text");
                    let scores = document.querySelectorAll(".option-score");
                    let checkedCorrect = document.querySelector(".correct-answer:checked");

                    isValid = options.length > 0 && scores.length > 0 && checkedCorrect;
                } else {
                    isValid = essayScore.value.trim() !== "" && essayAnswer.value.trim() !== "";
                }

                saveButton.disabled = !isValid;
            }

            questionType.addEventListener("change", toggleFields);
            addOptionBtn.addEventListener("click", addNewOption);
            essayScore.addEventListener("input", validateForm);
            essayAnswer.addEventListener("input", validateForm);
            answerOptions.addEventListener("input", validateForm);

            toggleFields();
        });
    </script>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebarMenu');
            sidebar.classList.toggle('active');
        });
    </script>

    <!-- JavaScript untuk mengubah konten tab -->
    <script>
        function materialModal(id, title, controller) {
            $("#id").val(id);
            $("#title").val(title);
            $("#controller").val(controller);
            $("#span_title").text(title);
        }

        function materialDetailPage() {
            var csrfToken = "{{ csrf_token() }}";
            let id = $("#id").val();
            let title = $("#title").val();
            let controller = $("#controller").val();
            window.location.href = "{{ route('php_material_detail') }}?phpid=" + id + "&start=" + controller;

            /*$.ajax({
                type: "POST",
                data: {
                    id: id,
                    title: title,
                    _token: csrfToken // Menambahkan token CSRF ke dalam data permintaan
                },
                dataType: 'html',
                url: "{{ route('php_material_detail') }}",
            success: function(res) {

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });*/
        }

        // Fungsi untuk mengubah warna ikon, teks, dan link menjadi biru
        function changeColor(id) {
            var icon = document.getElementById(id + 'Icon');
            var link = document.getElementById(id + 'Link');
            var text = document.getElementById(id + 'Text');

            // Mengembalikan warna ikon, teks, dan link ke warna awal
            var icons = document.getElementsByClassName('fas');
            var links = document.getElementsByClassName('nav-link');
            var texts = document.getElementsByClassName('nav-link-text');
            for (var i = 0; i < icons.length; i++) {
                icons[i].style.color = '#676767';
            }
            for (var j = 0; j < links.length; j++) {
                links[j].style.color = '#34364A';
            }
            for (var k = 0; k < texts.length; k++) {
                texts[k].style.color = '#34364A';
            }

            // Mengubah warna ikon, teks, dan link menjadi biru
            icon.style.color = '#1A79E3';
            link.style.color = '#1A79E3';
            text.style.color = '#1A79E3';
        }

        // Menambahkan event listener pada setiap link
        var manageUsersLink = document.getElementById('manageUsersLink');
        manageUsersLink.addEventListener('click', function () {
            changeColor('manageUsers');
        });

        var manageMaterialsLink = document.getElementById('manageMaterialsLink');
        manageMaterialsLink.addEventListener('click', function () {
            changeColor('manageMaterials');
        });

        var manageMaterialsLink = document.getElementById('manageQuestionsLink');
        manageQuestionsLink.addEventListener('click', function () {
            changeColor('manageQuestions');
        });

        var startLearningLink = document.getElementById('learningLink');
        startLearningLink.addEventListener('click', function () {
            changeColor('learning');
        });

        var validationLink = document.getElementById('validationLink');
        validationLink.addEventListener('click', function () {
            changeColor('validation');
        });

        var rankLink = document.getElementById('rankLink');
        rankLink.addEventListener('click', function () {
            changeColor('rank');
        });

        var settingsLink = document.getElementById('settingsLink');
        settingsLink.addEventListener('click', function () {
            changeColor('settings');
        });


        // Function to show the selected content based on sidebar link click
        // function showContent(contentId) {
        //     // Hide all content divs
        //     var contentDivs = document.getElementsByClassName('content');
        //     for (var i = 0; i < contentDivs.length; i++) {
        //         contentDivs[i].style.display = 'none';
        //     }

        //     // Show the selected content div
        //     var selectedContent = document.getElementById(contentId);
        //     if (selectedContent) {
        //         selectedContent.style.display = 'block';
        //     }
        // }

        function showContent(contentId) {
            var contentDivs = document.getElementsByClassName('content');
            for (var i = 0; i < contentDivs.length; i++) {
                contentDivs[i].style.display = 'none';
            }

            var selectedContent = document.getElementById(contentId);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            }
        }

        //  Change TAB
        $(document).ready(function () {
            $('#users-tab').on('click', function (e) {
                e.preventDefault();
                $('#materials-tab').removeClass('active');
                $(this).tab('show');
            });

            $('#materials-tab').on('click', function (e) {
                e.preventDefault();
                $('#users-tab').removeClass('active');
                $(this).tab('show');
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#dropdownContainer").click(function () {
                $("#dropdownContainer").toggleClass("active");
            });
            $("#dropdownContent").click(function (e) {
                e.stopPropagation();
            });
            $(document).click(function () {
                $("#dropdownContainer").removeClass("active");
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#dropdownContainer").click(function () {
                $("#dropdownContainer").toggleClass("active");
            });
            $("#dropdownContent").click(function (e) {
                e.stopPropagation();
            });
            $(document).click(function () {
                $("#dropdownContainer").removeClass("active");
            });
            $('#progressTable').DataTable({
                // Configuration options
                "paging": true,
                "ordering": true,
                "info": true,
                dom: 'Bfrtip', // Needs to include 'B' for buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_topic_finished_student_' + new Date().toLocaleDateString() + '_' + new Date().toLocaleTimeString(),
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            // Customizations go here
                        }
                    },
                    'pdf',
                ]
            });
            $('#studentSubmissionTable').DataTable({
                // Configuration options
                "paging": true,
                "ordering": true,
                "info": true,
                dom: 'Bfrtip', // Needs to include 'B' for buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_student_submission_student_' + new Date().toLocaleDateString() + '_' + new Date().toLocaleTimeString(),
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            // Customizations go here
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_student_submission_student_' + new Date().toLocaleDateString().replace(/\//g, '-') + '_' + new Date().toLocaleTimeString().replace(/:/g, '-'),
                        orientation: 'portrait', // 'portrait' or 'landscape'
                        pageSize: 'A4', // 'A3', 'A4', 'A5', 'LEGAL', 'LETTER' or 'TABLOID'
                        exportOptions: {
                            columns: ':visible' // Export visible columns only
                        },
                        customize: function (doc) {
                            doc.styles.title = {
                                color: '#4c4c4c',
                                fontSize: '20',
                                alignment: 'center'
                            }
                            doc.styles.tableHeader = {
                                fillColor: '#2d4154',
                                color: 'white',
                                alignment: 'center'
                            }
                            // Customize the PDF header, footer, etc. here
                        }
                    },
                ]
            });
            $('#finnishedProgressTable').DataTable({
                // Configuration options
                "paging": true,
                "ordering": true,
                "info": true,
                dom: 'Bfrtip', // Needs to include 'B' for buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_progress_student_' + new Date().toLocaleDateString() + '_' + new Date().toLocaleTimeString(),
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            // Customizations go here
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_progress_student_' + new Date().toLocaleDateString().replace(/\//g, '-') + '_' + new Date().toLocaleTimeString().replace(/:/g, '-'),
                        orientation: 'portrait', // 'portrait' or 'landscape'
                        pageSize: 'A4', // 'A3', 'A4', 'A5', 'LEGAL', 'LETTER' or 'TABLOID'
                        exportOptions: {
                            columns: ':visible' // Export visible columns only
                        },
                        customize: function (doc) {
                            doc.styles.title = {
                                color: '#4c4c4c',
                                fontSize: '20',
                                alignment: 'center'
                            }
                            doc.styles.tableHeader = {
                                fillColor: '#2d4154',
                                color: 'white',
                                alignment: 'center'
                            }
                            // Customize the PDF header, footer, etc. here
                        }
                    },
                ]
            });
            $('#tableStudentReport').DataTable({
                // Configuration options
                "paging": true,
                "ordering": true,
                "info": true,
                dom: 'Bfrtip', // Needs to include 'B' for buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_progress_student_' + new Date().toLocaleDateString() + '_' + new Date().toLocaleTimeString(),
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            // Customizations go here
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Data Export REACT',
                        filename: 'react_data_export_progress_student_' + new Date().toLocaleDateString().replace(/\//g, '-') + '_' + new Date().toLocaleTimeString().replace(/:/g, '-'),
                        orientation: 'portrait', // 'portrait' or 'landscape'
                        pageSize: 'A4', // 'A3', 'A4', 'A5', 'LEGAL', 'LETTER' or 'TABLOID'
                        exportOptions: {
                            columns: ':visible' // Export visible columns only
                        },
                        customize: function (doc) {
                            doc.styles.title = {
                                color: '#4c4c4c',
                                fontSize: '20',
                                alignment: 'center'
                            }
                            doc.styles.tableHeader = {
                                fillColor: '#2d4154',
                                color: 'white',
                                alignment: 'center'
                            }
                            // Customize the PDF header, footer, etc. here
                        }
                    },
                ]
            });
        });
    </script>


    <style>
        .dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
            transition: 0.3s;
            opacity: 0;
            transform: translateY(-10px);
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown.active .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <footer class="footer"
        style="background-color: #EAEAEA; color: #636363; text-align: center; padding: 10px 0; position: fixed; bottom: 0;  width: 100%; ">
        © 2026 Your Website. All rights reserved.
    </footer>

</body>


</html>