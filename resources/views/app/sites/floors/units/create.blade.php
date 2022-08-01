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
    <div class="card">
        <form class="form form-vertical"
            action="{{ route('sites.floors.units.store', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
            method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf
                {{ view('app.sites.floors.units.form-fields', [
                    'site' => $site,
                    'floor' => $floor,
                    'siteConfiguration' => $siteConfiguration,
                    'additionalCosts' => $additionalCosts,
                    'types' => $types,
                    'statuses' => $statuses,
                    'bulkOptions' => true,
                ]) }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    Save Unit
                </button>
                <a href="{{ route('sites.floors.units.index', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    {{ __('lang.commons.cancel') }}
                </a>
            </div>

        </form>
    </div>
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
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
                    $('#unit_number_div').hide().children("#unit_number").prop('disabled', true);
                } else {
                    $('#bulkOptionSlider').hide();
                    $('#bulk_units_checkbox_column').removeClass('mb-3');
                    $('#unit_number_div').show().children("#unit_number").prop('disabled', false);
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

            mergeTooltips(unitSlider, 15, ' <i class="bi bi-door-open" class="m-10"></i> - ');

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
                                    }).join(separator) + ' <i class="bi bi-door-open" class="m-10"></i>';
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
        });
    </script>
@endsection
