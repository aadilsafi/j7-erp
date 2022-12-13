@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-transfer-receipts.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Transfer File Receipt')

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
                <h2 class="content-header-title float-start mb-0">Create File Transfer Receipts</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-transfer-receipts.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="receiptForm" enctype="multipart/form-data"
        action="{{ route('sites.file-transfer-receipts.store', ['site_id' => encryptParams($site_id)]) }}" method="post"
        class="repeater">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-9 position-relative">
                {{ view('app.sites.file-transfer-receipts.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                    'banks' => $banks,
                    'chequebanks' => $banks,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">

                        {{-- <div class="d-block mb-1">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Discounted Amount
                            </label>
                            <input min="0" type="text"
                                class="form-control amountFormat @error('discounted_amount') is-invalid @enderror"
                                name="discounted_amount" id="discounted_amount" placeholder="Discounted Amount "
                                value="{{ isset($discounted_amount) ? $discounted_amount : null }}" />
                            @error('discounted_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> --}}



                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="created_date">Creation Date</label>
                            <input id="created_date" type="date" required placeholder="YYYY-MM-DD" name="created_date"
                                class="form-control form-control-lg" />
                        </div>

                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="type_name">Attachment</label>
                            <input id="attachment" type="file" class="filepond @error('attachment') is-invalid @enderror"
                                name="attachment" accept="image/png, image/jpeg, image/gif" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @can('sites.receipts.store')
                            <a id="saveButton" href="#"
                                class="btn disabled text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Save Receipts
                            </a>
                        @endcan

                        <a href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}"
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
@endsection

@section('page-js')
@endsection

@section('custom-js')

    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        FilePond.create(document.getElementById('attachment'), {
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            maxFiles: 1,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $(".online-mode-of-payment").trigger('change');
            $("#transaction_date").flatpickr({
                defaultDate: 'today',
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

        var created_date = $("#created_date").flatpickr({
            defaultDate: "today",
            minDate: '',
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        var unit_id = $('#unit_id');
        unit_id.wrap('<div class="position-relative"></div>');
        unit_id.select2({
            dropdownAutoWidth: !0,
            dropdownParent: unit_id.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on("change", function(e) {

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.file-transfer-receipts.ajax-get-transfer-file-data', ['site_id' => encryptParams($site_id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'getTransferFileData': parseInt($(this).val()),
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        showBlockUI('#receiptForm');
                        $('#unit_type').empty();
                        $('.floor').empty()
                        $('.unit_name').empty();
                        $('.unit_type').append('<option value="0" selected>' + response.unit_type +
                            '</option>');
                        $('.floor').append('<option value="0" selected>' + response.unit_floor +
                            '</option>');
                        $('.unit_name').append('<option value="0" selected>' + response.unit_name +
                            '</option>');

                        $('#customer_ap_amount').val(response.customerPayableAmount.toLocaleString());
                        $('#dealer_ap_amount').val(response.dealerPayableAmount.toLocaleString());
                        $('#vendor_ap_amount').val(response.vendorPayableAmount.toLocaleString());

                        $('#total_payable_amount').val(response.total_payable_amount.toLocaleString());

                        stakeholderData = response.transferOwner;
                        $('#transferOwner_full_name').val(stakeholderData.full_name);
                        $('#transferOwner_father_name').val(stakeholderData.father_name);
                        $('#transferOwner_cnic').val(stakeholderData.cnic);
                        $('#transferOwner_contact').val(stakeholderData.contact);
                        $('#transferOwner_email').val(stakeholderData.email);
                        $('#transferOwner_address').text(stakeholderData.address);
                        $('#transferOwner_occupation').val(stakeholderData.occupation);
                        $('#transferOwner_designation').val(stakeholderData.designation);
                        $('#transferOwner_ntn').val(stakeholderData.ntn);
                        $('#transferOwner_country').val(stakeholderData.country);
                        $('#transferOwner_state').val(stakeholderData.state);
                        $('#transferOwner_city').val(stakeholderData.city);

                        $('#transferOwner_optional_contact').val(stakeholderData
                            .optional_contact);
                        $('#transferOwner_optional_email').val(stakeholderData
                            .optional_email);
                        $('#transferOwner_comments').text(stakeholderData.comments);
                        $('#transferOwner_mailing_address').val(stakeholderData.mailing_address);
                        $('#transferOwner_nationality').val(stakeholderData.nationality);
                        if (stakeholderData.stakeholder_as == 'c') {
                            $('#transferOwner_company_name').val(stakeholderData.full_name);
                            $('#transferOwner_industry').val(stakeholderData.occupation);
                            $('#transferOwner_registration').val(stakeholderData.cnic);
                            $('#transferOwner_ntn').val(stakeholderData.ntn);
                            $('#companyForm').show();
                            $('#individualForm').hide();
                        }
                        if (stakeholderData.stakeholder_as == 'i') {
                            $('#companyForm').hide();
                            $('#individualForm').show();
                        }
                        $('#transferOwner').show();


                        fileOwnerData = response.fileOwner;
                        $('#fileOwner_full_name').val(fileOwnerData.full_name);
                        $('#fileOwner_father_name').val(fileOwnerData.father_name);
                        $('#fileOwner_cnic').val(fileOwnerData.cnic);
                        $('#fileOwner_contact').val(fileOwnerData.contact);
                        $('#fileOwner_email').val(fileOwnerData.email);
                        $('#fileOwner_address').text(fileOwnerData.address);
                        $('#fileOwner_occupation').val(fileOwnerData.occupation);
                        $('#fileOwner_designation').val(fileOwnerData.designation);
                        $('#fileOwner_ntn').val(fileOwnerData.ntn);
                        $('#fileOwner_country').val(fileOwnerData.country);
                        $('#fileOwner_state').val(fileOwnerData.state);
                        $('#fileOwner_city').val(fileOwnerData.city);

                        $('#fileOwner_optional_contact').val(fileOwnerData
                            .optional_contact);
                        $('#fileOwner_optional_email').val(fileOwnerData
                            .optional_email);
                        $('#fileOwner_comments').text(fileOwnerData.comments);
                        $('#fileOwner_mailing_address').val(fileOwnerData.mailing_address);
                        $('#fileOwner_nationality').val(fileOwnerData.nationality);
                        if (fileOwnerData.stakeholder_as == 'c') {
                            $('#fileOwner_company_name').val(fileOwnerData.full_name);
                            $('#fileOwner_industry').val(fileOwnerData.occupation);
                            $('#fileOwner_registration').val(fileOwnerData.cnic);
                            $('#fileOwner_ntn').val(fileOwnerData.ntn);
                            $('#OwnerCompanyForm').show();
                            $('#OwnerIndividualForm').hide();
                        }
                        if (fileOwnerData.stakeholder_as == 'i') {
                            $('#OwnerCompanyForm').hide();
                            $('#OwnerIndividualForm').show();
                        }

                        $('#fileOwner').show()

                        hideBlockUI('#receiptForm');
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


        $("#saveButton").click(function() {
            $("#receiptForm").submit();
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
    </script>
@endsection
