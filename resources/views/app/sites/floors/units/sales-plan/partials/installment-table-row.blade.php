<tr id="row_{{ $keyShow ? $key : 'total' }}">
    <th scope="row">{!! $keyShow ? $key : '&nbsp;' !!}</th>
    <td>
        <div class="position-relative" {!! $detailShow ? null : "style='display: none;'" !!}>
            <input type="text" class="form-control form-control-lg" id="installment_detail_{{ $key }}"
                {!! $detailShow ? "name='installments[table][" . $key . "][details]'" : null !!} placeholder="Installments"
                {!! isset($filteredData['details']) ? "value='" . $filteredData['details'] . "'" : null !!}
                onchange="storeUnchangedData({{ $key }}, 'details', this.value);" />
        </div>
    </td>
    <td>
        <div class="position-relative" {!! $dateShow ? null : "style='display: none;'" !!}>
            <input type="text" id="installment_date_{{ $key }}" {!! $dateShow ? "name='installments[table][" . $key . "][date]'" : null !!} readonly
                class="form-control" value="{{ $date }}" placeholder="Installment Date"
                onchange="storeUnchangedData('{{ $key }}', 'date');" />
        </div>
    </td>
    <td>
        <div class="position-relative text-end" {!! $amountShow ? null : "style='display: none;'" !!}>
            @php
                $installmentRate = isset($filteredData['amount']) ? $filteredData['amount'] : $amount;
            @endphp
            <input type="number" class="form-control form-control-lg text-end" min="0"
                {{ $amountReadonly ? 'readonly' : null }} id="installment_amount_{{ $key }}"
                value="{{ number_format((float) ($installmentRate > 0 ? $installmentRate : '0'), 2, '.', '') }}"
                {!! $amountName ? "name='installments[table][" . $key . "][amount]'" : null !!} placeholder="Total Amount"
                onchange="storeUnchangedData({{ $key }}, 'total_amount', this.value);" />
        </div>
    </td>
    <td>
        <div class="position-relative text-end">
            <input type="number" class="form-control form-control-lg text-end" min="0"
                id="paid_amount_{{ $key }}" placeholder="Paid Amount"
                onchange="storeUnchangedData({{ $key }}, 'paid_amount', this.value);" />
        </div>
    </td>
    <td>
        <div class="position-relative text-end">
            <input type="number" class="form-control form-control-lg text-end" min="0"
                id="paid_amount_{{ $key }}" placeholder="Remaining Amount"
                onchange="storeUnchangedData({{ $key }}, 'remaining_amount', this.value);" />
        </div>
    </td>
    <td>
        <div class="position-relative" {!! $remarksShow ? null : "style='display: none;'" !!}>
            <input type="text" class="form-control form-control-lg" id="installment_remark_{{ $key }}"
                {!! $remarksShow ? "name='installments[table][" . $key . "][remarks]'" : null !!} placeholder="Status"
                onchange="storeUnchangedData({{ $key }}, 'remarks', this.value);" {!! isset($filteredData['remarks']) ? "value='" . $filteredData['remarks'] . "'" : null !!} />
        </div>
    </td>
</tr>
