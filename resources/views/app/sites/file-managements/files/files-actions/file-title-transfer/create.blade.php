@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.file-resale.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create File Title Transfer')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css" />

@endsection

@section('custom-css')
    <style>
        .filepond--drop-label {
            color: #7367F0 !important;
        }

        .filepond--item-panel {
            background-color: #7367F0;
        }

        .filepond--panel-root {
            background-color: #e3e0fd;
        }

        #stakeholderNextOfKin {
            display: none;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create File Title Transfer</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.file-title-transfer.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="fileRefundForm" enctype="multipart/form-data"
        action="{{ route('sites.file-managements.file-title-transfer.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class="">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.files-actions.file-title-transfer.form-fields', [
                    'site_id' => $site_id,
                    'unit' => $unit,
                    'customer' => $customer,
                    'file' => $file,
                    'stakeholders' => $stakeholders,
                    'stakeholderTypes' => $stakeholderTypes,
                    'emptyRecord' => $emptyRecord,
                    'rebate_incentive' => $rebate_incentive,
                    'total_paid_amount' => $total_paid_amount,
                    'rebate_total' => $rebate_total,
                    'salesPlan' => $salesPlan,
                    'customFields' => $customFields,
                    'country' => $country,
                    'leadSources' => $leadSources,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">
                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                        <div class="d-block mb-1">
                            <div class="form-check form-check-primary">
                                <input type="checkbox" checked name="checkAttachment" class="form-check-input"
                                    value="1" id="colorCheck3">
                                <label class="form-check-label" for="colorCheck3">
                                    Attachment Attached
                                </label>
                            </div>
                        </div>

                        @can('sites.file-managements.file-title-transfer.store')
                            <a id="saveButton" href="#"
                                class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                                <i data-feather='save'></i>
                                Save File Title Transfer
                            </a>
                        @endcan

                        <a href="{{ route('sites.file-managements.file-title-transfer.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
@endsection

@section('custom-js')

    <script>
        var cp_state = 0;
        var cp_city = 0;
        var ra_state = 0;
        var ra_city = 0;
    </script>
    {{ view('app.sites.stakeholders.partials.stakeholder_form_scripts') }}

    <script>
        // FilePond.registerPlugin(
        //     FilePondPluginImagePreview,
        //     FilePondPluginFileValidateType,
        //     FilePondPluginFileValidateSize,
        //     FilePondPluginImageValidateSize,
        //     FilePondPluginImageCrop,
        // );

        // FilePond.create(document.getElementById('attachment'), {
        //     styleButtonRemoveItemPosition: 'right',
        //     imageCropAspectRatio: '1:1',
        //     acceptedFileTypes: ['image/png', 'image/jpeg'],
        //     maxFileSize: '1536KB',
        //     ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
        //     storeAsFile: true,
        //     allowMultiple: true,
        //     maxFiles: 1,
        //     checkValidity: true,
        //     credits: {
        //         label: '',
        //         url: ''
        //     }
        // });
    </script>

    <script type="text/javascript">
        $('#companyForm').hide();
        var selected_state_id = 0;
        var selected_city_id = 0;
        $("#stakeholder_as").val('i');

        $("#stakeholder_as").trigger('change');

        $(document).ready(function() {

            $("#payment_due_date").flatpickr({
                defaultDate: "today",
                minDate: '{{ $salesPlan->approved_date }}',
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            var e = $("#stackholders");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).on("change", function(e) {

                showBlockUI('#stakeholders_card');

                let stakeholder_id = $(this).val();

                let stakeholderData = {
                    id: 0,
                    full_name: '',
                    father_name: '',
                    occupation: '',
                    designation: '',
                    cnic: '',
                    contact: '',
                    address: '',
                }
                let div_stakeholder_type = $('#div_stakeholder_type');
                div_stakeholder_type.hide();
                $.ajax({
                    url: "{{ route('sites.stakeholders.ajax-get-by-id', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}"
                        .replace(':id', stakeholder_id),
                    type: 'GET',
                    data: {},
                    success: function(response) {
                        if (response.status) {
                            if (response.data) {
                                stakeholderData = response.data[0];
                            }
                            isDisable = parseFloat(stakeholder_id) > 0 ? true : false;

                            $('#stakeholder_as').val(stakeholderData.stakeholder_as).trigger(
                                'change');
                            $("#stakeholder_as").select2({
                                disabled: isDisable
                            });
                            if (stakeholderData.stakeholder_as == 'i') {
                                $('#full_name').val(stakeholderData.full_name).attr('readonly',
                                    isDisable && stakeholderData.full_name != null ? true :
                                    false);
                                $('#father_name').val(stakeholderData.father_name).attr(
                                    'readonly',
                                    isDisable && stakeholderData.father_name != null ?
                                    true :
                                    false);
                                $('#occupation').val(stakeholderData.occupation).attr(
                                    'readonly',
                                    isDisable && stakeholderData.occupation != null ? true :
                                    false);
                                $('#designation').val(stakeholderData.designation).attr(
                                    'readonly',
                                    isDisable && stakeholderData.designation != null ?
                                    true :
                                    false);
                                $('#cnic').val(stakeholderData.cnic).attr('readonly',
                                    isDisable && stakeholderData.cnic != null ? true :
                                    false);
                                $('#ntn').val(stakeholderData.ntn).attr('readonly',
                                    isDisable && stakeholderData.ntn != null ? true :
                                    false);
                                $('#passport_no').val(stakeholderData.passport_no).attr(
                                    'readonly',
                                    isDisable && stakeholderData.passport_no != null ?
                                    true :
                                    false);
                                $('#individual_email').val(stakeholderData.email).attr(
                                    'readonly',
                                    isDisable && stakeholderData.email != null ? true :
                                    false);
                                $('#office_email').val(stakeholderData.office_email).attr(
                                    'readonly',
                                    isDisable && stakeholderData.office_email != null ?
                                    true :
                                    false);
                                $('#mobile_contact').val(stakeholderData.mobile_contact).attr(
                                    'readonly',
                                    isDisable && stakeholderData.mobile_contact != null ?
                                    true :
                                    false);

                                $('#office_contact').val(stakeholderData.office_contact).attr(
                                    'readonly',
                                    isDisable && stakeholderData.office_contact != null ?
                                    true :
                                    false);
                                if (stakeholderData.office_contact != null) {
                                    intlOfficeContact.setNumber(stakeholderData.office_contact)
                                }

                                $("#dob").flatpickr({
                                    defaultDate: stakeholderData.date_of_birth,
                                })
                                $("#dob").attr(
                                    'readonly',
                                    isDisable && stakeholderData.date_of_birth != null ?
                                    true :
                                    false);
                                $('#referred_by').val(stakeholderData.referred_by).attr(
                                    'readonly',
                                    isDisable && stakeholderData.referred_by != null ?
                                    true :
                                    false);
                                $('#source').val(stakeholderData.source).trigger('change');
                                $('#is_local').val(stakeholderData.is_local).trigger('change');
                                $('#nationality').val(stakeholderData.nationality).trigger(
                                    'change');
                            }
                            if (stakeholderData.stakeholder_as == 'c') {
                                $('#company_name').val(stakeholderData.full_name).attr(
                                    'readonly',
                                    isDisable && stakeholderData.full_name != null ?
                                    true :
                                    false);
                                $('#registration').val(stakeholderData.cnic).attr(
                                    'readonly',
                                    isDisable && stakeholderData.cnic != null ?
                                    true :
                                    false);
                                $('#industry').val(stakeholderData.industry).attr(
                                    'readonly',
                                    isDisable && stakeholderData.industry != null ?
                                    true :
                                    false);
                                $('#company_office_contact').val(stakeholderData
                                    .office_contact).attr(
                                    'readonly',
                                    isDisable && stakeholderData.office_contact != null ?
                                    true :
                                    false);
                                if (stakeholderData.office_contact != null) {

                                    intlCompanyMobileContact.setNumber(stakeholderData
                                        .office_contact)
                                }
                                $('#strn').val(stakeholderData.strn).attr(
                                    'readonly',
                                    isDisable && stakeholderData.strn != null ?
                                    true :
                                    false);
                                $('#company_ntn').val(stakeholderData.ntn).attr(
                                    'readonly',
                                    isDisable && stakeholderData.ntn != null ?
                                    true :
                                    false);
                                $('#company_optional_contact').val(stakeholderData
                                    .mobile_contact).attr(
                                    'readonly',
                                    isDisable && stakeholderData.mobile_contact != null ?
                                    true :
                                    false);
                                if (stakeholderData.mobile_contact != null) {

                                    intlcompanyOptionalContact.setNumber(stakeholderData
                                        .mobile_contact)
                                }
                                $('#company_email').val(stakeholderData.email).attr(
                                    'readonly',
                                    isDisable && stakeholderData.referred_by != null ?
                                    true :
                                    false);
                                $('#company_office_email').val(stakeholderData.email)
                                    .attr(
                                        'readonly',
                                        isDisable && stakeholderData.referred_by != null ?
                                        true :
                                        false);
                                $('#website').val(stakeholderData.website).attr(
                                    'readonly',
                                    isDisable && stakeholderData.website != null ?
                                    true :
                                    false);
                                $('#parent_company').val(stakeholderData.parent_company).attr(
                                    'readonly',
                                    isDisable && stakeholderData.parent_company != null ?
                                    true :
                                    false);
                                $('#origin').val(stakeholderData.origin).trigger(
                                    'change');
                            }

                            // residential address
                            $('#residential_address_type').val(stakeholderData
                                .residential_address_type).attr(
                                'readonly',
                                isDisable && stakeholderData.residential_address_type !=
                                null ?
                                true :
                                false);
                            $('#residential_address').val(stakeholderData.residential_address)
                                .attr(
                                    'readonly',
                                    isDisable && stakeholderData.residential_address != null ?
                                    true :
                                    false);
                            ra_state = stakeholderData.residential_state_id;
                            ra_city = stakeholderData.residential_city_id;

                            $('#residential_country').val(stakeholderData
                                .residential_country_id).trigger(
                                'change');
                            $('#residential_postal_code').val(stakeholderData
                                .residential_postal_code).attr(
                                'readonly',
                                isDisable && stakeholderData.residential_postal_code !=
                                null ?
                                true :
                                false);

                            // mailing address
                            $('#mailing_address_type').val(stakeholderData
                                .mailing_address_type).attr(
                                'readonly',
                                isDisable && stakeholderData.mailing_address_type != null ?
                                true :
                                false);
                            $('#mailing_address').val(stakeholderData.mailing_address).attr(
                                'readonly',
                                isDisable && stakeholderData.mailing_address != null ?
                                true :
                                false);
                            cp_state = stakeholderData.mailing_state_id;
                            cp_city = stakeholderData.mailing_city_id;

                            $('#mailing_country').val(stakeholderData
                                .mailing_country_id).trigger(
                                'change');
                            $('#mailing_postal_code').val(stakeholderData
                                .mailing_postal_code).attr(
                                'readonly',
                                isDisable && stakeholderData.mailing_postal_code != null ?
                                true :
                                false);

                            $('#stackholder_next_of_kin').empty();
                            if (response.data[1].length > 0) {
                                $('#stakeholderNextOfKin').show();
                                $.each(response.data[1], function(i, item) {

                                    $('#stackholder_next_of_kin').append($('<option>', {
                                        value: item.id,
                                        text: item.full_name + ' s/o ' +
                                            item.father_name + ' ,' + item
                                            .cnic,
                                    }));

                                });
                            } else {
                                $('#stakeholderNextOfKin').hide();
                            }

                            let stakeholderType = '';
                            (stakeholderData.stakeholder_types).forEach(types => {
                                if (types.status) {
                                    stakeholderType +=
                                        '<p class="badge badge-light-success fs-5 ms-auto me-1">' +
                                        types.stakeholder_code + '</p>';
                                } else {
                                    stakeholderType +=
                                        '<p class="badge badge-light-danger fs-5 ms-auto me-1">' +
                                        types.stakeholder_code + '</p>';
                                }
                            });

                            div_stakeholder_type.html(stakeholderType);
                            div_stakeholder_type.show();


                        }
                        hideBlockUI('#stakeholders_card');
                    },
                    error: function(errors) {
                        console.error(errors);
                        hideBlockUI('#stakeholders_card');
                    }
                });
            });

            var country_id = $("#country_id");
            country_id.wrap('<div class="position-relative"></div>');
            country_id.select2({
                dropdownAutoWidth: !0,
                dropdownParent: country_id.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {

                $("#city_id").empty()
                $('#state_id').empty();

                $('#state_id').html('<option value=0>Select State</option>');
                $('#city_id').html('<option value=0>Select City</option>');
                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                    .replace(':countryId', $(this).val());
                if ($(this).val() > 0) {
                    showBlockUI('#fileRefundForm');
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'stateId': $(this).val(),
                            '_token': _token
                        },
                        success: function(response) {
                            if (response.success) {

                                $.each(response.states, function(key, value) {
                                    $("#state_id").append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                });
                                state_id.val(selected_state_id);
                                state_id.trigger('change');
                                hideBlockUI('#fileRefundForm');
                            } else {
                                hideBlockUI('#fileRefundForm');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            hideBlockUI('#fileRefundForm');
                        }
                    });
                }
            });


            var state_id = $("#state_id");
            state_id.wrap('<div class="position-relative"></div>');
            state_id.select2({
                dropdownAutoWidth: !0,
                dropdownParent: state_id.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {
                $("#city_id").empty()
                $('#city_id').html('<option value=0>Select City</option>');

                // alert($(this).val());
                showBlockUI('#fileRefundForm');

                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                    .replace(':stateId', $(this).val());
                if ($(this).val() > 0) {
                    showBlockUI('#fileRefundForm');
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'stateId': $(this).val(),
                            '_token': _token
                        },
                        success: function(response) {
                            if (response.success) {
                                $.each(response.cities, function(key, value) {
                                    $("#city_id").append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                });

                                city_id.val(selected_city_id);
                                city_id.trigger('change');

                                hideBlockUI('#fileRefundForm');
                            } else {
                                hideBlockUI('#fileRefundForm');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            hideBlockUI('#fileRefundForm');
                        }
                    });
                }
            });

            var city_id = $("#city_id");
            city_id.wrap('<div class="position-relative"></div>');
            city_id.select2({
                dropdownAutoWidth: !0,
                dropdownParent: city_id.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            });
        });

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        $(".expenses-list").repeater({
            initEmpty: true,
            show: function(e) {
                $(this).slideDown();
                feather && feather.replace({
                    width: 14,
                    height: 14
                });
                initializeFilePond();
                // console.log(e);
            },
            hide: function(e) {
                $(this).slideUp(e)
            }
        });
        // const input = $('.attachment');
        // $('#add-new-attachment').on('click', function() {
        //     initializeFilePond();
        // });

        function initializeFilePond() {
            const inputElements = document.querySelectorAll('input.filepond');
            console.log(inputElements.length);
            Array.from(inputElements).forEach(inputElement => {

                // create a FilePond instance at the input element location
                FilePond.create(inputElement, {
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
            });
        }

        var checkbtn = $('#colorCheck3').is(':checked')
        formValidations();

        $('#colorCheck3').change(function() {
            checkbtn = $('#colorCheck3').is(':checked');
            formValidations();
        })

        function formValidations() {
            var validator = $("#fileRefundForm").validate({
                rules: {
                    'transfer_rate': {
                        required: true,
                        digits: true,
                    },
                    'payment_due_date': {
                        required: true
                    },
                    'amount_to_be_paid': {
                        required: true,

                    },
                    'amount_remarks': {
                        required: true
                    },
                    'attachments[0][attachment_label]': {
                        required: function() {
                            return checkbtn;
                        }
                    },
                    'stakeholder_as': {
                        required: function() {
                            return $("#stakeholder_as").val() == 0;
                        }
                    },
                    'stakeholder_type': {
                        required: function() {
                            return $("#stakeholder_type").val() == 0;
                        }
                    },
                    'residential[address_type]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                    },
                    'residential[country]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                        min: function() {
                            return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                        },
                    },
                    'residential[state]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                        min: function() {
                            return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                        },
                    },
                    'residential[city]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                        min: function() {
                            return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                        },
                    },
                    'residential[address]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                    },
                    'residential[postal_code]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                    },
                    'mailing[address_type]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                    },
                    'mailing[country]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                        min: function() {
                            return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                        },
                    },
                    'mailing[state]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                        min: function() {
                            return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                        },
                    },
                    'mailing[city]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                        min: function() {
                            return $('#stakeholder_type').val() != 'L' ? 1 : 0;
                        },
                    },
                    'mailing[address]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                    },
                    'mailing[postal_code]': {
                        required: function() {
                            return $('#stakeholder_type').val() != 'L';
                        },
                    },
                    'company[company_name]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'c';
                        },
                    },
                    'company[registration]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'company[industry]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'company[company_ntn]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'company[strn]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'c' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'company[company_office_contact]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'c';
                        },
                    },
                    'individual[full_name]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i';
                        },
                    },
                    'individual[father_name]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'individual[occupation]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'individual[cnic]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'individual[mobile_contact]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i';
                        },
                    },
                    'individual[dob]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i' && $('#stakeholder_type').val() !=
                                'L';
                        },
                    },
                    'individual[nationality]': {
                        required: function() {
                            return $("#stakeholder_as").val() == 'i' && !$('#is_local').is(
                                ':checked') && $(
                                '#stakeholder_type').val() != 'L';
                        },
                        min: 1,
                    },
                },
                messages: {
                    'individual[nationality]': {
                        min: "Please select Nationality"
                    },
                    'stakeholder_as': {
                        required: "Please select stakeholder as",
                    },
                    'residential[address_type]': {
                        required: "Please select address type",
                    },
                    'residential[country]': {
                        required: "Please select country",
                        min: "Please select country"
                    },
                    'residential[state]': {
                        required: "Please select state",
                        min: "Please select state"
                    },
                    'residential[city]': {
                        required: "Please select city",
                        min: "Please select city"
                    },
                    'residential[address]': {
                        required: "Please enter address",
                    },
                    'residential[postal_code]': {
                        required: "Please enter postal code",
                    },
                    'mailing[address_type]': {
                        required: "Please select address type",
                    },
                    'mailing[country]': {
                        required: "Please select country",
                        min: "Please select country"
                    },
                    'mailing[state]': {
                        required: "Please select state",
                        min: "Please select state"
                    },
                    'mailing[city]': {
                        required: "Please select city",
                        min: "Please select city"
                    },
                    'mailing[address]': {
                        required: "Please enter address",
                    },
                    'mailing[postal_code]': {
                        required: "Please enter postal code",
                    },
                    'mailing_address': {
                        required: "Please enter mailing address",
                    },
                    'address': {
                        required: "Please enter address",
                    },
                    'optional_contact': {
                        required: "Please enter optional contact",
                    },
                    'full_name': {
                        required: "Please enter full name",
                    },
                    'father_name': {
                        required: "Please enter father name",
                    },
                    'cnic': {
                        required: "Please enter cnic",
                    },
                    'registration': {
                        required: "Please enter registration",
                    },
                    'company_name': {
                        required: "Please enter company name",
                    },
                    'email': {
                        required: "Please enter email",
                    }
                },
                errorClass: 'is-invalid text-danger',
                errorElement: "span",
                wrapper: "div",
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }

        $.validator.addMethod("ContactNoError", function(value, element) {
            // alert(intl.isValidNumber());
            // return intl.getValidationError() == 0;
            return intl.isValidNumber();

        }, "In Valid number");

        $.validator.addMethod("OPTContactNoError", function(value, element) {
            // alert(intl.isValidNumber());
            // return intl.getValidationError() == 0;
            // if(value != '' )
            if (value.length > 0) {
                return intlOptional.isValidNumber();
            } else {
                return true;
            }
        }, "In Valid number");

        function calculateTransferAmount() {
            let paid_amount = '{{ $total_paid_amount }}';
            paid_amount =  paid_amount.replace(/,/g, "");
            let transfer_rate = $('#transfer_rate').val().replace(/,/g, "");
            let unit_gross_area = '{{ $unit->gross_area }}';

            let amount_paid = 0.0;
            let profitCharges = $('#profit_charges').val().replace(/,/g, "");
            amount_paid = parseFloat(transfer_rate) * parseFloat(unit_gross_area);
            $('#amount_to_be_paid').val(amount_paid.toLocaleString());
        }

        $("#saveButton").click(function() {
            $("#fileRefundForm").removeClass('is-invalid text-danger')
            $("#fileRefundForm").submit();
        });

        $('#cpyAddress').on('change', function() {
            if ($(this).is(':checked')) {
                $('#mailing_address').val($('#stackholder_address').val());
            } else {
                $('#mailing_address').val('')
            }
        })
    </script>
@endsection
