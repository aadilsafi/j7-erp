<div class="card" id="div-next-of-kin" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;padding:10px">

<div class="row mb-1">

    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label fs-5" for="name">Full Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"  id="name" name="name" placeholder="Name" value="{{ isset($blacklist) ? $blacklist->name : old('name') }}"  />
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label fs-5" for="fatherName">Father Name <span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control form-control-lg @error('fatherName') is-invalid @enderror"
            id="fatherName"
            name="fatherName"
            placeholder="fatherName"
            value="{{ isset($blacklist) ? $blacklist->fatherName : old('fatherName') }}"
        />
        @error('fatherName')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label fs-5" for="cnic">CNIC<span class="text-danger showRequired">*</span></label>
        <input
            type="text"
            class="cp_cnic form-control form-control-md @error('cnic') is-invalid @enderror"
            id="cnic"

            name="cnic"
            placeholder="CNIC Without Dashes"
            value="{{ isset($blacklist) ? $blacklist->cnic : old('individual.cnic') }}"
        />
        @error('cnic')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

     <div class="col-lg-6 col-md-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="mailing_country">Select
                            Country <span class="text-danger showRequired">*</span></label>
                        <select class="select2" id="country" name="country">
                            <option value="0" selected>Select Country</option>
                            @foreach ($country as $countryRow)
                                <option @if ((isset($stakeholder) && $stakeholder->state->country_id) == $countryRow->id) selected @endif value="{{ $countryRow->id }}"> {{ $countryRow->name }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label" style="font-size: 15px;" for="state">Select State <span class="text-danger showRequired">*</span></label>
        <select class="select2" id="state" class="state" name="province">
            <option value="" selected>Select State</option>
        </select>
        @error('province')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 position-relative">
        <label class="form-label" style="font-size: 15px;" for="city">Select City <span class="text-danger showRequired">*</span></label>
        <select class="select2" id="city" name="district">
            <option value="" selected>Select City</option>
        </select>
        @error('district')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
</div>
