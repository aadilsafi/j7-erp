<div class="offcanvas offcanvas-bottom" tabindex="-1" id="queuesLoadingOffCanvas"
    aria-labelledby="queuesLoadingOffCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="queuesLoadingOffCanvasLabel" class="offcanvas-title">Under Construction</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-1">
            <div class="col-xl-12 col-lg-12 mb-1">
                <div class="progress progress-bar-primary">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="queueProgressBar" role="progressbar"
                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-xl-12 col-lg-12 mb-1">
                <button onclick="setProgressTo('queueProgressBar', 0)" class="btn btn-relief-outline-primary me-1">Initializing</button>
                <button onclick="setProgressTo('queueProgressBar', 10)" class="btn btn-relief-outline-primary me-1">10</button>
                <button onclick="setProgressTo('queueProgressBar', 30)" class="btn btn-relief-outline-primary me-1">30</button>
                <button onclick="setProgressTo('queueProgressBar', 50)" class="btn btn-relief-outline-primary me-1">50</button>
                <button onclick="setProgressTo('queueProgressBar', 80)" class="btn btn-relief-outline-primary me-1">80</button>
                <button onclick="setProgressTo('queueProgressBar', 100)" class="btn btn-relief-outline-primary me-1">100</button>
                <button onclick="start()" class="btn btn-relief-outline-primary me-1">Start Interval</button>
                <button onclick="stop()" class="btn btn-relief-outline-primary me-1">Stop Interval</button>
            </div>
        </div>
    </div>
</div>
