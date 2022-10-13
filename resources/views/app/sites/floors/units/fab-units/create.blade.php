@extends('app.layout.layout')

@section('seo-breadcrumb')
{{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.create', encryptParams($site->id),
encryptParams($floor->id)) }}
@endsection

@section('page-title', 'Create Unit')

@section('page-vendor')
@endsection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/extensions/nouislider.min.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets') }}/css/plugins/extensions/ext-component-sliders.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/core/colors/palette-noui.css">
@endsection

@section('custom-css')
<style>
    .noUi-tooltip {
        font-size: 20px;
        color: #7367f0;
    }

    .noUi-value {
        font-size: 15px !important;
        color: #7367f0 !important;
    }

    .hidediv {
        display: none;
    }
</style>
@endsection

@section('breadcrumbs')
<div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Create Unit</h2>
            <div class="breadcrumb-wrapper">
                {{ Breadcrumbs::render('sites.floors.units.create', encryptParams($site->id), encryptParams($floor->id))
                }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<form class="form form-vertical"
    action="{{ route('sites.floors.units.fab.store', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
    method="POST" id="fabUnitForm">

    <div class="row">
        <div id="loader" class="col-lg-9 col-md-9 col-sm-12 position-relative">

            @csrf
            {{ view('app.sites.floors.units.fab-units.form-fields', [
            'site' => $site,
            'units' => $units,
            'floor' => $floor,
            'siteConfiguration' => $siteConfiguration,
            'additionalCosts' => $additionalCosts,
            'types' => $types,
            'statuses' => $statuses,
            ]) }}

        </div>

        <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
            <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: 10 !important;">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row g-1">
                            <div class="col-md-12">
                                <button type="submit"
                                    class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                    <i data-feather='save'></i>
                                    <span id="create_unit_button_span">Save Unit</span>
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
<script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>

@endsection

@section('page-js')
<script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
@endsection

@section('custom-js')
<script>
    $(document).ready(function() {

        $('#unit_id').trigger('change');

        $('#unit_id').on('change', function() {
            var unit_id = $(this).val();
            if(unit_id !=0){
            showBlockUI('#loader');

                var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.floors.units.ajax-get-unit-data', ['site_id' => encryptParams($site->id),'floor_id' => encryptParams($floor->id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'unit_id': unit_id,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        // $('#floor_name').val(response.unit.name);
                        $('#width').val(response.unit.width);
                        $('#length').val(response.unit.length);
                        $('#net_area').val(response.unit.net_area);
                        $('#gross_area').val(response.unit.gross_area);
                        $('#price_sqft').val(response.unit.price_sqft);
                        $('#total_price1').val(response.unit.total_price);
                        $('#unit_number').val(response.max_unit_number);
                        $('#floor_name').val(response.floor_name);
                        $('#floor_id').val(response.unit.floor_id);
                        $('#unit_total_area').val(response.unit.gross_area);

                         $('.hidediv').show();

                        hideBlockUI('#loader');
                    } else {
                        hideBlockUI('#loader');
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });
                    }
                },
                error: function(error) {
                    hideBlockUI('#loader');
                    console.log(error);
                }
            });
            }
            });

            $('.is_corner').on('change', function() {
                // alert($(this).attr("name"));
                if ($(this).is(':checked')) {
                    $('#corner_id').attr('disabled', false);
                } else {
                    $('#corner_id').attr('disabled', true);
                }
            });

            // $("name=['fab-units'][0]['is_corner'][]").on('change', function(){
            //     alert(1);
            // });

            $('.is_facing').on('change', function() {
              

                if ($(this).is(':checked')) {
                    $('#facing_id').attr('disabled', false);
                } else {
                    $('#facing_id').attr('disabled', true);
                }
            });

            // $('.is_corner').trigger('change');
            // $('.is_facing').trigger('change');


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
        });

        // function test(){
        //     alert('test');
        // }
        $(".fab-units").repeater({
            // defaultValues: {
            //     'fabUnits[name]': test(),
            // },
            isFirstItemUndeletable: true,
                show: function() {
                    $(this).slideDown(), feather && feather.replace({
                        width: 14,
                        height: 14
                    })
                },
                hide: function(e) {
                    $(this).slideUp(e)
                }
            });

            $.validator.addMethod("checkArea", function(value, element) {
                var parentForm = $(element).closest('form');
                var unit_total_area = $('#unit_total_area').val();
                var sum_area = 0;
                if (value != '') {  
                    $(parentForm.find('.checkArea')).each(function() {
                        sum_area += Number($(this).val());
                    });
                }
               
                if(sum_area > unit_total_area){
                    return false;
                }else{
                    return true;
                }

            }, "Sub Units Gross area must be less than Total Gross area");

            var validator = $("#fabUnitForm").validate({

                    errorClass: 'is-invalid text-danger',
                    errorElement: "span",
                    wrapper: "div",
                    submitHandler: function(form) {
                        form.submit();
                    }
                    });
</script>
@endsection