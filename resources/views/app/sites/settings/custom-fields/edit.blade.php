@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.settings.custom-fields.edit', encryptParams($site_id), encryptParams($id)) }}
@endsection

@section('page-title', 'Edit Custom Fields')

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
                <h2 class="content-header-title float-start mb-0">Edit Custom Fields</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.settings.custom-fields.edit', encryptParams($site_id), encryptParams($id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical"
        action="{{ route('sites.settings.custom-fields.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                @method('PUT')
                {{ view('app.sites.settings.custom-fields.form-fields', [
                    'fieldTypes' => $fieldTypes,
                    'models' => $models,
                    'customField' => $customField,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                        <i data-feather='save'></i>
                                        Update Custom Field
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.settings.custom-fields.index', ['site_id' => encryptParams($site_id)]) }}"
                                        class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light">
                                        <i data-feather='x'></i>
                                        {{ __('lang.commons.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
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
    <script>
        e = $("#values");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
            tags: true,
            multiple: true,
        });
    </script>
@endsection
