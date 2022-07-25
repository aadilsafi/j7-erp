@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.index') }}
@endsection

@section('page-title', 'Site configuration')

@section('page-vendor')
@endsection

@section('page-css')
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Sites Configurations</h2>
                {{-- <div class="breadcrumb-wrapper">
                     {{ Breadcrumbs::render('sites.index') }}
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="app-user-view-connections">
        <div class="row">
            {{-- <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2"
                                    src="../../../app-assets/images/portrait/small/avatar-s-2.jpg" height="110"
                                    width="110" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h4>Gertrude Barton</h4>
                                    <span class="badge bg-light-secondary">Author</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around my-2 pt-75">
                            <div class="d-flex align-items-start me-2">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="check" class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">1.23k</h4>
                                    <small>Tasks Done</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="briefcase" class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">568</h4>
                                    <small>Projects Done</small>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Username:</span>
                                    <span>violet.dev</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Billing Email:</span>
                                    <span>vafgot@vultukir.org</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Status:</span>
                                    <span class="badge bg-light-success">Active</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Role:</span>
                                    <span>Author</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Tax ID:</span>
                                    <span>Tax-8965</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Contact:</span>
                                    <span>+1 (609) 933-44-22</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Language:</span>
                                    <span>English</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Country:</span>
                                    <span>Wake Island</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-2">
                                <a href="javascript:;" class="btn btn-primary me-1" data-bs-target="#editUser"
                                    data-bs-toggle="modal">Edit</a>
                                <a href="javascript:;" class="btn btn-outline-danger suspend-user">Suspended</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
            <!--/ User Sidebar --> --}}

            <div class="col-xl-12 col-lg-12">
                <ul class="nav nav-pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link active" id="site-congif-tab" data-bs-toggle="tab" href="#siteCongifData"
                            aria-controls="home" role="tab" aria-selected="true">
                            <i data-feather="home" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Site</span></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="homeIcon" aria-labelledby="homeIcon-tab" role="tabpanel">
                        <form action="{{ route('sites.configurations.configStore', ['id' => encryptParams($site->id)]) }}"
                            method="post">
                            @csrf
                            <div class="card">
                                <div class="card-body">

                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="tab-pane" id="profileIcon" aria-labelledby="profileIcon-tab" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <p>
                                    Candy canes donut chupa chups candy canes lemon drops oat cake wafer. Cotton candy candy
                                    canes marzipan
                                    carrot cake. Sesame snaps lemon drops candy marzipan donut brownie tootsie roll. Icing
                                    croissant bonbon
                                    biscuit gummi bears. Pudding candy canes sugar plum cookie chocolate cake powder
                                    croissant.
                                </p>
                                <p>
                                    Carrot cake tiramisu danish candy cake muffin croissant tart dessert. Tiramisu caramels
                                    candy canes
                                    chocolate cake sweet roll liquorice icing cupcake. Candy cookie sweet roll bear claw
                                    sweet roll.
                                </p>
                            </div>
                        </div>
                    </div> --}}
                </div>

            </div>
            <!--/ User Content -->
        </div>
    </section>
@endsection

@section('vendor-js')

@endsection

@section('page-js')
@endsection

@section('custom-js')
@endsection
