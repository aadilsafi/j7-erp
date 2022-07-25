<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="role_name">Role Name</label>
        <input type="text" class="form-control form-control-lg @error('role_name') is-invalid @enderror" id="role_name"
            name="role_name" placeholder="Role Name" value="{{ isset($role) ? $role->name : null }}" />
        @error('role_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="guard_name">Guard Name</label>
        <input type="text" class="form-control form-control-lg  " id="guard_name" name="guard_name" placeholder="web"
            value="{{ isset($role) ? $role->guard_name : 'web' }}" />
        @error('guard_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="form-check form-check-inline">
            <input type="hidden" name="default" value="0" />
            <input class="form-check-input @error('default') is-invalid @enderror" {{ isset($role) && $role->default ? 'checked' : null }} type="checkbox" id="default"
                name="default" value="1" />
            <label class="form-check-label" for="default">Default</label>
        </div>
        @error('default')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>
