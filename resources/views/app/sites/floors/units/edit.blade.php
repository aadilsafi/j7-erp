@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.edit', encryptParams($site->id), encryptParams($floor->id)) }}
@endsection

@section('page-title', 'Edit Unit')

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
                <h2 class="content-header-title float-start mb-0">Edit Unit</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.edit', encryptParams($site->id), encryptParams($floor->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical"
        action="{{ route('sites.floors.units.update', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id), 'id' => encryptParams($unit->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @method('PUT')
                @csrf
                {{ view('app.sites.floors.units.form-fields', [
                    'site' => $site,
                    'floor' => $floor,
                    'siteConfiguration' => $siteConfiguration,
                    'additionalCosts' => $additionalCosts,
                    'types' => $types,
                    'statuses' => $statuses,
                    'unit' => $unit,
                    'bulkOptions' => false,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI">
                                        <i data-feather='save'></i>
                                        <span id="update_unit_button_span">Update Unit</span>
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.floors.units.index', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
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

            $('#is_corner').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#corner_id').attr('disabled', false);
                } else {
                    $('#corner_id').attr('disabled', true);
                }
            });

            $('#is_facing').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#facing_id').attr('disabled', false);
                } else {
                    $('#facing_id').attr('disabled', true);
                }
            });

            $('#is_corner').trigger('change');
            $('#is_facing').trigger('change');

        });

        //Calculate Unit Price and Total Price from Gross Area
        $('#gross_area, #price_sqft').on('keyup', function() {
            var total_price = 0;
            var gross_area = 0;
            var price_sqft = 0;
            if ($(this).val() > 0) {
                gross_area = parseFloat($('#gross_area').val());
                price_sqft = parseFloat($('#price_sqft').val());
                total_price = gross_area * price_sqft;
            } else {
                total_price = 0;
                // $(this).val('0');
            }
            $('#total_price1').val('' + numberFormat(parseFloat(total_price).toFixed(2)));
            $('#total_price').val('' + parseFloat(total_price).toFixed(2));

        });
    </script>
@endsection
