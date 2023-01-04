<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label  fs-5" style="font-size: 15px" for="country_id">Country<span
                class="text-danger">*</span></label>
        <select class="form-select select2 form-select-lg" id="country_id" name="country_id" placeholder="Select Country" required>
            <option selected>Select Country</option>
            @foreach ($country as $value)
                <option value="{{ $value->id }}">
                    {{ $value->name }}
                </option>
            @endforeach
        </select>
        @error('country_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label  fs-5" style="font-size: 15px" for="state_id">State<span
                class="text-danger">*</span></label>
        <select class="form-select select2 form-select-lg" id="state_id" name="state_id" placeholder="Select State" required>
            <option selected>Select State</option>
        </select>
        @error('state_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">

    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label fs-5" for="type_name">Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Name" value="{{ isset($city) ? $city->name : old('name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- <input type="hidden" name="Userid" value="{{ isset($state) ? $state->id : '' }}"> --}}

{{-- @if (isset($customFields) && count($customFields) > 0)
    <hr>
    <div class="row mb-1 g-1">
        @forelse ($customFields as $field)
            {!! $field !!}
        @empty
        @endforelse
    </div>
@endif --}}
