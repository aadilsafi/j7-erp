@if ($with_col)
<div class="col-lg-{{ $bootstrapCols }} col-md-{{ $bootstrapCols }} position-relative">
    @endif

    <div class="row text-capitalize">
        <div class="col">
            <h5>{{$label}}</h5>
        </div>
        @foreach ($values as $key => $value)
        <div class="col">
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="{{$key}}">{{$value}}</label>
                <input class="form-check-input" type="radio" id="{{$key}}" name="{{$label}}" value="{{$value}}">
            </div>
        </div>
        @endforeach
    </div>

    @if ($with_col)
</div>
@endif