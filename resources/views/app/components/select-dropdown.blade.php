<select class="select2-size-lg form-select unit-p-type-select" data-id="{{ $id }}"
    data-field="{{ $field }}">
    @foreach ($values as $key => $value)

        @if ($value->id == $data_id)
            <option value="{{ $value->id }}" selected>{{ $key + 1 }} - {{ $type == 'status' ? $value->name : $value->tree}}</option>
        @else
            <option value="{{ $value->id }}">{{ $key + 1 }} - {{ $type == 'status' ? $value->name : $value->tree}}</option>
        @endif

    @endforeach
</select>
