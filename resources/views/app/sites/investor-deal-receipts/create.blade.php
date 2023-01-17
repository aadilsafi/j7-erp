@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.investor-deals-receipts.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Investor Deal Receipt')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
    <style>
        #otherValueDiv {
            display: none;
        }

        #installmentValueDiv {
            display: none;
        }

        #otherPurposeValueDiv {
            display: none;
        }

        #instllmentTableDiv {
            display: none;
        }

        #paidInstllmentTableDiv {
            display: none;
        }

        .onlineValueDiv {
            display: none;
        }

        .bankDiv {
            display: none;
        }

        .chequeValueDiv {
            display: none;
        }

        #modeOfPaymentDiv {
            display: none;
        }

        #transferOwner {
            display: none;

        }

        #fileOwner {
            display: none;
        }

        .filepond--drop-label {
            color: #7367F0 !important;
        }

        .filepond--item-panel {
            background-color: #7367F0;
        }

        .filepond--panel-root {
            background-color: #e3e0fd;
        }

        /* .filepond--item {
                                                                                                                                                                                                                                            width: calc(20% - 0.5em);
                                                                                                                                                                                                                                        } */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Investor Deal Receipts</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.investor-deals-receipts.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="receiptForm" enctype="multipart/form-data"
        action="{{ route('sites.investor-deals-receipts.store', ['site_id' => encryptParams($site_id)]) }}" method="post"
        class="repeater">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-9 position-relative">
                {{ view('app.sites.investor-deal-receipts.form-fields', [
                    'site_id' => $site_id,
                    'banks' => $banks,
                    'chequebanks' => $banks,
                    'investor_deals' => $investor_deals,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">

                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="total_payable_amount">Total Receivable
                                Amount</label>
                            <input readonly type="text" class="form-control amountFormat" id="receivable_amount"
                                placeholder="Total Receivable Amount " />

                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Receipt Document Number
                                <span class="text-danger">*</span>
                            </label>
                            <input name="doc_number" type="text"
                                class="form-control  @error('doc_number') is-invalid @enderror" id="doc_number"
                                placeholder="Receipt Document Number " />
                            @error('doc_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="created_date">Payment Date</label>
                            <input id="created_date" type="date" required placeholder="YYYY-MM-DD" name="created_date"
                                class="form-control form-control-lg" />
                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="type_name">Attachment</label>
                            <input id="attachment" type="file" multiple
                                class="filepond @error('attachment') is-invalid @enderror" name="attachment[]"
                                accept="image/png, image/jpeg, image/gif,application/pdf" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @can('sites.investor-deals-receipts.store')
                            <a id="saveButton" href="#"
                                class="btn disabled text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Save Receipt
                            </a>
                        @endcan

                        <a href="{{ route('sites.investor-deals-receipts.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>

@endsection

@section('custom-js')

    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
            FilePondPluginPdfPreview,
        );

        FilePond.create(document.getElementById('attachment'), {
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg', 'application/pdf'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            // maxFiles: 1,
            checkValidity: true,

        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $(".online-mode-of-payment").trigger('change');
            $("#transaction_date").flatpickr({
                defaultDate: 'today',
                maxDate: 'today',
                // minDate: '',
                // altInput: !0,
                dateFormat: "Y-m-d",
            });

            $('.repeater').repeater({
                show: function() {
                    $(this).slideDown();
                },
                isFirstItemUndeletable: true
            })

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
                // $.fn.filepond.setDefaults({
                //     required: true,
                // });
            });

            $(".online-mode-of-payment").click(function() {
                $('#otherValueDiv').hide();
                $('#onlineValueDiv').show();
                $('#chequeValueDiv').hide();
                $('.bankDiv').show();
                // $.fn.filepond.setDefaults({
                //     required: true,
                // });
            });

            $(".mode-of-payment").click(function() {
                $('#otherValueDiv').hide();
                $('#onlineValueDiv').hide();
                $('#chequeValueDiv').hide();
                $('.bankDiv').hide();
            });

            $(".other-purpose").click(function() {
                $('#otherPurposeValueDiv').show();
                $('#installmentValueDiv').hide();
            });

            $(".installment-purpose").click(function() {
                $('#installmentValueDiv').show();
                $('#otherPurposeValueDiv').hide();
            });

            $(".purpose").click(function() {
                $('#otherPurposeValueDiv').hide();
                $('#installmentValueDiv').hide();
            });

        });

        var created_date = $("#created_date").flatpickr({
            defaultDate: "today",
            minDate: '',
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        var transfer_file_id = $('#deal_id');
        transfer_file_id.wrap('<div class="position-relative"></div>');
        transfer_file_id.select2({
            dropdownAutoWidth: !0,
            dropdownParent: transfer_file_id.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on("change", function(e) {
            $('#modeOfPaymentDiv').hide();
            $('#transferOwner').hide();
            $('#fileOwner').hide()
            $('#saveButton').addClass('disabled');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.investor-deals-receipts.ajax-get-investor-deals-data', ['site_id' => encryptParams($site_id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'deal_id': parseInt($(this).val()),
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {

                        showBlockUI('#receiptForm');


                        let total_receivable_amount = parseFloat(response.receivable_amount)
                        $('#receivable_amount').val(total_receivable_amount.toLocaleString())

                        $('#modeOfPaymentDiv').show();

                        hideBlockUI('#receiptForm');
                        $('#saveButton').removeClass('disabled');

                    } else {
                        hideBlockUI('#receiptForm');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });

                    }
                },
                error: function(error) {
                    hideBlockUI('#receiptForm');

                    console.log(error);
                }
            });
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

        var validator = $("#receiptForm").validate({
            rules: {
                'doc_number': {
                    required: true,
                },
                'bank_name': {
                    required: function() {
                        let mode_of_payment = $("input[name='mode_of_payment']:checked").val();
                        if (mode_of_payment == 'Cheque' || mode_of_payment == 'Online') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                'bank_account_number': {
                    required: function() {
                        let mode_of_payment = $("input[name='mode_of_payment']:checked").val();
                        if (mode_of_payment == 'Cheque' || mode_of_payment == 'Online') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                'bank_contact_number': {
                    required: function() {
                        let mode_of_payment = $("input[name='mode_of_payment']:checked").val();
                        if (mode_of_payment == 'Cheque' || mode_of_payment == 'Online') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                'bank_branch': {
                    required: function() {
                        let mode_of_payment = $("input[name='mode_of_payment']:checked").val();
                        if (mode_of_payment == 'Cheque' || mode_of_payment == 'Online') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                'bank_branch_code': {
                    required: function() {
                        let mode_of_payment = $("input[name='mode_of_payment']:checked").val();
                        if (mode_of_payment == 'Cheque' || mode_of_payment == 'Online') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                'bank_address': {
                    required: function() {
                        let mode_of_payment = $("input[name='mode_of_payment']:checked").val();
                        if (mode_of_payment == 'Cheque' || mode_of_payment == 'Online') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }

            },
            // messages: {
            //     'stackholder[cnic]': {
            //         maxlength: "Cnic can't be greater then {0} digits without dashes",
            //         minlength: "Cnic can't be less then {0} digits without dashes",
            //     }
            // },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#saveButton").click(function() {
            let mode_of_payment = $("input[name='mode_of_payment']:checked").val();

            $('.is-invalid').removeClass('is-invalid');
            $('.errorClass').remove();

            if (mode_of_payment == 'Cheque') {
                let cheque_no = $('#cheque_no').val();
                if (cheque_no == '') {
                    $('#cheque_no').addClass('is-invalid');
                    $('#cheque_no').parent().append(
                        '<span class="is-invalid text-danger errorClass">Cheque No is required!</span>');
                } else {
                    $("#receiptForm").submit();
                }
            } else if (mode_of_payment == 'Online') {
                let transaction_date = $('#transaction_date').val();
                let online_instrument_no = $('#online_instrument_no').val();

                if (online_instrument_no == '') {
                    $('#online_instrument_no').addClass('is-invalid');
                    $('#online_instrument_no').parent().append(
                        '<span class="is-invalid text-danger errorClass">Transaction No is required!</span>');
                } else {
                    $("#receiptForm").submit();
                }

            } else if (mode_of_payment == 'Other') {
                let other_value = $('#other_value').val();
                let dealer_ap_amount_paid = $('#dealer_ap_amount_paid').val();
                let customer_ap_amount_paid = $('#customer_ap_amount_paid').val();
                let vendor_ap_amount_paid = $('#vendor_ap_amount_paid').val();

                let sum_ap_amount = parseFloat(dealer_ap_amount_paid) + parseFloat(customer_ap_amount_paid) +
                    parseFloat(vendor_ap_amount_paid);
                let amount_toBe_paid = parseFloat($('#total_payable_amount').val().replace(/,/g, ''));

                if (other_value == '') {
                    $('#other_value').addClass('is-invalid');
                    $('#other_value').parent().append(
                        '<span class="is-invalid text-danger errorClass">Other Payment Purpose is required!</span>'
                    );
                }

                let invalid_amount_stauts = 0;

                if (parseFloat(customer_ap_amount_paid) > parseFloat($('#customer_ap_amount').val().replace(/,/g,
                        ''))) {
                    $('#customer_ap_amount_paid').addClass('is-invalid');
                    invalid_amount_stauts = 1;
                    $('#customer_ap_amount_paid').after(
                        '<span class="is-invalid text-danger errorClass">Entered Amount shold not be greater than existing Customer payable amount!</span>'
                    );
                }

                if (parseFloat(vendor_ap_amount_paid) > parseFloat($('#vendor_ap_amount').val().replace(/,/g,
                        ''))) {
                    $('#vendor_ap_amount_paid').addClass('is-invalid');
                    invalid_amount_stauts = 1;
                    $('#vendor_ap_amount_paid').after(
                        '<span class="is-invalid text-danger errorClass">Entered Amount shold not be greater than existing Vendor payable amount!</span>'
                    );
                }

                if (parseFloat(dealer_ap_amount_paid) > parseFloat($('#dealer_ap_amount').val().replace(/,/g,
                        ''))) {
                    $('#dealer_ap_amount_paid').addClass('is-invalid');
                    invalid_amount_stauts = 1;
                    $('#dealer_ap_amount_paid').after(
                        '<span class="is-invalid text-danger errorClass">Entered Amount shold not be greater than existing Dealer payable amount!</span>'
                    );

                }

                if (sum_ap_amount != amount_toBe_paid || invalid_amount_stauts == 1) {
                    if (sum_ap_amount != amount_toBe_paid) {
                        $('#customer_ap_amount_paid').addClass('is-invalid');
                        $('#vendor_ap_amount_paid').addClass('is-invalid');
                        $('#dealer_ap_amount_paid').addClass('is-invalid');
                        $('#attachment').after(
                            '<span class="is-invalid text-danger errorClass">Sum Of All Entered Payable Amount is not equal to Amount To Be Paid!</span>'
                        );
                    }
                } else {
                    $("#receiptForm").submit();
                }

            } else {
                $("#receiptForm").submit();
            }

        });
    </script>
@endsection
