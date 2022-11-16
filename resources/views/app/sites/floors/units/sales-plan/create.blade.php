@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.sales-plans.create', encryptParams($site->id), encryptParams($floor->id), encryptParams($unit->id)) }}
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
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Sales Plan</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.sales-plans.create', encryptParams($site->id), encryptParams($floor->id), encryptParams($unit->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" id="create-sales-plan-form"
        action="{{ route('sites.floors.units.sales-plans.store', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id), 'unit_id' => encryptParams($unit->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.sites.floors.units.sales-plan.form-fields', [
                    'site' => $site,
                    'floor' => $floor,
                    'unit' => $unit,
                    'additionalCosts' => $additionalCosts,
                    'stakeholders' => $stakeholders,
                    'stakeholderTypes' => $stakeholderTypes,
                    'leadSources' => $leadSources,
                    'user' => $user,
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
                                        <label class="form-label fs-5" for="created_date">Creation Date</label>
                                        <input id="created_date" type="date" required placeholder="YYYY-MM-DD" name="created_date"
                                            class="form-control form-control-md" />
                                    </div>
                                    <hr>
                                    <div class="d-block mb-1">
                                        <label class="form-label fs-5" for="sales_plan_validity">Sales Plan Validity</label>
                                        <input type="text" id="sales_plan_validity" name="sales_plan_validity"
                                            class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                    </div>
                                    <hr>
                                    <button type="submit" value="save"
                                        class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                        <i data-feather='save'></i>
                                        <span id="create_sales_plan_button_span">Save Sales Plan</span>
                                    </button>
                                    {{-- <button type="submit" value="save_print"
                                        class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light mb-1">
                                        <i data-feather='printer'></i>
                                        <span id="save_print_sales_plan_button_span">Save & Print Sales Plan</span>
                                    </button> --}}
                                    <a href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id), 'unit_id' => encryptParams($unit->id)]) }}"
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
@endsection

@section('custom-js')
    <script>
        $("#created_date").flatpickr({
            defaultDate: "today",
            // minDate: "today",
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
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

                            // $('#stackholder_id').val(stakeholderData.id);
                            $('#stackholder_full_name').val(stakeholderData.full_name);
                            $('#stackholder_father_name').val(stakeholderData.father_name);
                            $('#stackholder_occupation').val(stakeholderData.occupation);
                            $('#stackholder_designation').val(stakeholderData.designation);
                            $('#stackholder_cnic').val(stakeholderData.cnic);
                            $('#stackholder_ntn').val(stakeholderData.ntn);
                            $('#stackholder_contact').val(stakeholderData.contact);
                            $('#stackholder_address').text(stakeholderData.address);
                            $('#stackholder_comments').text(stakeholderData.comments);

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

            $("#sales_plan_validity").flatpickr({
                defaultDate: "{{ now()->addDays($site->siteConfiguration->salesplan_validity_days) }}",
                minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $("#installments_start_date").flatpickr({
                defaultDate: "{{ now()->addDays($site->siteConfiguration->salesplan_installment_days) }}",
                minDate: "today",
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
            };

            $.ajax({
                url: '{{ route('sites.floors.units.sales-plans.ajax-generate-installments', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id), 'unit_id' => encryptParams($unit->id)]) }}',
                type: 'GET',
                data: data,
                success: function(response) {
                    let InstallmentRows = '';
                    if (response.status) {
                        $('#installments_table tbody#dynamic_installment_rows').empty();

                        for (let row of response.data.installments) {
                            InstallmentRows += row.row;
                        }

                        $('#installments_table tbody#dynamic_installment_rows').html(InstallmentRows);
                        InstallmentRows = '';

                        showBlockUI('#additional_expense_card');
                        lastInstallemtDate = response.data.installments[response.data.installments.length - 2]
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
            // console.log(action);
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

            console.log(dataArrays);

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
                    digits: true
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
                    required: true
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
                'stackholder[stackholder_id]': {
                    required: true
                },
                'stackholder[full_name]': {
                    required: true
                },
                'stackholder[cnic]': {
                    // minlength: 13,
                    // maxlength: 13,
                    required: true,
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
            wrapper: "div",
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
