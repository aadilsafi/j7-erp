@if ($with_col)
    <div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
@endif

<label class="form-label fs-5 text-capitalize" for="{{ $id }}">{{ $label ?? 'Password' }} <span
        class="text-danger">"{{ $required ? ' *' : null }}</span></label>

<input type="{{ $type }}" class="form-control form-control-lg" id="{{ $id }}" name="{{ $name }}"
    {{ $isEditMode && $customFieldValue ? ' value=' . $customFieldValue->value . '' : null }}
    {{ $type == 'text' && $maxlength > 0 ? 'maxlength=' . $maxlength . '' : '' }}
    {{ $type == 'text' && $minlength > 0 ? 'minlength=' . $minlength . '' : ' ' }}
    {{ $type == 'number' ? 'min=' . $min . '' : null }}
    {{ $type == 'number' && $max > 0 ? ' max=' . $max . '' : null }} {{ $required ? ' required' : null }}
    {{ $readonly ? 'readonly' : null }} {{ $disabled ? 'disabled' : null }} />


@if ($with_col)
    </div>
@endif
