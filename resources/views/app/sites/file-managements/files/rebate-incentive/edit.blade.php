@extends('app.layout.layout')

@section('seo-breadcrumb')
{{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.rebate-incentive.create', encryptParams($site_id))
}}
@endsection

@section('page-title', 'Edit Rebate Incentive')

@section('page-vendor')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/forms/wizard/bs-stepper.min.css">
@endsection

@section('page-css')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-wizard.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
<style>
    .hideDiv {}
</style>
@endsection

@section('breadcrumbs')
<div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
        <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Edit Rebate Incentive</h2>
            <div class="breadcrumb-wrapper">
                {{ Breadcrumbs::render('sites.file-managements.rebate-incentive.create', encryptParams($site_id)) }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<form id="rebateForm"
    action="{{ route('sites.file-managements.rebate-incentive.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($id)]) }}"
    method="post" class=" ">
    @csrf
    @method('PUT')


    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
            {{ view('app.sites.file-managements.files.rebate-incentive.form-fields', [
            'site_id' => $site_id,
            'edit_unit' => $edit_unit,
            'dealer_data' =>$dealer_data,
            'rebate_data' => $rebate_data
            ]) }}
        </div>
        <input type="hidden" id="edit_unit" value="{{$edit_unit->id}}">
        <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
            <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                <div class="card-body g-1">

                    <a id="saveButton" href="#"
                        class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                        <i data-feather='save'></i>
                        Save
                    </a>
                    <a href="{{ route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) }}"
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
<script src="{{ asset('app-assets') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
<script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
<script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
@endsection

@section('custom-js')
<script type="text/javascript">
    let unit_id =   $('#edit_unit').val();
    getData(unit_id);

    updateDealerForm();

        function getData(unit_id) {
            
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.file-managements.rebate-incentive.ajax-get-data', ['site_id' => encryptParams($site_id)]) }}";
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
                        $('#sales_source_lead_source').val(response.leadSource.name);
                        $('#stakeholder_id').val(response.stakeholder.id);
                        $('#customer_name').val(response.stakeholder.full_name);
                        $('#customer_father_name').val(response.stakeholder.father_name);
                        $('#customer_cnic').val(response.cnic);
                        $('#customer_address').val(response.stakeholder.address);
                        $('#customer_phone').val(response.stakeholder.contact);
                        $('#customer_occupation').val(response.stakeholder.occupation);

                        $('#td_unit_id').html(response.unit.unit_number);
                        $('#td_unit_area').html(response.unit.gross_area);
                        $('#td_unit_rate').html(response.unit.price_sqft.toLocaleString());

                        if (response.unit.facing != null) {
                            $('#td_unit_facing_charges').html(response.unit.facing.unit_percentage + '%');
                        } else {
                            $('#td_unit_facing_charges').html(0 + '%');
                        }

                        let unit_total = response.unit.price_sqft * response.unit.gross_area;
                        $('#unit_total').val(unit_total)

                        $('#td_unit_discount').html(response.salesPlan.discount_percentage + '%');
                        $('#td_unit_total').html(unit_total.toLocaleString());
                        $('#td_unit_downpayment').html(response.salesPlan.down_payment_percentage + '%');

                        if (response.unit.facing != null) {
                            let facing_value = response.salesPlan.discount_percentage * response.salesPlan
                                .total_price;
                            $('#td_unit_facing_charges_value').html(facing_value)
                        } else {
                            $('#td_unit_facing_charges_value').html(0);
                        }

                        $('#td_unit_discount_value').html(response.salesPlan.discount_total.toLocaleString());
                        $('#td_unit_total_value').html(response.salesPlan.total_price.toLocaleString());
                        $('#td_unit_downpayment_value').html(response.salesPlan.down_payment_total.toLocaleString());

                        rebateValue();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function rebateValue(){
            showBlockUI('#rebate-form');
            let rebate_percentage =  $('#rebate_percentage').val();
            let unit_total = $('#unit_total').val()
            let percentage = rebate_percentage / 100;
            let rebate_value = unit_total * percentage;
            $('#td_rebate').html(rebate_percentage + '%');
            $('#td_rebate_value').html(rebate_value.toLocaleString());
            $('#rebate_total').val(rebate_value);
            $('.hideDiv').css("display", "block");
            hideBlockUI('#rebate-form');
        }

        var e = $("#dealer");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).on("change", function(e) {
                updateDealerForm();
            });

            function updateDealerForm(){
                let dealer = $('#dealer').val();

                    if (dealer === "0") {
                        $('#div_new_dealer').show();
                    } else {
                        $('#div_new_dealer').hide();
                    }
                }

            var validator = $("#rebateForm").validate({
                rules: {
                'dealer[full_name]': {
                    required: true
                },
                'dealer[father_name]': {
                    required: true
                },
                'dealer[occupation]': {
                    required: true
                },
                'dealer[designation]': {
                    required: true
                },
                'dealer[contact]': {
                    required: true,
                    digits: true,
                },
                'dealer[cnic]': {
                    required: true,
                    digits: true
                },
                'dealer[ntn]': {
                    required: true,
                },
                'dealer[address]': {
                    required: true,
                },
                'deal_type': {
                    required: true,
                },
                
            },
                errorClass: 'is-invalid text-danger',
                errorElement: "span",
                wrapper: "div",
                submitHandler: function(form) {
                    form.submit();
                }
                });

        $("#saveButton").click(function() {
            $("#rebateForm").submit();
        });
</script>
@endsection