<div class="queue-loading-according position-fixed bottom-0 d-flex justify-content-center align-items-center w-100"
    style="pointer-events: none;">
    <div class="w-50">
        <div class="accordion accordion-margin" id="accordionMargin" style="pointer-events: auto !important;">
            <div class="accordion-item border-primary" style="border-radius: 10px 10px 0 0 !important; overflow: hidden;"
                id="accordian-queue">
                <h2 class="accordion-header" id="headingMarginOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#accordionMarginOne" aria-expanded="false" aria-controls="accordionMarginOne">
                        <span id="cup-svg">
                            <svg class="tea" width="37" height="30" viewbox="0 0 37 48" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z"
                                    stroke="var(--secondary)" stroke-width="2"></path>
                                <path
                                    d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34"
                                    stroke="var(--secondary)" stroke-width="2"></path>
                                <path id="teabag" fill="var(--secondary)" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z">
                                </path>
                                <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" stroke="var(--secondary)"></path>
                                <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13"
                                    stroke="var(--secondary)" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        {{-- &nbsp;&nbsp; --}}
                        <span class="text-primary" id="accordian-heading">
                            Some construction going on...
                        </span>
                    </button>
                </h2>
                <div id="accordionMarginOne" class="accordion-collapse collapse" aria-labelledby="headingMarginOne"
                    data-bs-parent="#accordionMargin">
                    <div class="accordion-body">
                        <div class="d-flex justify-content-end align-items-center" id="clear-all-queues-div" style="display: none !important;">
                            <a href="{{ route('batches.clear-all') }}" class="btn btn-sm btn-relief-outline-danger waves-effect waves-float waves-light" id="clear-all-queues-button">
                                <i data-feather='x-circle'></i>
                                Clear all
                            </a>
                        </div>
                        <div style="max-height: 285px; overflow-y: auto; padding: 15px;">
                            @forelse ($batches as $key => $batch)
                                <div class="card mb-1 queueProgressCard border-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p id="jobBatchId_{{ $key }}">{{ $batch->job_batch_id }}
                                            </p>
                                            {{-- <div class="mb-1">
                                                <div class="spinner-grow text-primary spinner-grow-sm" role="status"
                                                    id="queueSpinner_{{ $key }}">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <button type="button" id="queueButton_{{ $key }}" style="display: none;"
                                                    class="btn btn-relief-outline-success btn-sm waves-effect waves-float waves-light me-1">
                                                    <i data-feather='save'></i>
                                                    Preview
                                                </button>
                                            </div> --}}
                                            <p id="queueProgressBarProgress_{{ $key }}">Initializing...
                                            </p>
                                        </div>
                                        <div class="progress progress-bar-primary">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                id="queueProgressBar_{{ $key }}" role="progressbar"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                                style="width: 100%"></div>
                                        </div>
                                        {{-- <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                                                15%
                                            </div>
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                                                10%
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showOffCanvas(element, autoClose = true) {
        $('#' + element).offcanvas('show');
        if (autoClose) {
            setTimeout(function() {
                $('#' + element).offcanvas('hide');
            }, 100);
        }
    }

    function setProgressTo(progressBarID, progress, pendingJobs, processedJobs, totalJobs) {
        var progressBar = $('#queueProgressBar_' + progressBarID);

        switch (progress) {
            case 0:
                progressBar.addClass('progress-bar-animated').css('width', '100%');
                progressBar.parent().removeClass('progress-bar-success').addClass('progress-bar-primary');
                $('#queueProgressBarProgress_' + progressBarID).text('Initializing...');
                break;

            case 100:
                progressBar.addClass('progress-bar-animated').css('width', '100%');
                progressBar.parent().removeClass('progress-bar-primary').addClass('progress-bar-success');
                $('#queueProgressBarProgress_' + progressBarID).text(processedJobs + ' completed out of ' +
                    totalJobs);
                break;

            default:
                progressBar.removeClass('progress-bar-animated').css('width',
                    progress + '%');
                progressBar.parent().removeClass('progress-bar-success').addClass('progress-bar-primary');
                $('#queueProgressBarProgress_' + progressBarID).text(processedJobs + ' completed out of ' +
                    totalJobs);
                break;
        }
    }

    var intervalIDs = []

    pendingQueueCount = parseInt('{{ $batches->count() }}');

    function checkQueueBatchProgress(interval_id, batch_id, progressBarID) {
        $.ajax({
            url: '{{ route('batches.byid', ['batch_id' => ':batch_id']) }}'.replace(':batch_id',
                batch_id),
            type: 'GET',
            success: function(response) {
                if (response.status) {
                    setProgressTo(progressBarID, response.data.progress, response.data
                        .pendingJobs, response
                        .data.processedJobs, response.data.totalJobs);

                    if (response.data.progress == 100) {
                        window.clearInterval(interval_id);
                        QueueCompletedAction(progressBarID);
                    }
                }
            }
        });
    }

    function QueueCompletedAction(progressBarID) {
        console.log('progressBarID => ' + progressBarID);
        $('.queueProgressCard').removeClass('border-primary').addClass('border-success');
        pendingQueueCount--;

        if (pendingQueueCount == 0) {
            $('#queueLoadingTopbarIcon').removeClass('spinner').html(
                '<i style="color: #28C76F !important;" class="ficon" data-feather="check-circle"></i>'
            );
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }

            $('#accordian-queue').removeClass('border-primary').addClass('border-success');
            $('#clear-all-queues-div').show();
            $('#accordian-heading').removeClass('text-primary').addClass('text-success').html('Queues Completed...');
            $('#cup-svg path').attr('stroke', '#28C76F');

            $('#queueSpinner_' + progressBarID).hide();
            $('#queueButton_' + progressBarID).show();
        }
    }

    function startQueueInterval(batch_id, progressBarID) {
        var interval_id = setInterval(function() {
            checkQueueBatchProgress(interval_id, batch_id, progressBarID);
        }, 2500);
    }

    function toggleAccordian(action = null) {

        var accordian = $('#accordionMarginOne');
        if (accordian.hasClass('show')) {
            accordian.collapse('hide');
        } else {
            accordian.collapse('show');
        }
    }
</script>
