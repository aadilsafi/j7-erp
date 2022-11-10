<select class="form-control text-capitalize text-nowrap required selectField removeTolltip" style="width: 140px;"
    name="fields[]" {{ $is_disable ? 'disabled' : '' }}>
    <option value="">...No match,select a field...</option>
    @foreach ($db_fields as $k => $db_field)
        @continue(isset($spInstallment) && ($db_field == 'unit_short_label' || $db_field == 'stakeholder_cnic' || $db_field == 'total_price' || $db_field == 'down_payment_total' || $db_field == 'validity'))
        @if ($db_field == 'cnic')
            <option class="text-danger" value="{{ $db_field }}" {{ $name == $db_field ? 'selected' : '' }}>
                {{ $db_field }}<span class="text-danger">*</span></option>
        @else
            <option value="{{ $db_field }}" {{ $name == $db_field ? 'selected' : '' }}>
                {{ $db_field }}
            </option>
        @endif
    @endforeach
</select>
