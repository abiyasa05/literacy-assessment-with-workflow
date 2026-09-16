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
            /* Add shadow to the right side */
        }

        .material-panel {
            border: 1px solid #dfe7f2;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .material-page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .material-search {
            width: 288px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border: 1px solid #d7e0ec;
            border-radius: 7px;
            color: #617089;
        }

        .material-search input {
            width: 100%;
            border: 0;
            outline: 0;
            color: #182642;
        }

        .material-header {
            min-height: 56px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 18px;
            cursor: pointer;
            background: linear-gradient(90deg, #f1f6ff 0%, #fbfdff 100%);
        }

        .material-number {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 28px;
            border-radius: 50%;
            background: #e4edff;
            color: #175ed1;
            font-weight: 600;
        }

        .material-title {
            flex: 1;
            color: #182642;
            font-weight: 600;
        }

        .material-file-icon,
        .material-chevron {
            color: #182642;
        }

        .material-chevron {
            transition: transform .2s ease;
        }

        .material-header[aria-expanded="true"] .material-chevron {
            transform: rotate(180deg);
        }

        .material-view-button {
            min-width: 104px;
            border-radius: 5px;
            font-weight: 500;
        }

        .assessment-panel {
            margin: 12px 14px 16px;
            border: 1px solid #e5ebf3;
            border-radius: 7px;
            overflow: hidden;
        }

        .assessment-panel h5 {
            margin: 0;
            padding: 13px 16px;
            color: #182642;
            background: #f0f5fd;
            font-size: 16px;
            font-weight: 600;
        }

        .assessment-table thead th {
            padding: 10px 16px;
            border-top: 0;
            color: #182642;
            font-size: 12px;
            font-weight: 600;
            background: #fbfcfe;
        }

        .assessment-table tbody td {
            padding: 10px 16px;
            color: #263653;
            vertical-align: middle;
            border-color: #e5ebf3;
            font-size: 13px;
        }

        .assessment-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 12px;
            white-space: nowrap;
        }

        .status-pending {
            color: #45556d;
            background: #edf1f7;
        }

        .status-progress {
            color: #bd6500;
            background: #fff0d5;
        }

        .status-completed {
            color: #16814b;
            background: #dff7e8;
        }

        .assessment-action {
            min-width: 108px;
            color: #fff;
            border: 0;
            border-radius: 5px;
            font-weight: 500;
        }

        .action-start {
            background: #2ab673;
        }

        .action-continue {
            background: #f3a000;
        }

        .action-result {
            background: #2479ed;
        }

        @media (max-width: 576px) {
            .material-page-heading {
                display: block;
            }

            .material-search {
                width: 100%;
                margin-top: 14px;
            }

            .material-header {
                gap: 9px;
                padding: 10px;
            }

            .material-file-icon {
                display: none;
            }

            .material-view-button {
                min-width: auto;
                padding: 5px 7px;
                font-size: 11px;
            }

            .assessment-panel {
                margin: 10px 8px 12px;
            }

            .assessment-table {
                min-width: 620px;
            }
        }
    </style>

    <title>Tab Example</title>

    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- JavaScript Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Place these in the <head> section -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
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

<body>
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
                    <span>Student</span>
                </a>
            </div>

            <!-- Logo (di kanan pada mobile, tengah di desktop) -->
            <img src="{{ asset('./images/logo.png') }}" alt="logo" width="104" height="65" class="order-md-1 order-2 ms-auto" id="logoResponsive">

            <!-- Menu Desktop -->
            <div class="collapse navbar-collapse order-md-2 order-3 d-none d-md-flex" id="navbarSupportedContent">
                <div class="mx-auto">
                    <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/dashboard-student">Dashboard Student</a>
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
                                    <i class="fas fa-question-circle"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link active" href="{{ route('literacy_student_materials') }}"
                                        style="color: #34364A;" id="manageQuestionsLink">Materials</a>
                                </div>
                            </div>
                        </li>

                        {{-- <li class="nav-item">
                            <div class="row align-items-start">
                                <div class="col-2">
                                    <i class="fas fa-chart-bar"
                                        style="margin-top: 12px; margin-left: 15px; color: #676767;"></i>
                                </div>
                                <div class="col">
                                    <a class="nav-link" href="{{ route('literacy_assessments') }}"
                                        style="color: #34364A;">Assessments</a>
                                </div>
                            </div>
                        </li> --}}

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
                    <div class="material-page-heading">
                        <div>
                            <p style="font-size: 24px; font-weight: 500; color: #34364A; margin-bottom: 2px;">Materials List</p>
                            <p class="text-muted mb-0">Select a material to see the list of assessments</p>
                        </div>
                        <div class="material-search">
                            <i class="fas fa-search"></i>
                            <input type="search" id="materialSearch" placeholder="Search materials..." aria-label="Search materials">
                        </div>
                    </div>
                    <div class="container mt-4" id="materialAssessmentList">
                        @forelse ($materials_student as $index => $material_student)
                            <section class="material-panel mb-3">
                                <div class="material-header" data-toggle="collapse"
                                    data-target="#materialAssessments{{ $material_student->id }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                    <div class="material-number">{{ $index + 1 }}</div>
                                    <div class="material-title">{{ $material_student->title }}</div>
                                    <i class="far fa-file-alt material-file-icon"></i>
                                    @if ($material_student->file_path)
                                        <a href="{{ route('literacy_materials_view', $material_student->id) }}"
                                            class="btn btn-sm btn-outline-primary material-view-button" target="_blank"
                                            onclick="event.stopPropagation();">
                                            See Material
                                        </a>
                                    @endif
                                    <i class="fas fa-chevron-down material-chevron"></i>
                                </div>

                                <div id="materialAssessments{{ $material_student->id }}"
                                    class="collapse {{ $index === 0 ? 'show' : '' }}">
                                    <div class="assessment-panel">
                                        <h5>List of Assessments</h5>
                                        <div class="table-responsive">
                                            <table class="table assessment-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Nu</th>
                                                        <th>Assessment Title</th>
                                                        <th>Status</th>
                                                        <th>Score</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($material_student->assessments as $assessmentIndex => $assessment)
                                                        <tr>
                                                            <td>{{ $assessmentIndex + 1 }}</td>
                                                            <td>Assessment {{ $assessmentIndex + 1 }}</td>
                                                            <td>
                                                                @if ($assessment->status === 'completed')
                                                                    <span class="assessment-status status-completed">Completed</span>
                                                                @elseif ($assessment->status === 'in_progress')
                                                                    <span class="assessment-status status-progress">In Progress</span>
                                                                @else
                                                                    <span class="assessment-status status-pending">Pending</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($assessment->score !== null)
                                                                    {{ rtrim(rtrim(number_format((float) $assessment->score, 2, '.', ''), '0'), '.') }}
                                                                @else
                                                                    Not rated yet
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($assessment->status === 'pending')
                                                                    <form action="{{ route('literacy_assessments_start', $assessment->id) }}"
                                                                        method="POST" target="_blank" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-sm assessment-action action-start">
                                                                            <i class="fas fa-play mr-1"></i> Start
                                                                        </button>
                                                                    </form>
                                                                @elseif ($assessment->status === 'in_progress')
                                                                    <a href="{{ route('literacy_assessments_continue', $assessment->id) }}"
                                                                        class="btn btn-sm assessment-action action-continue" target="_blank">
                                                                        <i class="fas fa-redo mr-1"></i> Continue
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('literacy_assessment_result', $assessment->id) }}"
                                                                        class="btn btn-sm assessment-action action-result" target="_blank">
                                                                        <i class="fas fa-chart-bar mr-1"></i> Show Result
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                There's no assessment for this material yet.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @empty
                            <div class="alert alert-light border">There's no material available.</div>
                        @endforelse
                    </div>
                </div>
            </main>                        
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 80%;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><span id="span_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" style="margin-left: 10px; width: 160px;"
                        onclick="materialDetailPage()">
                        <i class="fas fa-key" style="margin-right: 5px;"></i>Enroll Material
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebarMenu');
            sidebar.classList.toggle('active');
        });
    </script>
    <!-- JavaScript to change tab content -->
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
                    _token: csrfToken // Add CSRF token to request data
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

        // Function to change icon, text, and link colors to blue
        function changeColor(id) {
            var icon = document.getElementById(id + 'Icon');
            var link = document.getElementById(id + 'Link');
            var text = document.getElementById(id + 'Text');

            // Restore icon, text, and link colors to the original color
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

            // Change icon, text, and link colors to blue
            icon.style.color = '#1A79E3';
            link.style.color = '#1A79E3';
            text.style.color = '#1A79E3';
        }

        // Add event listener to each link
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
            $('#progressTable').length && $('#progressTable').DataTable({
                // Configuration options
                "paging": true,
                "ordering": true,
                "info": true,
                // dom: 'Bfrtip', // Needs to include 'B' for buttons
                // buttons: [
                //     {
                //         extend: 'excelHtml5',
                //         text: 'Export to Excel',
                //         title: 'Data Export REACT',
                //         filename: 'react_data_export_topic_finished_student_' + new Date().toLocaleDateString() + '_' + new Date().toLocaleTimeString(),
                //         customize: function (xlsx) {
                //             var sheet = xlsx.xl.worksheets['sheet1.xml'];
                //             // Customizations go here
                //         }
                //     },
                //     'pdf',
                // ]
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
    <script>
        $(function () {
            $('#materialSearch').on('input', function () {
                var searchTerm = $(this).val().toLowerCase();

                $('.material-panel').each(function () {
                    var materialTitle = $(this).find('.material-title').text().toLowerCase();
                    $(this).toggle(materialTitle.indexOf(searchTerm) !== -1);
                });
            });
        });
    </script>
    <footer class="footer"
        style="background-color: #EAEAEA; color: #636363; text-align: center; padding: 10px 0; position: fixed; bottom: 0;  width: 100%; ">
        © 2026 Your Website. All rights reserved.
    </footer>

</body>


</html>