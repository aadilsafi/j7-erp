<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="permission_name">Permission Name</label>
        <input type="text" class="form-control form-control-lg @error('permission_name') is-invalid @enderror" id="permission_name"
        name="permission_name" placeholder="Permission Name" value="{{ isset($permission) ? $permission->name : null }}" />
        {{-- <span class="text-muted mt-2">Enter permission name</span> --}}
        @error('permission_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="guard_name">Guard Name</label>
        <input type="text" class="form-control form-control-lg  " id="guard_name" name="guard_name" placeholder="web"
            value="{{ isset($permission) ? $permission->guard_name : 'web' }}" />
        @error('guard_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>
