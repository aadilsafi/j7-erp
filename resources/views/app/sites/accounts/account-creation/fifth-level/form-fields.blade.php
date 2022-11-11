<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="name">Account Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-md @error('account_code') is-invalid @enderror"
                    id="name" name="name" placeholder="Account Name"
                    value="{{ isset($first_level) ? $first_level->name : old('name') }}" />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">Enter Account Name.</small></p>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="second_level">Third Level Code <span
                        class="text-danger">*</span></label>
                <select class="select2" name="fourth_level" id="fourth_level">
                    @foreach ($fourthLevelAccount as $fourthLevel)
                        <option value="{{ $fourthLevel->code }}">{{ $fourthLevel->name }} ({{ $fourthLevel->code }})
                        </option>
                    @endforeach
                </select>
                @error('fourth_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">Select Fourth Level Account Code.</small></p>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="account_code">Account Code <span
                        class="text-danger">*</span></label>
                <input type="number" maxLength="2"
                    class="form-control form-control-md @error('account_code') is-invalid @enderror" id="account_code"
                    name="account_code" placeholder="Account Code"
                    value="{{ isset($first_level) ? $first_level->account_code : old('account_code') }}" />
                @error('account_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">Enter Four Digit Code.</small></p>
                @enderror
            </div>
        </div>
    </div>
</div>
