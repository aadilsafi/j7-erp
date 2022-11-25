@if ($with_col)
    <div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
@endif

<label class="form-label fs-5 text-capitalize" for="{{ $id }}">{{ $label ?? 'Text Area' }} <span class="text-danger">{{ $required ? ' *' : null }}</span></label>

<textarea class="form-control form-control-lg" id="{{ $id }}" name="{{ $id }}"
    maxlength="{{ $maxlength }}" minlength="{{ $minlength }}" {{ $required ? 'required' : null }}
    {{ $readonly ? 'readonly' : null }} {{ $disabled ? 'disabled' : null }} rows="5">{{ $isEditMode && $customFieldValue ? $customFieldValue->value : null }}</textarea>

@if ($with_col)
    </div>
@endif
