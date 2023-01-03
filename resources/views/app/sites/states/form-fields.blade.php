<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label  fs-5" style="font-size: 15px" for="country_id">Country<span
                class="text-danger">*</span></label>
        <select class="form-select select2 form-select-lg" id="country_id" name="country_id" placeholder="Select Country"
            required>
            <option selected>Select Country</option>
            @foreach ($country as $value)
                <option value="{{ $value->id }}"
                    {{ isset($state) && $state->country_id == $value->id ? 'selected' : null }}>
                    {{ $value->name }}
                </option>
            @endforeach
        </select>
        @error('country_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="name">State<span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="State" value="{{ isset($state) ? $state->name : old('name') }}" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name"> Short Label<span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('short_label') is-invalid @enderror"
            id="short_label" name="short_label" placeholder="Short Label"
            value="{{ isset($state) ? $state->iso2 : old('short_label') }}" required />
        @error('short_label')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

