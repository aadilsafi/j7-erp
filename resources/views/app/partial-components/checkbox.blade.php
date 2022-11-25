@if ($with_col)
    <div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
@endif
<div class="form-check form-check-inline">

    <label class="form-check-label" for="{{ $id }}">{{ $label ?? 'Checkbox' }} <span
            class="text-danger">{{ $required ? ' *' : null }}</span></label>
    <input class="form-check-input" type="checkbox" id="{{ $id }}" name="{{ $id }}"
        value="{{ $value }}" {{ $required ? 'required' : null }}
        {{ $isEditMode && $customFieldValue && $value == $customFieldValue->value ? 'checked' : null }}
        {{ $disabled ? 'disabled' : null }}>
</div>
@if ($with_col)
    </div>
@endif
