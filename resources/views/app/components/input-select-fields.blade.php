<select class="form-control text-capitalize text-nowrap required selectField unit-p-select" data-id="{{ $id }}"
    data-field="{{ $field }}" id="unit_p_select_div_{{ $field }}{{ $id }}">

    @foreach ($values as $k => $value)
        <option value="{{ $k }}" {{ $selectedValue == $k ? 'selected' : '' }}>
            {{ $value }}
        </option>
    @endforeach
</select>
