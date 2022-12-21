<tr id="row_{{ $index['show'] ? $index['value'] : 'total' }}">
    <th scope="row">{!! $index['show'] ? $index['value'] : '&nbsp;' !!}</th>

    <td>
        <div class="position-relative" style="{{ $installments['show'] ? null : 'display: none;' }}">
            <input type="text" class="form-control text-center form-control-lg disbaled"
                id="installment_detail_{{ $index['value'] }}" {!! $installments['name'] ? "name='installments[table][" . $index['value'] . "][installment]'" : null !!}
                placeholder="{{ $installments['placeholder'] }}" value="{{ $installments['value'] }}"
                {{ $installments['disabled'] ? 'disabled' : null }}
                {{ $installments['readonly'] ? 'readonly' : null }} />
        </div>
    </td>

    <td style="width: 28%">
        <div class="position-relative" {!! $due_date['show'] ? null : "style='display: none;'" !!}>
            <input type="text" id="installment_date_{{ $index['value'] }}" {!! $due_date['name'] ? "name='installments[table][" . $index['value'] . "][due_date]'" : null !!}
                class="form-control" value="{{ $due_date['value'] }}" placeholder="{{ $due_date['placeholder'] }}"
                style="{{ $due_date['disabled'] ? 'background-color: #EFEFEF;' : null }}"
                {{ $due_date['disabled'] ? 'disabled' : null }} {{ $due_date['readonly'] ? 'readonly' : null }} />
        </div>
    </td>

    <td>
        <div class="position-relative text-end" {!! $total_amount['show'] ? null : "style='display: none;'" !!}>
            <input type="number" class="form-control form-control-lg text-end" min="0"
                id="installment_amount_{{ $index['value'] }}"
                value="{{ number_format((float) ($total_amount['value'] > 0 ? $total_amount['value'] : '0'), 2, '.', '') }}"
                {!! $total_amount['name'] ? "name='installments[table][" . $index['value'] . "][total_amount]'" : null !!} placeholder="{{ $total_amount['placeholder'] }}"
                onchange="storeUnchangedData({{ $index['value'] }}, 'amount', this.value, 'ArrAmounts');"
                {{ $total_amount['disabled'] ? 'disabled' : null }}
                {{ $total_amount['readonly'] ? 'readonly' : null }} />
        </div>
    </td>

    <td>
        <div class="position-relative" {!! $remarks['show'] ? null : "style='display: none;'" !!}>
            <input type="text" class="form-control form-control-lg" id="installment_remark_{{ $index['value'] }}"
                {!! $remarks['show'] ? "name='installments[table][" . $index['value'] . "][remarks]'" : null !!} placeholder="{{ $remarks['placeholder'] }}"
                onchange="storeUnchangedData({{ $index['value'] }}, 'remarks', this.value, 'ArrRemarks');"
                value="{{ $remarks['value'] }}" {{ $remarks['disabled'] ? 'disabled' : null }}
                {{ $remarks['readonly'] ? 'readonly' : null }} />
        </div>
    </td>
</tr>

@if ($index['show'] && !$due_date['readonly'] )
    <script>
        $("#installment_date_{{ $index['value'] }}").flatpickr({
            defaultDate: '{{ $due_date['value'] }}',
            minDate: '{{ isset($due_date['minDate']) ? $due_date['minDate'] : $due_date['value'] }}',
            // altInput: !0,
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                storeUnchangedData({{ $index['value'] }}, 'due_date', dateStr, 'ArrDueDates');
            }
        });
    </script>
@endif
