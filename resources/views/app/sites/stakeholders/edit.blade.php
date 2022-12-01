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
                            <button id="saveButton" type="submit"
                                class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Update Stakeholder
                            </button>
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
        var input = document.querySelector("#contact");
        intl = window.intlTelInput(input, ({
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            preferredCountries: ["pk"],
            separateDialCode: true,
            autoPlaceholder: 'polite',
            formatOnDisplay: true,
            nationalMode: true
        }));
        @if (is_null($stakeholder->countryDetails))
            intl.setCountry('pk');
        @else
            var selectdCountry = {!! $stakeholder->countryDetails != null ? $stakeholder->countryDetails : null !!}
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
        @if (is_null($stakeholder->optional_contact))
            intlOptional.setCountry('pk');
        @else
            var OptionalselectdCountry = {!! $stakeholder->OptionalCountryDetails != null ? $stakeholder->OptionalCountryDetails : null !!}
            intlOptional.setCountry(OptionalselectdCountry['iso2']);
        @endif
        inputOptional.addEventListener("countrychange", function() {
            $('#OptionalCountryDetails').val(JSON.stringify(intlOptional.getSelectedCountryData()))
        });
        $('#OptionalCountryDetails').val(JSON.stringify(intlOptional.getSelectedCountryData()))


        $(document).ready(function() {

            input.addEventListener("countrychange", function() {
                $('#countryDetails').val(JSON.stringify(intl.getSelectedCountryData()))
            });
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
                    // var index = $(this).index();
                    // var id = '#contact_' + index;
                    // console.log(id);
                    // var input = document.querySelector(id);

                    // intl[index] = window.intlTelInput(input, ({
                    //     utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    //     preferredCountries: ["pk"],
                    //     separateDialCode: true,
                    //     autoPlaceholder: 'polite',
                    //     formatOnDisplay: true,
                    //     nationalMode: true
                    // }));


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


        });

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
        var firstLoad = true;

        var country_id = $("#country_id");
        country_id.wrap('<div class="position-relative"></div>');
        country_id.select2({
            dropdownAutoWidth: !0,
            dropdownParent: country_id.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {
            showBlockUI('#stakeholderForm');

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

                            if (firstLoad) {
                                state_id.val('{{ $stakeholder->state_id }}');
                                if (state_id.val() > 0) {
                                    state_id.trigger('change');
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
                            if (firstLoad) {
                                city_id.val('{{ $stakeholder->city_id }}');
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

        function performAction(action) {
            if (action == 'C') {
                // $('#div-next-of-kin').toggle('fast', 'linear');
                $('#div-next-of-kin').show();
            } else {
                $('#div-next-of-kin').hide();
            }
        }

        country_id.val('{{ $stakeholder->country_id }}');
        country_id.trigger('change');

        $('#cpyAddress').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#mailing_address').val($('#address').val());
                } else {
                    $('#mailing_address').val('')
                }
            })
    </script>
@endsection
