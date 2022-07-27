<div class="row mb-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <label class="form-label" style="font-size: 15px" for="typesTree">Types</label>
        <select class="select2-size-lg form-select" id="typesTree" name="type">
            <option value="0" selected>Parent Type</option>
            @foreach ($types as $typeRow)
                <option value="{{ $typeRow['id'] }}"
                    {{ (isset($type) ? $type->parent_id : old('type')) == $typeRow['id'] ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $typeRow['tree'] }}</option>
            @endforeach
        </select>
        @error('type')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Type Name</label>
        <input type="text" class="form-control form-control-lg @error('type_name') is-invalid @enderror" id="type_name"
            name="type_name" placeholder="Type Name" value="{{ isset($type) ? $type->name : old('type_name') }}" onkeyup="convertToSlug(this.value);" />
        @error('type_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Slug</label>
        <input type="text" class="form-control form-control-lg @error('type_slug') is-invalid @enderror" id="type_slug"
            name="type_slug" placeholder="Slug" readonly value="{{ isset($type) ? $type->slug : old('type_slug') }}" />
        @error('type_slug')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

</div>
