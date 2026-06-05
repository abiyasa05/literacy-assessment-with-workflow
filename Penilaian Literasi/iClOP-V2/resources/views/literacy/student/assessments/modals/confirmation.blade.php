@include('literacy.student.assessments.modals.loading')

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmSubmitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submission Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                Are you sure you want to finish this assessment? Saved answers will be processed.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitAssessment()">Yes, Finish</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript script for assessment submission -->
<script>
    function showLoadingFeedback() {
        $('#loadingIcon').show();
        $('#successIcon').hide();
        $('#errorIcon').hide();
        $('#loadingText').text("Please wait, finishing the assessment...");
        $('#loadingModal').modal({backdrop: 'static', keyboard: false});
        $('#loadingModal').modal('show');
    }

    function showSuccessFeedback() {
        $('#loadingIcon').hide();
        $('#successIcon').show();
        $('#errorIcon').hide();
        $('#loadingText').text("Assessment completed successfully!");
    }

    function showErrorFeedback(message) {
        $('#loadingIcon').hide();
        $('#successIcon').hide();
        $('#errorIcon').show();
        $('#loadingText').text(message || "An error occurred while finishing the assessment.");
    }

    function submitAssessment() {
        var assessmentId = {{ $assessment->id ?? 'null' }};

        if (!assessmentId) {
            alert("Error: Assessment ID not found.");
            return;
        }

        $('#confirmSubmitModal').modal('hide');
        showLoadingFeedback();

        $.ajax({
            url: "/literacy/student/assessment/submit/" + assessmentId,
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                showSuccessFeedback();
                setTimeout(function() {
                    $('#loadingModal').modal('hide');
                    window.location.href = "/literacy/student/assessments";
                }, 2000);
            },
            error: function(xhr) {
                console.error(xhr);
                showErrorFeedback(xhr.responseJSON?.error || "Failed to complete the assessment.");
            }
        });
    }
</script>