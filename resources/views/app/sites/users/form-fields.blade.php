<div class="row mb-1">
    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label fs-5" for="name">Full Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Name" value="{{ isset($user) ? $user->name : old('name') }}" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label fs-5" for="type_name">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
            name="email" placeholder="Email" autocomplete="false"
            value="{{ isset($user) ? $user->email : old('email') }}" />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mb-1">
    @can('sites.users.edit.password')
        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
            <label class="form-label fs-5" for="type_name">Password <span class="text-danger">*</span></label>

            <input id="password" type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="password"
                placeholder="Password" autocomplete="false">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
            <label for="password-confirm" class="form-label fs-5">Confirm Password <span
                    class="text-danger">*</span></label>
            <input id="password-confirm" class="form-control form-control-lg" type="password" class="form-control"
                placeholder="Confirm Password" name="password_confirmation">
        </div>
    @endcan

</div>
<input type="hidden" name="Userid" value="{{ isset($user) ? $user->id : '' }}">

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="designation">Designation </label>
        <input type="text" class="form-control form-control-lg @error('designation') is-invalid @enderror"
            id="designation" name="designation" placeholder="Designation"
            value="{{ isset($user) ? $user->designation : old('designation') }}" />
        @error('designation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="cnic">CNIC <span class="text-danger">*</span></label>
        <input type="text" class="cp_cnic form-control form-control-md @error('cnic') is-invalid @enderror"
            id="cnic" name="cnic" placeholder="CNIC Without Dashes"
            value="{{ isset($user) ? $user->cnic : old('cnic') }}" />
        @error('cnic')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
   
</div>
<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <label class="form-label fs-5" for="contact">Contact # <span class="text-danger">*</span></label>
        <input type="tel" class="form-control form-control-lg ContactNoError @error('contact') is-invalid @enderror"
            id="contact" name="contact" placeholder=""
            value="{{ isset($user) ? $user->contact : old('contact') }}" />
        @error('contact')
            <div class="invalid-feedback ">{{ $message }}</div>
        @enderror
    </div>
    <input type="hidden" name="countryDetails" id="countryDetails">

    <div class="col-lg-6 col-md-6 col-sm-6">
        <label class="form-label fs-5" for="contact">Optional Contact # </label>
        <input type="tel"
            class="form-control form-control-lg OPTContactNoError @error('contact') is-invalid @enderror"
            id="optional_contact" name="optional_contact" placeholder=""
            value="{{ isset($user) ? $user->optional_contact : old('optional_contact') }}" />
        @error('optional_contact')
            <div class="invalid-feedback ">{{ $message }}</div>
        @enderror
    </div>
    <input type="hidden" name="OptionalCountryDetails" id="OptionalCountryDetails">
</div>
<div class="row mb-1">

    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
        <label class="form-label" style="font-size: 15px" for="parent_id">Select Country</label>
        <select class="select2" id="country_id" name="country_id">
            <option value="0" selected>Select Country</option>
            @foreach ($country as $countryRow)
                <option @if (isset($user) && $user->country_id == $countryRow->id) selected @endif value="{{ $countryRow->id }}">
                    {{ $countryRow->name }}</option>
            @endforeach
        </select>
        @error('country_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
        <label class="form-label" style="font-size: 15px" for="city_id">Select State</label>
        <select class="select2 " id="state_id" name="state_id">
            <option value="0" selected>Select State</option>
            @foreach ($state as $stateRow)
                <option @if (isset($user) && $user->state_id == $stateRow->id) selected @endif value="{{ $stateRow->id }}">
                    {{ $stateRow->name }}</option>
            @endforeach
        </select>
        @error('state_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
        <label class="form-label" style="font-size: 15px" for="city_id">Select City</label>
        <select class="select2 " id="city_id" name="city_id">
            <option value="0" selected>Select City</option>
            @foreach ($city as $cityRow)
                <option @if (isset($user) && $user->city_id == $cityRow->id) selected @endif value="{{ $cityRow->id }}">
                    {{ $cityRow->name }}</option>
            @endforeach
        </select>
        @error('city_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 position-relative">
        <label class="form-label fs-5" for="occupation">Nationality </label>
        <input type="text" class="form-control form-control-lg @error('occupation') is-invalid @enderror"
            id="nationality" name="nationality" placeholder="Nationality"
            value="{{ isset($user) ? $user->nationality : old('nationality') }}" />
        @error('nationality')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="address">Permanent Address <span class="text-danger">*</span></label>
        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
            placeholder="User Address">{{ isset($user) ? $user->address : old('address') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="mailing_address">Mailing Address <span class="text-danger">*</span> <span
                class="text-info">( Same as Permanent Address <input type="checkbox" id="cpyAddress" />
                )</span></label>
        <textarea class="form-control @error('mailing_address') is-invalid @enderror" name="mailing_address"
            id="mailing_address" rows="3" placeholder="Mailing Address">{{ isset($user) ? $user->mailing_address : old('mailing_address') }}</textarea>
        @error('mailing_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label fs-5" style="font-size: 15px" for="role_id">Role <span
                class="text-danger">*</span></label>
        <select class="form-select form-select-lg" id="role_id" name="role_id[]" multiple="multiple"
            placeholder="Select Roles">
            <option disabled>Select Role</option>
            @foreach ($roles as $key => $value)
                <option value="{{ $value->id }}"
                    {{ isset($Selectedroles) && in_array($value->name, $Selectedroles) ? 'selected' : null }}>
                    {{ $value->name }}
                </option>
            @endforeach
        </select>
        @error('role')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
@if (isset($customFields) && count($customFields) > 0)
    <hr>
    <div class="row mb-1 g-1">
        @forelse ($customFields as $field)
            {!! $field !!}
        @empty
        @endforelse
    </div>
@endif
