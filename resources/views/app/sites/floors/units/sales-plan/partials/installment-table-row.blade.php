<tr id="row_{{ $keyShow ? $key : 'total' }}">
    <th scope="row">{!! $keyShow ? $key : '&nbsp;' !!}</th>
    <td>
        <div class="position-relative" {!! $dateShow ? null : "style='display: none;'" !!}>
            <input type="text" id="installment_date_{{ $key }}" {!! $dateShow ? "name='installments[table][" . $key . "][date]'" : null !!} readonly
                class="form-control" value="{{ $date }}" placeholder="Installment Date" onchange="storeUnchangedData('{{ $key }}', 'date');" />
        </div>
    </td>
    <td>
        <div class="position-relative" {!! $detailShow ? null : "style='display: none;'" !!}>
            <input type="text" class="form-control form-control-lg" id="installment_detail_{{ $key }}"
                {!! $detailShow ? "name='installments[table][" . $key . "][details]'" : null !!} placeholder="Details"  onchange="storeUnchangedData({{ $key }}, 'details', this.value);"/>
        </div>
    </td>
    <td>
        <div class="position-relative text-end" {!! $amountShow ? null : "style='display: none;'" !!}>
            <input type="number" min="0" class="form-control form-control-lg text-end"
                {{ $amountReadonly ? 'readonly' : null }} id="installment_amount_{{ $key }}"
                value="{{ $amount }}" {!! $amountName ? "name='installments[table][" . $key . "][amount]'" : null !!} placeholder="Amount" onchange="storeUnchangedData({{ $key }}, 'amount', this.value);" />
        </div>
    </td>
    <td>
        <div class="position-relative" {!! $remarksShow ? null : "style='display: none;'" !!}>
            <input type="text" class="form-control form-control-lg" id="installment_remark_{{ $key }}"
                {!! $remarksShow ? "name='installments[table][" . $key . "][remarks]'" : null !!} placeholder="Remarks"  onchange="storeUnchangedData({{ $key }}, 'remarks', this.value);"/>
        </div>
    </td>
</tr>
