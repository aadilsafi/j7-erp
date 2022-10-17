@extends('app.layout.layout')

@section('seo-breadcrumb')
{{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.create', encryptParams($site->id),
encryptParams($floor->id)) }}
@endsection

@section('page-title', 'Create Bifurcated Unit')

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

    .hidediv,
    .areaAlert {
        display: none;
    }
</style>
@endsection

@section('breadcrumbs')
<div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Create Bifurcated Unit</h2>
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
            'emptyUnit' => $emptyUnit,
            ]) }}

        </div>

        <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
            <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: 10 !important;">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row g-1">
                            <div class="col-md-12">

                                <div class="alert alert-warning alert-dismissible m-0 fade show areaAlert" role="alert">
                                    <h4 class="alert-heading"><i data-feather='alert-triangle'
                                            class="me-50"></i>Warning!</h4>
                                    <div class="alert-body">
                                        <strong>Sub Units Gross area must be Equal to Total Unit Gross area.</strong>

                                    </div>
                                </div>
                                <hr>

                                <button
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
    $('#unit_id').trigger('change');

    $(document).ready(function() {


        $('#unit_id').on('change', function() {
            var unit_id = $(this).val();
            if(unit_id !=0){
                
                @if(!$errors->any())
                     $('[data-repeater-item]').slice(1).empty();
                @endif
                restInputs();
                $('.hidediv').hide();
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
                        $('#width').val(response.unit.width);
                        $('#length').val(response.unit.length);
                        $('#net_area').val(response.remaing_net);
                        $('#gross_area').val(response.remaing_gross);
                        $('#price_sqft').val(response.unit.price_sqft);
                        $('#total_price1').val(numberFormat(parseFloat(response.unit.total_price).toFixed(2)));
                        $('#unit_number').val(response.max_unit_number);
                        $('#floor_name').val(response.floor_name);
                        $('#floor_id').val(response.unit.floor_id);
                        $('#unit_total_area').val(response.remaing_gross);
                        $('#sub_unit_total_area').val(response.remaing_gross);
                        $('#unit_net_area').val(response.unit.net_area);
                        $('#sub_unit_total_area').val(response.unit.net_area);
                        if(response.remaing_gross == 0){
                            toastr.error('Unit is Already Bifurcated.',
                                "Error!", {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 2e3,
                                    closeButton: !0,
                                    tapToDismiss: !1,
                                });
                        }else{
                            $('.hidediv').show();

                        }

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

                    function restInputs(){
                        $('#width').val();
                        $('#length').val();
                        $('#net_area').val();
                        $('#gross_area').val();
                        $('#price_sqft').val();
                        $('#total_price1').val();
                        $('#unit_number').val();
                        $('#floor_name').val();
                        $('#floor_id').val();
                        $('#unit_total_area').val();
                        $('#sub_unit_total_area').val();
                    }

                    function calculatePrice(){
                        alert(1);
                    }

                    jQuery.validator.addClassRules({
                RequiredField: {
                    required: true,
                },
            });

            $.validator.addMethod("ValidateArea", function(value, element) {
                var parentForm = $(element).closest('form');
                var net_area;
                var gross_area;
                if (value != '') {  
                    $(parentForm.find('.tocheckArea')).each(function() {
                      
                        var name = $(this).attr('name');
                      var index = Number(name.substring(10,11));

                      net_area = $('[name="fab-units['+index+'][net_area]"]').val()
                      gross_area = $('[name="fab-units['+index+'][gross_area]"]').val();
                    
                    });
                   
                }
                if(Number(gross_area) >= Number(net_area)){
                    return true
                }else{
                    return false;
                }

            }, "Net area Must be less than or Equal to Gross area.");


            $.validator.addMethod("calculateArea", function(value, element) {
                var parentForm = $(element).closest('form');
                
                if (value != '') {  
                    $(parentForm.find('.tocheckArea')).each(function() {
                      
                        var name = $(this).attr('name');
                      var index = Number(name.substring(10,11));

                      var price = $('[name="fab-units['+index+'][price_sqft]"]').val()
                      var total_price = Number(price) * Number($(this).val());

                      $('[name="fab-units['+index+'][total_price]"]').val(parseFloat(total_price).toFixed(2))
                      $('[name="fab-units['+index+'][total_price1]"]').val(numberFormat(parseFloat(total_price).toFixed(2)))
                      
                      
                    });
                   
                }
               return true;

            });

            $.validator.addMethod("checkArea", function(value, element) {
                var parentForm = $(element).closest('form');
                var unit_total_area = $('#unit_total_area').val();
                var sum_area = 0;
                if (value != '') {  
                    $(parentForm.find('.tocheckArea')).each(function() {
                        sum_area += Number($(this).val());

                        var name = $(this).attr('name');
                      var index = Number(name.substring(10,11));

                      var price = $('[name="fab-units['+index+'][price_sqft]"]').val()
                      var total_price = Number(price) * Number($(this).val());

                      $('[name="fab-units['+index+'][total_price]"]').val(total_price)
                      $('[name="fab-units['+index+'][total_price1]"]').val(total_price)
                      
                      
                    });
                    $('#sub_unit_total_area').val(sum_area);
                }
               
                if(sum_area <= Number(unit_total_area)){
                    return true;
                }else{
                  
                    return false;
                }

            }, "Sub Units Gross area must be Equal to Total Unit Gross area");

            $.validator.addMethod("netArea", function(value, element) {
                var parentForm = $(element).closest('form');
                var unit_net_area = $('#unit_net_area').val();
                var sum_area = 0;
                if (value != '') {  
                    $(parentForm.find('.netArea')).each(function() {
                        sum_area += Number($(this).val());
                    });
                    $('#sub_units_net_area').val(sum_area);
                }
               
                if(sum_area <= Number(unit_net_area)){
                    return true;
                }else{
                  
                    return false;
                }

            }, "Sub Units Gross area must be Less Total Unit Gross area");

            var validator = $("#fabUnitForm").validate({
                    validClass: "success",
                    errorClass: 'is-invalid text-danger',
                    errorElement: "span",
                    wrapper: "div",
                    submitHandler: function(form) {
                        var unit_id =  $('#unit_id').val();
                        $('.areaAlert').hide();
                       
                        var sub_units = $('[data-repeater-item]').slice().length;
                        if(unit_id == 0){
                            toastr.error('Please Select Unit Id.',
                                "Error!", {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 2e3,
                                    closeButton: !0,
                                    tapToDismiss: !1,
                        });
                        }else if(sub_units <= 1){
                                toastr.error('Sub Units Must be more than 1.',
                                "Error!", {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 2e3,
                                    closeButton: !0,
                                    tapToDismiss: !1,
                                    });

                            }else if(Number($('#unit_total_area').val()) != Number($('#sub_unit_total_area').val())){
                                $('.areaAlert').show();
                                toastr.error('Sub Units Gross area must be Equal to Total Unit Gross area.',
                                "Error!", {
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    timeOut: 2e3,
                                    closeButton: !0,
                                    tapToDismiss: !1,
                                    });
                            }else{
                                form.submit();
                            }

                        
                    }
                    });
          
                    $(".fab-units").repeater({
                        defaultValues: {
                                'fab-units[width]': 0,
                                'fab-units[length]': 0,
                                
                            },
                        isFirstItemUndeletable: true,
                            show: function() {
                                $(this).slideDown(), feather && feather.replace({
                                    width: 14,
                                    height: 14
                                })
                                // if(isAvalibleSubUnits()){
                                //     $(this).slideDown(), feather && feather.replace({
                                //     width: 14,
                                //     height: 14
                                // })
                                // }else{
                                //     toastr.error('Sub Units Size Can not be more divided',
                                // "Error!", {
                                //     showMethod: "slideDown",
                                //     hideMethod: "slideUp",
                                //     timeOut: 2e3,
                                //     closeButton: !0,
                                //     tapToDismiss: !1,
                                //     });
                                // }
                                
                            },
                            hide: function(e) {
                                $(this).slideUp(e)
                            }
                        });

                        function isAvalibleSubUnits(){
                        validator
                        var sum_area = $('#sub_unit_total_area').val()
                        var unit_total_area = $('#unit_total_area').val();

                        return Number(sum_area) < Number(unit_total_area)

                    }
        });   
</script>
@endsection