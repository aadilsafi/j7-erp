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
            <input type="hidden" name="unit_total_area" id="unit_total_area">
            <input type="hidden" name="sub_unit_total_area" id="sub_unit_total_area">

            <input type="hidden" name="unit_net_area" id="unit_net_area">
            <input type="hidden" name="sub_unit_net_area" id="sub_unit_net_area">
        </div>

        <div class="{{$errors->any() ? '' : 'hidediv'}}">
            <div class="card">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="site_name">Site Name</label>
                        <input type="text" class="form-control form-control-lg @error('site_name') is-invalid @enderror"
                            id="site_name" name="site_name" placeholder="Site Name" readonly
                            value="{{ $site->name }}" />
                        @error('site_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="floor_id" id="floor_id">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label fs-5" for="floor_name">Floor Name</label>
                        <input type="text"
                            class="form-control form-control-lg @error('floor_name') is-invalid @enderror"
                            id="floor_name" name="floor_name" placeholder="Floor Name"
                            value="{{ $floor->name }} ({{ $floor->short_label }})" readonly />
                        @error('floor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative" style="display:none;">
                        <input type="hidden" name="unit_number_digits"
                            value="{{ getNHeightestNumber($siteConfiguration->unit_number_digits) }}">
                        <label class="form-label fs-5" for="unit_number">Unit Number</label>
                        <input type="number"
                            class="form-control form-control-lg @error('unit_number') is-invalid @enderror"
                            id="unit_number" name="unit_number" min=""
                            max="{{ getNHeightestNumber($siteConfiguration->unit_number_digits) }}"
                            placeholder="Unit Number" value="" {{ isset($unit) ? 'readonly' : '' }} />
                    </div>
                </div>
                <div class="fab-units">
                    <div data-repeater-list="fab-units">
                        <div data-repeater-item>
                            <div class="card m-0">
                                <div class="card-header mt-1">
                                    <h3>Bifurcated Unit</h3>
                                    <button
                                        class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                        data-repeater-delete id="delete-contact-person" type="button">
                                        <i data-feather="x" class="me-25"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                                <div class="card-body">

                                    <div class="row mb-2" id="hide_div">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="name">Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                id="name" name="fab-units[name]" placeholder="Name"
                                                value="{{ old('fab-units[name]') }}" />
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label" style="font-size: 15px" for="status_id">Unit
                                                Status</label>
                                            @if (isset($unit) && count($unit->salesPlan) > 0 &&
                                            $unit->salesPlan[0]->status == 1)
                                            <input type="hidden" name="fab-units[status_id]"
                                                value="{{ $unit->status_id }}">
                                            @endif
                                            <select class="select2-size-lg form-select" id="status_id"
                                                name="fab-units[status_id]" disabled>
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
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="width">Width (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('width') is-invalid @enderror"
                                                id="width" name="fab-units[width]" placeholder="Width (sqft)"
                                                value="{{old('fab-units.*.width')}}" />
                                            @error('fab-units[width]')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="length">Length (sqft)</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('length') is-invalid @enderror"
                                                id="length" name="fab-units[length]" placeholder="Length (sqft)"
                                                value="{{old('fab-units[length]')}}" />
                                            @error('fab-units[length]')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="net_area">Net Area (sqft)</label>
                                            <input type="number"
                                                class="RequiredField netArea form-control form-control-lg @error('net_area') is-invalid @enderror"
                                                id="net_area" name="fab-units[net_area]" placeholder="Net Area (sqft)"
                                                min="0" value="{{ old('fab-units[net_area]')  }}" />
                                            @error('fab-units[net_area]')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="gross_area">Gross Area
                                                (sqft)</label>
                                            <input type="number"
                                                class="RequiredField gross_area checkArea tocheckArea form-control form-control-lg @error('gross_area') is-invalid @enderror"
                                                id="gross_area" name="fab-units[gross_area]"
                                                placeholder="Gross Area (sqft)" min="0"
                                                value="{{old('fab-units[gross_area]') }}" />
                                            @error('fab-units[gross_area]')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="price_sqft">Price (sqft)</label>
                                            <input type="number"
                                                class="RequiredField calculateArea price_sqft form-control form-control-lg @error('price_sqft') is-invalid @enderror"
                                                id="price_sqft" name="fab-units[price_sqft]" placeholder="Price (sqft)"
                                                min="0" value="{{ old('fab-units[price_sqft]') }}" />
                                            @error('fab-units[price_sqft]')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-4 col-sm-4 position-relative">
                                            <input type="hidden" name="fab-units[total_price]" id="total_price"
                                                value="0">
                                            <label class="form-label fs-5" for="total_price">Total Price</label>
                                            <input type="text"
                                                class="total_price1 RequiredField form-control form-control-lg @error('total_price') is-invalid @enderror"
                                                id="total_price1" name="fab-units[total_price1]"
                                                placeholder="Total Price (sqft)" readonly
                                                value="{{old('fab-units.*.total_price1')}}" />
                                            @error('fab-units[total_price1]')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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