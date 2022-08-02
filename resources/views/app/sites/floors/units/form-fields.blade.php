<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label" style="font-size: 15px" for="type_id">Unit Type</label>
        <select class="select2-size-lg form-select" id="type_id" name="type_id">
            <option value="" selected>Unit Type</option>
            @foreach ($types as $row)
                <option value="{{ $row->id }}"
                    {{ (isset($unit) ? $unit->type_id : old('type_id')) == $row->id ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $row->tree }}</option>
            @endforeach
        </select>
        @error('type_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="site_name">Site Name</label>
        <input type="text" class="form-control form-control-lg @error('site_name') is-invalid @enderror"
            id="site_name" name="site_name" placeholder="Site Nmae" readonly value="{{ $site->name }}" />
        @error('site_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="floor_name">Floor Name</label>
        <input type="text" class="form-control form-control-lg @error('floor_name') is-invalid @enderror"
            id="floor_name" name="floor_name" placeholder="Floor Name" value="{{ $floor->name }}" readonly />
        @error('floor_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="name">Name</label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Name" value="{{ isset($unit) ? $unit->name : old('name') }}" />
        @error('name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <div id="unit_number_div">
            <label class="form-label fs-5" for="unit_number">Unit Number</label>
            <input type="number" class="form-control form-control-lg @error('unit_number') is-invalid @enderror"
                id="unit_number" name="unit_number" min="1"
                max="{{ getNHeightestNumber($siteConfiguration->unit_number_digits) }}" placeholder="Unit Number"
                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                value="{{ isset($unit) ? $unit->unit_number : old('unit_number') ?? 1 }}"
                {{ isset($unit) ? 'disabled' : '' }} />
            @error('name')
                <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="width">Width (sqft)</label>
        <input type="text" class="form-control form-control-lg @error('width') is-invalid @enderror" id="width"
            name="width" placeholder="Width (sqft)" value="{{ isset($unit) ? $unit->width : old('width') ?? 0 }}" />
        @error('width')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="length">Length (sqft)</label>
        <input type="text" class="form-control form-control-lg @error('length') is-invalid @enderror" id="length"
            name="length" placeholder="Length (sqft)"
            value="{{ isset($unit) ? $unit->length : old('length') ?? 0 }}" />
        @error('length')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-2">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label fs-5" for="price">Price (sqft)</label>
        <input type="text" class="form-control form-control-lg @error('price') is-invalid @enderror" id="price"
            name="price" placeholder="Price (sqft)" value="{{ isset($unit) ? $unit->price : old('price') ?? 0 }}" />
        @error('price')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card border">
            <div class="card-body">

                {{-- Is Corner --}}
                <div class="row mb-2">
                    <div class="col-xl-3 col-lg-3">
                        <div class="d-flex align-items-center h-100">
                            <div class="form-check form-check-primary">
                                <input type="hidden" name="is_corner" value="0">
                                <input type="checkbox" class="form-check-input" name="is_corner" id="is_corner"
                                    value="1"
                                    {{ isset($unit) ? ($unit->is_corner == 1 ? 'checked' : 'unchecked') : (is_null(old('is_corner')) ? '' : (old('is_corner') == 1 ? 'checked' : 'unchecked')) }} />
                                <label class="form-check-label" for="is_corner">Corner</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floor --}}
                <div class="row">
                    <div class="col-xl-3 col-lg-3">
                        <div class="d-flex align-items-center h-100">
                            <div class="form-check form-check-primary">
                                <input type="hidden" name="is_facing" value="0">
                                <input type="checkbox" class="form-check-input" name="is_facing" id="is_facing"
                                    value="1"
                                    {{ isset($unit) ? ($unit->is_facing == 1 ? 'checked' : 'unchecked') : (is_null(old('is_facing')) ? '' : (old('is_facing') == 1 ? 'checked' : 'unchecked')) }} />
                                <label class="form-check-label" for="is_facing">Facing</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <label class="form-label" style="font-size: 15px" for="facing_id">Facing Charges</label>
                        <select class="select2-size-lg form-select" id="facing_id" name="facing_id" disabled />
                        <option value="" selected>Select Facing Charges</option>
                        @foreach ($additionalCosts as $row)
                            <option value="{{ $row['id'] }}" {{ $row->has_child ? 'disabled' : '' }}
                                {{ (isset($unit) ? $unit->facing_id : old('facing_id')) == $row['id'] ? 'selected' : '' }}>
                                {{ $loop->index + 1 }} - {{ $row['tree'] }}</option>
                        @endforeach
                        </select>
                        @error('facing_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row mb-3">

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label" style="font-size: 15px" for="status_id">Unit Status</label>
        <select class="select2-size-lg form-select" id="status_id" name="status_id">
            @foreach ($statuses as $row)
                <option value="{{ $row->id }}"
                    {{ (isset($unit) ? $unit->status_id : old('status_id')) == $row->id ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $row->name }}</option>
            @endforeach
        </select>
        @error('status_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

</div>

@if ($bulkOptions)
    <div class="card border-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 position-relative" id="bulk_units_checkbox_column">
                    <div class="d-flex align-items-center h-100">
                        <div class="form-check form-check-primary">
                            <input type="hidden" name="add_bulk_unit" value="0">
                            <input type="checkbox" class="form-check-input" name="add_bulk_unit" id="add_bulk_unit"
                                value="1" />
                            <label class="form-check-label" for="add_bulk_unit">Add Bulk Units</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                    <div class="card m-0" id="bulkOptionSlider" style="display: none;">
                        <div class="card-body">

                            <input type="hidden" name="slider_input_1" id="slider_input_1" value="1">
                            <input type="hidden" name="slider_input_2" id="slider_input_2" value="20">

                            <div id="primary-color-slider" class="circle-filled slider-primary mt-md-1 mt-3 mb-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
