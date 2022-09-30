<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">

        <div class="row mb-1">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <label class="form-label" style="font-size: 15px" for="additionalCost">Additional Cost</label>
                <select class="select2-size-lg form-select" id="additionalCost" name="additionalCost">
                    <option value="0" selected>Parent Additional Cost</option>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($additionalCosts as $row)
                        @continue(isset($additionalCost) && $additionalCost->id == $row['id'])
                        @continue(!$row['has_child'])

                        <option value="{{ $row['id'] }}"
                            {{ (isset($additionalCost) ? $additionalCost->parent_id : old('additionalCost')) == $row['id'] ? 'selected' : '' }}>
                            {{ $counter }} - {{ $row['tree'] }}</option>

                        @php
                            $counter++;
                        @endphp
                    @endforeach
                </select>
                @error('additionalCost')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="name">Name</label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                    id="name" name="name" placeholder="Name"
                    value="{{ isset($additionalCost) ? $additionalCost->name : old('name') }}"
                    onkeyup="convertToSlug(this.value);" />
                @error('name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row my-2">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="d-flex flex-column">
                    <label class="form-check-label mb-50" for="has_child">Has sub additional cost</label>
                    <div class="form-check form-switch form-check-primary">
                        <input type="hidden" name="has_child" value="0" />
                        <input type="checkbox" class="form-check-input" id="has_child" name="has_child" value="1"
                            {{ isset($additionalCost) ? ($additionalCost->has_child == 1 ? 'checked' : 'unchecked') : (is_null(old('has_child')) ? 'checked' : (old('has_child') == 1 ? 'checked' : 'unchecked')) }} />
                        <label class="form-check-label" for="has_child">
                            <span class="switch-icon-left"><i data-feather="check"></i></span>
                            <span class="switch-icon-right"><i data-feather="x"></i></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row collapse" id="hasSubAdditionalCost">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card hasChildCard m-0" id="hasChildCard"
                    style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-body">

                        {{-- Site --}}
                        <div class="row mb-2">
                            <div class="col-xl-3 col-lg-3">
                                <div class="d-flex align-items-center h-100">
                                    <div class="form-check form-check-primary">
                                        <input type="hidden" name="applicable_on_site" value="0">
                                        <input type="checkbox" class="form-check-input" name="applicable_on_site"
                                            id="applicable_on_site" value="1"
                                            {{ isset($additionalCost) ? ($additionalCost->applicable_on_site == 1 ? 'checked' : 'unchecked') : (is_null(old('has_child')) ? '' : (old('has_child') == 1 ? 'checked' : 'unchecked')) }} />
                                        <label class="form-check-label" for="applicable_on_site">Applicable On
                                            Site</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9">
                                <label class="form-label fs-5" for="site_percentage">Site Percentage</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('site_percentage') is-invalid @enderror"
                                    id="site_percentage" name="site_percentage" placeholder="Site Percentage" readonly
                                    value="{{ (isset($additionalCost) ? $additionalCost->site_percentage : old('site_percentage')) ?? 0 }}" />
                                @error('site_percentage')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Floor --}}
                        <div class="row mb-2">
                            <div class="col-xl-3 col-lg-3">
                                <div class="d-flex align-items-center h-100">
                                    <div class="form-check form-check-primary">
                                        <input type="hidden" name="applicable_on_floor" value="0">
                                        <input type="checkbox" class="form-check-input" name="applicable_on_floor"
                                            id="applicable_on_floor" value="1"
                                            {{ isset($additionalCost) ? ($additionalCost->applicable_on_floor == 1 ? 'checked' : 'unchecked') : (is_null(old('has_child')) ? '' : (old('has_child') == 1 ? 'checked' : 'unchecked')) }} />
                                        <label class="form-check-label" for="applicable_on_floor">Applicable On
                                            Floor</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9">
                                <label class="form-label fs-5" for="floor_percentage">Floor Percentage</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('floor_percentage') is-invalid @enderror"
                                    id="floor_percentage" name="floor_percentage" placeholder="Floor Percentage"
                                    readonly
                                    value="{{ (isset($additionalCost) ? $additionalCost->floor_percentage : old('floor_percentage')) ?? 0 }}" />
                                @error('floor_percentage')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- site --}}
                        <div class="row mb-2">
                            <div class="col-xl-3 col-lg-3">
                                <div class="d-flex align-items-center h-100">
                                    <div class="form-check form-check-primary">
                                        <input type="hidden" name="applicable_on_unit" value="0">
                                        <input type="checkbox" class="form-check-input" name="applicable_on_unit"
                                            id="applicable_on_unit" value="1"
                                            {{ isset($additionalCost) ? ($additionalCost->applicable_on_unit == 1 ? 'checked' : 'unchecked') : (is_null(old('has_child')) ? '' : (old('has_child') == 1 ? 'checked' : 'unchecked')) }} />
                                        <label class="form-check-label" for="applicable_on_unit">Applicable On
                                            Unit</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9">
                                <label class="form-label fs-5" for="unit_percentage">Unit Percentage</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('unit_percentage') is-invalid @enderror"
                                    id="unit_percentage" name="unit_percentage" placeholder="Unit Percentage"
                                    readonly
                                    value="{{ (isset($additionalCost) ? $additionalCost->unit_percentage : old('unit_percentage')) ?? 0 }}" />
                                @error('unit_percentage')
                                    <div class="invalid-tooltip">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
