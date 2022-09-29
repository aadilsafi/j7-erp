<div class="row mb-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <label class="form-label" style="font-size: 15px" for="teamsTree">Teams</label>
        <select class="select2-size-lg form-select" id="teamsTree" name="team">
            <option value="0" selected>Parent Team</option>
            @foreach ($teams as $typeRow)
            @continue(isset($team) && $team->id == $typeRow['id'])
            <option value="{{ $typeRow['id'] }}" {{ (isset($team) ? $team->parent_id : old('team')) == $typeRow['id'] ?
                'selected' : '' }}>
                {{ $loop->index + 1 }} - {{ $typeRow['tree'] }}</option>
            @endforeach
        </select>
        @error('team')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="team_name">Team Name</label>
        <input type="text" class="form-control form-control-lg @error('team_name') is-invalid @enderror" id="team_name"
            name="team_name" placeholder="Team Name" value="{{ isset($team) ? $team->name : old('team_name') }}"
            onkeyup="convertToSlug(this.value);" />
        @error('team_name')
        <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div>


<div class="row my-2">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        <div class="form-check form-check-primary">
            <input type="hidden" name="has_team" value="0">
            <input type="checkbox" class="form-check-input" id="has_team" name="has_team" value="1" {{ isset($team) ?
                ($team->has_team == 1 ? 'checked' : 'unchecked') : (is_null(old('has_team')) ? 'checked' :
            (old('has_team') == 1 ? 'checked' : 'unchecked')) }} />
            <label class="form-check-label" for="has_team">Has Sub Team</label>
        </div>
    </div>
</div>

<div class="row mb-2">

    <div class="row mb-1 hasChildCard" id="hasChildCard" style="display: none;" <div
        class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <label class="form-label fs-5" style="font-size: 15px" for="user_id">Users</label>
        <select class="form-select form-select-lg" id="user_id" name="user_id[]" multiple="multiple"
            placeholder="Select Users">
            <option disabled>Select User</option>
            @foreach ($users as $key => $value)
            <option value="{{ $value->id }}" {{ isset($team_users) && in_array($value->name, $team_users) ? 'selected' :
                null }}>
                {{ $value->name }}</option>
            @endforeach
        </select>
        @error('role')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

</div>
</div>