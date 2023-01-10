@include('app.sites.stakeholders.partials.stakeholder-form-fields')

{{-- custom fields --}}
@if (isset($customFields) && count($customFields) > 0)

    <div class="card" id="custom_fields" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
        <div class="card-header">
            <h3> Custom Fields</h3>
        </div>
        <div class="card-body">
            <hr>
            <div class="row mb-1 g-1">
                @forelse ($customFields as $field)
                    {!! $field !!}
                @empty
                @endforelse
            </div>
        </div>
    </div>
@endif

{{-- next-of-kin-list --}}
<div class="card" id="div-next-of-kin" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Next Of Kins </h3>
    </div>
    <div class="card-body">
        <div class="next-of-kin-list">
            <div data-repeater-list="next-of-kins">
                @forelse ((isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? $stakeholder->nextOfKin : old('next_of_kin')) ?? $emtyNextOfKin as $key => $KinData)

                    <div data-repeater-item>
                        <div class="card m-0">
                            <div class="card-header pt-0">
                                <h3>Next Of Kin</h3>

                                <button
                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                    data-repeater-delete id="delete-next-of-kin" type="button">
                                    <i data-feather="x" class="me-25"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div>
                                    <div class="row mb-1">
                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label" style="font-size: 15px"
                                                id="kin_{{ $key }}" for="stakeholder_type">Select Next Of
                                                Kin <span class="text-danger">*</span></label>
                                            <select class="form-control kinId uniqueKinId" id="kin_{{ $key }}"
                                                name="next_of_kin[{{ $key }}][stakeholder_id]">
                                                <option value="0" selected>Select Next Of Kin</option>
                                                @foreach ($stakeholders as $stakeholderssss)
                                                    @continue(isset($stakeholder) && $stakeholderssss->id == $stakeholder->id)

                                                    <option value="{{ $stakeholderssss->id }}"
                                                        {{ isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? ($stakeholderssss->id == $KinData->kin_id ? 'selected' : '') : '' }}>
                                                        {{ $stakeholderssss->full_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" id="relation_{{ $key }}"
                                                for="father_name">Relation</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('relation') is-invalid @enderror"
                                                id="relation_{{ $key }}"
                                                value="{{ isset($stakeholder) && count($stakeholder->nextOfKin) > 0 ? $KinData->relation : '' }}"
                                                name="next_of_kin[{{ $key }}][relation]" placeholder="Relation"
                                                value="" />
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

{{--  stakeholders in case of kins --}}

<div class="card" id="div_stakeholders" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Stakeholders</h3>
    </div>
    <div class="card-body">
        <div class="stakeholders-list">
            <div data-repeater-list="stakeholders">
                @forelse ((isset($stakeholder) && count($parentStakeholders) > 0 ? $parentStakeholders : old('stakeholders')) ?? $emtykinStakeholders as $key => $stakeholderData)

                    <div data-repeater-item>
                        <div class="card m-0">

                            <div class="card-body">

                                <div class="row mb-1">
                                    <input type="hidden" name="stakeholders[{{ $key }}][id]"
                                        value="{{ isset($stakeholder) && count($parentStakeholders) > 0 ? $stakeholderData->id : 0 }}">
                                    <div class="col-lg-5 col-md-5 col-sm-5 position-relative">
                                        <label class="form-label" style="font-size: 15px"
                                            id="stakeholders_{{ $key }}" for="stakeholder_type">Select
                                            Stakeholder
                                            <span class="text-danger">*</span></label>
                                        <select class="form-control selectStk" id="stakeholders_{{ $key }}"
                                            name="stakeholders[{{ $key }}][stakeholder_id]">
                                            <option value="0" selected>Select Stakeholder</option>
                                            @foreach ($stakeholders as $k => $stakeholderssss)
                                                @continue(isset($stakeholder) && $stakeholderssss->id == $stakeholder->id)

                                                <option value="{{ $stakeholderssss->id }}"
                                                    {{ isset($stakeholder) && count($stakeholder->KinStakeholders) > 0 ? ($stakeholderssss->id == $stakeholderData['stakeholder_id'] ? 'selected' : '') : '' }}>
                                                    {{ $stakeholderssss->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-5 col-md-5 col-sm-5 position-relative">
                                        <label class="form-label fs-5" id="relation_{{ $key }}"
                                            for="father_name">Relation</label>
                                        <input type="text"
                                            class="form-control form-control-md @error('relation') is-invalid @enderror"
                                            id="stakeholders_{{ $key }}[relation]"
                                            value="{{ isset($stakeholder) && count($stakeholder->KinStakeholders) > 0 ? $stakeholderData['relation'] : '' }}"
                                            name="stakeholders[{{ $key }}][relation]" placeholder="Relation"
                                            value="" />
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 position-relative text-center">
                                        <button
                                            class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap mt-2"
                                            data-repeater-delete id="delete-stakeholders" type="button">
                                            <i data-feather="x" class="me-25"></i>
                                            <span>Delete</span>
                                        </button>
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
                        id="first-stakeholder" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- contacts --}}
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
                                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                                            <label class="form-label" style="font-size: 15px"
                                                for="stackholders">Stakeholders</label>
                                            <select class="form-select contact-person-select"
                                                data-id="{{ $key }}"
                                                name="contact-persons[{{ $key }}][stakeholder_contact_id]">
                                                <option value="0" selected>Create new Stakeholder...</option>
                                                @forelse ($contactStakeholders as $cstakeholder)
                                                    @continue(isset($stakeholder) && $cstakeholder->id == $stakeholder->id)
                                                    <option value="{{ $cstakeholder->id }}"
                                                        {{ $oldContactPersons['stakeholder_contact_id'] == $cstakeholder->id ? 'selected' : '' }}>
                                                        {{ $cstakeholder->full_name }} s/o
                                                        {{ $cstakeholder->father_name }} {{ $cstakeholder->cnic }},
                                                        {{ $cstakeholder->contact }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="full_name_{{ $key }}">Full
                                                Name</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('full_name') is-invalid @enderror"
                                                id="full_name_{{ $key }}"
                                                name="contact-persons[{{ $key }}][full_name]"
                                                placeholder="Stakeholder Name"
                                                value="{{ $oldContactPersons['full_name'] }}" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="father_name">Father / Husband
                                                Name</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('father_name') is-invalid @enderror"
                                                id="father_name_{{ $key }}"
                                                name="contact-persons[{{ $key }}][father_name]"
                                                placeholder="Father / Husband Name"
                                                value="{{ $oldContactPersons['father_name'] }}" />
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                                            <label class="form-label fs-5" for="occupation">Occupation</label>
                                            <input type="text"
                                                class="form-control form-control-md @error('occupation') is-invalid @enderror"
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
                                                class="form-control form-control-md @error('designation') is-invalid @enderror"
                                                id="designation_{{ $key }}"
                                                name="contact-persons[{{ $key }}][designation]"
                                                placeholder="Designation"
                                                value="{{ $oldContactPersons['designation'] }}" />
                                        </div>
                                        <input type="hidden"
                                            name="contact-persons[{{ $key }}][countryDetails]"
                                            id="countryDetails">

                                        <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                                            <label class="form-label fs-5" for="contact">Contact</label>
                                            <input type="tel"
                                                class="form-control form-control-md intl @error('contact') is-invalid @enderror"
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
                                                class="unique cp_cnic form-control form-control-md @error('cnic') is-invalid @enderror"
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
                                                class="form-control form-control-md @error('ntn') is-invalid @enderror"
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
