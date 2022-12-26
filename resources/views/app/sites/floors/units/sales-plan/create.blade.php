@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.sales-plans.create', encryptParams($site->id), encryptParams(1), encryptParams(1)) }}
@endsection

@section('page-title', 'Create Sales Plan')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/extensions/nouislider.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/plugins/extensions/ext-component-sliders.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/core/colors/palette-noui.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/plugins/forms/pickers/form-flat-pickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css" />

@endsection

@section('custom-css')
    <style>
        .noUi-tooltip {
            font-size: 20px;
            color: #7367f0;
        }

        .noUi-value {
            font-size: 15px !important;
            color: #7367f0 !important;
        }

        #stakeholderNextOfKin {
            display: none;
        }

        #main-div {
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

        .input-group .intl-tel-input .form-control {
            border-top-left-radius: 4px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 0;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Sales Plan</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.sales-plans.create', encryptParams($site->id), encryptParams(1), encryptParams(1)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" id="create-sales-plan-form"
        action="{{ route('sites.sales_plan.store', ['site_id' => encryptParams($site->id)]) }}" method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.sites.floors.units.sales-plan.form-fields', [
                    'site' => $site,
                    'units' => $unit,
                    'additionalCosts' => $additionalCosts,
                    'stakeholders' => $stakeholders,
                    'stakeholderTypes' => $stakeholderTypes,
                    'leadSources' => $leadSources,
                    'user' => $user,
                    'country' => $country,
                    'customFields' => $customFields,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3 class="p-0">Additional Costs</h3>
                    </div>
                    <div class="card-body">
                        <div class="row custom-options-checkable g-1">

                            @foreach ($additionalCosts as $key => $additionalCost)
                                @continue($additionalCost->has_child)
                                <div class="col-md-12">
                                    <input class="custom-option-item-check additional-cost-checkbox" type="checkbox"
                                        name="additionalCostCheckbox"
                                        id="checkbox-{{ $additionalCost->slug }}-{{ $key }}" />
                                    <label class="custom-option-item p-1"
                                        for="checkbox-{{ $additionalCost->slug }}-{{ $key }}">
                                        <span class="d-flex justify-content-between flex-wrap">
                                            <span class="fw-bolder">{{ $key }}.
                                                {{ $additionalCost->name }}</span>
                                        </span>
                                        <span class="d-flex justify-content-between flex-wrap">
                                            <span class="fw-bolder"></span>
                                            @if ($additionalCost->applicable_on_unit)
                                                <span class="fw-bolder">{{ $additionalCost->unit_percentage }} %</span>
                                            @else
                                                <span class="fw-bolder">0 %</span>
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    <div class="d-block mb-1">
                                        <label class="form-label fs-5" for="created_date">Creation Date<span
                                                class="text-danger">*</span></label>
                                        <input id="created_date" type="date" required placeholder="YYYY-MM-DD"
                                            name="created_date" class="form-control form-control-md" />
                                    </div>
                                    <hr>
                                    <div class="d-block mb-1">
                                        <label class="form-label fs-5" for="sales_plan_validity">Sales Plan Validity<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="sales_plan_validity" name="sales_plan_validity"
                                            class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                    </div>
                                    <hr>
                                    @can('sites.sales_plan.store')
                                        <button type="submit" value="save" disabled id="savebtn"
                                            class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                            <i data-feather='save'></i>
                                            <span id="create_sales_plan_button_span">Save Sales Plan</span>
                                        </button>
                                    @endcan

                                    {{-- <button type="submit" value="save_print"
                                        class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light mb-1">
                                        <i data-feather='printer'></i>
                                        <span id="save_print_sales_plan_button_span">Save & Print Sales Plan</span>
                                    </button> --}}
                                    <a href="{{ url()->previous() }}"
                                        class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                        <i data-feather='x'></i>
                                        {{ __('lang.commons.cancel') }}
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="alert alert-warning alert-dismissible m-0 fade show" role="alert">
                        <h4 class="alert-heading"><i data-feather='alert-triangle' class="me-50"></i>Warning!</h4>
                        <div class="alert-body">
                            Any change in <strong>PRIMARY DATA</strong> and <strong>INSTALLMENT DETAILS</strong> will effect
                            the installments table.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
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
        $('#companyForm').hide();
        $("#stakeholder_as").val('i');

        $("#stakeholder_as").trigger('change');

        window['moment-range'].extendMoment(moment);

        var t = setTimeout(calculateInstallments, 1000),
            dataArrays = {
                ArrAmounts: [],
                ArrRemarks: [],
                ArrDueDates: [],
            },
            unchangedData = [],
            lastInstallemtDate = 'today';

        $(document).ready(function() {

            var t = $("#unit_id");
            t.wrap('<div class="position-relative"></div>');
            t.select2({
                dropdownAutoWidth: !0,
                dropdownParent: t.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).change(function() {
                showBlockUI('#create-sales-plan-form');

                if ($(this).val() == 0) {
                    $('#main-div').hide();
                    $('#savebtn').attr('disabled', true)
                } else {
                    $.ajax({
                        url: "{{ route('ajax-get-unit') }}",
                        type: 'POST',
                        data: {
                            unit_id: $(this).val(),
                        },
                        success: function(response) {
                            if (response.status) {
                                if (response.data) {
                                    unitData = response.data[0];
                                    floorData = response.data[1];
                                }

                                $('#unit_no').val(unitData.floor_unit_number);
                                $('#floor_no').val(floorData.short_label);
                                $('#unit_type').val(unitData.type.name);
                                $('#unit_size').val(unitData.gross_area);
                                $('#unit_price').val(unitData.price_sqft);
                                $('#floor_id').val(floorData.id);

                                $('#unit_price').trigger('change');
                                $('#unit_downpayment_percentage').trigger('change');
                                updateTable();
                                $('#savebtn').attr('disabled', false)

                            }

                        },
                        error: function(errors) {
                            console.error(errors);

                        }
                    });
                    $('#main-div').show();

                }
                hideBlockUI('#create-sales-plan-form');

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
                    url: "{{ route('sites.stakeholders.ajax-get-by-id', ['site_id' => encryptParams($site->id), 'id' => ':id']) }}"
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

            var e = $("#sales_source_lead_source");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            }).on("change", function(e) {
                let newLeadSource = $(this).val();

                if (newLeadSource === "0") {
                    $('#div_sales_source_lead_source').show();
                } else {
                    $('#div_sales_source_lead_source').hide();
                }
            });

            var installmentsRowAction = '';

            $(".touchspin-icon").TouchSpin({
                buttondown_class: "btn btn-primary",
                buttonup_class: "btn btn-primary",
                buttondown_txt: feather.icons["chevron-down"].toSvg(),
                buttonup_txt: feather.icons["chevron-up"].toSvg(),
                min: 1,
                max: 50,
            }).on("touchspin.on.stopspin", function() {
                updateTable();
            }).on("change", function() {
                var t = $(this);
                $(".bootstrap-touchspin-up, .bootstrap-touchspin-down").removeClass("disabled-max-min");
                1 == t.val() && $(this).siblings().find(".bootstrap-touchspin-down").addClass(
                    "disabled-max-min");
                50 == t.val() && $(this).siblings().find(".bootstrap-touchspin-up").addClass(
                    "disabled-max-min");
            });

            $('.installment_type_radio').on('change', function() {
                var ele = $(this);

                switch (ele.val()) {
                    case 'quarterly':
                        $('#how_many').text('Quaters');
                        break;

                    case 'monthly':
                        $('#how_many').text('Months');
                        break;

                    default:
                        break;
                }

                updateTable();
            });

            $("#created_date").flatpickr({
                defaultDate: "today",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    installmentDate.set("minDate", dateStr);
                    installmentDate.setDate(new Date(dateStr).fp_incr(
                        {{ $site->siteConfiguration->salesplan_installment_days }}));

                    validityDate.set('minDate', new Date(dateStr).fp_incr(
                        {{ $site->siteConfiguration->salesplan_validity_days }}));

                    validityDate.setDate(new Date(dateStr).fp_incr(
                        {{ $site->siteConfiguration->salesplan_validity_days }}));

                    dataArrays.ArrDueDates = [];
                    mergeArrays();
                    updateTable();
                },
            });

            var validityDate = $("#sales_plan_validity").flatpickr({
                defaultDate: "{{ now()->addDays($site->siteConfiguration->salesplan_validity_days) }}",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            var installmentDate = $("#installments_start_date").flatpickr({
                defaultDate: "{{ now()->addDays($site->siteConfiguration->salesplan_installment_days) }}",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    dataArrays.ArrDueDates = [];
                    mergeArrays();
                    updateTable();
                },
            });

            $('#unit_price').on('change', function() {
                let unit_price = conventToFloatNumber($(this).val());
                let unit_size = conventToFloatNumber($('#unit_size').val());
                let totalPriceUnit = conventToFloatNumber(unit_price * unit_size);

                $('#total-price-unit').val(numberFormat(totalPriceUnit)).trigger('change');
            });

            $('#total-price-unit').on('change', function() {
                $('div[id^="div-"]:visible input[id^="percentage-"]').trigger('change');
                $('#percentage-discount').trigger('change');
            });

            $('.additional-cost-checkbox').on('change', function() {
                let elementId = $(this).attr('id');
                elementId = elementId.slice(('checkbox-').length);

                if ($(this).is(':checked')) {
                    $(`#div-${elementId}`).show('fast', 'linear', function() {
                        $('div[id^="div-"]:visible input[id^="percentage-"]').trigger('change');
                    });
                    $(`#status-${elementId}`).val(true);
                } else {
                    $(`#div-${elementId}`).hide('fast', 'linear', function() {
                        $('div[id^="div-"]:visible input[id^="percentage-"]').trigger('change');
                    });
                    $(`#status-${elementId}`).val(false);
                }
            });

            $('input[id^="percentage-"]').on('change', function() {

                let elementId = $(this).attr('id');
                elementId = elementId.slice(('percentage-').length);
                let unitPriceTotal = conventToFloatNumber($('#total-price-unit').val()).toFixed(2);

                let percentage = conventToFloatNumber($(`#percentage-${elementId}`).val()).toFixed(2);

                let totalPrice = conventToFloatNumber((unitPriceTotal * percentage) / 100).toFixed(2);

                $(`#total-price-${elementId}`).val(numberFormat(totalPrice));
                calculateUnitGrandAmount();
            });

            $('#unit_downpayment_percentage').on('change', function() {
                let unitPrice = conventToFloatNumber($('#unit_rate_total').val()).toFixed(2);

                let percentage = conventToFloatNumber($(this).val());

                let totalDownPayment = conventToFloatNumber((unitPrice * percentage) / 100);

                $('#unit_downpayment_total').val(numberFormat(conventToFloatNumber(totalDownPayment)
                    .toFixed(2)));

                updateTable();
            });

            $('#unit_downpayment_percentage').trigger('change');

            $(".expenses-list").repeater({
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

            $('#add-new-expense').on('click', function() {
                $(".expense_due_date").flatpickr({
                    defaultDate: lastInstallemtDate,
                    minDate: lastInstallemtDate,
                    // altInput: !0,
                    dateFormat: "Y-m-d",
                    onChange: function(selectedDates, dateStr, instance) {
                        if (dateStr.length < 1) {
                            $('#add-new-expense').trigger('click');
                        }
                    }
                });

                $('input[name^="expenses"]').map(function() {
                    $(this).rules('add', {
                        required: true,
                    });
                });
            });

        });

        function calculateUnitGrandAmount() {
            let grandUnitAmount = 0;

            $('input[id^="total-price-"]').each(function() {

                let elementId = $(this).attr('id');
                elementId = elementId.slice(('total-price-').length);

                if ($(`#div-${elementId}`).is(':visible')) {
                    if ($(this).attr('id') == 'total-price-discount') {
                        grandUnitAmount -= conventToFloatNumber($(this).val());
                    } else {
                        grandUnitAmount += conventToFloatNumber($(this).val());
                    }
                }
            });

            $('#unit_rate_total').val(numberFormat(parseFloat(grandUnitAmount).toFixed(2)));
            $('#unit_downpayment_percentage').trigger('change');
        }

        function calculateInstallments(action = '') {

            showBlockUI('#installments_acard');

            let unitDownPayment = conventToFloatNumber($('#unit_downpayment_total').val()).toFixed(2);
            let unit_rate_total = conventToFloatNumber($('#unit_rate_total').val()).toFixed(2);

            let installment_amount = conventToFloatNumber(Math.abs(unit_rate_total - unitDownPayment));

            let installments_start_date = $('#installments_start_date').val();
            // startDate: '2021-12-15',
            // installment_amount: 10708425,
            // length: 16,

            let data = {
                length: parseInt($(".touchspin-icon").val()),
                startDate: installments_start_date,
                installment_amount: installment_amount,
                rangeCount: $(".custom-option-item-check:checked").val() == 'quarterly' ? 90 : 30,
                rangeBy: 'days',
                unchangedData: unchangedData,
                unit_id: $('#unit_id').val()
            };

            if ($('#unit_id').val() > 0) {
                $.ajax({
                    url: "{{ route('sites.sales_plan.ajax-generate-installments', ['site_id' => encryptParams($site->id)]) }}",

                    type: 'GET',
                    data: data,
                    success: function(response) {
                        let InstallmentRows = '';
                        if (response.status) {
                            $('#installments_table tbody#dynamic_installment_rows').empty();
                            // console.log(response.data.installments)
                            for (let row of response.data.installments) {
                                InstallmentRows += row.row;
                            }

                            $('#installments_table tbody#dynamic_installment_rows').html(InstallmentRows);
                            InstallmentRows = '';

                            showBlockUI('#additional_expense_card');
                            lastInstallemtDate = response.data.installments[response.data.installments.length -
                                    2]
                                .date;

                            flatpickr($(".expense_due_date"), {
                                defaultDate: lastInstallemtDate,
                                minDate: lastInstallemtDate,
                            });
                            hideBlockUI('#additional_expense_card');

                            $('#base-installment').val(response.data.baseInstallmentTotal);
                        } else {
                            if (response.message.error == 'invalid_amout') {
                                Toast.fire({
                                    icon: 'error',
                                    title: "Invalid Amount"
                                });
                            }
                        }
                        hideBlockUI('#installments_acard');
                    },
                    error: function(errors) {
                        console.error(errors);
                        hideBlockUI('#installments_acard');
                    }
                });
            }


        }

        function storeUnchangedData(key, field, value, Arr) {
            let index = dataArrays[Arr].findIndex(function(element) {
                return element.key == key && element.field == field;
            });

            if (index > -1) {
                const newData = {
                    key: key,
                    field: field,
                    value: value,
                };

                if (value > 0 || value.length > 0) {
                    dataArrays[Arr].splice(index, 1, newData);
                } else {
                    dataArrays[Arr].splice(index, 1);
                }
            }

            if (index < 0 && (value > 0 || value.length > 0)) {
                dataArrays[Arr].push({
                    key: key,
                    field: field,
                    value: value,
                });
            }

            dataArrays[Arr].sort((a, b) => conventToIntNumber(a.key) - conventToIntNumber(b.key));

            if (field === 'due_date') {
                dataArrays[Arr] = dataArrays[Arr].filter(function(element) {
                    return element.key <= key;
                });
            }

            mergeArrays();
        }

        function mergeArrays() {
            let ArrAmounts = dataArrays.ArrAmounts;
            let ArrRemarks = dataArrays.ArrRemarks;
            let ArrDueDates = dataArrays.ArrDueDates;

            unchangedData = [...ArrAmounts, ...ArrRemarks, ...ArrDueDates];

            // console.log(dataArrays);

            updateTable();
        }

        $('#unit_price, input[id^="percentage-"], #unit_downpayment_percentage').on('focusout',
            function() {
                updateTable();
            }
        );

        function updateTable() {
            clearTimeout(t);
            t = setTimeout(calculateInstallments, 1500, 1);
        }

        function conventToIntNumber(number) {
            return parseInt(number.toString().replace(/,/g, ''));
        }

        function conventToFloatNumber(number) {
            return parseFloat(number.toString().replace(/,/g, ''));
        }


        var validator = $("#create-sales-plan-form").validate({
            // debug: true,
            rules: {
                'unit_id': {
                    required: true
                },
                // 1. PRIMARY DATA
                'unit[no]': {
                    required: true
                },
                'unit[floor_no]': {
                    required: true
                },
                'unit[type]': {
                    required: true
                },
                'unit[size]': {
                    required: true,
                },
                'unit[price][unit]': {
                    required: true,
                    digits: true
                },
                'unit[price][total]': {
                    required: true,
                },

                //// Unit Discount
                'unit[discount][percentage]': {
                    required: true,
                },

                //// Unit Grand Total
                'unit[grand_total]': {
                    required: true!= 'i' || $("#stakeholder_as").val() != 'c'
                },

                //// Unit Down Payment
                'unit[downpayment][percentage]': {
                    required: true
                },
                'unit[downpayment][total]': {
                    required: true
                },

                // 2. INSTALLMENT DETAILS
                'installments[types][type]': {
                    required: true
                },
                'installments[types][value]': {
                    required: true,
                    min: 1,
                    digits: true
                },
                'installments[start_date]': {
                    required: true
                },

                // 3. STAKEHOLDER DATA (LEAD'S DATA)
                'stakeholder_as': {
                    required: function() {
                        return $("#stakeholder_as").val() == 0;
                    }
                },
                'company[company_name]': {
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
                'individual[mobile_contact]': {
                    required: function() {
                        return $("#stakeholder_as").val() == 'i';
                    },
                },

                // 4. SALES SOURCE
                'sales_source[sales_type]': {
                    required: true
                },
                'sales_source[new]': {
                    required: function(element) {
                        return $("#sales_source_lead_source").val() == 0;
                    }
                },
            },
            // validClass: "is-valid",
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            // wrapper: "div",
            submitHandler: function(form) {
                form.submit();
            }
        });

        // validator.resetForm();
        // validator.showErrors({
        //     "firstname": "I know that your firstname is Pete, Pete!"
        // });
    </script>
@endsection
