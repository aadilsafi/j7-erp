@if ($with_col)
<div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
    @endif

    <label class="form-label fs-5 text-capitalize" for="{{ $id }}">{{ $label ?? 'Password' }}</label>

    <input type="{{$type}}" class="form-control form-control-lg" id="{{ $id }}" name="{{ $name }}" {{$type=='text'
        ? 'maxlength=' .$maxlength.' minlength='.$minlength.'' : null}} {{$type=='number'
        ? 'min=' .$min.' max='.$max.'' : null}} {{$required
        ? ' required' : null }} {{ $readonly ? 'readonly' : null }} {{ $disabled ? 'disabled' : null }} />


    @if ($with_col)
</div>
@endif