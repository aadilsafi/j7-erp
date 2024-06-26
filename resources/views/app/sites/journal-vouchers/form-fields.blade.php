<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="name">Voucher Number <span class="text-danger">*</span></label>
                <input readonly type="text"
                    class="form-control form-control-md @error('serial_number') is-invalid @enderror" id="serial_number"
                    name="serial_number" placeholder="Journal Voucher Number" value="{{ $journal_serial_number }}" />
                @error('serial_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">Journal Voucher Serial Number.</small></p>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="name"> User Name <span class="text-danger">*</span></label>
                <input readonly type="text"
                    class="form-control form-control-md @error('voucher_name') is-invalid @enderror" id="user_name"
                    name="user_name" placeholder="Journal Voucher Name" value="{{ Auth::user()->name }}" />
                @error('user_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">User Name.</small></p>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="name"> JVE Number <span class="text-danger">*</span></label>
                <input type="text" readonly class="form-control form-control-md @error('voucher_name') is-invalid @enderror"
                    id="voucher_name" name="" placeholder="JVE Number"
                   value="JVE-{{  $origin_number }}"  />
                @error('voucher_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">JVE Number.</small></p>
                @enderror
            </div>

        </div>

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="name">Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control comments @error('remarks') is-invalid @enderror" id="remarks"
                name="remarks" rows="3" placeholder="Remarks"></textarea>
                @error('remarks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <p class="m-0"><small class="text-muted">Enter Journal Voucher Remarks.</small></p>
                @enderror

            </div>
        </div>


    </div>
</div>
{{-- Form Repeater --}}
<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Journal Voucher Entries ( JVE-{{  $origin_number, }})</h3>
    </div>

    <div class="card-body">
        <table class="table table-hover table-striped table-borderless" id="installments_table"
            style="position: relative;">
            <thead style="position: sticky; top: 0; z-index: 10;">
                <div class="row custom_row mb-1 text-center">
                    <div class="col-3 text-center  position-relative">
                        <p>ACCOUNT CODE</p>
                    </div>

                    <div class="col-3 position-relative">
                        <p>DATE</p>
                    </div>

                    <div class="col position-relative">
                        <p>DEBIT</p>
                    </div>

                    <div class="col position-relative">
                        <p>CREDIT</p>
                    </div>

                    <div class="col-2 position-relative">
                        <p>REMARKS</p>
                    </div>

                    <div class="col-1 position-relative">
                        {{-- <p>ACTION</p> --}}
                    </div>
                </div>
            </thead>
        </table>
        <div class="journal-voucher-entries-list">
            <div data-repeater-list="journal-voucher-entries">
                <div data-repeater-item>
                    @if (isset($JournalVoucherEntries))
                        @foreach (isset($JournalVoucherEntries) && count($JournalVoucherEntries) ? $JournalVoucherEntries : [] as $JournalVoucherEntry)
                            <div class="card m-0">

                                <div>
                                    <div>
                                        <table class="table table-hover table-striped table-borderless"
                                            id="installments_table" style="position: relative;">

                                            <div>
                                                <div>
                                                    <div>
                                                        <tbody id="">

                                                            <div class="row mb-1">

                                                                <div class="col-3 position-relative">
                                                                    <select class=" form-control selectClass"
                                                                        name="account_number" id="fifth_level">
                                                                        <option value="">Select Account Codes
                                                                        </option>
                                                                        @foreach ($fifthLevelAccount as $fifthLevel)

                                                                            <option
                                                                                @if (isset($JournalVoucherEntry) && $JournalVoucherEntry->account_number == $fifthLevel->code) selected @endif
                                                                                value="{{ $fifthLevel->code }}">
                                                                                {{ $fifthLevel->name }}
                                                                                ({{ $fifthLevel->code }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>

                                                                <div class="col-3  position-relative">
                                                                    <input type="date"
                                                                        class="form-control voucher_date form-control-md @error('voucher_name') is-invalid @enderror"
                                                                        id="voucher_date" name="voucher_date"
                                                                        value="" />
                                                                </div>

                                                                <div class="col position-relative">
                                                                    <input type="number"
                                                                        @if (isset($JournalVoucherEntry)) value="{{ $JournalVoucherEntry->debit }}" @endif
                                                                        class="form-control debitInput form-control-md @error('debit') is-invalid @enderror"
                                                                        id="debit" name="debit"
                                                                        placeholder="Debit" value="" />

                                                                </div>
                                                                <div class="col position-relative">
                                                                    <input type="number"
                                                                        @if (isset($JournalVoucherEntry)) value="{{ $JournalVoucherEntry->credit }}" @endif
                                                                        class="form-control creditInput form-control-md @error('credit') is-invalid @enderror"
                                                                        id="credit" name="credit"
                                                                        placeholder="Credit" value="" />

                                                                </div>
                                                                <div class="col-2 position-relative">
                                                                    <input type="text"
                                                                        @if (isset($JournalVoucherEntry)) value="{{ $JournalVoucherEntry->remarks }}" @endif
                                                                        class="form-control form-control-md @error('remarks') is-invalid @enderror"
                                                                        id="remarks" name="remarks"
                                                                        placeholder="Remarks" value="" />
                                                                </div>

                                                                <div class="col-1 position-relative">
                                                                    <button
                                                                        class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                                                        data-repeater-delete
                                                                        id="delete-journal-voucher-entries"
                                                                        type="button">
                                                                        <i data-feather="x" class="me-25"></i>
                                                                        {{-- <span>Delete</span> --}}
                                                                    </button>
                                                                </div>
                                                            </div>

                                                        </tbody>
                                                    </div>
                                                </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card m-0">

                            <div>
                                <div>
                                    <table class="table table-hover table-striped table-borderless"
                                        id="installments_table" style="position: relative;">

                                        <div>
                                            <div>
                                                <div>
                                                    <tbody id="">

                                                        <div class="row mb-1">

                                                            <div class="col-3 position-relative">
                                                                <select required class=" form-control accountsSelect "
                                                                    name="account_number" id="fifth_level">
                                                                    <option value="">Select Account Codes
                                                                    </option>

                                                                    @foreach ($fifthLevelAccount as $fifthLevel)
                                                                    {{-- Hide Customer AR Accounts  --}}
                                                                    @php
                                                                        $customer_ar_starting_code = '10202000000000';
                                                                        $customer_ar_ending_code = '10209000000000';
                                                                    @endphp
                                                                    @if((float)$fifthLevel->code > (float)$customer_ar_starting_code &&  (float)$fifthLevel->code < (float)$customer_ar_ending_code)
                                                                        @continue
                                                                    @endif
                                                                    {{-- Hide Customer AR Accounts  --}}

                                                                    {{-- Hide Customer AP Accounts  --}}
                                                                    @php
                                                                        $customer_ap_starting_code = '20201010000000';
                                                                        $customer_ap_ending_code = '20201020000000';
                                                                    @endphp
                                                                    @if((float)$fifthLevel->code > (float)$customer_ap_starting_code &&  (float)$fifthLevel->code < (float)$customer_ap_ending_code)
                                                                        @continue
                                                                    @endif

                                                                    @php
                                                                        $dealer_ap_starting_code = '20201020000000';
                                                                        $dealer_ap_ending_code = '20201030000000';
                                                                    @endphp
                                                                    @if((float)$fifthLevel->code > (float)$dealer_ap_starting_code &&  (float)$fifthLevel->code < (float)$dealer_ap_ending_code)
                                                                        @continue
                                                                    @endif
                                                                    {{-- Hide Customer AP Accounts  --}}

                                                                    {{-- Hide File Action Accounts  --}}
                                                                    @php
                                                                        $sales_plan_approval = '40101010001001'; //Revenue Sales
                                                                        $sales_plan_dis_approval = '40201010001004';
                                                                        $refund_account = '40201010001002';
                                                                        $buyback_account = '40201010001001';
                                                                        $cancellation_account = '40201010001003';
                                                                        $revenue_cancel_charges = '40101010001002';
                                                                        $revenue_transfer_fees = '40101010001003';
                                                                        $revenue_other_income = '40101010001004';
                                                                    @endphp
                                                                    @if((float)$fifthLevel->code ==  (float)$sales_plan_approval || (float)$fifthLevel->code == (float)$sales_plan_dis_approval ||  (float)$fifthLevel->code == (float)$refund_account ||  (float)$fifthLevel->code == (float)$buyback_account ||
                                                                         (float)$fifthLevel->code == (float)$cancellation_account || (float)$fifthLevel->code == (float)$revenue_cancel_charges ||  (float)$fifthLevel->code == (float)$revenue_transfer_fees ||  (float)$fifthLevel->code == (float)$revenue_other_income)
                                                                        @continue
                                                                    @endif
                                                                    {{-- Hide File Action Accounts  --}}
                                                                        <option
                                                                            @if (isset($JournalVoucherEntry) && $JournalVoucherEntry->account_number == $fifthLevel->code) selected @endif
                                                                            value="{{ $fifthLevel->code }}">
                                                                            {{ $fifthLevel->name }}
                                                                            ({{ account_number_format($fifthLevel->code) }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </div>

                                                            <div class="col-3  position-relative">
                                                                <input required type="date"
                                                                    class="form-control voucher_date form-control-md @error('voucher_name') is-invalid @enderror"
                                                                    id="voucher_date" name="voucher_date"
                                                                    value="" />
                                                            </div>

                                                            <div class="col position-relative">
                                                                <input required type="number"
                                                                    @if (isset($JournalVoucherEntry))   value="{{ $JournalVoucherEntry->debit }}" @endif
                                                                    class="form-control debitInput form-control-md @error('debit') is-invalid @enderror"
                                                                    id="debit"  min="0"  value="0" name="debit" placeholder="Debit"
                                                                    value="" />

                                                            </div>
                                                            <div class="col position-relative">
                                                                <input required type="number"
                                                                    @if (isset($JournalVoucherEntry)) value="{{ $JournalVoucherEntry->credit }}" @endif
                                                                    class="form-control creditInput form-control-md @error('credit') is-invalid @enderror"
                                                                    id="credit"  min="0" value="0" name="credit"
                                                                    placeholder="Credit" value="" />

                                                            </div>
                                                            <div class="col-2 position-relative">
                                                                <input type="text"
                                                                    @if (isset($JournalVoucherEntry)) value="{{ $JournalVoucherEntry->remarks }}" @endif
                                                                    class="form-control form-control-md @error('remarks') is-invalid @enderror"
                                                                    id="remarks" name="remarks"
                                                                    placeholder="Remarks" value="" />
                                                            </div>

                                                            <div class="col-1 position-relative">
                                                                <button
                                                                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                                                    data-repeater-delete
                                                                    id="delete-journal-voucher-entries"
                                                                    type="button">
                                                                    <i data-feather="x" class="me-25"></i>
                                                                    {{-- <span>Delete</span> --}}
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </tbody>
                                                </div>
                                            </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <table class="table table-hover table-striped table-borderless" id="installments_table"
                style="position: relative;">
                <thead style="position: sticky; top: 0; z-index: 10;">
                    <div class="row custom_row mb-1 text-center">
                        <div class="col-3 text-center  position-relative">
                            {{-- <p>Total</p> --}}
                        </div>

                        <div class="col-3 position-relative">
                            <p>Total </p>
                        </div>

                        <div class="col position-relative">

                            <input readonly id="total_debit" type="text" required placeholder=" Debit"
                                name="total_debit"
                                @if (isset($JournalVoucher)) value="{{ number_format($JournalVoucher->total_debit) }}" @else value="0" @endif
                                class="form-control form-control-md" />

                        </div>

                        <div class="col position-relative">
                            <input
                                @if (isset($JournalVoucher)) value="{{ number_format($JournalVoucher->total_credit) }}" @else value="0" @endif
                                readonly id="total_credit" type="text" required placeholder=" Credit"
                                name="total_credit" class="form-control form-control-md" />
                        </div>

                        <div class="col-2 position-relative">
                            {{-- <p>REMARKS</p> --}}
                        </div>

                        <div class="col-1 position-relative">
                            {{-- <p>ACTION</p> --}}
                        </div>
                    </div>
                </thead>
            </table>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-relief-outline-primary waves-effect waves-float waves-light"
                        id="first-contact-person" type="button" data-repeater-create>
                        <i data-feather="plus" class="me-25"></i>
                        <span>Add New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
