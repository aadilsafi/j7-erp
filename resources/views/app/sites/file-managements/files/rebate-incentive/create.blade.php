@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.rebate-incentive.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Rebate Incentive')

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
        .hideDiv {
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
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Rebate Incentive</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.rebate-incentive.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="rebateForm"
        action="{{ route('sites.file-managements.rebate-incentive.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class=" ">
        @csrf

        <div class="row">
            <div id="rebate" class="col-lg-9 col-md-9 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.rebate-incentive.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                    'dealer_data' => $dealer_data,
                    'rebate_files' => $rebate_files,
                    'customFields' => $customFields,
                    'banks' => $banks,
                    'chequebanks' => $banks,
                    'country' => $country,
                    'leadSources' => $leadSources,
                    'stakeholderTypes' => $stakeholderTypes,
                ]) }}
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0; z-index:10;">
                    <div class="card-body g-1">
                        @can('sites.file-managements.rebate-incentive.store')
                            <a id="saveButton" href="#"
                                class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                                <i data-feather='save'></i>
                                Save
                            </a>
                        @endcan

                        <a href="{{ route('sites.file-managements.rebate-incentive.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script src="{{ asset('app-assets') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
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
        var cp_state = 0;
        var cp_city = 0;
        var ra_state = 0;
        var ra_city = 0;
    </script>
    {{ view('app.sites.stakeholders.partials.stakeholder_form_scripts') }}


    <script type="text/javascript">

        $("#transaction_date").flatpickr({
            defaultDate: "today",
            // minDate: 'today',
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $('#companyForm').hide();
        $("#stakeholder_as").val('i');

    $("#stakeholder_as").trigger('change');
        var selected_state_id = 0;
        var selected_city_id = 0;

        function getData(unit_id) {
            showBlockUI('#rebate');
            if (unit_id > 0) {
                var _token = '{{ csrf_token() }}';
                let url =
                    "{{ route('sites.file-managements.rebate-incentive.ajax-get-data', ['site_id' => encryptParams($site_id)]) }}";
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'unit_id': unit_id,
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {

                            if ($('.newAddition').length > 0) {

                                $('.newAddition').remove();
                                $('#faceCharges').css("display", "block");
                                $('#faceChargesPercentage').css("display", "block");
                                $('#td_unit_facing_charges').css("display", "block");
                                $('#td_unit_facing_charges_value').css("display", "block");
                            }

                            if (response.additionalCosts.length > 0) {
                                for (let i = 0; i < response.additionalCosts.length; i++) {
                                    $('#faceCharges').before('<th class="text-nowrap newAddition">' + response
                                        .additionalCosts[i].name + '</th>');
                                    $('#faceChargesPercentage').before(
                                        '<th class="text-nowrap newAddition">%</th>');
                                    $('#td_unit_facing_charges').before('<td class="text-nowrap newAddition">' +
                                        response.additionalCosts[i].unit_percentage + '%</td>');
                                    let facing_value = (response.additionalCosts[i].unit_percentage / 100) *
                                        (response.salesPlan.unit_price * response.unit.gross_area);
                                    $('#td_unit_facing_charges_value').before(
                                        '<td class="text-nowrap newAddition">' + facing_value
                                        .toLocaleString() +
                                        '</td>');
                                }
                                $('#faceCharges').css("display", "none");
                                $('#faceChargesPercentage').css("display", "none");
                                $('#td_unit_facing_charges').css("display", "none");
                                $('#td_unit_facing_charges_value').css("display", "none");
                            }


                            $('#sales_source_lead_source').val(response.leadSource.name);
                            $('#stakeholder_id').val(response.stakeholder.id);
                            $('#customer_name').val(response.stakeholder.full_name);
                            $('#customer_father_name').val(response.stakeholder.father_name);
                            $('#customer_cnic').val(response.cnic);
                            $('#customer_ntn').val(response.stakeholder.ntn);
                            $('#customer_comments').val(response.stakeholder.comments);
                            $('#customer_address').val(response.stakeholder.address);
                            $('#customer_mailing_address').val(response.stakeholder.mailing_address);
                            $('#customer_phone').val(response.stakeholder.mobile_contact);
                            $('#optional_customer_phone').val(response.stakeholder.optional_contact);
                            $('#customer_occupation').val(response.stakeholder.occupation);
                            $('#customer_designation').val(response.stakeholder.designation);

                            // var countryDetails = JSON.parse(response.stakeholder.countryDetails);

                            // if (countryDetails == null) {
                            //     intl.setCountry('pk');
                            // } else {
                            //     intl.setCountry(countryDetails['iso2']);
                            // }

                            // $('#countryDetails').val(JSON.stringify(intl
                            //     .getSelectedCountryData()))

                            // var OptionalCountryDetails = JSON.parse(response.stakeholder
                            //     .OptionalCountryDetails);
                            // if (OptionalCountryDetails == null) {
                            //     intlOptional.setCountry('pk');
                            // } else {
                            //     intlOptional.setCountry(OptionalCountryDetails['iso2']);
                            // }

                            // $('#OptionalCountryDetails').val(JSON.stringify(intlOptional
                            //     .getSelectedCountryData()))

                            $('#td_unit_id').html(response.unit.unit_number);
                            $('#td_unit_area').html(response.unit.gross_area);
                            $('#td_unit_rate').html(parseFloat(response.salesPlan.unit_price).toLocaleString());
                            $('#td_unit_floor').html(response.floor);

                            if (response.facing != null && response.facing != '') {
                                $('#td_unit_facing_charges').html(response.facing.unit_percentage + '%');
                            } else {
                                $('#td_unit_facing_charges').html(0 + '%');
                            }

                            let unit_total = response.unit.price_sqft * response.unit.gross_area;
                            $('#unit_total').val(unit_total)

                            $('#td_unit_discount').html(response.salesPlan.discount_percentage + '%');
                            $('#td_unit_total').html(unit_total.toLocaleString());
                            $('#td_unit_downpayment').html(response.salesPlan.down_payment_percentage + '%');

                            if (response.facing != null) {
                                // let facing_value = response.salesPlan.discount_percentage * response.salesPlan
                                //     .total_price;
                                // $('#td_unit_facing_charges_value').html(facing_value.toLocaleString())
                                $('#td_unit_facing_charges_value').html(0);
                            } else {
                                $('#td_unit_facing_charges_value').html(0);
                            }

                            $('#td_unit_discount_value').html(parseFloat(response.salesPlan.discount_total)
                                .toLocaleString());
                            $('#td_unit_total_value').html(parseFloat(response.salesPlan.total_price)
                                .toLocaleString());
                            $('#sales_plan_total').val(parseFloat(response.salesPlan.total_price)
                                .toLocaleString())

                            $('#td_unit_downpayment_value').html(parseFloat(response.salesPlan
                                    .down_payment_total)
                                .toLocaleString());
                            hideBlockUI('#rebate');
                        } else {
                            hideBlockUI('#rebate');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something Went Wrong!!',
                            });
                        }
                    },
                    error: function(error) {
                        hideBlockUI('#rebate');
                        console.log(error);
                    }
                });
            } else {
                $('.hideDiv').hide();
                hideBlockUI('#rebate');

            }

        }

        $(document).on('blur', '#rebate_percentage', function() {

            showBlockUI('#rebate');
            let rebate_percentage_value = $('#rebate_percentage').val();

            if ($.isNumeric(rebate_percentage_value) && rebate_percentage_value > 0 && rebate_percentage_value <=
                100) {
                let rebate_percentage = parseFloat($('#rebate_percentage').val());

                rebate_percentage = (rebate_percentage > 100) ? 100 : rebate_percentage;

                rebate_percentage = (rebate_percentage < 0) ? 0 : rebate_percentage;

                let unit_total = parseFloat($('#sales_plan_total').val());

                let rebate_value = parseFloat((rebate_percentage * unit_total) / 100);

                $('#td_rebate').html(rebate_percentage + '%');

                $('#td_rebate_value').html(rebate_value.toLocaleString());

                $('#rebate_total').val(rebate_value);

                if (unit_total > 0) {
                    $('.hideDiv').show();
                }
                $('.check').remove();
            } else {
                $('.check').remove();
                $('#rebate_percentage').val('');
                $('#rebate_percentage').after(
                    '<span class="text-danger check">Please Enter Numeric Value From 1 to 100</span>');
                $('.hideDiv').hide();
            }


            window.setTimeout(function() {
                // do whatever you want to do
                hideBlockUI('#rebate');
            }, 700);
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
                            $('#is_local').prop( "checked", stakeholderData.is_local );

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

        var validator = $("#rebateForm").validate({
            rules: {
                'rebate_percentage': {
                    required: true,
                },
                'dealer[full_name]': {
                    required: true
                },
                'dealer[father_name]': {
                    required: true
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
        $(document).ready(function() {

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
                showBlockUI('#rebateForm');
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
                            hideBlockUI('#rebateForm');
                        } else {
                            hideBlockUI('#rebateForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#rebateForm');
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
            showBlockUI('#rebateForm');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-cities', ['stateId' => ':stateId']) }}"
                .replace(':stateId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#rebateForm');
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

                            hideBlockUI('#rebateForm');
                        } else {
                            hideBlockUI('#rebateForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#rebateForm');
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

        $("#saveButton").click(function() {
            $("#rebateForm").submit();
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
