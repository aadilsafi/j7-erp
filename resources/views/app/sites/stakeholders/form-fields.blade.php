<div class="card">
    <div class="card-body" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
        @if (!isset($stakeholder))
            <div class="row mb-1">
                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                    <label class="form-label" style="font-size: 15px" for="stakeholder_type">Stakeholder Type</label>
                    <select class="form-select form-select-lg" id="stakeholder_type" name="stakeholder_type"
                        {{ isset($stakeholder) ? 'disabled' : null }}>
                        <option value="0" selected>Select Stakeholder Type</option>
                        @foreach ($stakeholderTypes as $key => $value)
                            @continue($value == 'K')
                            <option value="{{ $value }}">
                                {{ Str::of($key)->lower()->ucfirst()->replace('_', ' ') }}
                            </option>
                        @endforeach
                    </select>
                    @error('stakeholder_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @else
            <div class="row mb-1">
                <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                    <div class="d-flex justify-content-between">
                        @forelse ($stakeholder->stakeholder_types as $type)
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <span
                                    class="badge badge-light-{{ $type->status ? 'success' : 'danger' }} fs-5 mb-50">{{ $type->stakeholder_code }}</span>
                                <div class="form-check form-switch form-check-success">
                                    <input type="checkbox" class="form-check-input"
                                        id="stakeholder_type_{{ $type->type }}"
                                        onchange="performAction('{{ $type->type }}')"
                                        name="stakeholder_type[{{ $type->type }}]" value="1"
                                        {{ $type->status ? 'checked' : null }}
                                        {{ $type->status || $type->type == 'K' ? 'disabled' : null }} />
                                    <label class="form-check-label" for="stakeholder_type_{{ $type->type }}">
                                        <span class="switch-icon-left"><i data-feather="check"></i></span>
                                        <span class="switch-icon-right"><i data-feather="x"></i></span>
                                    </label>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
            <hr>
        @endif
        <div class="row mb-1">

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="full_name">Full Name</label>
                <input type="text" class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                    id="full_name" name="full_name" placeholder="Stakeholder Name"
                    value="{{ isset($stakeholder) ? $stakeholder->full_name : old('full_name') }}" />
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="father_name">Father Name</label>
                <input type="text" class="form-control form-control-lg @error('father_name') is-invalid @enderror"
                    id="father_name" name="father_name" placeholder="Father Name"
                    value="{{ isset($stakeholder) ? $stakeholder->father_name : old('father_name') }}" />
                @error('father_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="occupation">Occupation</label>
                <input type="text" class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                    id="occupation" name="occupation" placeholder="Occupation"
                    value="{{ isset($stakeholder) ? $stakeholder->occupation : old('occupation') }}" />
                @error('occupation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="row mb-1">

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="designation">Designation</label>
                <input type="text" class="form-control form-control-lg @error('designation') is-invalid @enderror"
                    id="designation" name="designation" placeholder="Designation"
                    value="{{ isset($stakeholder) ? $stakeholder->designation : old('designation') }}" />
                @error('designation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="contact">Contact</label>
                <input type="number" class="form-control form-control-lg @error('contact') is-invalid @enderror"
                    id="contact" name="contact" placeholder="Contact Number"
                    value="{{ isset($stakeholder) ? $stakeholder->contact : old('contact') }}" />
                @error('contact')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="cnic">CNIC</label>
                <input type="number" class="cp_cnic form-control form-control-lg @error('cnic') is-invalid @enderror"
                    id="cnic" name="cnic" placeholder="CNIC Without Dashes" min="13"
                    value="{{ isset($stakeholder) ? $stakeholder->cnic : old('cnic') }}" />
                @error('cnic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="ntn">NTN</label>
                <input type="number" class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                    id="ntn" name="ntn" placeholder="NTN Number"
                    value="{{ isset($stakeholder) ? $stakeholder->ntn : old('ntn') }}" />
                @error('ntn')
                    <div class="invalid-feedback ">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="address">Stakeholder Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                    placeholder="Stakeholder Address">{{ isset($stakeholder) ? $stakeholder->address : old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="comments">Comments</label>
                <textarea class="form-control @error('comments') is-invalid @enderror" name="comments" id="comments" rows="3"
                    placeholder="Comments">{{ isset($stakeholder) ? $stakeholder->comments : old('comments') }}</textarea>
                @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1" id="div-next-of-kin"
            style="{{ isset($stakeholder) && $stakeholder->stakeholder_types->where('type', 'C')->first()->status ? null : 'display: none;' }}">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label" style="font-size: 15px" for="parent_id">Select Kin</label>
                <select class="form-select form-select-lg" id="parent_id" name="parent_id">
                    <option value="0" selected>Select Kin</option>
                    @foreach ($stakeholders as $stakeholderRow)
                        @continue(!$stakeholderRow->stakeholder_types->where('type', 'C')->first()->status)
                        @continue(isset($stakeholder) && $stakeholderRow['id'] == $stakeholder->id)
                        <option value="{{ $stakeholderRow['id'] }}"
                            {{ (isset($stakeholder) ? $stakeholder->parent_id : old('type')) == $stakeholderRow['id'] ? 'selected' : '' }}>
                            {{ $loop->index + 1 }} - {{ $stakeholderRow['full_name'] }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="relation">Relation</label>
                <input type="text" class="form-control form-control-lg @error('relation') is-invalid @enderror"
                    id="stakeholder_name" name="relation" placeholder="Relation"
                    value="{{ isset($stakeholder) ? $stakeholder->relation : old('stakeholder_name') }}" />
                @error('relation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Contact Persons</h3>

    </div>
    <div class="card-body">
        <div class="contact-persons-list">
            <div data-repeater-list="contact-persons">
                @forelse ((isset($stakeholder) && count($stakeholder->contacts) > 0 ? $stakeholder->contacts : old('contact-persons')) ?? $emptyRecord as $key => $oldContactPersons)
                    <div data-repeater-item>
                        <div class="card m-0">
                            <div class="card-header pt-0">
                                <h3>Contact Person</h3>

                                <button
                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                    data-repeater-delete id="delete-contact-person" type="button">
                                    <i data-feather="x" class="me-25"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="full_name_{{ $key }}">Full
                                                Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                                                id="full_name_{{ $key }}"
                                                name="contact-persons[{{ $key }}][full_name]"
                                                placeholder="Stakeholder Name"
                                                value="{{ $oldContactPersons['full_name'] }}" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="father_name">Father Name</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('father_name') is-invalid @enderror"
                                                id="father_name_{{ $key }}"
                                                name="contact-persons[{{ $key }}][father_name]"
                                                placeholder="Father Name"
                                                value="{{ $oldContactPersons['father_name'] }}" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="occupation">Occupation</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                                                id="occupation_{{ $key }}"
                                                name="contact-persons[{{ $key }}][occupation]"
                                                placeholder="Occupation"
                                                value="{{ $oldContactPersons['occupation'] }}" />
                                        </div>

                                    </div>

                                    <div class="row mb-1">

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="designation">Designation</label>
                                            <input type="text"
                                                class="form-control form-control-lg @error('designation') is-invalid @enderror"
                                                id="designation_{{ $key }}"
                                                name="contact-persons[{{ $key }}][designation]"
                                                placeholder="Designation"
                                                value="{{ $oldContactPersons['designation'] }}" />
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="contact">Contact</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('contact') is-invalid @enderror"
                                                id="contact_{{ $key }}"
                                                name="contact-persons[{{ $key }}][contact]"
                                                placeholder="Contact Number"
                                                value="{{ $oldContactPersons['contact'] }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-1">

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="cnic">CNIC</label>
                                            <input type="number"
                                                class="unique cp_cnic form-control form-control-lg @error('cnic') is-invalid @enderror"
                                                id="cnic_{{ $key }}"
                                                name="contact-persons[{{ $key }}][cnic]"
                                                placeholder="CNIC Without Dashes"
                                                value="{{ $oldContactPersons['cnic'] }}" />
                                            @error('contact-persons.cnic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="ntn">NTN</label>
                                            <input type="number"
                                                class="form-control form-control-lg @error('ntn') is-invalid @enderror"
                                                id="ntn_{{ $key }}"
                                                name="contact-persons[{{ $key }}][ntn]"
                                                placeholder="NTN Number" value="{{ $oldContactPersons['ntn'] }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label fs-5" for="address">Stakeholder Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                name="contact-persons[{{ $key }}][address]" id="address_{{ $key }}" rows="3"
                                                placeholder="Stakeholder Address">{{ $oldContactPersons['address'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                        id="first-contact-person" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
