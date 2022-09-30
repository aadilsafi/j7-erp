@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.copy', $site_id) }}
@endsection

@section('page-title', 'Copy Floor')

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
                <h2 class="content-header-title float-start mb-0">Copy Floor</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.copy', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form class="form form-vertical" action="{{ route('sites.floors.copyStore', ['site_id' => $site_id]) }}"
            method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf
                <div class="row mb-2">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <label class="form-label" style="font-size: 15px" for="floor">Select Floor to Copy *</label>
                        <select class="select2-size-lg form-select" id="floor" name="floor"
                            onchange="floorValue(this.value)">
                            <option value="" selected>Select Floor to Copy</option>
                            @foreach ($floors as $floor)
                                <option value="{{ $floor->id }}" {{ $floor->units_count == 0 ? 'disabled' : '' }}
                                    {{ old('additionalCost') == $floor->id ? 'selected' : '' }}>
                                    {{ $loop->index + 1 }} - {{ $floor->name }} ({{ $floor->units_count }} Units)
                                </option>
                            @endforeach
                        </select>
                        @error('floor')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

                        <div class="card m-0 border-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                        <div class="card m-0" id="bulkOptionSlider">
                                            <div class="card-body">

                                                <input type="hidden" name="copy_floor_from" id="copy_floor_from"
                                                    value="1">
                                                <input type="hidden" name="copy_floor_to" id="copy_floor_to"
                                                    value="20">

                                                <div id="primary-color-slider"
                                                    class="circle-filled slider-primary mt-md-1 mt-3 mb-4">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

                        <div id="shortLabelForm">

                        </div>
                    </div>
                </div>

            </div>


            <div class="card-footer d-flex align-items-center justify-content-end">
                <button id="copy_floor_button" type="submit"
                    class="btn btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI me-1">
                    <i data-feather='copy'></i>
                    <span id="copy_floor_button_span">Copy Floor </span>
                </button>
                <a href="{{ route('sites.floors.index', ['site_id' => encryptParams(decryptParams($site_id))]) }}"
                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    {{ __('lang.commons.cancel') }}
                </a>
            </div>

        </form>
    </div>

    @php
    $siteConfiguration = getSiteConfiguration($site_id);
    $max_floors = max($floors->pluck('order')->toArray());
    @endphp

@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        var unitSlider = document.getElementById("primary-color-slider");

        noUiSlider.create(unitSlider, {
            behaviour: "tap-drag",
            tooltips: wNumb({
                decimals: 0,
                postfix: ' <i class="bi bi-stack m-10"></i>'
            }),
            connect: !0,
            step: 1,
            start: [1, 10],
            range: {
                min: parseInt('{{ $max_floors > 0 ? $max_floors + 1 : 1 }}'),
                max: parseInt(
                    '{{ !is_null($siteConfiguration) && $siteConfiguration->site_max_floors > 0 ? $siteConfiguration->site_max_floors : 50 }}'
                ),
            },
            pips: {
                mode: "range",
                stepped: !0,
                density: 10,
                format: wNumb({
                    decimals: 0,
                    postfix: ' <i class="bi bi-stack m-10"></i>'
                })
            },
            direction: 'ltr'
        });

        var inputs = [

        ];

        var inputs = [
            document.getElementById('copy_floor_from'),
            document.getElementById('copy_floor_to')
        ];
        unitSlider.noUiSlider.on('update', function(values, handle, unencoded) {
            inputs[handle].value = parseInt(values[handle]);
        });
        mergeTooltips(unitSlider, 5, ' <i class="bi bi-stack m-10"></i> - ');

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

                $('#copy_floor_button_span').html('Copy Floors [' + parseInt(
                    getDifference(values[0], values[1]) + 1) + ']');

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
                                }).join(separator) + ' <i class="bi bi-stack m-10"></i>';
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
            shortLabel(a,b);
            return Math.abs(a - b);
        }

        function floorValue(value) {

            // var slider = unitSlider.noUiSlider;
            // var range = slider.options.range;

            // range.min = parseInt((value > 0 ? value : 0)) + 1;

            // slider.updateOptions({
            //     range: {
            //         'min':
            //         range.min,
            //         'max':
            //         range.max,
            //     }
            // });
        }

        function shortLabel(a,b){
            $('#shortLabelForm').empty();
            // $('#shortLabelForm').append('<label class="form-label" style="font-size: 15px" for="floor">Enter Short Labels for floor ('+parseInt(a)+' to '+parseInt(b)+')*</label>')
            for(let i = parseInt(a); i<=parseInt(b); i++){
                $('#shortLabelForm').append('<label class="form-label" style="font-size: 15px" for="floor">Enter Short Label for floor ('+i+')*</label><input type="text" required class="form-control mb-2" name="shortLabel['+i+']" placeholder="Short label for floor '+i+'">');
            }
        }

    </script>
@endsection
