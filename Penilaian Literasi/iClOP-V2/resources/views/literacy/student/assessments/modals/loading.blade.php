<!-- Modal Loading (Bootstrap 4 Compatible) -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content text-center py-5" style="background: rgba(255, 255, 255, 0.95); border: none;">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <!-- Spinner -->
                <div id="loadingIcon" class="spinner-border text-primary mb-4" role="status" style="width: 4rem; height: 4rem; display: none;">
                    <span class="sr-only">Loading...</span>
                </div>
                <!-- Success -->
                <div id="successIcon" class="text-success mb-4" style="display: none;">
                    <i class="fa fa-check-circle" style="font-size: 4rem;"></i>
                </div>
                <!-- Error -->
                <div id="errorIcon" class="text-danger mb-4" style="display: none;">
                    <i class="fa fa-times-circle" style="font-size: 4rem;"></i>
                </div>
                <!-- Text -->
                <p id="loadingText" style="font-size: 1.25rem; font-weight: 500;">Please wait, processing...</p>
            </div>
        </div>
    </div>
</div>