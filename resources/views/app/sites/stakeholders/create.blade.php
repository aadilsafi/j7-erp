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
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
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
                            <div class="d-block mb-1">
                                <label class="form-label fs-5" for="type_name">Passport Attachment</label>
                                <input id="passport_attachment" type="file"
                                    class="filepond @error('attachment') is-invalid @enderror" name="passport_attachment[]"
                                    multiple accept="image/png, image/jpeg, image/gif, application/pdf" />
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
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
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
            FilePondPluginPdfPreview,
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

        FilePond.create(document.getElementById('passport_attachment'), {
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg', 'application/pdf'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
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
                $(this).slideDown(function() {
                    $(this).find('.contact-person-select').select2().val(0).trigger('change');
                }), feather && feather.replace({
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
