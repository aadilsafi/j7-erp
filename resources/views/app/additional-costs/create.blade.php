@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.additional-costs.create', $site_id) }}
@endsection

@section('page-title', 'Create Additional Cost')

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
                <h2 class="content-header-title float-start mb-0">Create Additional Cost</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.additional-costs.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" action="{{ route('sites.additional-costs.store', ['site_id' => $site_id]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.additional-costs.form-fields', ['additionalCosts' => $additionalCosts]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                        <i data-feather='save'></i>
                                        Save Additional Cost
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.additional-costs.index', ['site_id' => encryptParams($site_id)]) }}"
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
        $(document).ready(function() {
            $('#has_child').trigger('change');
        });
        let allCheckboxes = $('#applicable_on_site, #applicable_on_floor, #applicable_on_unit');

        function convertToSlug(text) {
            let slug = $('#slug');
            slug.val(text.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
        }

        $('#has_child').on('change', function() {
            if ($(this).is(':checked')) {
                $('#hasSubAdditionalCost').collapse('hide');
                allCheckboxes.attr('checked', false).trigger('change');
            } else {
                $('#hasSubAdditionalCost').collapse('show');
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
