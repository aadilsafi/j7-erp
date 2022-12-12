@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.users.edit', $site_id) }}
@endsection

@section('page-title', 'Edit User')

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
                <h2 class="content-header-title float-start mb-0">Edit User</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.users.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="userForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.users.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($user->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        @csrf
                        @method('put')
                        {{ view('app.sites.users.form-fields', [
                            'user' => $user,
                            'roles' => $roles,
                            'Selectedroles' => $Selectedroles,
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
                        @can('sites.users.update')
                            <button id="saveButton" type="submit"
                                class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI me-1 mb-1">
                                <i data-feather='save'></i>
                                Update User
                            </button>
                        @endcan

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

    <script>
        var editImage = "";
        var id = <?php echo $user->id; ?>;

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
            required: true,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });

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
            @if (is_null($user->countryDetails))
                intl.setCountry('pk');
            @else
                var selectdCountry = {!! $user->countryDetails != null ? $user->countryDetails : null !!}
                intl.setCountry(selectdCountry['iso2']);
                $('#countryDetails').val(JSON.stringify(intl.getSelectedCountryData()))
            @endif

            var inputOptional = document.querySelector("#optional_contact");
            intlOptional = window.intlTelInput(inputOptional, ({
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                preferredCountries: ["pk"],
                separateDialCode: true,
                autoPlaceholder: 'polite',
                formatOnDisplay: true,
                nationalMode: true
            }));
            @if (is_null($user->optional_contact))
                intlOptional.setCountry('pk');
            @else
                var OptionalselectdCountry = {!! $user->OptionalCountryDetails != null ? $user->OptionalCountryDetails : null !!}
                intlOptional.setCountry(OptionalselectdCountry['iso2']);
            @endif
            inputOptional.addEventListener("countrychange", function() {
                $('#OptionalCountryDetails').val(JSON.stringify(intlOptional.getSelectedCountryData()))
            });
            $('#OptionalCountryDetails').val(JSON.stringify(intlOptional.getSelectedCountryData()))

            var firstLoad = true;

            var country_id = $("#country_id");
            country_id.wrap('<div class="position-relative"></div>');
            country_id.select2({
                dropdownAutoWidth: !0,
                dropdownParent: country_id.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {
                showBlockUI('#userForm');
                $("#city_id").empty()
                $('#state_id').empty();
                $('#state_id').html('<option value=0>Select State</option>');
                $('#city_id').html('<option value=0>Select City</option>');
                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                    .replace(':countryId', $(this).val());
                if ($(this).val() > 0) {
                    showBlockUI('#userForm');
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
                                hideBlockUI('#userForm');

                                if (firstLoad) {
                                    state_id.val('{{ $user->state_id }}');
                                    if (state_id.val() > 0) {
                                        state_id.trigger('change');
                                    } else {
                                        firstLoad = false;
                                    }
                                }
                            } else {
                                hideBlockUI('#userForm');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            hideBlockUI('#userForm');
                        }
                    });
                }

                hideBlockUI('#userForm');
            });

            var city_id = $("#city_id");
            city_id.wrap('<div class="position-relative"></div>');
            city_id.select2({
                dropdownAutoWidth: !0,
                dropdownParent: city_id.parent(),
                width: "100%",
                containerCssClass: "select-lg",
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

                showBlockUI('#userForm');

                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                    .replace(':stateId', $(this).val());
                if ($(this).val() > 0) {
                    showBlockUI('#userForm');
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
                                hideBlockUI('#userForm');
                                if (firstLoad) {
                                    city_id.val('{{ $user->city_id }}');
                                    firstLoad = false;
                                }
                            } else {
                                hideBlockUI('#userForm');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            hideBlockUI('#userForm');
                        }
                    });
                }

                hideBlockUI('#userForm');
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


            country_id.val('{{ $user->country_id }}');
            country_id.trigger('change');
        });
    </script>
@endsection
