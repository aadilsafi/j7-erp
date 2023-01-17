@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{-- {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.sales-plans.create', encryptParams($site->id), encryptParams($floor->id), encryptParams($unit->id)) }} --}}
@endsection

@section('page-title', 'Payment Plan Preview')

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
                <h2 class="content-header-title float-start mb-0">Payment Plan Preview</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.sales-plans.initail-sales-plan', $site->id, encryptParams(1), encryptParams(1), encryptParams(1)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" id="create-sales-plan-form">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

                <div class="card m-0 mb-1" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row w-100">
                                        <div class="col-10">
                                            <h3>1. PRIMARY DATA</h3>
                                        </div>
                                        <div class="col-2 text-center">
                                            <a href="javascript:void(0);"
                                                class="btn btn-lg btn-relief-outline-primary waves-effect waves-float waves-light ms-3"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Print Sales Plan"
                                                onclick="printSalesPlanTemplate('{{ encryptParams($site->siteConfiguration->salesplan_default_payment_plan_template) }}','{{ encryptParams($salePlan->id) }}');">
                                                <i class="bi bi-printer" style="font-size: 1.1rem" class="m-10"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col-10">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                                                    <label class="form-label fs-5" for="unit_no">Unit No</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="unit_no" name="unit[no]" placeholder="Unit No"
                                                        value="{{ $salePlan->unit->floor_unit_number }}" readonly />
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                                                    <label class="form-label fs-5" for="floor_no">Floor No</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="floor_no" name="unit[floor_no]" placeholder="Floor No"
                                                        value="{{ $salePlan->unit->floor->short_label }}" readonly />
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                                                    <label class="form-label fs-5" for="unit_type">Unit Type</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="unit_type" name="unit[type]" placeholder="Unit Type"
                                                        value="{{ $salePlan->unit->type->name }}" readonly />
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative mb-1">
                                                    <label class="form-label fs-5" for="unit_size">Unit Size(sq.ft)</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="unit_size" name="unit[size]" placeholder="Unit Size(sq.ft)"
                                                        value="{{ number_format($salePlan->unit->gross_area, 2) }}"
                                                        readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-center">
                                            <div class="mt-2">
                                                <img width="120px" height="120px" src="{{ $qrCodeimg }}"
                                                    alt="qr code">
                                            </div>

                                        </div>
                                    </div>


                                    {{-- PRICING --}}
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <div class="card m-0"
                                                style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                                                <div class="card-header">
                                                    <h3>PRICING</h3>
                                                </div>

                                                <div class="card-body">
                                                    {{-- Unit Rate Row --}}
                                                    <div class="row mb-1" id="div-unit">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <label class="form-label fs-5" for="unit_price">Unit
                                                                Price (Rs)</label>
                                                            <input type="text" min="0"
                                                                class="form-control form-control-lg" id="unit_price"
                                                                name="unit[price][unit]" placeholder="Unit Price" readonly
                                                                value="{{ number_format($salePlan->unit_price, 2) }}" />
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <label class="form-label fs-5" for="total-price-unit">Amount
                                                                (Rs)</label>
                                                            <input type="text" class="form-control form-control-lg"
                                                                readonly id="total-price-unit" name="unit[price][total]"
                                                                placeholder="Amount"
                                                                value="{{ number_format($salePlan->unit_price * $salePlan->unit->gross_area, 2) }}" />
                                                        </div>
                                                    </div>

                                                    <div id="div_additional_cost">
                                                        {{-- Additional Cost Rows --}}
                                                        @foreach ($additional_costs as $key => $additionalCost)
                                                            @php
                                                                $additionalCostPercentage = $additionalCost->applicable_on_unit ? $additionalCost->unit_percentage : 0;
                                                                
                                                                $additionalCostTotalAmount = (1 * $additionalCostPercentage) / 100;
                                                            @endphp

                                                            <div class="row mb-1"
                                                                id="div-{{ $additionalCost->slug }}-{{ $key }}">



                                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                                    <label class="form-label fs-5"
                                                                        for="price-{{ $additionalCost->slug }}-{{ $key }}">{{ $additionalCost->name }}
                                                                        (%)
                                                                    </label>

                                                                    <input readonly type="number" min="0"
                                                                        max="100" step="0.1"
                                                                        class="form-control form-control-lg additional-cost-percentage"
                                                                        id="percentage-{{ $additionalCost->slug }}-{{ $key }}"
                                                                        name="unit[additional_cost][{{ $additionalCost->slug }}][percentage]"
                                                                        placeholder="{{ $additionalCost->name }}"
                                                                        value="{{ number_format($additionalCost->pivot->percentage, 2) }}" />

                                                                </div>

                                                                <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                                    <label class="form-label fs-5"
                                                                        for="total-price-{{ $additionalCost->slug }}-{{ $key }}">Amount
                                                                        (Rs)</label>

                                                                    <input type="text" readonly
                                                                        class="form-control form-control-lg additional-cost-total-price"
                                                                        id="total-price-{{ $additionalCost->slug }}-{{ $key }}"
                                                                        name="unit[additional_cost][{{ $additionalCost->slug }}][total]"
                                                                        readonly placeholder="Amount"
                                                                        value="{{ number_format($additionalCost->pivot->amount, 2) }}" />
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>

                                                    {{-- Discount Row --}}
                                                    <div class="row mb-1" id="div-discount">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <label class="form-label fs-5"
                                                                for="percentage-discount">Discount
                                                                (%)</label>
                                                            <input readonly type="text"
                                                                class="form-control form-control-lg"
                                                                id="percentage-discount" name="unit[discount][percentage]"
                                                                placeholder="Discount %"
                                                                value="{{ number_format($salePlan->discount_percentage, 2) }}" />
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <label class="form-label fs-5"
                                                                for="total-price-discount">Amount
                                                                (Rs)</label>
                                                            <input type="text" class="form-control form-control-lg"
                                                                readonly id="total-price-discount"
                                                                name="unit[discount][total]" placeholder="Discount"
                                                                value="{{ number_format($salePlan->discount_total, 2) }}" />
                                                        </div>
                                                    </div>

                                                    {{-- Total Amount Row --}}
                                                    <div class="row mb-1">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">

                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <hr>
                                                            <label class="form-label fw-bolder fs-5"
                                                                for="unit_rate_total">Total
                                                                (Rs)</label>
                                                            <input type="text" class="form-control form-control-lg"
                                                                id="unit_rate_total" name="unit[grand_total]"
                                                                placeholder="Total"
                                                                value="{{ number_format($salePlan->total_price, 2) }}"
                                                                readonly />
                                                        </div>
                                                    </div>

                                                    {{-- Downpayment Row --}}
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <label class="form-label fs-5"
                                                                for="unit_downpayment_percentage">Down
                                                                Payment
                                                                (%)</label>
                                                            <input readonly type="text"
                                                                class="form-control form-control-lg"
                                                                id="unit_downpayment_percentage"
                                                                name="unit[downpayment][percentage]"
                                                                placeholder="Down Payment %" min="0"
                                                                max="100"
                                                                value="{{ number_format($salePlan->down_payment_percentage, 2) }}" />
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                                            <label class="form-label fs-5"
                                                                for="unit_downpayment_total">Amount
                                                                (Rs)</label>
                                                            <input type="text" class="form-control form-control-lg"
                                                                readonly id="unit_downpayment_total"
                                                                value="{{ number_format($salePlan->down_payment_total, 2) }}"
                                                                name="unit[downpayment][total]" placeholder="Amount" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card m-0 mb-1" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row">
                            <div class="card-header">
                                <h3>2. INSTALLMENT DETAILS</h3>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                        <div class="card m-0"
                                            style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                                            <div class="card-body">
                                                <div class="table-responsive"
                                                    style="max-height: 50rem; overflow-y: auto;">

                                                    <table class="table table-hover table-striped table-borderless"
                                                        id="installments_table" style="position: relative;">
                                                        <thead style="position: sticky; top: 0; z-index: 10;">
                                                            <tr class="">
                                                                <th scope="col">#</th>
                                                                <th scope="col">Installments</th>
                                                                <th scope="col">Due Date</th>
                                                                <th scope="col">Total Amount</th>
                                                                @if ($salePlan->payment_plan_serial_id != null)
                                                                    <th scope="col">Paid Amount</th>
                                                                    <th scope="col">Remaining Amount</th>
                                                                    <th scope="col">Status</th>
                                                                @endif
                                                            </tr>
                                                        </thead>

                                                        <tbody id="dynamic_installment_rows">
                                                            @foreach ($installments as $installment)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1 }}</td>
                                                                    <td>{{ $installment->details }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($installment->date)->format('F j, Y') }}
                                                                    </td>
                                                                    <td>{{ number_format($installment->amount, 2) }}</td>

                                                                    @if ($salePlan->payment_plan_serial_id != null)
                                                                        <td>{{ number_format($installment->paid_amount, 2) }}
                                                                        </td>
                                                                        <td>{{ number_format($installment->remaining_amount, 2) }}
                                                                        </td>
                                                                        @if ($installment->status == 'paid')
                                                                            <td>
                                                                                <span
                                                                                    style="color: green; font-weight: bold;">{{ Str::of($installment->status)->replace('_', ' ')->title() }}</span>
                                                                            </td>
                                                                        @elseif($installment->status == 'partially_paid')
                                                                            <td>
                                                                                <span
                                                                                    style="color: rgb(255, 123, 0); font-weight: bold;">{{ Str::of($installment->status)->replace('_', ' ')->title() }}
                                                                                    {{ \Carbon\Carbon::parse($installment->date)->isPast() ? ', Due' : '' }}</span>
                                                                            </td>
                                                                        @elseif($installment->status == 'unpaid' && \Carbon\Carbon::parse($installment->date)->isPast())
                                                                            <td>
                                                                                <span
                                                                                    style="color: red; font-weight: bold;">Due</span>
                                                                            </td>
                                                                        @else
                                                                            <td>
                                                                                <span>{{ Str::of($installment->status)->replace('_', ' ')->title() }}</span>
                                                                            </td>
                                                                        @endif
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stakeholder Data Div --}}

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>3. STAKEHOLDER DETAILS</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $salePlan->stakeholder->pin_code }}</p>
                        {{ view('app.sites.stakeholders.partials.stakeholder-preview-fields', ['stakeholder' => $salePlan->stakeholder, 'hideBorders' => true]) }}
                    </div>
                </div>

                {{-- Stakeholder Data Div --}}

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>4. SALES SOURCE</h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="sales_source_full_name">Sales Person</label>
                                <input type="text" class="form-control form-control-lg" id="sales_source_full_name"
                                    name="sales_source[full_name]" placeholder="Sales Person"
                                    value="{{ $salePlan->user->name }}" disabled />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">

                                @php
                                    $roles = $salePlan->user->roles->pluck('name')->toArray();
                                    $roles = implode(', ', $roles);
                                @endphp

                                <label class="form-label fs-5" for="sales_source_status">Status</label>
                                <input type="text" class="form-control form-control-lg" id="sales_source_status"
                                    name="sales_source[status]" placeholder="Status" value="{{ $roles }}"
                                    disabled />
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                <label class="form-label fs-5" for="sales_source_contact_no">Contact No</label>
                                <input type="text" class="form-control form-control-lg" id="sales_source_contact_no"
                                    name="sales_source[contact_no]" placeholder="Contact No"
                                    value="{{ $salePlan->user->contact }}" disabled />
                            </div>
                        </div>

                        <div class="row mb-1 g-1">

                            <div class="col-lg-12 col-md-12 col-sm-6 position-relative">
                                <div id="div_sales_source_lead_source">
                                    <label class="form-label fs-5" for="sales_source_new">Lead Source </label>
                                    <input readonly type="text" class="form-control form-control-lg"
                                        id="sales_source_new" name="sales_source[new]" placeholder="Lead Source"
                                        value="{{ $salePlan->leadSource->name }}" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-header">
                        <h3>5. COMMENTS </h3>
                    </div>

                    <div class="card-body">
                        <div class="row mb-1 g-1">

                            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="comments">Comments</label>
                                <textarea disabled class="form-control form-control-lg" id="custom_comments" name="sale_plan_comments"
                                    placeholder="Comments" rows="4">{{ $salePlan->comments }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
    </form>
    {{-- Printing Modal --}}
    {{-- @include('app.sites.floors.units.sales-plan.partials.print-templates', [
        'salesPlanTemplates' => $salesPlanTemplates,
        'showTemplateType' => 'payment_plan'
    ]) --}}
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
    <script>
        function openTemplatesModal(sales_plan_id) {
            $('#sales_plan_id').val(sales_plan_id);
            $('#modal-sales-plan-template').modal('show');
        }

        function printSalesPlanTemplate(template_id, sales_plan_id) {

            let url =
                "{{ route('sites.floors.units.sales-plans.templates.print', ['site_id' => encryptParams($site), 'floor_id' => encryptParams(1), 'unit_id' => encryptParams(1), 'sales_plan_id' => ':sales_plan_id', 'id' => ':id']) }}"
                .replace(':sales_plan_id', sales_plan_id)
                .replace(':id', template_id);
            window.open(url, '_blank').focus();

        }
    </script>
@endsection

@section('custom-js')
@endsection