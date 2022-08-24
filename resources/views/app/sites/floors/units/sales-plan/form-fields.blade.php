<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>1. PRIMARY DATA</h3>
    </div>

    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="unit_no">Unit No</label>
                <input type="text" class="form-control form-control-lg @error('unit_no') is-invalid @enderror"
                    id="unit_no" name="unit_no" placeholder="Unit No" />
                @error('unit_no')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="floor_no">Floor No</label>
                <input type="text" class="form-control form-control-lg @error('floor_no') is-invalid @enderror"
                    id="floor_no" name="floor_no" placeholder="Floor No" />
                @error('floor_no')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="unit_type">Unit Type</label>
                <input type="text" class="form-control form-control-lg @error('unit_type') is-invalid @enderror"
                    id="unit_type" name="unit_type" placeholder="Unit Type" />
                @error('unit_type')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="unit_size">Unit Size(sq.ft)</label>
                <input type="text" class="form-control form-control-lg @error('unit_size') is-invalid @enderror"
                    id="unit_size" name="unit_size" placeholder="Unit Size(sq.ft)" />
                @error('unit_size')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="unit_orientation">Unit Orientation</label>
                <input type="text"
                    class="form-control form-control-lg @error('unit_orientation') is-invalid @enderror"
                    id="unit_orientation" name="unit_orientation" placeholder="Unit Orientation" />
                @error('unit_orientation')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>2. STAKEHOLDER DATA (LEAD'S DATA)</h3>
    </div>

    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="stackholders">Stakeholders</label>
                <select class="select2-size-lg form-select" id="stackholders" name="stackholders">
                    <option value="0" selected>New Stakeholder</option>
                    <option value="1">Stackholders</option>
                    <option value="1">Stackholders1</option>
                    <option value="1">Stackholders2</option>
                    <option value="1">Stackholders3</option>
                    <option value="1">Stackholders4</option>
                    <option value="1">Stackholders5</option>
                    <option value="1">Stackholders6</option>
                    <option value="1">Stackholders7</option>
                    <option value="1">Stackholders8</option>
                    <option value="1">Stackholders9</option>
                    <option value="1">Stackholders0</option>
                    <option value="1">Stackholders12</option>
                    <option value="1">Stackholders12</option>

                </select>
                @error('stackholders')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="full_name">Full Name</label>
                <input type="text" class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                    id="full_name" name="full_name" placeholder="Full Name" />
                @error('full_name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="father_name">Father Name</label>
                <input type="text" class="form-control form-control-lg @error('father_name') is-invalid @enderror"
                    id="father_name" name="father_name" placeholder="Father Name" />
                @error('father_name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="occupation">Occupation</label>
                <input type="text" class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                    id="occupation" name="occupation" placeholder="Occupation" />
                @error('occupation')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="designation">Designation</label>
                <input type="text" class="form-control form-control-lg @error('designation') is-invalid @enderror"
                    id="designation" name="designation" placeholder="Designation" />
                @error('designation')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="cnic">CNIC</label>
                <input type="text" class="form-control form-control-lg @error('cnic') is-invalid @enderror"
                    id="cnic" name="cnic" placeholder="CNIC" />
                @error('cnic')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="contact">Contact</label>
                <input type="text" class="form-control form-control-lg @error('contact') is-invalid @enderror"
                    id="contact" name="contact" placeholder="Contact" />
                @error('contact')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="address">Address</label>
                <textarea class="form-control form-control-lg @error('address') is-invalid @enderror" id="address" name="address"
                    placeholder="Address" rows="5"></textarea>
                @error('address')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>3. SALES SOURCE</h3>
    </div>

    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="stackholders">Stakeholders</label>
                <select class="select2-size-lg form-select" id="stackholders" name="stackholders">
                    <option value="0" selected>New Stakeholder</option>
                    <option value="1">Stackholders</option>
                    <option value="1">Stackholders1</option>
                    <option value="1">Stackholders2</option>
                    <option value="1">Stackholders3</option>
                    <option value="1">Stackholders4</option>
                    <option value="1">Stackholders5</option>
                    <option value="1">Stackholders6</option>
                    <option value="1">Stackholders7</option>
                    <option value="1">Stackholders8</option>
                    <option value="1">Stackholders9</option>
                    <option value="1">Stackholders0</option>
                    <option value="1">Stackholders12</option>
                    <option value="1">Stackholders12</option>

                </select>
                @error('stackholders')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="full_name">Full Name</label>
                <input type="text" class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                    id="full_name" name="full_name" placeholder="Full Name" />
                @error('full_name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="father_name">Father Name</label>
                <input type="text" class="form-control form-control-lg @error('father_name') is-invalid @enderror"
                    id="father_name" name="father_name" placeholder="Father Name" />
                @error('father_name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="occupation">Occupation</label>
                <input type="text" class="form-control form-control-lg @error('occupation') is-invalid @enderror"
                    id="occupation" name="occupation" placeholder="Occupation" />
                @error('occupation')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="designation">Designation</label>
                <input type="text" class="form-control form-control-lg @error('designation') is-invalid @enderror"
                    id="designation" name="designation" placeholder="Designation" />
                @error('designation')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="cnic">CNIC</label>
                <input type="text" class="form-control form-control-lg @error('cnic') is-invalid @enderror"
                    id="cnic" name="cnic" placeholder="CNIC" />
                @error('cnic')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 position-relative">
                <label class="form-label fs-5" for="contact">Contact</label>
                <input type="text" class="form-control form-control-lg @error('contact') is-invalid @enderror"
                    id="contact" name="contact" placeholder="Contact" />
                @error('contact')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="address">Address</label>
                <textarea class="form-control form-control-lg @error('address') is-invalid @enderror" id="address" name="address"
                    placeholder="Address" rows="5"></textarea>
                @error('address')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>


<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-header">
        <h3>4. INSTALLMENT DETAILS</h3>
    </div>

    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="row custom-options-checkable g-1">

                    <div class="col-md-5">
                        <input class="custom-option-item-check" type="radio" name="installments[types][type]"
                            id="installment_quarterly" value="quarterly" checked />
                        <label class="custom-option-item p-1" for="installment_quarterly">
                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                <span class="fw-bolder">Quarterly</span>
                                <span class="fw-bolder">3 Months</span>
                            </span>
                            <small class="d-block">Installment will be calculated on quarterly basis</small>
                        </label>
                    </div>

                    <div class="col-md-5">
                        <input class="custom-option-item-check" type="radio" name="installments[types][type]"
                            id="installment_monthly" value="monthly" />
                        <label class="custom-option-item p-1" for="installment_monthly">
                            <span class="d-flex justify-content-between flex-wrap mb-50">
                                <span class="fw-bolder">Monthly</span>
                                <span class="fw-bolder">1 Month</span>
                            </span>
                            <small class="d-block">Installment will be calculated on montly basis</small>
                        </label>
                    </div>

                    <div class="col-md-2">
                        <p class="m-0 fw-bolder d-block mb-1">How Many (<span id="how_many">Quaters</span>)?</p>
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="input-group input-group-lg ">
                                <input type="number" class="touchspin-icon" readonly
                                    name="installments[types][value]" value="1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">

                <button type="button" onclick="testdateranger()">click me</button>

                <div class="table-responsive">

                    <table class="table table-hover table-borderless" id="installments_table">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Details</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Remarks</th>
                            </tr>
                        </thead>
                        <tr id="row_0">
                            <th scope="row">1</th>
                            <td>
                                <div class="">
                                    <input type="text" id="installment_date_0"
                                        name="installments[installments][0][date]"
                                        class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" />
                                </div>
                            </td>
                            <td>
                                <div class="position-relative">
                                    <input type="text" class="form-control form-control-lg"
                                        id="installment_detail_0" name="installments[installments][0][details]"
                                        placeholder="Details" />
                                </div>
                            </td>
                            <td>
                                <div class="position-relative">
                                    <input type="number" class="form-control form-control-lg"
                                        id="installment_amount_0" name="installments[installments][0][amount]"
                                        placeholder="Amount" />
                                </div>
                            </td>
                            <td>
                                <div class="position-relative">
                                    <input type="text" class="form-control form-control-lg"
                                        id="installment_remark_0" name="installments[installments][0][remarks]"
                                        placeholder="Remarks" />
                                </div>
                            </td>
                        </tr>
                        <tbody id="dynamic_installment_rows">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
