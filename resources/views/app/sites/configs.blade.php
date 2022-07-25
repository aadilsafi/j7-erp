@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.index') }}
@endsection

@section('page-title', 'Site Configuration')

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
                    <li class="nav-item">
                        <a class="nav-link" id="floorCongifTab" data-bs-toggle="tab" href="#floorCongifData"
                            aria-controls="floor" role="tab" aria-selected="false">
                            <i data-feather="home" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Floor</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="unitCongifTab" data-bs-toggle="tab" href="#unitCongifData"
                            aria-controls="unit" role="tab" aria-selected="false">
                            <i data-feather="home" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Unit</span></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="siteCongifData" aria-labelledby="site-congif-tab" role="tabpanel">
                        <form action="{{ route('sites.configurations.configStore', ['id' => encryptParams($site->id)]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="selected_tab" value="site">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        {{-- {{ dd($site) }} --}}
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="name">Site Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ $site->name }}"
                                                placeholder="Site Name" />
                                            @error('name')
                                                <div class="invalid-tooltip">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="name">Site Address</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('address') is-invalid @enderror"
                                                id="address" name="address" value="{{ $site->address }}"
                                                placeholder="Site Address" />
                                            @error('address')
                                                <div class="invalid-tooltip">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="name">Area Width</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('area_width') is-invalid @enderror"
                                                id="area_width" name="area_width" value="{{ $site->area_width }}"
                                                placeholder="Area Width" />
                                            @error('area_width')
                                                <div class="invalid-tooltip">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="name">Area Length</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('area_length') is-invalid @enderror"
                                                id="area_length" name="area_length" value="{{ $site->area_length }}"
                                                placeholder="Area Length" />
                                            @error('area_length')
                                                <div class="invalid-tooltip">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-1">

                                        @forelse ($site->siteConfiguration->toArray() as $key => $value)
                                            @if ($key != 'site_id' && explode('_', $key)[0] == 'site')
                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                    <label class="form-label fs-5"
                                                        for="{{ $key }}">{{ Str::of($key)->title()->replace('_', ' ') }}</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg @error($key) is-invalid @enderror"
                                                        id="{{ $key }}" name="arr_site[{{ $key }}]"
                                                        value="{{ $value }}"
                                                        placeholder="{{ Str::of($key)->title()->replace('_', ' ') }}" />
                                                    @error($key)
                                                        <div class="invalid-tooltip">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit"
                                            class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                                            <i data-feather='save'></i>
                                            Update Configurations
                                        </button>

                                        <button type="reset"
                                            class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                                            <i data-feather='x'></i>
                                            {{ __('lang.commons.cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="floorCongifData" aria-labelledby="floorCongifTab" role="tabpanel">
                        <form action="{{ route('sites.configurations.configStore', ['id' => encryptParams($site->id)]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="selected_tab" value="floor">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        @forelse ($site->siteConfiguration->toArray() as $key => $value)
                                            @if ($key != 'site_id' && explode('_', $key)[0] == 'floor')
                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                    <label class="form-label fs-5"
                                                        for="{{ $key }}">{{ Str::of($key)->title()->replace('_', ' ') }}</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg @error($key) is-invalid @enderror"
                                                        id="{{ $key }}" name="arr_floor[{{ $key }}]"
                                                        value="{{ $value }}"
                                                        placeholder="{{ Str::of($key)->ucfirst()->replace('_', ' ') }}" />
                                                    @error($key)
                                                        <div class="invalid-tooltip">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit"
                                            class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                                            <i data-feather='save'></i>
                                            Update Configurations
                                        </button>

                                        <button type="reset"
                                            class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                                            <i data-feather='x'></i>
                                            {{ __('lang.commons.cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="unitCongifData" aria-labelledby="unitCongifTab" role="tabpanel">
                        <form action="{{ route('sites.configurations.configStore', ['id' => encryptParams($site->id)]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="selected_tab" value="unit">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-1">
                                        @forelse ($site->siteConfiguration->toArray() as $key => $value)
                                            @if ($key != 'site_id' && explode('_', $key)[0] == 'unit')
                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                    <label class="form-label fs-5"
                                                        for="{{ $key }}">{{ Str::of($key)->title()->replace('_', ' ') }}</label>
                                                    <input type="text"
                                                        class="form-control form-control-lg @error($key) is-invalid @enderror"
                                                        id="{{ $key }}" name="arr_unit[{{ $key }}]"
                                                        value="{{ $value }}"
                                                        placeholder="{{ Str::of($key)->ucfirst()->replace('_', ' ') }}" />
                                                    @error($key)
                                                        <div class="invalid-tooltip">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            @endif
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button type="submit"
                                            class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                                            <i data-feather='save'></i>
                                            Update Configurations
                                        </button>

                                        <button type="reset"
                                            class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                                            <i data-feather='x'></i>
                                            {{ __('lang.commons.cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
