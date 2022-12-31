@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.create', encryptParams($site->id), encryptParams($floor->id)) }}
@endsection

@section('page-title', 'Create Unit')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/extensions/nouislider.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/plugins/extensions/ext-component-sliders.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/core/colors/palette-noui.css">
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
                <h2 class="content-header-title float-start mb-0">Create Unit</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.create', encryptParams($site->id), encryptParams($floor->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical" id="units_form"
        action="{{ route('sites.floors.units.store', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf
                {{ view('app.sites.floors.units.form-fields', [
                    'site' => $site,
                    'floor' => $floor,
                    'siteConfiguration' => $siteConfiguration,
                    'additionalCosts' => $additionalCosts,
                    'types' => $types,
                    'statuses' => $statuses,
                    'max_unit_number' => $max_unit_number,
                    'bulkOptions' => true,
                    'customFields' => $customFields,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: 10 !important;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    @can('sites.floors.units.store')
                                        <button type="submit"
                                            class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                            <i data-feather='save'></i>
                                            <span id="create_unit_button_span">Save Unit</span>
                                        </button>
                                    @endcan

                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.floors.units.index', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
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
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {

            $('#is_corner').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#corner_id').attr('disabled', false);
                } else {
                    $('#corner_id').attr('disabled', true);
                }
            });

            $('#is_facing').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#facing_id').attr('disabled', false);
                } else {
                    $('#facing_id').attr('disabled', true);
                }
            });

            $('#is_corner').trigger('change');
            $('#is_facing').trigger('change');

            $('#add_bulk_unit').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#bulkOptionSlider').show();
                    $('#bulk_units_checkbox_column').addClass('mb-3');
                    $('#hide_div').hide();
                    $("#unit_number, #name").attr('disabled', true);
                    mergeTooltips(unitSlider, 15, ' <i class="bi bi-door-open" class="m-10"></i> - ');
                } else {
                    $('#bulkOptionSlider').hide();
                    $('#bulk_units_checkbox_column').removeClass('mb-3');
                    $('#hide_div').show();
                    $("#unit_number, #name").attr('disabled', false);
                    $('#create_unit_button_span').html('Save Unit');
                }
            });

            var unitSlider = document.getElementById("primary-color-slider");

            noUiSlider.create(unitSlider, {
                behaviour: "tap-drag",
                tooltips: wNumb({
                    decimals: 0,
                    postfix: ' <i class="bi bi-door-open" class="m-10"></i>'
                }),
                connect: !0,
                step: 1,
                start: [0, 20],
                range: {
                    min: 1,
                    max: parseInt('{{ getNHeightestNumber($siteConfiguration->unit_number_digits) }}') + 1,
                },
                pips: {
                    mode: "range",
                    stepped: !0,
                    density: 10,
                    format: wNumb({
                        decimals: 0,
                        postfix: ' <i class="bi bi-door-open" class="m-10"></i>'
                    })
                },
                direction: 'ltr'
            });

            var inputs = [

            ];

            var inputs = [
                document.getElementById('slider_input_1'),
                document.getElementById('slider_input_2')
            ];

            unitSlider.noUiSlider.on('update', function(values, handle, unencoded) {
                inputs[handle].value = parseInt(values[handle]);
            });

            // mergeTooltips(unitSlider, 15, ' <i class="bi bi-door-open" class="m-10"></i> - ');

            function mergeTooltips(slider, threshold, separator) {

                var textIsRtl = getComputedStyle(slider).direction === 'rtl';
                var isRtl = slider.noUiSlider.options.direction === 'rtl';
                var isVertical = slider.noUiSlider.options.orientation === 'vertical';
                var tooltips = slider.noUiSlider.getTooltips();
                var origins = slider.noUiSlider.getOrigins();

                // Move tooltips into the origin element. The default stylesheet handles this.
                tooltips.forEach(function(tooltip, index) {
                    if (tooltip) {
                        origins[index].appendChild(tooltip);
                    }
                });

                slider.noUiSlider.on('update', function(values, handle, unencoded, tap, positions) {

                    if ($('#add_bulk_unit').is(':checked')) {
                        $('#create_unit_button_span').html('Save Units [' + parseInt(
                            getDifference(values[0], values[1]) + 1) + ']');
                    }

                    var pools = [
                        []
                    ];
                    var poolPositions = [
                        []
                    ];
                    var poolValues = [
                        []
                    ];
                    var atPool = 0;

                    // Assign the first tooltip to the first pool, if the tooltip is configured
                    if (tooltips[0]) {
                        pools[0][0] = 0;
                        poolPositions[0][0] = positions[0];
                        poolValues[0][0] = values[0];
                    }

                    for (var i = 1; i < positions.length; i++) {
                        if (!tooltips[i] || (positions[i] - positions[i - 1]) > threshold) {
                            atPool++;
                            pools[atPool] = [];
                            poolValues[atPool] = [];
                            poolPositions[atPool] = [];
                        }

                        if (tooltips[i]) {
                            pools[atPool].push(i);
                            poolValues[atPool].push(values[i]);
                            poolPositions[atPool].push(positions[i]);
                        }
                    }

                    pools.forEach(function(pool, poolIndex) {
                        var handlesInPool = pool.length;

                        for (var j = 0; j < handlesInPool; j++) {
                            var handleNumber = pool[j];

                            if (j === handlesInPool - 1) {
                                var offset = 0;

                                poolPositions[poolIndex].forEach(function(value) {
                                    offset += 1000 - value;
                                });

                                var direction = isVertical ? 'bottom' : 'right';
                                var last = isRtl ? 0 : handlesInPool - 1;
                                var lastOffset = 1000 - poolPositions[poolIndex][last];
                                offset = (textIsRtl && !isVertical ? 100 : 0) + (offset /
                                    handlesInPool) - lastOffset;

                                // Center this tooltip over the affected handles

                                tooltips[handleNumber].innerHTML = poolValues[poolIndex].map(
                                        function(item) {
                                            return parseInt(item, 10);
                                        }).join(separator) +
                                    ' <i class="bi bi-door-open" class="m-10"></i>';
                                tooltips[handleNumber].style.display = 'block';
                                tooltips[handleNumber].style[direction] = offset + '%';
                            } else {
                                // Hide this tooltip
                                tooltips[handleNumber].style.display = 'none';
                            }
                        }
                    });
                });
            }

            function getDifference(a, b) {
                return Math.abs(a - b);
            }


            //Calculate Unit Price and Total Price from Gross Area
            $('#gross_area, #price_sqft').on('keyup', function() {
                var total_price = 0;
                var gross_area = 0;
                var price_sqft = 0;
                if ($(this).val() > 0) {
                    gross_area = parseFloat($('#gross_area').val());
                    price_sqft = parseFloat($('#price_sqft').val());
                    total_price = gross_area * price_sqft;
                } else {
                    total_price = 0;
                    // $(this).val('0');
                }
                $('#total_price1').val('' + numberFormat(parseFloat(total_price).toFixed(2)));
                $('#total_price').val('' + parseFloat(total_price).toFixed(2));

            });
        });

        var validator = $("#units_form").validate({
            rules: {
                'type_id': {
                    required: true,
                },
                'name':{
                    required: true,
                },
                'net_area':{
                    required: true,
                },
                'gross_area':{
                    required: true,
                },
                'price_sqft':{
                    required: true,
                },
            },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {
                form.submit();
            }
        });
    </script>
@endsection
