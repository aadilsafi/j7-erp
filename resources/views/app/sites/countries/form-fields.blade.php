<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="name">Country Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Name" value="{{ isset($country) ? $country->name : old('name') }}" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Capital <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('capital') is-invalid @enderror" id="capital"
            name="capital" placeholder="Capital"
            value="{{ isset($country) ? $country->capital : old('capital') }}" />
        @error('capital')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name"> Short Label<span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('short_label') is-invalid @enderror" id="short_label"
            name="short_label" placeholder="Short Label"
            value="{{ isset($country) ? $country->iso3 : old('short_label') }}" />
        @error('short_label')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Phone Code <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('phone_code') is-invalid @enderror" id="phone_code"
            name="phone_code" placeholder="Phone Code"
            value="{{ isset($country) ? $country->phonecode : old('phone_code') }}" />
        @error('phone_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
