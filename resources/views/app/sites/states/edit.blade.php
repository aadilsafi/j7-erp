@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.states.edit', $site_id) }}
@endsection

@section('page-title', 'Edit State')

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
                <h2 class="content-header-title float-start mb-0">Edit State</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.states.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="stateForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.settings.states.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($state->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        @csrf
                        @method('put')
                        {{ view('app.sites.states.form-fields', ['country' => $country], ['state' => $state]) }}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        {{-- <div class="d-block mb-1">
                        <label class="form-label fs-5" for="type_name">CNIC Attachment</label>
                        <input id="attachment" type="file" class="filepond @error('attachment') is-invalid @enderror"
                            name="attachment[]" multiple accept="image/png, image/jpeg, image/gif" />
                        @error('attachment')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <hr> --}}
                        <button type="submit"
                            class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                            <i data-feather='save'></i>
                            Update State
                        </button>
                        <a href="{{ route('sites.settings.states.index', ['site_id' => encryptParams($site_id)]) }}"
                            class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                            <i data-feather='x'></i>
                            {{ __('lang.commons.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')
@endsection
