@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.investors-deals.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Investor Deals')

@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/forms/wizard/bs-stepper.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-wizard.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css" />
@endsection

@section('custom-css')
    <style>
        .custom_row div p {
            margin: 0;
            padding: 1rem;
            font-weight: 700;

        }

        .custom_row div input {
            margin: 0;
            padding: 1rem;
            font-weight: 700;

        }

        .custom_row {
            background-color: #f3f2f7;
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
                                } */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Investor Deals</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.investors-deals.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="stakeholderInvestorDealForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.investors-deals.store', ['site_id' => encryptParams($site_id)]) }}" method="POST">

        <div class="row">
            <div id="investorsDeal" class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.sites.stakeholder-investors.form-fields', [
                    'investors' => $investors,
                    'country' => $country,
                    'leadSources' => $leadSources,
                    'stakeholderTypes' => $stakeholderTypes,
                    'units' => $units,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="d-block mb-1">
                                    <label class="form-label" style="font-size: 15px" for="floor">
                                        Deal Document Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input name="doc_number" type="text"
                                        class="form-control  @error('doc_number') is-invalid @enderror" id="doc_number"
                                        placeholder="Deal Document Number " />
                                    @error('doc_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-block mb-1">
                                    <label class="form-label fs-5" for="created_date">Creation Date<span
                                            class="text-danger">*</span></label>
                                    <input id="created_date" type="date" required placeholder="YYYY-MM-DD"
                                        name="created_date" class="form-control form-control-lg" />
                                </div>

                                {{-- <div class="d-block mb-1">
                                    <label class="form-label" style="font-size: 15px" for="floor">
                                        Total Received Amount
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input name="total_received_amount" type="text"
                                        class="form-control amountFormat @error('total_received_amount') is-invalid @enderror"
                                        id="total_received_amount" placeholder="Total Received Amount " />
                                    @error('total_received_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="d-block mb-1">
                                    <label class="form-label fs-5" for="type_name">Attachment<span
                                            class="text-danger">*</span></label>
                                    <input  id="attachment" type="file"
                                        class="filepond @error('attachment') is-invalid @enderror" name="attachment[]"
                                        multiple accept="image/png, image/jpeg, image/gif, application/pdf" />
                                    @error('attachment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>
                                @can('sites.investors-deals.store')
                                    <div class="col-md-12">
                                        <a id="saveButton" href="#"
                                            class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                                            <i data-feather='save'></i>
                                            Save
                                        </a>
                                    </div>
                                @endcan
                                <div class="col-md-12">
                                    <a href="{{ route('sites.investors-deals.index', ['site_id' => encryptParams($site_id)]) }}"
                                        class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light">
                                        <i data-feather='x'></i>
                                        {{ __('lang.commons.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js"></script>
@endsection

@section('custom-js')
    {{ view('app.sites.stakeholders.partials.stakeholder_form_scripts') }}
    <script>
        var validator = $("#stakeholderInvestorDealForm").validate({

            rules: {
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

                'stakeholder_id': {
                    required: true,
                },
                'doc_number': {
                    required: true,
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

        $.validator.addMethod("all_units", function(value, element) {

            var parentForm = $(element).closest('form');
            var unitRepeated = 0;
            if (value != '') {
                $(parentForm.find('.all_units_id')).each(function() {
                    if ($(this).val() === value) {
                        unitRepeated++;
                    }
                });
            }
            return unitRepeated === 1 || unitRepeated === 0;

        }, "Units can't be duplicated");

        $("#saveButton").click(function() {
            $("#stakeholderInvestorDealForm").submit();
        });


        $(document).ready(function() {
            $(this).find('.all_units').select2({});
        })
        $(".unit-deal-list").repeater({
            // initEmpty: true,
            show: function() {
                $(this).slideDown(function() {
                    $(this).find('.all_units').select2().val('').trigger('change');
                    calculateTotalReceivedAmount();
                }), feather && feather.replace({
                    width: 14,
                    height: 14
                })
            },
            hide: function(e) {

                $(this).slideUp(e)
                $(this).find('.received_amount').val(0);
                calculateTotalReceivedAmount();
            }

        });

        $(document).on('change', '.received_amount', function(e) {
            calculateTotalReceivedAmount()
        });

        function calculateTotalReceivedAmount(){
            let sum = 0;
            $('.received_amount').each(index => {
                let value = $("input[name='unit-deals[" + index + "][received_amount]']").val();
                value = value.replace(/,/g, "");
                if (value > 0) {
                    sum = parseFloat(sum) + parseFloat(value);
                }
            });
            $('.total_recieved').val(sum.toLocaleString());
        }

        var cp_state = 0;
        var cp_city = 0;
        var ra_state = 0;
        var ra_city = 0;

        var created_date = $("#created_date").flatpickr({
            defaultDate: "today",
            maxDate: 'today',
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

            showBlockUI('#investorsDeal');

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
                            $('#is_local').prop("checked", stakeholderData.is_local);

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
                    hideBlockUI('#investorsDeal');
                },
                error: function(errors) {
                    console.error(errors);
                    hideBlockUI('#investorsDeal');
                }
            });
        });

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
            // required:true,
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

@endsection
