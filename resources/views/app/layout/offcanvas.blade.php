<div class="offcanvas offcanvas-bottom" tabindex="-1" id="queuesLoadingOffCanvas"
    aria-labelledby="queuesLoadingOffCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="queuesLoadingOffCanvasLabel" class="offcanvas-title">Under Construction</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @forelse ($batches as $key => $batch)
            <div class="row mb-1">
                <div class="col-xl-12 col-lg-12">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-end align-items-center">
                                <p id="queueProgressBarProgress_{{ $key }}">Initializing...</p>
                            </div>
                            <div class="progress progress-bar-primary">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                    id="queueProgressBar_{{ $key }}" role="progressbar" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>
