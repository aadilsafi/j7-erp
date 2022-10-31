<select class="form-control text-capitalize text-nowrap required" style="width: 230px;" name="fields[]" required
    {{ $is_disable ? 'disabled' : '' }}>
    <option value="">...No match,select a field...</option>
    @foreach ($db_fields as $k => $db_field)
        @if ($db_field == 'cnic')
            <option class="text-danger" value="{{ $db_field }}" {{ $name == $db_field ? 'selected' : '' }}>
                {{ $db_field }}<span class="text-danger">*</span></option>
        @else
            <option value="{{ $db_field }}" {{ $name == $db_field ? 'selected' : '' }}>
                {{ $db_field }}
            </option>
        @endif
    @endforeach
</select>
