<div class="row mb-1">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
        <label class="form-label" style="font-size: 15px" for="stakeholderTree">Next Of Kin</label>
        <select class="select2-size-lg form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
            <option value="0" selected>Parent Type</option>
            @foreach ($stakeholders as $stakeholderRow)
                @continue(isset($type) && $type->id == $stakeholderRow['id'])
                <option value="{{ $stakeholderRow['id'] }}"
                    {{ (isset($stakeholder) ? $stakeholder->parent_id : old('type')) == $stakeholderRow['id'] ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $stakeholderRow['tree'] }}</option>
            @endforeach
        </select>
        @error('parent_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Next Of Kin Relation</label>
        <input type="text" class="form-control form-control-lg @error('relation') is-invalid @enderror"
            id="stakeholder_name" name="relation" placeholder="Next Of Kin Relation"
            value="{{ isset($stakeholder) ? $stakeholder->relation : old('stakeholder_name') }}" onkeyup="convertToSlug(this.value);" />
        @error('relation')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1 mt-2">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Full Name</label>
        <input type="text" class="form-control form-control-lg @error('full_name') is-invalid @enderror"
            id="stakeholder_name" name="full_name" placeholder="Stakeholder Name"
            value="{{ isset($stakeholder) ? $stakeholder->full_name : old('stakeholder_name') }}" onkeyup="convertToSlug(this.value);" />
        @error('full_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Father Name</label>
        <input type="text" class="form-control form-control-lg @error('father_name') is-invalid @enderror"
            id="father_name" name="father_name" placeholder="Father Name"
            value="{{ isset($stakeholder) ? $stakeholder->father_name : old('father_name') }}" onkeyup="convertToSlug(this.value);" />
        @error('father_name')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 mt-2 position-relative">
        <label class="form-label fs-5" for="type_name">Occupation</label>
        <input type="text" class="form-control form-control-lg @error('occupation') is-invalid @enderror"
            id="occupation" name="occupation" placeholder="Occupation Name"
            value="{{ isset($stakeholder) ? $stakeholder->occupation : old('occupation') }}" onkeyup="convertToSlug(this.value);" />
        @error('occupation')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 mt-2 position-relative">
        <label class="form-label fs-5" for="type_name">Designation</label>
        <input type="text" class="form-control form-control-lg @error('designation') is-invalid @enderror"
            id="designation" name="designation" placeholder="Designation"
            value="{{ isset($stakeholder) ? $stakeholder->designation : old('designation') }}" onkeyup="convertToSlug(this.value);" />
        @error('designation')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 mt-2 position-relative">
        <label class="form-label fs-5" for="type_name">CNIC</label>
        <input type="text" class="form-control form-control-lg @error('cnic') is-invalid @enderror"
            id="cnic" name="cnic" placeholder="CNIC"
            value="{{ isset($stakeholder) ? $stakeholder->cnic : old('cnic') }}" onkeyup="convertToSlug(this.value);" />
        @error('cnic')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>



    <div class="col-lg-6 col-md-6 col-sm-6 mt-2 position-relative">
        <label class="form-label fs-5" for="type_name">Contact</label>
        <input type="text" class="form-control form-control-lg @error('contact') is-invalid @enderror"
            id="contact" name="contact" placeholder="Contact Number"
            value="{{ isset($stakeholder) ? $stakeholder->contact : old('contact') }}" onkeyup="convertToSlug(this.value);" />
        @error('contact')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 mt-2 position-relative">
        <label class="form-label fs-5" for="type_name">CNIC Attachment</label>
        <input id="attachment" type="file"
            class="filepond "
            name="attachment[]"
            multiple
            accept="image/png, image/jpeg, image/gif"/>
        @error('attachment')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 mt-2 position-relative">
        <label class="form-label fs-5" for="type_name">Stakeholder Address</label>
        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Stakeholder Address">
        </textarea>
        {{-- <input type="text" class="form-control form-control-lg @error('contact') is-invalid @enderror"
            id="address" name="address" placeholder="Address"
            value="{{ isset($stakeholder) ? $stakeholder->address : old('address') }}" onkeyup="convertToSlug(this.value);" /> --}}
        @error('address')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>



</div>


