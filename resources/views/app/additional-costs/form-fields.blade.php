{{-- {{ dd($additionalCost) }} --}}
<div class="row mb-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <label class="form-label" style="font-size: 15px" for="additionalCostTree">Additional Cost</label>
        <select class="select2-size-lg form-select" id="additionalCostTree" name="additionalCost">
            <option value="0" selected>Parent Additional Cost</option>
            @foreach ($additionalCosts as $row)
                <option value="{{ $row['id'] }}"
                    {{ (isset($additionalCost) ? $additionalCost->parent_id : old('additionalCost')) == $row['id'] ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $row['tree'] }}</option>
            @endforeach
        </select>
        @error('additionalCost')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="additional_cost_name">Name</label>
        <input type="text" class="form-control form-control-lg @error('additional_cost_name') is-invalid @enderror"
            id="additional_cost_name" name="additional_cost_name" placeholder="Name"
            value="{{ isset($additionalCost) ? $additionalCost->name : old('additional_cost_name') }}"
            onkeyup="convertToSlug(this.value);" />
        @error('additional_cost_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="additional_cost_slug">Slug</label>
        <input type="text" class="form-control form-control-lg @error('additional_cost_slug') is-invalid @enderror"
            id="additional_cost_slug" name="additional_cost_slug" placeholder="Slug" readonly
            value="{{ isset($additionalCost) ? $additionalCost->slug : old('additional_cost_slug') }}" />
        @error('additional_cost_slug')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row my-2">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="form-check form-check-primary">
            <input type="hidden" name="has_child" value="0">
            <input type="checkbox" class="form-check-input" id="has_child"
                {{ ((isset($additionalCost) ? $additionalCost->has_child : old('has_child')) == 1 ? 'checked' : '') }} checked />
            <label class="form-check-label" for="has_child">Has child</label>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="card border-primary hasChildCard" id="hasChildCard" style="display: none;">
            <div class="card-body">

                {{-- Site --}}
                <div class="row mb-2">
                    <div class="col-xl-3 col-lg-3">
                        <div class="d-flex align-items-center h-100">
                            <div class="form-check form-check-primary">
                                <input type="hidden" name="applicable_on_site" value="0">
                                <input type="checkbox" class="form-check-input" name="applicable_on_site"
                                    id="applicable_on_site" value="1"
                                    {{ (isset($additionalCost) ? $additionalCost->applicable_on_site : old('applicable_on_site')) == 1 ? 'selected' : '' }} />
                                <label class="form-check-label" for="applicable_on_site">Applicable On Site</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <label class="form-label fs-5" for="site_percentage">Site Percentage</label>
                        <input type="text"
                            class="form-control form-control-lg @error('site_percentage') is-invalid @enderror"
                            id="site_percentage" name="site_percentage" placeholder="Site Percentage" disabled
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
                                    {{ (isset($additionalCost) ? $additionalCost->applicable_on_floor : old('applicable_on_floor')) == 1 ? 'selected' : '' }} />
                                <label class="form-check-label" for="applicable_on_floor">Applicable On Floor</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <label class="form-label fs-5" for="floor_percentage">Floor Percentage</label>
                        <input type="text"
                            class="form-control form-control-lg @error('floor_percentage') is-invalid @enderror"
                            id="floor_percentage" name="floor_percentage" placeholder="Floor Percentage" disabled
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
                                    {{ (isset($additionalCost) ? $additionalCost->applicable_on_unit : old('applicable_on_unit')) == 1 ? 'selected' : '' }} />
                                <label class="form-check-label" for="applicable_on_unit">Applicable On Unit</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <label class="form-label fs-5" for="unit_percentage">Unit Percentage</label>
                        <input type="text"
                            class="form-control form-control-lg @error('unit_percentage') is-invalid @enderror"
                            id="unit_percentage" name="unit_percentage" placeholder="Unit Percentage" disabled
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
