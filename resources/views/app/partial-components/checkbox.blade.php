@if ($with_col)
    <div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
@endif
<div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" id="{{ $id }}" name="{{ $name }}"
        value="{{ $value }}" {{ $required ? 'required' : null }} {{ $checked ? 'checked' : null }}
        {{ $disabled ? 'disabled' : null }}>
    <label class="form-check-label" for="{{ $id }}">{{ $label ?? 'Checkbox' }}</label>
</div>
@if ($with_col)
    </div>
@endif
