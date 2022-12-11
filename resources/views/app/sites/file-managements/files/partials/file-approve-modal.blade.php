<div class="modal modal-slide-in approve-modal fade" id="file-approve-modal">
    <div class="modal-dialog">
        <div class="modal-content pt-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
            <div class="modal-header mb-1">
                <h3 class="modal-title" id="salesTemplateModalLabel">Select Payment Method</h3>

            </div>
            <div class="modal-body flex-grow-1 p-1">
                <div class="mb-1 mt-2 py-1">
                    <input type="hidden" name="file_id" id="file_id" value="0">
                    <div class="row custom-options-checkable mb-2 g-1">
                        <div class="col">
                            <input class="custom-option-item-check checkClass mode-of-payment" type="radio" checked
                                name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon1" value="Cash">
                            <label class="custom-option-item text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon1">
                                {{-- <i data-feather='dollar-sign'></i> --}}
                                <i class="bi bi-cash-coin" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Cash</span>
                            </label>
                        </div>
                        <div class="col">
                            <input class="custom-option-item-check checkClass cheque-mode-of-payment" type="radio"
                                name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon2" value="Cheque">
                            <label class="custom-option-item text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon2">
                                <i class="bi bi-bank" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Cheque</span>
                            </label>
                        </div>
                        <div class="col">
                            <input class="custom-option-item-check checkClass online-mode-of-payment" type="radio"
                                name="mode_of_payment" id="customOptionsCheckableRadiosWithIcon3" value="Online">
                            <label class="custom-option-item text-center p-1"
                                for="customOptionsCheckableRadiosWithIcon3">
                                <i class="bi bi-app-indicator" style="font-size: 20px"></i>
                                <span class="custom-option-item-title h4 d-block">Online</span>
                            </label>
                        </div>
                    </div>

                    {{-- Online Payment Mode Details --}}
                    <div class="row mb-2 g-1" id="onlineValueDiv" style="display: none;">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="online_instrument_no">Transaction
                                No <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('online_instrument_no') is-invalid @enderror"
                                id="online_instrument_no" name="online_instrument_no" placeholder="Online Transaction"
                                value="{{ isset($receipt) ? $receipt->online_instrument_no : old('online_instrument_no') }}" />
                            @error('online_instrument_no')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="transaction_date">Transaction
                                Date <span class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control form-control-lg @error('transaction_date') is-invalid @enderror"
                                id="transaction_date" name="transaction_date" placeholder="Transaction Date"
                                value="{{ isset($receipt) ? $receipt->transaction_date : old('transaction_date') }}" />
                            @error('transaction_date')
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Cheque Payment Mode Details --}}
                    <div class="row mb-2 g-1" id="chequeValueDiv" style="display: none;">

                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="cheque_no">Cheque No <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control form-control-lg @error('cheque_no') is-invalid @enderror"
                                id="cheque_no" name="cheque_no" placeholder="Cheque No"
                                value="{{ isset($receipt) ? $receipt->cheque_no : old('cheque_no') }}" />
                            @error('cheque_no')
                                <span class="text-danger">*</span>
                                <div class="invalid-tooltip">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative bankDiv" id="bankDiv">
                        <label class="form-label" style="font-size: 15px" for="bank">Select Bank<span
                                class="text-danger">*</span></label>
                        <select class="form-select form-select-lg bank" id="bank" name="bank_id">
                            <option value="0">Create new Bank</option>
                            @foreach ($chequebanks as $banks)
                                <option value="{{ $banks->id }}">{{ $banks->name }} -
                                    {{ $banks->branch_code }}</option>
                                {{-- @empty --}}
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="text-center p-4">
                    <a onclick="ApproveModalRequest()"
                        class="btn btn-relief-outline-primary waves-effect waves-float waves-light text-center"
                        style="margin: 5px" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve request"
                        href="#">
                        Approve Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
