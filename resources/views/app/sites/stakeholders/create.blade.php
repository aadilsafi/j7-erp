@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.stakeholders.create', $site_id) }}
@endsection

@section('page-title', 'Create Stakeholder')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css" />
@endsection

@section('custom-css')
    <style>
        label {
            font-weight: 450;
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

        #div-next-of-kin {
            display: none;
        }

        #companyForm {
            display: none;
        }

        #individualForm {
            display: none;
        }

        #common_form {
            display: none;
        }

        #stakeholderType {
            display: none;
        }

        .iti {
            width: 100%;
        }

        .intl-tel-input {
            display: table-cell;
        }

        .intl-tel-input .selected-flag {
            z-index: 4;
        }

        .intl-tel-input .country-list {
            z-index: 5;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Stakeholder</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.stakeholders.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="stakeholderForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.stakeholders.store', ['site_id' => encryptParams($site_id)]) }}" method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                @csrf
                {{ view('app.sites.stakeholders.form-fields', [
                    'stakeholders' => $stakeholders,
                    'stakeholderTypes' => $stakeholderTypes,
                    'emptyRecord' => $emptyRecord,
                    'customFields' => $customFields,
                    'country' => $country,
                    'city' => $city,
                    'state' => $state,
                    'leadSources' => $leadSources,
                    'emtyNextOfKin' => $emtyNextOfKin,
                    'contactStakeholders' => $contactStakeholders,
                    'emtykinStakeholders' => $emtykinStakeholders,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: 10 !important;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="d-block mb-1">
                                <label class="form-label fs-5" for="type_name">CNIC Attachment</label>
                                <input id="attachment" type="file"
                                    class="filepond @error('attachment') is-invalid @enderror" name="attachment[]" multiple
                                    accept="image/png, image/jpeg, image/gif" />
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            @can('sites.stakeholders.store')
                                <button type="submit" value="save"
                                    class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                    <i data-feather='save'></i>
                                    Save Stakeholder
                                </button>
                            @endcan

                            <a href="{{ route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)]) }}"
                                class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                <i data-feather='x'></i>
                                {{ __('lang.commons.cancel') }}
                            </a>
                        </div>
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
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
@endsection

@section('custom-js')
    <script>
        showBlockUI('#stakeholderForm');

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
            maxFiles: 2,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });

        var dob = $("#dob").flatpickr({
            defaultDate: "today",
            minDate: '',
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#nationality').val(167).change();

            @php
                $data = old();
            @endphp

            @if (!is_null(old('stakeholder_type')))
                $('#stakeholderType').trigger('change');
            @endif

            @if (!is_null(old('residential_country')))
                $('#residential_country').val({{ old('residential_country') }});
                $('#residential_country').trigger('change')
            @endif

            @if (!is_null(old('mailing_country')))
                $('#mailing_country').val({{ old('mailing_country') }});
                $('#mailing_country').trigger('change')
            @endif
        });

        var cp_state = 0;
        var cp_city = 0;

        var t = $("#stakeholder_as");
        t.wrap('<div class="position-relative"></div>');
        t.select2({
            dropdownAutoWidth: !0,
            dropdownParent: t.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            showBlockUI('#stakeholderForm');

            if ($(this).val() == 0) {
                $('#stakeholderType').hide();
                $('#companyForm').hide();
                $('#individualForm').hide();
                $('#common_form').hide();
            } else if ($(this).val() == 'c') {
                $('#stakeholderType').show();
                $('#companyForm').show();
                $('#individualForm').hide();
                $('#common_form').show();
                $('#change_residential_txt').html('<u>Billing Address</u>')
                $('#change_mailing_txt').html('<u>Shipping Address</u>')
            } else if ($(this).val() == 'i') {
                $('#stakeholderType').show();
                $('#companyForm').hide();
                $('#individualForm').show();
                $('#common_form').show();
                $('#change_residential_txt').html('<u>Residential Address</u>')
                $('#change_mailing_txt').html('<u>Mailing Address</u>')
            }
            hideBlockUI('#stakeholderForm');
        });

        // Individual Contact no fields
        var mobileContact = document.querySelector("#mobile_contact");
        intlMobileContact = window.intlTelInput(mobileContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));

        $('#mobileContactCountryDetails').val(JSON.stringify(intlMobileContact.getSelectedCountryData()));

        mobileContact.addEventListener("countrychange", function() {
            $('#mobileContactCountryDetails').val(JSON.stringify(intlMobileContact
                .getSelectedCountryData()))
        });

        // Individual office contact no
        var officeContact = document.querySelector("#office_contact");
        intlOfficeContact = window.intlTelInput(officeContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));
        $('#OfficeContactCountryDetails').val(JSON.stringify(intlOfficeContact.getSelectedCountryData()))

        officeContact.addEventListener("countrychange", function() {
            $('#OfficeContactCountryDetails').val(JSON.stringify(intlOfficeContact
                .getSelectedCountryData()))
        });

        // Company Contact no fields
        var companyOfficeContact = document.querySelector("#company_office_contact");
        intlCompanyMobileContact = window.intlTelInput(companyOfficeContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));

        $('#CompanyOfficeContactCountryDetails').val(JSON.stringify(intlCompanyMobileContact
            .getSelectedCountryData()));

        companyOfficeContact.addEventListener("countrychange", function() {
            $('#CompanyOfficeContactCountryDetails').val(JSON.stringify(intlCompanyMobileContact
                .getSelectedCountryData()))
        });

        // company optional contact no
        var companyoptionalContact = document.querySelector("#company_optional_contact");
        intlcompanyOptionalContact = window.intlTelInput(companyoptionalContact, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));
        $('#companyMobileContactCountryDetails').val(JSON.stringify(intlcompanyOptionalContact
            .getSelectedCountryData()))

        companyoptionalContact.addEventListener("countrychange", function() {
            $('#companyMobileContactCountryDetails').val(JSON.stringify(intlcompanyOptionalContact
                .getSelectedCountryData()))
        });


        @if (!is_null(old('mobileContactCountryDetails')))
            var mbCountry = {!! old('mobileContactCountryDetails') !!}
            $('#mobileContactCountryDetails').val({!! old('mobileContactCountryDetails') !!})
            intlMobileContact.setCountry(mbCountry['iso2']);
        @endif
        @if (!is_null(old('OfficeContactCountryDetails')))
            // var officeCountry = {!! old('OfficeContactCountryDetails') !!}
            // $('#OfficeContactCountryDetails').val({!! old('OfficeContactCountryDetails') !!})
            // intlOfficeContact.setCountry(officeCountry['iso2']);
            intlOfficeContact.setCountry('pk');
        @endif
        @if (!is_null(old('CompanyOfficeContactCountryDetails')))
            // var companyContact = {!! old('CompanyOfficeContactCountryDetails') !!}
            // $('#CompanyOfficeContactCountryDetails').val({!! old('CompanyOfficeContactCountryDetails') !!})
            intlCompanyMobileContact.setCountry('pk');
        @endif
        @if (!is_null(old('companyMobileContactCountryDetails')))
            // var officeOptional = {!! old('companyMobileContactCountryDetails') !!}
            // $('#companyMobileContactCountryDetails').val({!! old('companyMobileContactCountryDetails') !!})
            intlcompanyOptionalContact.setCountry('pk');
        @endif

        var residential_country = $("#residential_country");
        residential_country.wrap('<div class="position-relative"></div>');
        residential_country.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_country.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            $("#residential_city").empty()
            $('#residential_state').empty();
            $('#residential_state').html('<option value=0>Select State</option>');
            $('#residential_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                .replace(':countryId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#stakeholderForm');
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
                                $("#residential_state").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });
                            hideBlockUI('#stakeholderForm');

                            @if (!is_null(old('residential_state')))
                                $('#residential_state').val({{ old('residential_state') }});
                                $('#residential_state').trigger('change')
                            @endif
                        } else {
                            hideBlockUI('#stakeholderForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#stakeholderForm');
                    }
                });
            }
        });


        var residential_state = $("#residential_state");
        residential_state.wrap('<div class="position-relative"></div>');
        residential_state.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_state.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            $("#residential_city").empty()
            $('#residential_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                .replace(':stateId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#stakeholderForm');
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
                                $("#residential_city").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });
                            hideBlockUI('#stakeholderForm');
                            @if (!is_null(old('residential_city')))
                                $('#residential_city').val({{ old('residential_city') }});
                                $('#residential_city').trigger('change')
                            @endif
                        } else {
                            hideBlockUI('#stakeholderForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#stakeholderForm');
                    }
                });
            }
        });

        var residential_city = $("#residential_city");
        residential_city.wrap('<div class="position-relative"></div>');
        residential_city.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_city.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        });

        var mailing_country = $("#mailing_country");
        mailing_country.wrap('<div class="position-relative"></div>');
        mailing_country.select2({
            dropdownAutoWidth: !0,
            dropdownParent: mailing_country.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            $("#mailing_city").empty()
            $('#mailing_state').empty();
            $('#mailing_state').html('<option value=0>Select State</option>');
            $('#mailing_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                .replace(':countryId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#stakeholderForm');
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
                                $("#mailing_state").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });

                            mailing_state.val(cp_state);
                            mailing_state.trigger('change');

                            @if (isset($data['mailing_state']))
                                mailing_state.val("{{ $data['mailing_state'] }}");
                                mailing_state.trigger('change');
                            @endif

                            hideBlockUI('#stakeholderForm');

                        } else {
                            hideBlockUI('#stakeholderForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#stakeholderForm');
                    }
                });
            }
        });


        var mailing_state = $("#mailing_state");
        mailing_state.wrap('<div class="position-relative"></div>');
        mailing_state.select2({
            dropdownAutoWidth: !0,
            dropdownParent: mailing_state.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            $("#mailing_city").empty()
            $('#mailing_city').html('<option value=0>Select City</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                .replace(':stateId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#stakeholderForm');
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
                                $("#mailing_city").append('<option value="' +
                                    value
                                    .id + '">' + value.name + '</option>');
                            });
                            mailing_city.val(cp_city);
                            mailing_city.trigger('change');

                            @if (isset($data['mailing_city']))
                                $("#mailing_city").val(
                                    "{{ $data['mailing_city'] }}");
                            @endif

                            hideBlockUI('#stakeholderForm');
                        } else {
                            hideBlockUI('#stakeholderForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#stakeholderForm');
                    }
                });
                hideBlockUI('#stakeholderForm');
            }
        });

        var mailing_city = $("#mailing_city");
        mailing_city.wrap('<div class="position-relative"></div>');
        mailing_city.select2({
            dropdownAutoWidth: !0,
            dropdownParent: mailing_city.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        });

        var e = $("#stakeholder_type");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on('select2:select', function(e) {
            var data = e.params.data;
            if (data.id == 'C') {
                $('#div-next-of-kin').show();
            } else {
                $('#div-next-of-kin').hide();
            }
        });

        $(".next-of-kin-list").repeater({
            initEmpty: true,
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


        $(".contact-persons-list").repeater({
            // initEmpty: true,
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

        $.validator.addMethod("uniqueKinId", function(value, element) {
            var parentForm = $(element).closest('form');
            var cnicRepeated = 0;
            if (value != '') {
                $(parentForm.find('.kinId')).each(function() {
                    if ($(this).val() === value) {
                        cnicRepeated++;
                    }
                });
            }
            return cnicRepeated === 1 || cnicRepeated === 0;

        }, "Kins can't be duplicated");
        $.validator.addMethod("unique", function(value, element) {
            var parentForm = $(element).closest('form');
            var cnicRepeated = 0;
            if (value != '') {
                $(parentForm.find('.cp_cnic')).each(function() {
                    if ($(this).val() === value) {
                        cnicRepeated++;
                    }
                });
            }
            return cnicRepeated === 1 || cnicRepeated === 0;

        }, "Contact Person CNIC can't be duplicated");
        $.validator.addMethod("ContactNoError", function(value, element) {
            // console.log(element.name)
            if (element.name == 'mobile_contact') {
                return intlMobileContact.isValidNumber();
            }
            if (element.name == 'company_office_contact') {
                return intlCompanyMobileContact.isValidNumber();
            }
        }, "In Valid number");

        $.validator.addMethod("OPTContactNoError", function(value, element) {

            if (value.length > 0) {
                if (element.name == 'office_contact') {
                    return intlOfficeContact.isValidNumber();
                }
                if (element.name == 'company_optional_contact') {
                    return intlcompanyOptionalContact.isValidNumber();
                }
            } else {
                return true;
            }
        }, "In Valid number");


        var validator = $("#stakeholderForm").validate({
            rules: {
                'mailing_address': {
                    required: true,
                },
                'address': {
                    required: true,
                },
                'optional_contact': {
                    required: false,
                },
                'full_name': {
                    required: true,
                },
                'father_name': {
                    required: true,
                },
                'cnic': {
                    required: true,
                },
                'registration': {
                    required: true,
                },
                'company_name': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i'
                    },
                },
                'email': {
                    required: true,
                }
            },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {
                form.submit();
            }
        });

        $('#is_local').on('change', function() {
            if($(this).is(':checked')){
                $('#nationality').val(167).change();
            }else{
                $('#nationality').val(0).change();
            }
        });

        @if (!isset($data['contact-persons']))
            $('#delete-contact-person').trigger('click');
        @endif
        @if (!isset($data['next-of-kin']))
            $('#delete-next-of-kin').trigger('click');
        @endif

        $('#cpyAddress').on('change', function() {
            if ($(this).is(':checked')) {
                cp_state = $('#residential_state').val();
                cp_city = $('#residential_city').val();

                $('#mailing_address_type').val($('#residential_address_type').val());
                $('#mailing_country').val($('#residential_country').val());
                mailing_country.trigger('change')
                $('#mailing_postal_code').val($('#residential_postal_code').val());
                $('#mailing_address').val($('#residential_address').val());

            } else {
                $('#mailing_address_type').val('')
                $('#mailing_country').val(0)
                $('#mailing_postal_code').val('');
                $('#mailing_address').val('');
            }
        })
        @if (isset($data['stakeholder_as']))
            $("#stakeholder_as").trigger('change');
        @endif
        @if (isset($data['country_id']))
            $("#country_id").trigger('change');
        @endif

        $(document).on('change', '.contact-person-select', function(e) {
            var index = Number(this.name.replace("contact-persons[", "").replace("][stakeholder_contact_id]", ""));
            let stakeholder_id = this.value;
            if (stakeholder_id > 0) {
                showBlockUI('#stakeholderForm');

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

                            $('[name="contact-persons[' + index + '][full_name]"]').val(stakeholderData
                                .full_name)
                            $('[name="contact-persons[' + index + '][father_name]"]').val(
                                stakeholderData
                                .father_name);
                            $('[name="contact-persons[' + index + '][occupation]"]').val(stakeholderData
                                .occupation);
                            $('[name="contact-persons[' + index + '][designation]"]').val(
                                stakeholderData
                                .designation);
                            $('[name="contact-persons[' + index + '][cnic]"]').val(stakeholderData
                                .cnic);
                            $('[name="contact-persons[' + index + '][ntn]"]').val(stakeholderData.ntn);
                            $('[name="contact-persons[' + index + '][contact]"]').val(stakeholderData
                                .contact);

                            $('[name="contact-persons[' + index + '][address]"]').val(stakeholderData
                                .address);
                            console.log($('[name="contact-persons[' + index + '][address]"]'))
                        }
                        hideBlockUI('#stakeholderForm');
                    },
                    error: function(errors) {
                        console.error(errors);
                        hideBlockUI('#stakeholderForm');
                    }
                });
            } else {
                $('[name="contact-persons[' + index + '][full_name]"]').val('')
                $('[name="contact-persons[' + index + '][father_name]"]').val('');
                $('[name="contact-persons[' + index + '][occupation]"]').val('');
                $('[name="contact-persons[' + index + '][designation]"]').val('');
                $('[name="contact-persons[' + index + '][cnic]"]').val('');
                $('[name="contact-persons[' + index + '][ntn]"]').val('');
                $('[name="contact-persons[' + index + '][contact]"]').val('');
                $('[name="contact-persons[' + index + '][address]"]').val('');
            }

        });

        hideBlockUI('#stakeholderForm');
    </script>
@endsection
