@if (!isset($stakeholder))
    <div class="row mb-1">
        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
            <label class="form-label" style="font-size: 15px" for="stakeholder_type">Stakeholder Type</label>
            <select class="form-select form-select-lg" id="stakeholder_type" name="stakeholder_type"
                {{ isset($stakeholder) ? 'disabled' : null }}>
                <option value="0" selected>Select Stakeholder Type</option>
                @foreach ($stakeholderTypes as $key => $value)
                    @continue($value == 'K')
                    {{-- @continue(isset($stakeholder) && $stakeholder->id == $stakeholderRow['id']) --}}
                    <option value="{{ $value }}">{{ Str::of($key)->lower()->ucfirst()->replace('_', ' ') }}
                    </option>
                @endforeach
            </select>
            @error('stakeholder_type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
@endif

<div class="row mb-1">

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Full Name</label>
        <input type="text" class="form-control form-control-lg @error('full_name') is-invalid @enderror"
            id="full_name" name="full_name" placeholder="Stakeholder Name"
            value="{{ isset($stakeholder) ? $stakeholder->full_name : old('full_name') }}" />
        @error('full_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Father Name</label>
        <input type="text" class="form-control form-control-lg @error('father_name') is-invalid @enderror"
            id="father_name" name="father_name" placeholder="Father Name"
            value="{{ isset($stakeholder) ? $stakeholder->father_name : old('father_name') }}" />
        @error('father_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Occupation</label>
        <input type="text" class="form-control form-control-lg @error('occupation') is-invalid @enderror"
            id="occupation" name="occupation" placeholder="Occupation Name"
            value="{{ isset($stakeholder) ? $stakeholder->occupation : old('occupation') }}" />
        @error('occupation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row mb-1">

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Designation</label>
        <input type="text" class="form-control form-control-lg @error('designation') is-invalid @enderror"
            id="designation" name="designation" placeholder="Designation"
            value="{{ isset($stakeholder) ? $stakeholder->designation : old('designation') }}" />
        @error('designation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">CNIC</label>
        <input type="text" class="form-control form-control-lg @error('cnic') is-invalid @enderror" id="cnic"
            name="cnic" placeholder="CNIC Without Dashes"
            value="{{ isset($stakeholder) ? $stakeholder->cnic : old('cnic') }}" />
        @error('cnic')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
        <label class="form-label fs-5" for="type_name">Contact</label>
        <input type="text" class="form-control form-control-lg @error('contact') is-invalid @enderror" id="contact"
            name="contact" placeholder="Contact Number"
            value="{{ isset($stakeholder) ? $stakeholder->contact : old('contact') }}" />
        @error('contact')
            <div class="invalid-feedback ">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label fs-5" for="type_name">Stakeholder Address</label>
        <textarea class="form-control @error('contact') is-invalid @enderror" name="address" id="address" rows="3"
            placeholder="Stakeholder Address">{{ isset($stakeholder) ? $stakeholder->address : old('address') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-1">

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label" style="font-size: 15px" for="parent_id">Next Of Kin</label>
        <select class="form-select form-select-lg" id="parent_id" name="parent_id">
            <option value="0" selected>Select Next Of Kin</option>
            @foreach ($stakeholders as $stakeholderRow)
                @continue(!$stakeholderRow->stakeholder_types->where('type', 'C')->first()->status)
                <option value="{{ $stakeholderRow['id'] }}"
                    {{ (isset($stakeholder) ? $stakeholder->parent_id : old('type')) == $stakeholderRow['id'] ? 'selected' : '' }}>
                    {{ $loop->index + 1 }} - {{ $stakeholderRow['tree'] }}</option>
            @endforeach
        </select>
        @error('parent_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="type_name">Next Of Kin Relation</label>
        <input type="text" class="form-control form-control-lg @error('relation') is-invalid @enderror"
            id="stakeholder_name" name="relation" placeholder="Next Of Kin Relation"
            value="{{ isset($stakeholder) ? $stakeholder->relation : old('stakeholder_name') }}" />
        @error('relation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
