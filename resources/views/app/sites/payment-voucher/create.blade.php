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

        .bankDiv {
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
                    'banks' => $banks,
                    'chequebanks' => $banks,
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
                                name="amount_to_be_paid" id="amount_to_be_paid" placeholder="Amount Received" />
                            @error('amount_in_numbers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <a id="saveButton" href="#"
                            class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                            <i data-feather='save'></i>
                            Save
                        </a>
                        <a href="{{ route('sites.payment-voucher.index', ['site_id' => $site_id]) }}"
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
        $(".other-mode-of-payment").click(function() {
            $('#otherValueDiv').show();
            $('#onlineValueDiv').hide();
            $('#chequeValueDiv').hide();
            $('.bankDiv').hide();
        });

        $(".cheque-mode-of-payment").click(function() {
            $('#otherValueDiv').hide();
            $('#onlineValueDiv').hide();
            $('#chequeValueDiv').show();
            $('.bankDiv').show();
        });

        $(".online-mode-of-payment").click(function() {
            $('#otherValueDiv').hide();
            $('#onlineValueDiv').show();
            $('#chequeValueDiv').hide();
            $('.bankDiv').show();
        });

        $(".mode-of-payment").click(function() {
            $('#otherValueDiv').hide();
            $('#onlineValueDiv').hide();
            $('#chequeValueDiv').hide();
            $('.bankDiv').hide();
        });

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
            showBlockUI('#paymentVoucher');
            let formated_amount = $(this).val().replace(/,/g, "");
            let amount_to_be_paid = $(this).val();
            if ($.isNumeric(formated_amount)) {

                let total_payable_amount = $('#total_payable_amount').val().replace(/,/g, "");
                if (parseFloat(formated_amount) > parseFloat(total_payable_amount)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Amount to be paid can not be greater than total payable amount',
                    });
                    $(this).val('');
                    hideBlockUI('#paymentVoucher');
                } else {
                    let advance_given = $('#advance_given').val().replace(/,/g, "");
                    let discount_given = $('#discount_recevied').val().replace(/,/g, "");

                    let remaining_amount = parseFloat(total_payable_amount) - parseFloat(formated_amount);

                    let net_payable = parseFloat(total_payable_amount);

                    $('#remaining_payable').val(remaining_amount.toLocaleString());
                    $('#net_payable').val(net_payable.toLocaleString());

                    hideBlockUI('#paymentVoucher');
                }

            } else {
                $(this).val('');
                hideBlockUI('#paymentVoucher');
            }
            hideBlockUI('#paymentVoucher');

        });


        var validator = $("#paymentVoucher").validate({
            rules: {
                'stakeholder_id': {
                    required: true,
                },
                'stakeholder_type_id': {
                    required: true,
                    // digits: true
                },
                'amount_to_be_paid': {
                    required: true
                },
                'tax_status': {
                    required: true
                },
                'description': {
                    required: true
                },
                'account_payable': {
                    required: true
                },
                'total_payable_amount': {
                    required: true
                },
                'remaining_payable': {
                    required: true
                },
                'net_payable': {
                    required: true
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
            let mode_of_payment = $("input[name='mode_of_payment']:checked").val();

            if (mode_of_payment == 'Cheque') {
                let cheque_no = $('#cheque_no').val();
                if (cheque_no == '') {
                    $('#cheque_no').addClass('is-invalid');
                    $('#cheque_no').parent().append(
                        '<span class="is-invalid text-danger">Cheque No is required!</span>');
                } else {
                    $("#paymentVoucher").submit();
                }
            } else if (mode_of_payment == 'Online') {
                let transaction_date = $('#transaction_date').val();
                let online_instrument_no = $('#online_instrument_no').val();

                if (online_instrument_no == '') {
                    $('#online_instrument_no').addClass('is-invalid');
                    $('#online_instrument_no').parent().append(
                        '<span class="is-invalid text-danger">Transaction No is required!</span>');
                } else {
                    $("#paymentVoucher").submit();
                }

            }
            else if (mode_of_payment == 'Other') {
                let other_value = $('#other_value').val();
                if(other_value == ''){
                    $('#other_value').addClass('is-invalid');
                    $('#other_value').parent().append(
                        '<span class="is-invalid text-danger">Other Value is required!</span>');
                }
                else{
                    $("#paymentVoucher").submit();
                }
            }
            else {
                $("#paymentVoucher").submit();
            }
        });

        var e = $(".bank");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on("change", function(e) {
            let bank = parseInt($(this).val());
            showBlockUI('.bankDiv');
            let bankData = {
                id: 0,
                name: '',
                account_number: '',
                branch: '',
                branch_code: '',
                comments: '',
                contact_number: '',
                address: '',
            };
            $.ajax({
                url: "{{ route('sites.banks.ajax-get-by-id', ['site_id' => encryptParams($site_id)]) }}",
                type: 'POST',
                data: {
                    'id': bank
                },
                success: function(response) {
                    if (response.success == true && response.bank != null) {
                        $('.name').val(response.bank.name).attr('readOnly', (
                            response.bank.name.length > 0));
                        $('.account_number').val(response.bank.account_number).attr('readOnly', (
                            response.bank.account_number.length > 0));
                        $('.contact_number').val(response.bank.contact_number).attr('readOnly', (
                            response.bank.contact_number.length > 0));
                        $('.branch').val(response.bank.branch).attr('readOnly', (response.bank.branch
                            .length > 0));
                        $('.branch_code').val(response.bank.branch_code).attr('readOnly', (response.bank
                            .branch_code.length > 0));
                        $('.comments').val(response.bank.comments).attr('readOnly', true);
                        $('.address').val(response.bank.address).attr('readOnly', (response.bank.address
                            .length > 0));
                        hideBlockUI('.bankDiv');
                    } else {

                        $('#name').val('').removeAttr('readOnly');
                        $('#account_number').val('').removeAttr('readOnly');
                        $('#contact_number').val('').removeAttr('readOnly');
                        $('#branch').val('').removeAttr('readOnly');
                        $('#branch_code').val('').removeAttr('readOnly');
                        $('#comments').val('').removeAttr('readOnly');
                        $('#address').val('').removeAttr('readOnly');
                    }
                    hideBlockUI('.bankDiv');
                },
                error: function(errors) {
                    console.error(errors);
                    hideBlockUI('.bankDiv');
                }
            });
        });

        $("#transaction_date").flatpickr({
            defaultDate: "today",
            // minDate: "today",
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });



    </script>
@endsection
