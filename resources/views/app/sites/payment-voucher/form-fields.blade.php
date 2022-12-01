<div class="row mb-1">
    <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
        <div class="card m-0" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Stakeholder
                        </label>
                        <select id="stakeholder"
                            class="select2 form-select" name="stakeholder_id">
                            <option value="0">Select Stakeholder</option>
                            @foreach ($stakholders as $row)
                                <option value="{{ $row->id }}">
                                    {{ $row->full_name }} ( {{ cnicFormat($row->cnic) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                        <label class="form-label" style="font-size: 15px" for="unit_id">
                            Select Stakeholder Type
                        </label>
                        <select id="stakholder_type"
                            class="select2 form-select" name="stakeholder_type_id">

                        </select>
                    </div>

                </div>




            </div>
        </div>

    </div>
</div>



