{{-- <div class="offcanvas offcanvas-bottom" tabindex="-1" id="queuesLoadingOffCanvas"
    aria-labelledby="queuesLoadingOffCanvasLabel">
    <div class="offcanvas-header">
        <h4 id="queuesLoadingOffCanvasLabel" class="offcanvas-title">Under Construction</h4>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @forelse ($batches as $key => $batch)
            <div class="row mb-1">
                <div class="col-xl-12 col-lg-12">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <p id="jobBatchId_{{ $key }}">{{ $batch->job_batch_id }}</p>
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
            <div class="row mb-1">
                <div class="col-xl-12 col-lg-12">
                    <div class="d-flex justify-content-center align-items-center">
                        <h3 class="m-0">No Data Found!!</h3>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div> --}}

<div class="queue-loading-according position-fixed bottom-0 d-flex justify-content-center align-items-center w-100">
    <div class="w-50">
        <div class="accordion accordion-margin" id="accordionMargin">
            <div class="accordion-item border-primary">
                <h2 class="accordion-header" id="headingMarginOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#accordionMarginOne" aria-expanded="false" aria-controls="accordionMarginOne">
                        <span>
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
                        <span class="text-primary">
                            Some construction going on...
                        </span>
                    </button>
                </h2>
                <div id="accordionMarginOne" class="accordion-collapse collapse" aria-labelledby="headingMarginOne"
                    data-bs-parent="#accordionMargin">
                    <div class="accordion-body">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                    class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                                    aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                                    aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                @forelse ($batches as $key => $batch)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="../../../app-assets/images/slider/02.jpg" class="d-block w-100"
                                            alt="First slide" />
                                    </div>
                                @empty
                                @endforelse
                               </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
