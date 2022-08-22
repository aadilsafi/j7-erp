<div class="row mb-1">

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2">
        <label class="form-label" style="font-size: 15px" for="roles">Roles</label>
        <select class="select2-size-lg form-select" id="roles" name="parent_id">
            {{-- <option value="1" selected>Parent Role</option> --}}
            @foreach ($roles as $roleRow)
                @continue(isset($role) && $role->id == $roleRow['id'])

                <option value="{{ $roleRow['id'] }}"
                    {{ in_array($roleRow->name, ['Director', 'Admin', 'Super Admin']) ? 'selected' : '' }}
                    {{ (isset($role) ? $role->parent_id : old('type')) == $roleRow['id'] ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $roleRow['tree'] }}</option>
            @endforeach
        </select>
        @error('parent_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="role_name">Role Name</label>
        <input type="text" class="form-control form-control-lg @error('role_name') is-invalid @enderror"
            id="role_name" name="role_name" placeholder="Role Name" value="{{ isset($role) ? $role->name : null }}" />
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
            <input class="form-check-input @error('default') is-invalid @enderror"
                {{ isset($role) && $role->default ? 'checked' : null }} type="checkbox" id="default" name="default"
                value="1" />
            <label class="form-check-label" for="default">Default</label>
        </div>
        @error('default')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>
