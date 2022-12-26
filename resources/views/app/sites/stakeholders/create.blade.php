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

        #div_stakeholders {
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
        var cp_state = 0;
        var cp_city = 0;
        var ra_state = 0;
        var ra_city = 0;
    </script>
    {{ view('app.sites.stakeholders.partials.stakeholder_form_scripts') }}

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
    </script>

    <script type="text/javascript">
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
                $('#div_stakeholders').hide();
            } else if (data.id == 'K') {
                $('#div-next-of-kin').hide();
                $('#div_stakeholders').show();
            } else {
                $('#div_stakeholders').hide();
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

        $(".stakeholders-list").repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown(function() {
                    $(this).find('.selectStk').select2({
                        placeholder: 'Select Stakeholder'
                    });
                }), feather && feather.replace({
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



        var validator = $("#stakeholderForm").validate({
            rules: {
                'stakeholder_as': {
                    required: function() {
                        return $("#stakeholder_as").val() != 'i' || $("#stakeholder_as").val() != 'c';
                    },
                },
                'residential[address_type]': {
                    required: true,
                },
                'residential[country]': {
                    required: true,
                    min: 1
                },
                'residential[state]': {
                    required: true,
                    min: 1
                },
                'residential[city]': {
                    required: true,
                    min: 1
                },
                'residential[address]': {
                    required: true,
                },
                'residential[postal_code]': {
                    required: true,
                },
                'mailing[address_type]': {
                    required: true,
                },
                'mailing[country]': {
                    required: true,
                    min: 1
                },
                'mailing[state]': {
                    required: true,
                    min: 1
                },
                'mailing[city]': {
                    required: true,
                    min: 1
                },
                'mailing[address]': {
                    required: true,
                },
                'mailing[postal_code]': {
                    required: true,
                },
                'company[company_name]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
                    },
                },
                'company[registration]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
                    },
                },
                'company[industry]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
                    },
                },
                'company[company_ntn]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
                    },
                },
                'company[strn]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'c';
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
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[occupation]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[cnic]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[mobile_contact]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[dob]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },
                'individual[nationality]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i' && !$('#is_local').is(':checked');
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
                if ($("#stakeholder_as").val() == 'i' || $("#stakeholder_as").val() == 'c') {
                    form.submit();
                }
            }
        });

        @if (!isset($data['contact-persons']))
            $('#delete-contact-person').trigger('click');
        @endif
        @if (!isset($data['next-of-kin']))
            $('#delete-next-of-kin').trigger('click');
        @endif

        @if (isset($data['stakeholder_as']))
            $("#stakeholder_as").trigger('change');
        @endif
        @if (isset($data['country_id']))
            $("#country_id").trigger('change');
        @endif

        $(document).on('change', '.contact-person-select', function(e) {
            var index = Number(this.name.replace("contact-persons[", "").replace(
                "][stakeholder_contact_id]", ""));
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

                            $('[name="contact-persons[' + index + '][full_name]"]').val(
                                stakeholderData
                                .full_name)
                            $('[name="contact-persons[' + index + '][father_name]"]').val(
                                stakeholderData
                                .father_name);
                            $('[name="contact-persons[' + index + '][occupation]"]').val(
                                stakeholderData
                                .occupation);
                            $('[name="contact-persons[' + index + '][designation]"]').val(
                                stakeholderData
                                .designation);
                            $('[name="contact-persons[' + index + '][cnic]"]').val(
                                stakeholderData
                                .cnic);
                            $('[name="contact-persons[' + index + '][ntn]"]').val(
                                stakeholderData.ntn);
                            $('[name="contact-persons[' + index + '][contact]"]').val(
                                stakeholderData
                                .contact);

                            $('[name="contact-persons[' + index + '][address]"]').val(
                                stakeholderData
                                .address);
                            console.log($('[name="contact-persons[' + index +
                                '][address]"]'))
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
