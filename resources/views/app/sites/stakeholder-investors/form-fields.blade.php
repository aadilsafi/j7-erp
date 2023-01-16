<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="dealer">
                    Select Investor<span class="text-danger">*</span></label>
                <select class="" id="stackholders" name="investor_id">
                    <option value="">Create new Investor</option>
                    @forelse ($investors as $investor)
                        <option value="{{ $investor->stakeholder->id }}">
                            {{ $investor->stakeholder->full_name }} ( {{ $investor->stakeholder->cnic }} )
                        </option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-6 position-relative mt-2"
                style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                {{ view('app.sites.stakeholders.partials.stakeholder-form-fields', [
                    'stakeholderTypes' => $stakeholderTypes,
                    'country' => $country,
                    'leadSources' => $leadSources,
                    'hideBorders' => true,
                ]) }}
            </div>
        </div>

    </div>


</div>

{{-- Form Repeater --}}
<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>Select Units</h3>
    </div>

    <div class="card-body">
        <table class="table table-hover table-striped table-borderless" id="installments_table"
            style="position: relative;">
            <thead style="position: sticky; top: 0; z-index: 10;">
                <div class="row custom_row mb-1 text-center">
                    <div class="col-4 text-center  position-relative">
                        <p>Unit</p>
                    </div>

                    {{-- <div class="col-3 position-relative">
                        <p>DATE</p>
                    </div> --}}

                    <div class="col-3 position-relative">
                        <p>Amount Receivable</p>
                    </div>

                    {{-- <div class="col-3 position-relative">
                        <p>Payback Amount</p>
                    </div> --}}

                    <div class="col-3 position-relative">
                        <p>REMARKS</p>
                    </div>

                    <div class="col-2 position-relative">
                        {{-- <p>ACTION</p> --}}
                    </div>
                </div>
            </thead>
        </table>
        <div class="unit-deal-list">
            <div data-repeater-list="unit-deals">
                <div data-repeater-item>
                    <div class="card m-0">

                        <div>
                            <div>
                                <table class="table table-hover table-striped table-borderless" id="installments_table"
                                    style="position: relative;">

                                    <div>
                                        <div>
                                            <div>
                                                <tbody id="">

                                                    <div class="row mb-1">

                                                        <div class="col-4 position-relative">
                                                            <select required class="form-control  all_units all_units_id" id="all_units"
                                                                name="unit">
                                                                <option value=''>Select Units</option>
                                                                @forelse ($units as $unit)
                                                                    <option value="{{ $unit->id }}">
                                                                        {{ $unit->name }} (
                                                                        {{ $unit->floor_unit_number }} )
                                                                        ({{ $unit->status->name }} )
                                                                    </option>
                                                                @empty
                                                                @endforelse
                                                            </select>

                                                        </div>

                                                        {{-- <div class="col-3  position-relative">
                                                            <input type="date"
                                                                class="form-control voucher_date form-control-md @error('voucher_name') is-invalid @enderror"
                                                                id="voucher_date" name="voucher_date"
                                                                value="" />
                                                        </div> --}}

                                                        <div class="col-3 position-relative">
                                                            <input type="text" required
                                                                class="form-control amountFormat received_amount form-control-md @error('received_amount') is-invalid @enderror"
                                                                id="received_amount" name="received_amount"
                                                                placeholder="Amount Receivable" value="" />

                                                        </div>
                                                        {{-- <div class="col-3 position-relative">
                                                            <input type="text"
                                                                class="form-control amountFormat form-control-md @error('payback_amount') is-invalid @enderror"
                                                                id="payback_amount" name="payback_amount"
                                                                placeholder="Payback Amount" value="" />
                                                        </div> --}}
                                                        <div class="col-3 position-relative">
                                                            <input type="text"
                                                                class="form-control form-control-md @error('remarks') is-invalid @enderror"
                                                                id="remarks" name="remarks" placeholder="Remarks"
                                                                value="" />
                                                        </div>

                                                        <div class="col-2 position-relative">
                                                            <button
                                                                class="btn btn-relief-outline-danger waves-effect waves-float waves-light text-nowrap px-1"
                                                                data-repeater-delete id="delete-journal-voucher-entries"
                                                                type="button">
                                                                <i data-feather="x" class="me-25"></i>
                                                                <span>Delete</span>
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
                </div>
            </div>
            <table class="table table-hover table-striped table-borderless" id="installments_table"
                style="position: relative;">
                <thead style="position: sticky; top: 0; z-index: 10;">
                    <div class="row custom_row mb-1 text-center">


                        <div class="col-4 position-relative">
                            <p>Total Amount Receivable</p>
                        </div>

                        <div class="col-3 position-relative">

                            <input readonly id="total_recieved" type="text" required placeholder="0"
                                name=""
                                class="form-control form-control-md total_recieved" />

                        </div>

                        <div class="col-5 position-relative">

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
