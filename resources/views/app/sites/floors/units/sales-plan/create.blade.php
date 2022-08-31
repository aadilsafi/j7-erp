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
                {{ view('app.sites.floors.units.sales-plan.form-fields', ['site' => $site, 'floor' => $floor, 'unit' => $unit, 'additionalCosts' => $additionalCosts]) }}

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
                                                {{ $additionalCost->tree }}</span>
                                        </span>
                                        <span class="d-flex justify-content-between flex-wrap">
                                            <span class="fw-bolder"></span>
                                            <span class="fw-bolder">{{ $additionalCost->site_percentage }}%</span>
                                        </span>
                                    </label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">

                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="sales_plan_validity">Sales Plan Validity</label>
                            <input type="text" id="sales_plan_validity" name="sales_plan_validity"
                                class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                        </div>
                        <hr>
                        <button type="submit" value="save"
                            class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light mb-1">
                            <i data-feather='save'></i>
                            <span id="create_sales_plan_button_span">Save Sales Plan</span>
                        </button>
                        <button type="submit" value="save_print"
                            class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light mb-1">
                            <i data-feather='printer'></i>
                            <span id="save_print_sales_plan_button_span">Save & Print Sales Plan</span>
                        </button>
                        <a href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id), 'unit_id' => encryptParams($unit->id)]) }}"
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
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
@endsection

@section('page-js')
    {{-- <script src="{{ asset('app-assets') }}/pages/create-sales-plan.min.js"></script> --}}
@endsection

@section('custom-js')
    <script>
        window['moment-range'].extendMoment(moment);
        $(document).ready(function() {


            var installmentsRowAction = '';

            $(".touchspin-icon").TouchSpin({
                buttondown_class: "btn btn-primary",
                buttonup_class: "btn btn-primary",
                buttondown_txt: feather.icons["chevron-down"].toSvg(),
                buttonup_txt: feather.icons["chevron-up"].toSvg(),
                min: 0,
                max: 50,
            }).on("touchspin.on.stopupspin", function() {}).on("touchspin.on.stopdownspin", function() {}).on(
                "touchspin.on.stopspin",
                function() {}).on("change", function() {
                var t = $(this);
                $(".bootstrap-touchspin-up, .bootstrap-touchspin-down").removeClass("disabled-max-min");
                0 == t.val() && $(this).siblings().find(".bootstrap-touchspin-down").addClass(
                    "disabled-max-min");
                50 == t.val() && $(this).siblings().find(".bootstrap-touchspin-up").addClass(
                    "disabled-max-min")
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
            });

            $(".flatpickr-basic").flatpickr({
                defaultDate: "today",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d"
            });

            $('#unit_price').on('change', function() {
                let unit_price = parseFloat($(this).val()).toFixed(2);
                let unit_size = parseFloat($('#unit_size').val()).toFixed(2);
                let totalPriceUnit = parseFloat(unit_price * unit_size).toFixed(2);

                $('#total-price-unit').val(totalPriceUnit).trigger('change');
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
                } else {
                    $(`#div-${elementId}`).hide('fast', 'linear', function() {
                        $('div[id^="div-"]:visible input[id^="percentage-"]').trigger('change');
                    });
                }
                // $(`#div-${elementId}`).toggle('fast', 'linear', function() {
                //     $('div[id^="div-"]:visible input[id^="percentage-"]').trigger('change');
                // });

            });

            $('input[id^="percentage-"]').on('change', function() {

                let elementId = $(this).attr('id');
                elementId = elementId.slice(('percentage-').length);

                let unitPriceTotal = parseFloat($('#total-price-unit').val()).toFixed(2);

                let percentage = parseFloat($(`#percentage-${elementId}`).val()).toFixed(2);

                let totalPrice = parseFloat((unitPriceTotal * percentage) / 100).toFixed(2);

                $(`#total-price-${elementId}`).val(totalPrice);
                calculateUnitGrandAmount();
            });

            $('#unit_downpayment_percentage').on('change', function() {
                let unitPrice = parseFloat(($('#unit_rate_total').val()).replace(/,/g, '')).toFixed(2);

                let percentage = parseFloat($(this).val());

                let totalDownPayment = parseFloat((unitPrice * percentage) / 100);

                $('#unit_downpayment_total').val(parseFloat(totalDownPayment).toFixed(2));
            });

            $('#unit_downpayment_percentage').trigger('change');
        });

        function calculateUnitGrandAmount() {
            let grandUnitAmount = 0;

            $('input[id^="total-price-"]').each(function() {

                let elementId = $(this).attr('id');
                elementId = elementId.slice(('total-price-').length);

                if ($(`#div-${elementId}`).is(':visible')) {
                    if ($(this).attr('id') == 'total-price-discount') {
                        grandUnitAmount -= parseFloat($(this).val());
                    } else {
                        grandUnitAmount += parseFloat($(this).val());
                    }
                }
            });

            // $('#unit_rate_total').val(new Intl.NumberFormat().format(parseFloat(grandUnitAmount).toFixed(2)));
            $('#unit_rate_total').val(parseFloat(grandUnitAmount).toFixed(2));
            $('#unit_downpayment_percentage').trigger('change');
        }
        var unchangedData = [];

        function calculateInstallments() {

            showBlockUI('#installments_acard');

            let unitDownPayment = parseFloat($('#unit_downpayment_total').val()).toFixed(2);
            let unit_rate_total = parseFloat($('#unit_rate_total').val()).toFixed(2);

            let installment_amount = parseFloat(Math.abs(unit_rate_total - unitDownPayment));

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
                    $('#installments_table tbody#dynamic_installment_rows').empty();
                    if (response.status) {

                        for (let row of response.data.installments) {
                            InstallmentRows += row.row;
                        }

                        $('#installments_table tbody#dynamic_installment_rows').html(InstallmentRows);
                        InstallmentRows = '';
                        hideBlockUI('#installments_acard');
                    }
                },
                error: function(errors) {
                    console.error(errors);
                    hideBlockUI('#installments_acard');
                }
            });
        }

        function storeUnchangedData(key, field, value) {

            console.log(unchangedData);
            var index = unchangedData.findIndex(function(element) {
                return element.key == key && element.field == field;
            });

            if (index > -1) {
                unchangedData.splice(index, 1);
            }

            if (value.length > 0) {
                unchangedData.push({
                    key: key,
                    field: field,
                    value: value
                });
            }
        }
    </script>
@endsection
