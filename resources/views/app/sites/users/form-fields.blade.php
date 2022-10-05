<div class="row mb-1">
    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="name">Full Name</label>
        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
            name="name" placeholder="Name" value="{{ isset($user) ? $user->name : old('name') }}" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Email</label>
        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
            name="email" placeholder="Email" autocomplete="false"
            value="{{ isset($user) ? $user->email : old('email') }}" />
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Phone Number</label>
        <input type="number" class="form-control form-control-lg @error('phone_no') is-invalid @enderror"
            id="phone_no" name="phone_no" placeholder="03xxxxxxxxx"
            value="{{ isset($user) ? $user->phone_no : old('phone_no') }}" />
        @error('phone_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row mb-1">
    @can('sites.users.edit.password')
        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
            <label class="form-label fs-5" for="type_name">Password</label>

            <input id="password" type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="password"
                placeholder="Password" autocomplete="false">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
            <label for="password-confirm" class="form-label fs-5">Confirm Password</label>
            <input id="password-confirm" class="form-control form-control-lg" type="password" class="form-control"
            placeholder="Confirm Password"  name="password_confirmation">
        </div>
    @endcan

</div>

<input type="hidden" name="Userid" value="{{ isset($user) ? $user->id : '' }}">

<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label fs-5" style="font-size: 15px" for="role_id">Role</label>
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