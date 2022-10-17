@if ($with_col)
<div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
    @endif

    <label class="form-label fs-5 text-title" for="{{ $id }}">{{ $label ?? 'Email' }}</label>
    <input class="form-control form-control-lg" type="email" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" {{
        $required ? 'required' : null }} {{ $readonly ? 'readonly' : null }} {{ $disabled ? 'disabled' : null }}>

    @if ($with_col)
</div>
@endif