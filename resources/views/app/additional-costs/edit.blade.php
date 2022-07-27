@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.additional-costs.edit') }}
@endsection

@section('page-title', 'Edit Additional Cost')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Edit Additional Cost</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.additional-costs.edit') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form class="form form-vertical" action="{{ route('sites.additional-costs.update', ['site_id' => encryptParams($additionalCost->site_id), 'id' => encryptParams($additionalCost->id)]) }}"
            method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">
                @method('PUT')
                @csrf
                {{ view('app.additional-costs.form-fields', ['additionalCost' => $additionalCost, 'additionalCosts' => $additionalCosts]) }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    Update Additional Cost
                </button>
                <a href="{{ route('sites.additional-costs.index', ['site_id' => encryptParams($site_id)]) }}"
                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    {{ __('lang.commons.cancel') }}
                </a>
            </div>

        </form>
    </div>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        let allCheckboxes = $('#applicable_on_site, #applicable_on_floor, #applicable_on_unit');
        $(document).ready(function() {
            $('#has_child').trigger('change');
            allCheckboxes.trigger('change');
        });

        function convertToSlug(text) {
            let slug = $('#slug');
            slug.val(text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        }

        $('#has_child').on('change', function() {
            if ($(this).is(':checked')) {
                $('#hasChildCard').hide();
                allCheckboxes.attr('checked', false).trigger('change');
            } else {
                $('#hasChildCard').show();
            }
        });

        $('#applicable_on_site').on('change', function() {
            if ($(this).is(':checked')) {
                $('#site_percentage').attr('readonly', false);
            } else {
                $('#site_percentage').attr('readonly', true).val(0);
            }
        });

        $('#applicable_on_floor').on('change', function() {
            if ($(this).is(':checked')) {
                $('#floor_percentage').attr('readonly', false);
            } else {
                $('#floor_percentage').attr('readonly', true).val(0);
            }
        });

        $('#applicable_on_unit').on('change', function() {
            if ($(this).is(':checked')) {
                $('#unit_percentage').attr('readonly', false);
            } else {
                $('#unit_percentage').attr('readonly', true).val(0);
            }
        });
    </script>
@endsection
