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
        $(document).ready(function() {

            var input = document.querySelector("#stackholder_contact");
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

        });
    </script>
    <script type="text/javascript">
        $('#companyForm').hide();

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
                            $('#customer_phone').val(response.stakeholder.contact);
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
                                let facing_value = response.salesPlan.discount_percentage * response.salesPlan
                                    .total_price;
                                $('#td_unit_facing_charges_value').html(facing_value.toLocaleString())
                            } else {
                                $('#td_unit_facing_charges_value').html(0);
                            }

                            $('#td_unit_discount_value').html(parseFloat(response.salesPlan.discount_total)
                                .toLocaleString());
                            $('#td_unit_total_value').html(parseFloat(response.salesPlan.total_price)
                                .toLocaleString());
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

            if ( $.isNumeric(rebate_percentage_value) &&  rebate_percentage_value > 0  &&  rebate_percentage_value <= 100) {
                let rebate_percentage = parseFloat($('#rebate_percentage').val());

                rebate_percentage = (rebate_percentage > 100) ? 100 : rebate_percentage;

                rebate_percentage = (rebate_percentage < 0) ? 0 : rebate_percentage;

                let unit_total = parseFloat($('#unit_total').val());

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
                $('#rebate_percentage').after('<span class="text-danger check">Please Enter Numeric Value From 1 to 100</span>');
                $('.hideDiv').hide();
            }


            window.setTimeout(function() {
                // do whatever you want to do
                hideBlockUI('#rebate');
            }, 700);
        });

        var e = $("#dealer");
        e.wrap('<div class="position-relative"></div>');
        e.select2({
            dropdownAutoWidth: !0,
            dropdownParent: e.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).on("change", function(e) {

            let dealer = parseInt($(this).val());
            showBlockUI('#rebateForm');
            let stakeholderData = {
                id: 0,
                full_name: '',
                father_name: '',
                occupation: '',
                designation: '',
                cnic: '',
                ntn: '',
                contact: '',
                address: '',
            };

            $.ajax({
                url: "{{ route('sites.stakeholders.ajax-get-by-id', ['site_id' => encryptParams($site_id), 'id' => ':id']) }}"
                    .replace(':id', dealer),
                type: 'GET',
                data: {},
                success: function(response) {

                    if (response.status) {
                        if (response.data) {
                            stakeholderData = response.data[0];
                            isDisable = dealer == 0 ? false : true;
                            country_id.val(stakeholderData.country_id);
                            country_id.trigger('change');
                            // $('#stackholder_id').val(stakeholderData.id);
                            $('#stackholder_full_name').val(stakeholderData.full_name).attr(
                                'disabled',
                                isDisable);
                            $('#stackholder_father_name').val(stakeholderData.father_name).attr(
                                'disabled', isDisable);
                            $('#stackholder_occupation').val(stakeholderData.occupation).attr(
                                'disabled', isDisable);
                            $('#stackholder_designation').val(stakeholderData.designation).attr(
                                'disabled', isDisable);

                            $('#stackholder_cnic').val(format('XXXXX-XXXXXXX-X', stakeholderData
                                    .cnic))
                                .attr('disabled', isDisable);
                            $('#stackholder_contact').val(stakeholderData.contact).attr('disabled',
                                isDisable);
                            $('#stackholder_ntn').val(stakeholderData.ntn).attr('disabled',
                                isDisable);
                            if ((stakeholderData.comments != null)) {
                                $('#stackholder_comments').val(stakeholderData.comments).attr(
                                    'disabled', isDisable);
                            }
                            $('#optional_contact').val(stakeholderData.optional_contact).attr(
                                'disabled', isDisable);
                            $('#mailing_address').val(stakeholderData.mailing_address).attr(
                                'disabled', isDisable);
                            $('#stackholder_address').text(stakeholderData.address).attr(
                                'disabled', isDisable);
                            $('#stackholder_email').val(stakeholderData.email).attr(
                                'disabled', isDisable);
                            $('#stackholder_optional_email').val(stakeholderData
                                .optional_email).attr(
                                'disabled', isDisable);
                            $('#nationality').val(stakeholderData.nationality).attr(
                                'disabled', isDisable);

                            selected_state_id = stakeholderData.state_id;
                            selected_city_id = stakeholderData.city_id;

                            if (stakeholderData.stakeholder_as == 'c') {
                                $('#company_name').val(stakeholderData.full_name).attr(
                                    'disabled', isDisable);
                                $('#industry').val(stakeholderData.occupation).attr(
                                    'disabled', isDisable);
                                $('#registration').val(stakeholderData.cnic).attr(
                                    'disabled', isDisable);
                                $('#ntn').val(stakeholderData.ntn).attr(
                                    'disabled', isDisable);
                                $('#companyForm').show();
                                $('#individualForm').hide();

                            }
                            if (stakeholderData.stakeholder_as == 'i') {

                                $('#companyForm').hide();
                                $('#individualForm').show();

                            }
                            // var countryDetails = JSON.parse(stakeholderData.countryDetails);

                            // if (countryDetails == null) {
                            //     intl.setCountry('pk');
                            // } else {
                            //     intl.setCountry(countryDetails['iso2']);
                            // }

                            // $('#countryDetails').val(JSON.stringify(intl
                            //     .getSelectedCountryData()))

                            // var OptionalCountryDetails = JSON.parse(stakeholderData
                            //     .OptionalCountryDetails);
                            // if (OptionalCountryDetails == null) {
                            //     intlOptional.setCountry('pk');
                            // } else {
                            //     intlOptional.setCountry(OptionalCountryDetails['iso2']);
                            // }

                            // $('#OptionalCountryDetails').val(JSON.stringify(intlOptional
                            //     .getSelectedCountryData()))

                        }
                    }

                    hideBlockUI('#rebateForm');
                },
                error: function(errors) {
                    console.error(errors);
                    hideBlockUI('#rebateForm');
                }

            });


            // if (dealer === "0") {
            //     $('#div_new_dealer').show();
            // } else {
            //     $('#div_new_dealer').hide();
            // }
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
                // 'dealer[occupation]': {
                //     required: true
                // },
                // 'dealer[designation]': {
                //     required: true
                // },
                'dealer[contact]': {
                    required: true,
                    digits: true,
                },
                'dealer[cnic]': {
                    required: true,
                    // digits: true,
                    // maxlength: 13,
                    // minlength: 13
                },
                'dealer_id': {
                    required: true,
                },
                'dealer[address]': {
                    required: true,
                },
                'dealer[mailing_address]': {
                    required: true,
                },
                'deal_type': {
                    required: true,
                },

            },
            messages: {
                'dealer[cnic]': {
                    maxlength: "Cnic can't be greater then {0} digits without dashes",
                    minlength: "Cnic can't be less then {0} digits without dashes",
                },
                'dealer_id' : {
                    min: "Select Dealer."
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
