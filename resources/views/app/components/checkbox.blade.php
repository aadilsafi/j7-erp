<div class="form-check form-check-primary" id="unit_p_input_div_{{ $field }}{{ $id }}">
    @if ($is_true)
        <input type="checkbox" data-id="{{ $id }}" data-field="{{ $field }}" class="form-check-input unit-p-checkbox"
        id="colorCheck6" checked />

        @if($field=='is_facing')
            {{view('app.components.unit-preview-cell',['id'=>$id,
            'field'=>'facing_id','value'=>$data->facing->name, 'inputtype'=>'select'])}}
        @endif
    @else
        <input type="checkbox" data-id="{{ $id }}" data-field="{{ $field }}" class="form-check-input unit-p-checkbox"
            id="colorCheck6" />
    @endif
</div>
