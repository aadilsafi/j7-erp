@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.users.create', $site_id) }}
@endsection

@section('page-title', 'Create User')

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
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create User</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.users.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="userForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.users.store', ['site_id' => encryptParams($site_id)]) }}" method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card">
                    <div class="card-body" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        @csrf
                        {{ view('app.sites.users.form-fields', [
                            'roles' => $role,
                            'customFields' => $customFields,
                            'country' => $country,
                            'city' => $city,
                            'state' => $state,
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        {{-- <div class="d-block mb-1">
                        <label class="form-label fs-5" for="type_name">CNIC Attachment</label>
                        <input id="attachment" type="file" class="filepond @error('attachment') is-invalid @enderror"
                            name="attachment[]" multiple accept="image/png, image/jpeg, image/gif" />
                        @error('attachment')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr> --}}

                        <button type="submit"
                            class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                            <i data-feather='save'></i>
                            Save User
                        </button>

                        <a href="{{ route('sites.users.index', ['site_id' => encryptParams($site_id)]) }}"
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
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
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
            maxFiles: 2,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var input = document.querySelector("#contact");
            intl = window.intlTelInput(input, ({
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                preferredCountries: ["pk"],
                separateDialCode: true,
                autoPlaceholder: 'polite',
                formatOnDisplay: true,
                nationalMode: true
            }));

            input.addEventListener("countrychange", function() {
                $('#countryDetails').val(JSON.stringify(intl.getSelectedCountryData()))
            });
            $('#countryDetails').val(JSON.stringify(intl.getSelectedCountryData()))

            var inputOptional = document.querySelector("#optional_contact");
            intlOptional = window.intlTelInput(inputOptional, ({
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                preferredCountries: ["pk"],
                separateDialCode: true,
                autoPlaceholder: 'polite',
                formatOnDisplay: true,
                nationalMode: true
            }));

            inputOptional.addEventListener("countrychange", function() {
                $('#OptionalCountryDetails').val(JSON.stringify(intlOptional.getSelectedCountryData()))
            });
            $('#OptionalCountryDetails').val(JSON.stringify(intlOptional.getSelectedCountryData()))

            $("#city_id").empty()
            $('#state_id').empty();

            var e = $("#country_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {

                $("#city_id").empty()
                $('#state_id').empty();
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
                                $('#state_id').html('<option value=0>Select State</option>');
                                $('#city_id').html('<option value=0>Select City</option>');
                                $.each(response.states, function(key, value) {
                                    $("#state_id").append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                });
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


            var e = $("#state_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {
                $("#city_id").empty()
                // alert($(this).val());
                showBlockUI('#stakeholderForm');

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
                                $('#city_id').html('<option value=0>Select City</option>');
                                $.each(response.cities, function(key, value) {
                                    $("#city_id").append('<option value="' + value
                                        .id + '">' + value.name + '</option>');
                                });
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


            var e = $("#role_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            });

            $('#cpyAddress').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#mailing_address').val($('#address').val());
                } else {
                    $('#mailing_address').val('')
                }
            })

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
            var validator = $("#userForm").validate({
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
                    }
                },
                errorClass: 'is-invalid text-danger',
                errorElement: "span",
                wrapper: "div",
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
