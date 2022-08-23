@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.sales-plans.create', encryptParams($site), encryptParams($floor), encryptParams($unit->id)) }}
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
                    {{ Breadcrumbs::render('sites.floors.units.sales-plans.create', encryptParams($site), encryptParams($floor), encryptParams($unit->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical"
        action="{{ route('sites.floors.units.sales-plans.store', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.sites.floors.units.sales-plan.form-fields') }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>Additional Costs</h3>
                    </div>
                    <div class="card-body">
                        <div class='form-check d-flex flex-column'>
                            <div class="mb-1">
                                <input class='form-check-input' type='checkbox' id='chkRolePermission_' checked />
                                <label class='form-check-label' for='chkRolePermission_'>asd</label>
                            </div>
                            <div class="mb-1">
                                <input class='form-check-input' type='checkbox' id='chkRolePermission_' checked />
                                <label class='form-check-label' for='chkRolePermission_'>sss</label>
                            </div>
                            <div class="mb-1">
                                <input class='form-check-input' type='checkbox' id='chkRolePermission_' checked />
                                <label class='form-check-label' for='chkRolePermission_'>dadad</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
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
                        <a href="{{ route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams($site), 'floor_id' => encryptParams($floor), 'unit_id' => encryptParams($unit->id)]) }}"
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
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {

            var installmentsRowAction = '';

            $(".touchspin-icon").TouchSpin({
                buttondown_class: "btn btn-primary",
                buttonup_class: "btn btn-primary",
                buttondown_txt: feather.icons["chevron-down"].toSvg(),
                buttonup_txt: feather.icons["chevron-up"].toSvg(),
                min: 1,
                max: 50,
            }).on("touchspin.on.stopupspin", function() {
                installmentsRowAction = "stopupspin";
                console.log(installmentsRowAction);

            }).on("touchspin.on.stopdownspin", function() {
                installmentsRowAction = "stopdownspin";
                console.log(installmentsRowAction);
            }).on("touchspin.on.stopspin", function() {
                var t = $(this);
                if (installmentsRowAction == "stopupspin") {
                    // debugger
                    addInstallmentsRows(t.val());
                } else if (installmentsRowAction == "stopdownspin") {
                    addInstallmentsRows(t.val());
                }
            }).on("change", function() {
                var t = $(this);
                $(".bootstrap-touchspin-up, .bootstrap-touchspin-down").removeClass("disabled-max-min");
                1 == t.val() && $(this).siblings().find(".bootstrap-touchspin-down").addClass(
                    "disabled-max-min");
                50 == t.val() && $(this).siblings().find(".bootstrap-touchspin-up").addClass(
                    "disabled-max-min")
            });

            $('.custom-option-item-check').on('change', function() {
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
                minDate: "today",
            });

        });

        function addInstallmentsRows(num) {
            if (num > 0) {
                var row = "";
                for (let index = 1; index < num; index++) {
                    row += `
                    <tr id="row_${index}">
                        <th scope="row">${index + 1}</th>
                        <td>
                            <div class="">
                                <input type="text" id="installment_date_${index}"
                                    name="installments[installments][${index}][date]"
                                    class="form-control" placeholder="YYYY-MM-DD" />
                            </div>
                        </td>
                        <td>
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-lg"
                                    id="installment_detail_${index}" name="installments[installments][${index}][detail]"
                                    placeholder="Detail" />
                            </div>
                        </td>
                        <td>
                            <div class="position-relative">
                                <input type="number" class="form-control form-control-lg"
                                    id="installment_amount_${index}" name="installments[installments][${index}][amount]"
                                    placeholder="Amount" />
                            </div>
                        </td>
                        <td>
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-lg"
                                    id="installment_remark_${index}" name="installments[installments][${num}][remark]"
                                    placeholder="Detail" />
                            </div>
                        </td>
                    </tr>`;
                }


                $('#installments_table #dynamic_installment_rows').html(row);
            }
            installmentsRowAction = '';
        }

        function installmentsRemoveRow() {
            $('#installments_table #dynamic_installment_rows tr:last').remove();
        }

        function getDatesInRange(startDate, endDate) {
            const date = new Date(startDate.getTime());

            const dates = [];

            while (date <= endDate) {
                dates.push(new Date(date));
                date.setDate(date.getDate() + 1);
            }

            return dates;
        }
        function testdateranger() {

            const d1 = new Date('2022-01-18');
            const d2 = new Date('2022-01-24');

            console.log(getDatesInRange(d1, d2));
        }
    </script>
@endsection


{{-- if (installmentsRowAction == "stopupspin") {
    // var installment = $(this).val();
    // var total = $("#total").val();
    // var installment_value = (total / installment).toFixed(2);
    // $("#installment_value").val(installment_value);
} else if (installmentsRowAction == "stopdownspin") {
    // var installment = $(this).val();
    // var total = $("#total").val();
    // var installment_value = (total / installment).toFixed(2);
    // $("#installment_value").val(installment_value);
} --}}
