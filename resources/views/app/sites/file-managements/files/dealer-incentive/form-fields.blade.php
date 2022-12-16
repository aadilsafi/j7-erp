<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Dealer
                        </label>
                        <select id="dealer_id" @if (isset($edit_unit)) disabled @endif
                            class="select2 form-select  @error('unit_id') is-invalid @enderror" name="dealer_id"
                            onchange="getData(this.options[this.selectedIndex].value)">
                            <option value="0">Select Dealer</option>
                            @foreach ($stakeholders as $row)
                                <option value="{{ $row->stakeholder->id }}">
                                    {{ $row->stakeholder->full_name }} ( {{ cnicFormat($row->stakeholder->cnic) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                            Select Units
                        </label>
                        <select id="unit_id" onchange="CalculateTotalArea(this.options[this.selectedIndex].value)"
                            multiple class="select2 form-select unit_id " name="multiple[]">
                        </select>
                    </div> --}}

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                            Incentive Amount
                        </label>
                        <input min="0" onchange="CalculateTotalDealerIncentive()" id="dealer_incentive"
                            type="number" class="form-control @error('dealer_incentive') is-invalid @enderror"
                            name="dealer_incentive" placeholder="Dealer Incentive Amount">
                        @error('dealer_incentive')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


            </div>
        </div>

    </div>
</div>


<div class="row mb-1 mt-2 hideDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <h4 class="mb-1">Unit Details</h3>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">

                                <table class="table table-hover table-striped table-borderless text-center"
                                    id="installments_table" style="position: relative;">
                                    <thead style="position: sticky; top: 0; z-index: 10;">
                                        <tr class="text-nowrap">
                                            <th>#</th>
                                            <th class="text-start">Select Unit</th>
                                            <th class="text-center">Unit Name</th>
                                            <th class="text-center">Unit Number</th>
                                            <th class="text-center">Unit Area</th>
                                            {{-- <th>Unit Price</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="dynamic_unit_rows">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

            </div>
        </div>

    </div>
</div>
<div class="row mb-1 mt-2 hideDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <h4 class="mb-1">Paid Unit Details</h3>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <div class="table-responsive" style="max-height: 50rem; overflow-y: auto;">
                                <table class="table table-hover table-striped table-borderless text-center"
                                    id="installments_table" style="position: relative;">
                                    <thead style="position: sticky; top: 0; z-index: 10;">
                                        <tr class="text-nowrap">
                                            <th>#</th>
                                            {{-- <th>Select Unit</th> --}}
                                            <th class="text-center">Unit Name</th>
                                            <th class="text-center">Unit Number</th>
                                            <th class="text-center">Unit Area</th>
                                            <th>Incentive</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dynamic_paid_unit_rows">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-1 mt-2 hideDiv">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                {{-- <h4 class="mb-1">Unit Details</h3> --}}

                <div class="row mb-1 mt-2">

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                            Total Unit Area Sold
                        </label>
                        <input readonly id="total_unit_area" type="number"
                            class="form-control   @error('total_unit_area') is-invalid @enderror" name="total_unit_area"
                            value="" placeholder="Total Unit Area ">
                        @error('total_unit_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                            Total Incentive Amount
                        </label>
                        <input min="0" readonly id="total_dealer_incentive" type="text"
                            class="form-control @error('total_dealer_incentive') is-invalid @enderror"
                            name="total_dealer_incentive" placeholder="Total Incentive Amount">
                        @error('total_dealer_incentive')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row mb-1">
                    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                        <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                            <label class="form-label" style="font-size: 15px" for="floor">
                                Comments
                            </label>
                            <textarea class="form-control form-control-lg" id="custom_comments" name="comments" placeholder="Comments"
                                rows="5">{{ isset($rebate_data) ? $rebate_data->comments : old('comments') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@if (isset($customFields) && count($customFields) > 0)

    <div class="card hideDiv" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">

        <div class="card-body">

            <div class="row mb-1 g-1">
                @forelse ($customFields as $field)
                    {!! $field !!}
                @empty
                @endforelse
            </div>
        </div>
    </div>
@endif
