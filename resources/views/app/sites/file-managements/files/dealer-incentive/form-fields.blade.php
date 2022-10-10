<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                             Select Dealer</h6>
                        </label>
                        <select id="dealer_id" @if (isset($edit_unit)) disabled @endif
                            class="select2 form-select  @error('unit_id') is-invalid @enderror" name="dealer_id"
                            onchange="getData(this.options[this.selectedIndex].value)">
                            <option>Select Dealer</option>
                            @foreach ($dealer_data as $dealer)
                            @continue(isset($incentives) && in_array($dealer->id, $incentives))
                                <option value="{{ $dealer->stakeholder->id }}">
                                    {{ $dealer->stakeholder->full_name }} ( {{ cnicFormat($dealer->stakeholder->cnic )}} )
                                    - {{ $dealer->stakeholder->designation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label"  style="font-size: 15px" for="floor">
                             Select Units</h6>
                        </label>
                        <select id="unit_id"  onchange="CalculateTotalArea(this.options[this.selectedIndex].value)" multiple class="select2 form-select unit_id " name="multiple[]">
                            {{-- <option>Select Units</option> --}}
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                              Incentive Amount</h6>
                        </label>
                        <input min="0" onchange="CalculateTotalDealerIncentive()"  id="dealer_incentive" type="number"
                            class="form-control @error('dealer_incentive') is-invalid @enderror"
                            name="dealer_incentive" placeholder="Dealer Incentive Amount">
                        @error('dealer_incentive')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row mb-1">


                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                             Total Unit Area Sold (sqft)
                        </label>
                        <input readonly id="total_unit_area" type="number"
                            class="form-control   @error('total_unit_area') is-invalid @enderror"
                            name="total_unit_area" value="" placeholder="Total Unit Area ">
                        @error('total_unit_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="floor">
                            Total Incentive Amount (per/sqft)
                        </label>
                        <input min="0" readonly id="total_dealer_incentive" type="number"
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
                            <label class="form-label"  style="font-size: 15px" for="floor">
                                 Comments</h6>
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
