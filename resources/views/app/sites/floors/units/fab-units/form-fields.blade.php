<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="unit_id">Unit</label>
                <select class="select2-size-lg form-select" id="unit_id" name="unit_id">
                    <option value="0" selected>Unit</option>
                    @foreach ($units as $row)
                    <option value="{{ $row->id }}" {{ (isset($unit) ? $unit->id : old('unit_id')) == $row->id ?
                        'selected' : '' }}>
                        {{ $loop->index + 1 }} - {{ $row->name }} - {{$row->floor_unit_number}}</option>
                    @endforeach
                </select>
                @error('type_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="hidediv">
            <div class="card">
                <div class="fab-units">
                    <div data-repeater-list="fab-units">
                        <div data-repeater-item>
                            <div class="card m-0">
                                <div class="card-header">
                                    <h3>Fabricated Units</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-1">
                                        <div class="col text-right">
                                            <button
                                                class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1 my-1"
                                                data-repeater-delete id="delete-contact-person" type="button">
                                                <i data-feather="x" class="me-25"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>

                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="site_name">Site Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('site_name') is-invalid @enderror"
                                                id="site_name" name="fabUnits[site_name]" placeholder="Site Name"
                                                readonly value="{{ $site->name }}" />
                                            @error('site_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="floor_name">Floor Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('floor_name') is-invalid @enderror"
                                                id="floor_name" name="fabUnits[floor_name]" placeholder="Floor Name"
                                                value="{{ $floor->name }} ({{ $floor->short_label }})" readonly />
                                            @error('floor_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-2" id="hide_div">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="name">Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                id="name" name="fabUnits[name]" placeholder="Name"
                                                value="{{ isset($unit) ? $unit->name : old('name') }}" />
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label" style="font-size: 15px" for="status_id">Unit
                                                Status</label>
                                            @if (isset($unit) && count($unit->salesPlan) > 0 &&
                                            $unit->salesPlan[0]->status
                                            == 1)
                                            <input type="hidden" name="fabUnits[status_id]"
                                                value="{{ $unit->status_id }}">
                                            @endif
                                            <select class="select2-size-lg form-select" id="status_id"
                                                name="fabUnits[status_id]" {{ isset($unit) && count($unit->salesPlan) &&
                                                $unit->salesPlan[0]->status == 1 ?
                                                'disabled'
                                                :
                                                null }}>
                                                @foreach ($statuses as $row)
                                                @continue(!isset($unit) && $row->id != 1)
                                                <option value="{{ $row->id }}" {{ (isset($unit) ? $unit->status_id :
                                                    old('status_id'))
                                                    ==
                                                    $row->id ?
                                                    'selected' : '' }}>
                                                    {{ $loop->index + 1 }} - {{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative" style="display:none;">
                                            <input type="hidden" name="fabUnits[unit_number_digits]"
                                                value="{{ getNHeightestNumber($siteConfiguration->unit_number_digits) }}">
                                            <label class="form-label fs-5" for="unit_number">Unit Number</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('unit_number') is-invalid @enderror"
                                                id="unit_number" name="fabUnits[unit_number]" min=""
                                                max="{{ getNHeightestNumber($siteConfiguration->unit_number_digits) }}"
                                                placeholder="Unit Number" value="" {{ isset($unit) ? 'readonly' : ''
                                                }} />
                                            @error('unit_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="width">Width (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('width') is-invalid @enderror"
                                                id="width" name="fabUnits[width]" placeholder="Width (sqft)"
                                                value="{{ isset($unit) ? $unit->width : old('width') ?? 0 }}" />
                                            @error('width')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="length">Length (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('length') is-invalid @enderror"
                                                id="length" name="fabUnits[length]" placeholder="Length (sqft)"
                                                value="{{ isset($unit) ? $unit->length : old('length') ?? 0 }}" />
                                            @error('length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="net_area">Net Area (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('net_area') is-invalid @enderror"
                                                id="net_area" name="fabUnits[net_area]" placeholder="Net Area (sqft)"
                                                min="0"
                                                value="{{ isset($unit) ? $unit->net_area : old('net_area') ?? 0 }}" />
                                            @error('net_area')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="gross_area">Gross Area
                                                (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('gross_area') is-invalid @enderror"
                                                id="gross_area" name="fabUnits[gross_area]"
                                                placeholder="Gross Area (sqft)" min="0"
                                                value="{{ isset($unit) ? $unit->gross_area : old('gross_area') ?? 0 }}" />
                                            @error('gross_area')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="price_sqft">Price (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('price_sqft') is-invalid @enderror"
                                                id="price_sqft" name="fabUnits[price_sqft]" placeholder="Price (sqft)"
                                                min="0"
                                                value="{{ isset($unit) ? $unit->price_sqft : old('price_sqft') ?? 0 }}" />
                                            @error('price_sqft')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <input type="hidden" name="fabUnits[total_price]" id="total_price"
                                                value="0">
                                            <label class="form-label fs-5" for="total_price">Total Price</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('total_price') is-invalid @enderror"
                                                id="total_price1" placeholder="Total Price (sqft)" readonly
                                                value="{{ isset($unit) ? number_format($unit->total_price) : number_format(old('total_price')) ?? '0.00' }}" />
                                            @error('total_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <div class="card m-0"
                                                style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                                                <div class="card-body">

                                                    {{-- Is Corner --}}
                                                    <div class="row mb-2">
                                                        <div class="col-xl-3 col-lg-3">
                                                            <div class="d-flex align-items-center h-100">
                                                                <div class="form-check form-check-primary">
                                                                    <input type="hidden" name="fabUnits[is_corner]"
                                                                        value="0">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="fabUnits[is_corner]" id="is_corner"
                                                                        value="1" {{ isset($unit) ? ($unit->is_corner ==
                                                                    1 ?
                                                                    'checked' :
                                                                    'unchecked') : (is_null(old('is_corner')) ? '' :
                                                                    (old('is_corner')
                                                                    == 1
                                                                    ?
                                                                    'checked' : 'unchecked')) }} />
                                                                    <label class="form-check-label"
                                                                        for="is_corner">Corner</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Floor --}}
                                                    <div class="row">
                                                        <div class="col-xl-3 col-lg-3">
                                                            <div class="d-flex align-items-center h-100">
                                                                <div class="form-check form-check-primary">
                                                                    <input type="hidden" name="fabUnits[is_facing]"
                                                                        value="0">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="fabUnits[is_facing]" id="is_facing"
                                                                        value="1" {{ isset($unit) ? ($unit->is_facing ==
                                                                    1 ?
                                                                    'checked' :
                                                                    'unchecked') : (is_null(old('is_facing')) ? '' :
                                                                    (old('is_facing')
                                                                    == 1
                                                                    ?
                                                                    'checked' : 'unchecked')) }} />
                                                                    <label class="form-check-label"
                                                                        for="is_facing">Facing</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-9 col-lg-9">
                                                            <label class="form-label" style="font-size: 15px"
                                                                for="facing_id">Facing
                                                                Charges</label>
                                                            <select class="select2-size-lg form-select" id="facing_id"
                                                                name="fabUnits[facing_id]" disabled />
                                                            <option value="" selected>Select Facing Charges</option>
                                                            @foreach ($additionalCosts as $row)
                                                            <option value="{{ $row['id'] }}" {{ $row->has_child ?
                                                                'disabled'
                                                                : '' }}
                                                                {{ (isset($unit) ? $unit->facing_id :
                                                                old('facing_id')) ==
                                                                $row['id'] ?
                                                                'selected' :
                                                                '' }}>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                                id="first-contact-person" type="button" data-repeater-create>
                                <i data-feather="plus" class="me-25"></i>
                                <span>Add New</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>