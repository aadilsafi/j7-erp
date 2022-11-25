@if ($with_col)
    <div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
@endif

<label class="form-label fs-5 text-capitalize" for="{{ $id }}">
    {{ $label ?? 'Select' }} <span class="text-danger">{{ $required ? ' *' : null }}</span>
</label>
<select id="{{ $id }}" class="select2 form-select" name="{{ $id }}{{ $multiple ? '[]' : null }}"
    {{ $multiple ? 'multiple' : null }} {{ $required ? 'required' : null }} {{ $readonly ? 'readonly' : null }}
    {{ $disabled ? 'disabled' : null }}>
    <option value="">Select {{ $label }}</option>
    @foreach ($values ?? [] as $key => $value)
        <option value="{{ $key }}"
            {{ $isEditMode && $customFieldValue && $value == $customFieldValue->value ? 'selected' : null }}>
            {{ $value }}
        </option>
    @endforeach

</select>


@if ($with_col)
    </div>
@endif

<script>
    var e = $("#{{ $id }}");
    e.wrap('<div class="position-relative"></div>');
    e.select2({
        dropdownAutoWidth: !0,
        dropdownParent: e.parent(),
        width: "100%",
        containerCssClass: "select-lg",
    });
</script>
