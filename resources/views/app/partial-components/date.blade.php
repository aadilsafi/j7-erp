@if ($with_col)
    <div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
@endif

<div class="d-block mb-1">
    <label class="form-label fs-5" for="{{ $id }}">{{ $label }} <span class="text-danger"> {{ $required ? ' *' : null }}</span></label>
    <input type="text" id="{{ $id }}" name="{{ $name }}" class="form-control flatpickr-basic"
        placeholder="YYYY-MM-DD" {{ $required ? 'required' : null }} {{ $disabled ? 'disabled' : null }}
        {{ $readonly ? 'readonly' : null }}
        {{ $isEditMode && $customFieldValue ? ' value=' . $customFieldValue->value . '' : null }} />
</div>

@if ($with_col)
    </div>
@endif

<script>
    $("#{{ $id }}").flatpickr({
        defaultDate: "{{ $isEditMode && $customFieldValue ? $customFieldValue->value : $value }}",
        altInput: !0,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
</script>
