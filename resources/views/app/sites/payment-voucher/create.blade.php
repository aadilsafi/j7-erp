@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.payment-voucher.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Payment Voucher')

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
        .hideDiv {
            display: none;
        }

        .form-label {
            margin-top: 10px;
        }

        #main-div {
            display: none;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Payment Voucher</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.payment-voucher.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="paymentVoucher" action="{{ route('sites.payment-voucher.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class=" ">
        @csrf

        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.payment-voucher.form-fields', [
                    'site_id' => $site_id,
                    'stakholders' => $stakholders,
                ]) }}
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">

                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Amount To Be Paid <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control amountFormat @error('amount_in_numbers') is-invalid @enderror"
                                name="amount_to_be_paid" id="amount_to_be_paid" placeholder="Amount Received"/>
                            @error('amount_in_numbers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <a id="saveButton" href="#"
                            class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                            <i data-feather='save'></i>
                            Save
                        </a>
                        <a href="{{ route('sites.payment-voucher.index', ['site_id' => encryptParams($site_id)]) }}"
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
        var e = $("#stakeholderAP");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-stakeholder_types', ['stakeholderId' => ':stakeholderId']) }}"
                .replace(':stakeholderId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#paymentVoucher');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#stakholder_type').empty();
                            $("#stakholder_type").html(response.types);

                            // Stakhoder data
                            $('#name').val(response.stakeholder.full_name);
                            $('#identity_number').val(response.stakeholder.cnic);
                            $('#buiness_address').val(response.stakeholder.mailing_address);
                            $('#ntn').val(response.stakeholder.ntn);

                            hideBlockUI('#paymentVoucher');
                        } else {
                            hideBlockUI('#paymentVoucher');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#paymentVoucher');
                    }
                });
            }
        });

        var t = $("#stakholder_type");
        t.wrap('<div class="position-relative"></div>');
        t.select2({
            dropdownAutoWidth: !0,
            dropdownParent: t.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            $('#main-div').hide();

            showBlockUI('#paymentVoucher');
            if ($(this).val()[0] == 'C') {
                $('#main-div').show();
                $('#representativeBussinessInputFields').hide();
                $('#expanseAccountInputField').hide();
                $('.advanceDiscountInputField').hide();
                $('#paymentTermsInputs').hide();
                // $('#d-div').hide();
                // $('#v-div').hide();
                // $('#c-div').show();

            }
            if ($(this).val()[0] == 'D') {
                $('#main-div').show();
                $('#representativeBussinessInputFields').show();
                $('#expanseAccountInputField').show();
                $('.advanceDiscountInputField').hide();
                $('#paymentTermsInputs').hide();
                // $('#c-div').hide();
                // $('#v-div').hide();
                // $('#d-div').show();

            }
            if ($(this).val()[0] == 'V') {
                $('#main-div').show();
                $('#representativeBussinessInputFields').show();
                $('#expanseAccountInputField').show();
                $('.advanceDiscountInputField').show()
                $('#paymentTermsInputs').show();
                // $('#d-div').hide();
                // $('#c-div').hide();
                // $('#v-div').show();

            }
            hideBlockUI('#paymentVoucher');

        });

        function getAccountsPayableData(stakeholder_type) {
            // alert($('#stakeholderAP').val());
            let stakeholder_id = $('#stakeholderAP').val();
            var _token = '{{ csrf_token() }}';
            let url = "{{ route('sites.payment-voucher.ajax-get-accounts-payable-data', ['site_id' => $site_id]) }}";

            $.ajax({
                url: url,
                type: 'post',
                data: {
                    '_token': _token,
                    'stakeholder_id': stakeholder_id,
                    'stakeholder_type': stakeholder_type,
                },
                success: function(response) {
                    if (response.success) {


                        $('#total_payable_amount').val(response.payable_amount);
                        $('#account_payable').val(response.account_payable);


                        hideBlockUI('#paymentVoucher');
                    } else {
                        hideBlockUI('#paymentVoucher');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                    hideBlockUI('#paymentVoucher');
                }
            });




        }


        $('#amount_to_be_paid').on('focusout', function() {
            let formated_amount = $(this).val().replace(/,/g, "");
            let amount_to_be_paid = $(this).val();

            if($.isNumeric(formated_amount)){

            }
            else{
                $(this).val('');

            }

        });


        var validator = $("#paymentVoucher").validate({
            rules: {
                // 'dealer_id': {
                //     required: true,
                // },
                // 'dealer_incentive': {
                //     required: true,
                //     digits: true
                // },
                // 'total_unit_area': {
                //     required: true
                // },
                // 'total_dealer_incentive': {
                //     required: true
                // },
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
