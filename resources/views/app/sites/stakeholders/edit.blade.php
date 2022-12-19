@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.stakeholders.edit', $site_id) }}
@endsection

@section('page-title', 'Edit Stakeholder')

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

        .input-group .intl-tel-input .form-control {
            border-top-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 0;
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
                <h2 class="content-header-title float-start mb-0">Edit Stakeholder</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.stakeholders.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- @dd($stakeholder) --}}
    <form id="stakeholderForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.stakeholders.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($stakeholder->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                @csrf
                @method('put')
                {{ view('app.sites.stakeholders.form-fields', [
                    'stakeholders' => $stakeholders,
                    'stakeholder' => $stakeholder,
                    'stakeholderTypes' => $stakeholderTypes,
                    'emptyRecord' => $emptyRecord,
                    'country' => $country,
                    'city' => $city,
                    'state' => $state,
                    'emtyNextOfKin' => $emtyNextOfKin,
                    'customFields' => $customFields,
                    'contactStakeholders' => $contactStakeholders,
                    'leadSources' => $leadSources,
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
                            @can('sites.stakeholders.update')
                                <button id="saveButton" type="submit"
                                    class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 buttonToBlockUI mb-1">
                                    <i data-feather='save'></i>
                                    Update Stakeholder
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

    <script type="text/javascript">
        $(document).ready(function() {

            $('#companyForm').hide();
            $('#individualForm').hide();
            $('#common_form').hide()
            $('#stakeholderType').hide();

            var dob = $("#dob").flatpickr({
                defaultDate: "{{ $stakeholder->date_of_birth }}",
                minDate: '',
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            @php
                $data = old();
            @endphp

            @if (!is_null(old('stakeholder_type')))
                $('#stakeholderType').val({{ old('stakeholder_type') }}).change();
            @endif

            var cp_state = 0;
            var cp_city = 0;

            var stakeholderAs = $("#stakeholder_as");
            stakeholderAs.wrap('<div class="position-relative"></div>');
            stakeholderAs.select2({
                dropdownAutoWidth: !0,
                dropdownParent: stakeholderAs.parent(),
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

            stakeholderAs.val('{{ $stakeholder->stakeholder_as }}');
            stakeholderAs.trigger('change');

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

            @if (is_null($stakeholder->mobileContactCountryDetails))
                intlMobileContact.setCountry('pk');
            @else
                var selectdCountry = {!! $stakeholder->mobileContactCountryDetails != null ? $stakeholder->mobileContactCountryDetails : null !!}
                intlMobileContact.setCountry(selectdCountry['iso2']);
                $('#mobileContactCountryDetails').val(JSON.stringify(intlMobileContact.getSelectedCountryData()));
            @endif

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

            @if (is_null($stakeholder->OfficeContactCountryDetails))
                intlOfficeContact.setCountry('pk');
            @else
                var OptionalselectdCountry = {!! $stakeholder->OfficeContactCountryDetails != null ? $stakeholder->OfficeContactCountryDetails : null !!}
                intlOfficeContact.setCountry(OptionalselectdCountry['iso2']);
            @endif

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

            @if (is_null($stakeholder->OfficeContactCountryDetails))
                intlOfficeContact.setCountry('pk');
            @else
                var OptselectdCountry = {!! $stakeholder->OfficeContactCountryDetails != null ? $stakeholder->OfficeContactCountryDetails : null !!}
                intlCompanyMobileContact.setCountry(OptselectdCountry['iso2']);
            @endif

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

            @if (is_null($stakeholder->mobileContactCountryDetails))
                intlcompanyOptionalContact.setCountry('pk');
            @else
                var OptselectdCountry = {!! $stakeholder->mobileContactCountryDetails != null ? $stakeholder->mobileContactCountryDetails : null !!}
                intlcompanyOptionalContact.setCountry(OptselectdCountry['iso2']);
            @endif

            $('#div-next-of-kin').hide();
            var e = $("#parent_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {
                if ($(this).val() == 0) {
                    $('#stakeholder_name').attr("readonly", true).val('');
                } else {
                    $('#stakeholder_name').removeAttr("readonly");
                }
            });

            var areStakeholderContactsExist = {{ isset($stakeholder->contacts[0]) ? 'false' : 'true' }};
            var areStakeholderKinsExist = {{ count($stakeholder->nextOfKin) > 0 ? 'false' : 'true' }};

            $(".next-of-kin-list").repeater({

                initEmpty: areStakeholderKinsExist,
                show: function() {
                    $(this).slideDown(), feather && feather.replace({
                        width: 14,
                        height: 14
                    })
                },
                hide: function(e) {
                    $(this).slideUp(e)
                }
            })

            $(".contact-persons-list").repeater({
                initEmpty: areStakeholderContactsExist,
                show: function() {
                    $(this).slideDown(), feather && feather.replace({
                        width: 14,
                        height: 14
                    })
                },
                hide: function(e) {
                    $(this).slideUp(e)
                }
            })

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

            $.validator.addMethod("ContactNoError", function(value, element) {

                return intl.isValidNumber();

            }, "In Valid number");
            $.validator.addMethod("OPTContactNoError", function(value, element) {

                if (value.length > 0) {
                    return intlOptional.isValidNumber();
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
                        required: true,
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
            @forelse ($stakeholder->stakeholder_types as $type)
                @if ($type->type == 'C' && $type->status)
                    $('#div-next-of-kin').show();
                @else
                @endif
            @empty
            @endforelse

            function performAction(action) {
                if (action == 'C') {
                    // $('#div-next-of-kin').toggle('fast', 'linear');
                    $('#div-next-of-kin').show();
                } else {
                    $('#div-next-of-kin').hide();
                }
            }

            var firstLoad = true;

            // residential address
            var residential_country = $("#residential_country");
            residential_country.wrap('<div class="position-relative"></div>');
            residential_country.select2({
                dropdownAutoWidth: !0,
                dropdownParent: residential_country.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {

                $("#residential_state").empty()
                $('#residential_city').empty();
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

                                if (firstLoad) {
                                    residential_state.val(
                                        '{{ $stakeholder->residential_state_id }}');
                                    if (residential_state.val() > 0) {
                                        residential_state.trigger('change');
                                    } else {
                                        firstLoad = false;
                                    }
                                }
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
                                if (firstLoad) {
                                    residential_city.val(
                                        '{{ $stakeholder->residential_city_id }}');
                                    firstLoad = false;
                                }
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

            residential_country.val('{{ $stakeholder->residential_country_id }}');
            residential_country.trigger('change');


            // mailing address

            var mfirstLoad = true;

            var mailing_country = $("#mailing_country");
            mailing_country.wrap('<div class="position-relative"></div>');
            mailing_country.select2({
                dropdownAutoWidth: !0,
                dropdownParent: mailing_country.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {

                $("#mailing_state").empty()
                $('#mailing_city').empty();
                $('#mailing_state').html('<option value=0>Select State</option>');
                $('#mailing_city').html('<option value=0>Select City</option>');
                let _token = '{{ csrf_token() }}';
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

                                hideBlockUI('#stakeholderForm');
                                if (mfirstLoad) {
                                    mailing_state.val(
                                        '{{ $stakeholder->mailing_state_id }}');
                                    if (mailing_state.val() > 0) {
                                        mailing_state.trigger('change');
                                    } else {
                                        mfirstLoad = false;
                                    }
                                }
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
                let _token = '{{ csrf_token() }}';
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

                                hideBlockUI('#stakeholderForm');
                                if (mfirstLoad) {
                                    mailing_city.val(
                                        '{{ $stakeholder->mailing_city_id }}');
                                    mfirstLoad = false;
                                }
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

            var mailing_city = $("#mailing_city");
            mailing_city.wrap('<div class="position-relative"></div>');
            mailing_city.select2({
                dropdownAutoWidth: !0,
                dropdownParent: mailing_city.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            });

            mailing_country.val('{{ $stakeholder->mailing_country_id }}');
            mailing_country.trigger('change');

            @if (!is_null(old('mobileContactCountryDetails')))
                var mbCountry = {!! old('mobileContactCountryDetails') !!}
                $('#mobileContactCountryDetails').val({!! old('mobileContactCountryDetails') !!})
                intlMobileContact.setCountry(mbCountry['iso2']);

                var officeCountry = {!! old('OfficeContactCountryDetails') !!}
                $('#OfficeContactCountryDetails').val({!! old('OfficeContactCountryDetails') !!})
                intlOfficeContact.setCountry(officeCountry['iso2']);

                var companyContact = {!! old('CompanyOfficeContactCountryDetails') !!}
                $('#CompanyOfficeContactCountryDetails').val({!! old('CompanyOfficeContactCountryDetails') !!})
                intlCompanyMobileContact.setCountry(companyContact['iso2']);

                var officeOptional = {!! old('OfficeContactCountryDetails') !!}
                $('#OfficeContactCountryDetails').val({!! old('OfficeContactCountryDetails') !!})
                intlcompanyOptionalContact.setCountry(officeOptional['iso2']);
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
        });

        function performAction(action) {
            if (action == 'C') {
                $('#div-next-of-kin').show();
            } else {
                $('#div-next-of-kin').hide();
            }
        }

        var editImage = "";
        var id = <?php echo $stakeholder->id; ?>;

        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        var files = [];

        @forelse($images as $image)
            files.push({
                source: '{{ $image->getUrl() }}',
            });
        @empty
        @endforelse

        FilePond.create(document.getElementById('attachment'), {

            files: files,
            styleButtonRemoveItemPosition: 'right',
            // imageValidateSizeMinWidth: 1000,
            // imageValidateSizeMinHeight: 1000,
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            maxFiles: 2,
            minFiles: 2,
            // required: true,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });

        $('#cpyAddress').on('change', function() {
            if ($(this).is(':checked')) {
                $('#mailing_address').val($('#address').val());
            } else {
                $('#mailing_address').val('')
            }
        })

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
    </script>
@endsection
