<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="name">Site Name</label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Site Name" value="{{ isset($site) ? $site->name : null }}" />
        @error('name')
        <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="address">Address</label>
        <input type="text" class="form-control form-control-lg" id="address" name="address" placeholder="Address"
            value="{{ isset($site) ? $site->address : '' }}" />
        @error('address')
        <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="area_width">Area Width (sqrt)</label>
        <input type="text" class="form-control form-control-lg" id="area_width" name="area_width"
            placeholder="Area Width in Sqft" value="{{ isset($site) ? $site->area_width : '' }}" />
        @error('area_width')
        <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="area_length">Area Length (sqrt)</label>
        <input type="text" class="form-control form-control-lg  " id="area_length" name="area_length"
            placeholder="Area Length in Sqft" value="{{ isset($site) ? $site->area_length : '' }}" />
        @error('area_length')
        <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mb-1">

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
        <label class="form-label" style="font-size: 15px" for="country">Countries</label>
        <select class="select2-size-lg form-select" id="countries" name="country">
            <option value="0" selected disabled>Country</option>
            @foreach ($countries as $country)
                <option value="{{ $country['id'] }}"
                    {{ old('country') == $country->id ? 'selected' : ( isset($site) && $site->country->id == $country->id ? 'selected' : '') }}>
                    {{ $country['name'] }}</option>
            @endforeach
        </select>
        @error('type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
        <label class="form-label" style="font-size: 15px" for="city">Cities</label>
        <select class="select2-size-lg form-select" id="city" name="city">
            <option value="0" selected disabled>City</option>
        </select>
        @error('type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="max_floors">Max Floors</label>
        <input type="text" class="form-control form-control-lg  " id="max_floors" name="max_floors"
            placeholder="Max Floors" value="{{ isset($site) ? $site->max_floors : '' }}" />
        @error('max_floors')
        <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>
