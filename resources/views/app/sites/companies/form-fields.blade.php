<div class="row mb-1">
    <div class="col-lg col-md position-relative">
        <label class="form-label fs-5" for="name">Company Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Name" value="{{ isset($company) ? $company->name : old('name') }}" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg col-md position-relative">
        <label class="form-label fs-5" for="type_name">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
            name="email" placeholder="Email" autocomplete="false"
            value="{{ isset($company) ? $company->email : old('email') }}" />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg col-md col-sm">
        <label class="form-label fs-5" for="contact">Contact # <span class="text-danger">*</span></label>
        <input type="tel" class="form-control form-control-lg ContactNoError @error('contact') is-invalid @enderror"
            id="contact" name="contact_no" placeholder=""
            value="{{ isset($company) ? $company->contact : old('contact') }}" />
        @error('contact')
            <div class="invalid-feedback ">{{ $message }}</div>
        @enderror
    </div>
    <input type="hidden" name="countryDetails" id="countryDetails">
</div>

<div class="row mb-1">
    <div class="col-lg col-md col-sm position-relative">
        <label class="form-label fs-5" for="address">Permanent Address <span class="text-danger">*</span></label>
        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
            placeholder="company Address">{{ isset($company) ? $company->address : old('address') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
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
