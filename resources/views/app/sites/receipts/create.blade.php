@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.receipts.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create  Receipt')

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

        #customerData {
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
                    width: calc(50% - 0.5em);
                }                                                                                                         } */
        */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create  Receipt</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.receipts.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="receiptForm" enctype="multipart/form-data"
        action="{{ route('sites.receipts.store', ['site_id' => encryptParams($site_id)]) }}" method="post"
        class="repeater">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-9 position-relative">
                {{ view('app.sites.receipts.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                    'customFields' => $customFields,
                    'banks' => $banks,
                    'chequebanks' => $banks,
                ]) }}
            </div>
            @php
                $amount_paid = 0;
                $amount_received = 0;
            @endphp
            @isset($draft_receipts[0])
                @php
                    $amount_received = $draft_receipts[0]['amount_received'];
                @endphp

                @foreach ($draft_receipts as $draft_receipt)
                    @php

                        $amount_paid = $amount_paid + $draft_receipt->amount_in_numbers;
                        if (isset($draft_receipt->discounted_amount) && (float) $draft_receipt->discounted_amount > 0) {
                            $remaining_amount = $draft_receipt->amount_received - $draft_receipt->amount_in_numbers + (float) $draft_receipt->discounted_amount;
                        } else {
                            $remaining_amount = $draft_receipt->amount_received - $draft_receipt->amount_in_numbers;
                        }

                    @endphp
                @endforeach
            @endisset
            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">
                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="doc_number">
                                Document Number
                                <span class="text-danger">*</span>
                            </label>
                            <input name="doc_number" type="text"
                                class="form-control  @error('doc_number') is-invalid @enderror" id="doc_number"
                                placeholder="Document Number" />
                            @error('doc_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Amount Received <span class="text-danger">*</span>
                            </label>
                            <input id="amount_received" type="text"
                                class="form-control amountFormat @error('amount_in_numbers') is-invalid @enderror"
                                @if ($amount_received == 0) name="amount_received" @endif
                                placeholder="Amount Received" @if ($amount_received > 0) readonly @endif
                                value="{{ isset($amount_received) ? number_format($amount_received, 2) : null }}" />
                            @error('amount_in_numbers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Discounted Amount
                            </label>
                            <input type="text"
                                class="form-control amountFormat @error('discounted_amount') is-invalid @enderror"
                                name="discounted_amount" id="discounted_amount" placeholder="Discounted Amount "
                                value="{{ isset($discounted_amount) ? $discounted_amount : null }}" />
                            @error('discounted_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Total Payable Amount
                            </label>
                            <input readonly type="text"
                                class="form-control amountFormat @error('total_payable_amount') is-invalid @enderror"
                                id="total_payable_amount" placeholder="Total Payable Amount " />
                            @error('total_payable_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="created_date">Creation Date</label>
                            <input id="created_date" type="date" required placeholder="YYYY-MM-DD" name="created_date"
                                class="form-control form-control-lg" />
                        </div>

                        @if ($amount_received > 0)
                            <div class="d-block mb-1">
                                <label class="form-label" style="font-size: 15px" for="floor">
                                    <h6 style="font-size: 15px"> Amount Remaining</h6>
                                </label>
                                <input type="text" class="form-control  @error('amount_in_numbers') is-invalid @enderror"
                                    @if ($amount_received > 0) name="amount_received" @endif
                                    placeholder="Amount Received" readonly
                                    value="{{ number_format($remaining_amount, 2) }}" />
                                @error('amount_in_numbers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="type_name">Attachment</label>
                            <input id="attachment" type="file" class="filepond @error('attachment') is-invalid @enderror"
                                name="attachment[]" multiple accept="image/png, image/jpeg, image/gif, application/pdf" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        @if ($amount_received > 0)
                            <div class="alert alert-warning alert-dismissible m-0 fade show" role="alert">
                                <h4 class="alert-heading"><i data-feather='alert-triangle' class="me-50"></i>Warning!</h4>
                                <div class="alert-body">
                                    <strong>On Cancel Receipts created against Amount Received will be Effected.
                                </div>
                            </div>
                            <hr>
                        @endif

                        {{-- <div class="d-block mb-1">
                            <button
                                class="btn text-nowrap w-100 btn-relief-outline-primary waves-effect waves-float waves-light me-1 mb-1"
                                type="button" data-repeater-create>
                                <i data-feather="plus" class="me-25"></i>
                                <span class="text-nowrap">Receipt Form</span>
                            </button>
                        </div>
                        <hr> --}}
                        @can('sites.receipts.store')
                            <a id="saveButton" href="#"
                                class="btn disabled text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Save Receipts
                            </a>
                        @endcan


                        @if ($amount_received > 0)
                            <a onclick="destroyDraft()"
                                class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                <i data-feather='x'></i>
                                {{ __('lang.commons.cancel') }}
                            </a>
                        @else
                            <a href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}"
                                class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                <i data-feather='x'></i>
                                {{ __('lang.commons.cancel') }}
                            </a>
                        @endif

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
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>
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
            // maxFiles: 2,
            checkValidity: true,
            allowPdfPreview: true,
            credits: {
                label: '',
                url: ''
            }
        });
        FilePond.setOptions({
            allowPdfPreview: true,
            pdfPreviewHeight: 320,
            pdfComponentExtraParams: 'toolbar=0&view=fit&page=1'
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $(".online-mode-of-payment").trigger('change');
            $("#transaction_date").flatpickr({
                defaultDate: 'today',
                maxDate: 'today',
                // altInput: !0,
                dateFormat: "Y-m-d",
            });

            $('.repeater').repeater({
                show: function() {
                    $(this).slideDown();
                },

                // hide: function(deleteElement) {

                //     Swal.fire({
                //         icon: 'warning',
                //         title: 'Warning',
                //         text: 'Are you sure you want to delete this receipt form!!!',
                //         showCancelButton: true,
                //         cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                //         confirmButtonText: '{{ __('lang.commons.yes_delete') }}',
                //         confirmButtonClass: 'btn-danger',
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             $(this).slideUp(deleteElement);
                //         }
                //     });
                // },
                isFirstItemUndeletable: true
            })
            var e = $("#unit_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            });

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

        function setIds(a) {
            var unit_id = a.name;
            $('.unit_id').attr('id', unit_id);
        }

        function setAmountIds(a) {
            let elements = document.getElementsByName(a.name);
        }

        var created_date = $("#created_date").flatpickr({
            defaultDate: "today",
            maxDate: 'today',
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#discounted_amount').on('focusout', function() {
            var discounted_amount = $(this).val();
            $('.amountToBePaid').trigger('focusout');
        });

        $('.amountToBePaid').on('focusout', function() {
            var amount = $(this).val().replace(/,/g, "")
            var formatAmount = amount;
            if ($.isNumeric(amount) && amount > 0) {

                var unit_id = $(this).attr('unit_id');
                var discounted_amount = $('#discounted_amount').val().replace(/,/g, "");
                if (discounted_amount > 0) {
                    amount = parseFloat(amount) + parseFloat(discounted_amount);
                }
                if (amount <= 0) {
                    toastr.error('Invalid Amount.',
                        "Error!", {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 2e3,
                            closeButton: !0,
                            tapToDismiss: !1,
                        });
                }
                if (unit_id == null || unit_id == 'undefined') {
                    toastr.error('Please Select Unit Number first.',
                        "Error!", {
                            showMethod: "slideDown",
                            hideMethod: "slideUp",
                            timeOut: 2e3,
                            closeButton: !0,
                            tapToDismiss: !1,
                        });
                }
                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('sites.receipts.ajax-get-unpaid-installments', ['site_id' => encryptParams($site_id)]) }}";
                if (amount > 0 && unit_id > 0) {
                    showBlockUI('#loader');
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'unit_id': unit_id,
                            'amount': amount,
                            '_token': _token
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#saveButton').removeClass('disabled');
                                $('#paidInstllmentTableDiv').show().parent().addClass('mb-2');
                                $('#instllmentTableDiv').show().parent().addClass('mb-2');
                                $('#modeOfPaymentDiv').show().parent().addClass('mb-2');
                                $('#customerData').show().parent().addClass('mb-2');
                                $('#paid_dynamic_total_installment_rows').empty();
                                $('#dynamic_total_installment_rows').empty();
                                $('#installments').empty();

                                $('#stackholder_full_name').val(response.stakeholders['full_name']);
                                $('#stackholder_father_name').val(response.stakeholders['father_name']);
                                $('#stackholder_occupation').val(response.stakeholders['occupation']);
                                $('#stackholder_designation').val(response.stakeholders['designation']);
                                $('#stackholder_ntn').val(response.stakeholders['ntn']);
                                $('#stackholder_cnic').val(response.stakeholders['cnic']);
                                $('#stackholder_contact').val(response.stakeholders['mobile_contact']);
                                $('#stackholder_address').val(response.stakeholders[
                                    'residential_address']);
                                $('#stackholder_mailing_address').val(response.stakeholders[
                                    'mailing_address']);
                                $('#stackholder_country').val(response.country);
                                $('#stackholder_state').val(response.state);
                                $('#stackholder_city').val(response.city);
                                if (response.stakeholders['stakeholder_as'] == 'c') {

                                    $('#stackholder_contact').val(response.stakeholders[
                                        'office_contact']);

                                }
                                // created_date.set('minDate', new Date(response.sales_plan[
                                //     'created_date']));

                                var total_installments = 1;
                                var order = null;

                                for (var i = 0; i <= response.already_paid.length; i++) {
                                    if (response.already_paid[i] != null) {
                                        var d = response.already_paid[i]['details']

                                        $('#paid_dynamic_total_installment_rows').append(
                                            '<tr class="text-nowrap">',
                                            '<td class="text-nowrap text-center">' + (i + 1) +
                                            '</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .already_paid[i]['details'] + '</td>',
                                            // '<td class="text-nowrap text-center">'+response.total_calculated_installments[i]['date']+'</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .already_paid[i]['amount'].toLocaleString('en') +
                                            '</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .already_paid[i]['paid_amount'].toLocaleString('en') +
                                            '</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .already_paid[i]['remaining_amount'].toLocaleString(
                                                'en') +
                                            '</td>',
                                            '</tr>',
                                            '<td class="text-nowrap text-center">' + response
                                            .already_paid[i]['status'] + '</td>',
                                            '</tr>', );
                                    }
                                }

                                for (i = 0; i <= response.total_calculated_installments.length; i++) {
                                    if (response.total_calculated_installments[i] != null) {
                                        if (response.total_calculated_installments[i][
                                                'installment_order'
                                            ] == 0) {
                                            order = 'Down Payment';
                                        } else {
                                            order = response.total_calculated_installments[i][
                                                'installment_order'
                                            ];
                                        }
                                        $('#dynamic_total_installment_rows').append(
                                            '<tr class="text-nowrap">',
                                            '<td class="text-nowrap text-center">' + (i + 1) +
                                            '</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .total_calculated_installments[i]['detail'] + '</td>',
                                            // '<td class="text-nowrap text-center">'+response.total_calculated_installments[i]['date']+'</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .total_calculated_installments[i]['amount']
                                            .toLocaleString() + '</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .total_calculated_installments[i]['paid_amount']
                                            .toLocaleString() + '</td>',
                                            '<td class="text-nowrap text-center">' + response
                                            .total_calculated_installments[i]['remaining_amount']
                                            .toLocaleString() +
                                            '</td>',
                                            '</tr>',
                                            '<td class="text-nowrap text-center">' + response
                                            .total_calculated_installments[i]['partially_paid']
                                            .toLocaleString() +
                                            '</td>',
                                            '</tr>', );
                                    }
                                }
                                hideBlockUI('#loader');

                            } else {
                                $('#saveButton').addClass('disabled');
                                hideBlockUI('#loader');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            hideBlockUI('#loader');
                        }
                    });
                }
                var formated = parseFloat(formatAmount).toLocaleString('en');
                $(this).val(formated)
            } else {
                $(this).val('')
            }
        });

        function getUnitTypeAndFloor(unit_id, id) {
            var unit_type = id.replace("unit_id", "unit_type");
            var unit_name = id.replace("unit_id", "unit_name");
            var floor = id.replace("unit_id", "floor");
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.receipts.ajax-get-unit-type-and-unit-floor', ['site_id' => encryptParams($site_id)]) }}";
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
                        showBlockUI('#loader');
                        $('.amountToBePaid').attr('unit_id', response.unit_id);
                        $('#unit_type').empty();
                        $('.amountToBePaid').empty();
                        $('.floor').empty()
                        $('.unit_name').empty();
                        $('.unit_type').append('<option value="0" selected>' + response.unit_type +
                            '</option>');
                        $('.floor').append('<option value="0" selected>' + response.unit_floor + '</option>');
                        $('.unit_name').append('<option value="0" selected>' + response.unit_name +
                            '</option>');

                        $('#customer_ap_amount').val(response.customerPayableAmount.toLocaleString());
                        $('#dealer_ap_amount').val(response.dealerPayableAmount.toLocaleString());
                        $('#vendor_ap_amount').val(response.vendorPayableAmount.toLocaleString());

                        $('#total_payable_amount').val(response.total_payable_amount.toLocaleString());

                        if (response.customerPayableAmount <= 0) {
                            $('#customer_ap_amount_paid').attr('readonly', true);
                        }

                        if (response.dealerPayableAmount <= 0) {
                            $('#dealer_ap_amount_paid').attr('readonly', true);
                        }

                        if (response.vendorPayableAmount <= 0) {
                            $('#vendor_ap_amount_paid').attr('readonly', true);
                        }

                        $("#created_date").flatpickr({
                            defaultDate: "today",
                            maxDate: 'today',
                            minDate: response.salesPlan.approved_date,
                            altInput: !0,
                            altFormat: "F j, Y",
                            dateFormat: "Y-m-d",
                        });


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
                    console.log(error);
                }
            });
        }

        $("#saveButton").click(function() {
            let mode_of_payment = $("input[name='receipts[0][mode_of_payment]']:checked").val();

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
                let amount_toBe_paid = parseFloat($('#amountToBePaid').val().replace(/,/g, ''));

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
                        '<span class="is-invalid text-danger errorClass">Entered Amount should not be greater than existing Customer payable amount!</span>'
                    );
                }

                if (parseFloat(vendor_ap_amount_paid) > parseFloat($('#vendor_ap_amount').val().replace(/,/g,
                        ''))) {
                    $('#vendor_ap_amount_paid').addClass('is-invalid');
                    invalid_amount_stauts = 1;
                    $('#vendor_ap_amount_paid').after(
                        '<span class="is-invalid text-danger errorClass">Entered Amount should not be greater than existing Vendor payable amount!</span>'
                    );
                }

                if (parseFloat(dealer_ap_amount_paid) > parseFloat($('#dealer_ap_amount').val().replace(/,/g,
                        ''))) {
                    $('#dealer_ap_amount_paid').addClass('is-invalid');
                    invalid_amount_stauts = 1;
                    $('#dealer_ap_amount_paid').after(
                        '<span class="is-invalid text-danger errorClass">Entered Amount should not be greater than existing Dealer payable amount!</span>'
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

        function destroyDraft() {

            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Are you sure you want to destroy the draft receipts ?',
                showCancelButton: true,
                cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                confirmButtonText: 'Yes, Change it!',
                confirmButtonClass: 'btn-danger',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-relief-outline-danger waves-effect waves-float waves-light me-1',
                    cancelButton: 'btn btn-relief-outline-success waves-effect waves-float waves-light me-1'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    let url =
                        "{{ route('sites.receipts.destroy-draft', ['site_id' => encryptParams($site_id)]) }}";
                    location.href = url;
                }
            });

        }

        var e = $(".bank");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on("change", function(e) {
            let bank = parseInt($(this).val());
            showBlockUI('#loader');
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
                        hideBlockUI('#loader');
                    } else {

                        $('#name').val('').removeAttr('readOnly');
                        $('#account_number').val('').removeAttr('readOnly');
                        $('#contact_number').val('').removeAttr('readOnly');
                        $('#branch').val('').removeAttr('readOnly');
                        $('#branch_code').val('').removeAttr('readOnly');
                        $('#comments').val('').removeAttr('readOnly');
                        $('#address').val('').removeAttr('readOnly');
                    }
                    hideBlockUI('#loader');
                },
                error: function(errors) {
                    console.error(errors);
                    hideBlockUI('#loader');
                }
            });
        });

        var validator = $("#receiptForm").validate({
            rules: {
                'receipts[0][bank_name]': {
                    required: function() {
                        let mode_of_payment = $("input[name='receipts[0][mode_of_payment]']:checked").val();
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
    </script>
@endsection
