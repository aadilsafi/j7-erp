<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">

    <div class="card-body">

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 col-sm-12 position-relative">
                <label class="form-label fs-5" for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                    id="name" name="name" placeholder="Name"
                    value="{{ isset($customFiled) ? $customFiled->name : old('name') }}" />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div id="nameHelp" class="form-text">Enter custom field name.</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label" style="font-size: 15px" for="type">Field Type <span
                        class="text-danger">*</span></label>
                <select class="select2-size-lg form-select" id="type" name="type">
                    <option value="0" selected>Field Type</option>
                    @foreach ($fieldTypes as $key => $value)
                        <option value="{{ $value }}"
                            {{ (isset($customFiled) ? $customFiled->type : old('type')) == $value ? 'selected' : '' }}>
                            {{ $loop->index + 1 }} - {{ $key }}</option>
                    @endforeach
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div id="typeHelp" class="form-text">Enter custom field type.</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                <label class="form-label fs-5" for="order">Order <span class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-lg @error('order') is-invalid @enderror"
                    id="order" name="order" placeholder="Order" min="1" max="50" minlength="1"
                    maxlength="2" value="{{ isset($customFiled) ? $customFiled->order : old('order') ?? 1 }}" />
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div id="orderHelp" class="form-text">Enter the order of column visibility.</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                <label class="form-label fs-5" for="bootstrap_column">Bootstrap Columns <span
                        class="text-danger"></span></label>
                <input type="number"
                    class="form-control form-control-lg @error('bootstrap_column') is-invalid @enderror"
                    id="bootstrap_column" name="bootstrap_column" placeholder="Bootstrap Columns"
                    value="{{ isset($customFiled) ? $customFiled->bootstrap_column : old('bootstrap_column') }}" />
                @error('bootstrap_column')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div id="bootstrap_columnHelp" class="form-text">By default system will consider 6 columns. 0 will hide
                        the field.</div>
                @enderror
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="custom_field_model">Bind To <span
                        class="text-danger">*</span></label>
                <select class="select2-size-lg form-select" id="custom_field_model" name="custom_field_model">
                    <option value="0" selected>Bind To</option>
                    @foreach ($models as $path => $name)
                        <option value="{{ $path }}"
                            {{ (isset($customFiled) ? $customFiled->custom_field_model : old('custom_field_model')) == $path ? 'selected' : '' }}>
                            {{ $loop->index + 1 }} - {{ Str::of($name)->plural()->replace('_', ' ')->title() }}
                        </option>
                    @endforeach
                </select>
                @error('custom_field_model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div id="emailHelp" class="form-text">Bind this feild to the List</div>
                @enderror
            </div>
        </div>

        <div class="row mb-1">

            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label" style="font-size: 15px" for="values">Field Values <span
                        class="text-danger"></span></label>
                <select class="form-select" id="values" name="values[]" multiple></select>
                @error('values')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div id="emailHelp" class="form-text">Enter custom field values (Applicable only on Multi-Select,
                        RadioBox, CheckBox).</div>
                @enderror
            </div>

        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <h4 class="card-title">Custom Fields Validations</h4>
            </div>
        </div>


        <div class="row mb-1">

            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" value="0" name="required">
                                    <input class="form-check-input" type="checkbox" name="required" id="required"
                                        value="1">
                                    <label class="form-check-label" for="required">Required</label>
                                </div>
                                @error('required')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="requiredHelp" class="form-text">Check if field is required.</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" value="0" name="disabled">
                                    <input class="form-check-input" type="checkbox" name="disabled" id="disabled"
                                        value="1">
                                    <label class="form-check-label" for="disabled">Disabled</label>
                                </div>
                                @error('disabled')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="disabledHelp" class="form-text">Check if field is required.</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" value="0" name="in_table">
                                    <input class="form-check-input" type="checkbox" name="in_table" id="in_table"
                                        value="1">
                                    <label class="form-check-label" for="in_table">Show in Table</label>
                                </div>
                                @error('in_table')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="in_tableHelp" class="form-text">Check if field will show in the table.</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <div class="form-check form-check-inline">
                                    <input type="hidden" value="0" name="multiple">
                                    <input class="form-check-input" type="checkbox" name="multiple" id="multiple"
                                        value="1">
                                    <label class="form-check-label" for="multiple">Multiple Values</label>
                                </div>
                                @error('multiple')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="multipleHelp" class="form-text">Applicable only on Multi-Select Type.</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mb-1">

            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <div class="card m-0" style="border: 2px solid #eee; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="min">Min (Number) <span class="text-danger"></span></label>
                                <input type="number" class="form-control form-control-lg @error('min') is-invalid @enderror"
                                    id="min" name="min" placeholder="Min"
                                    value="{{ isset($customFiled) ? $customFiled->min : old('min') ?? 0 }}" />
                                @error('min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="minHelp" class="form-text">Applicable on only numbers (Negative allowed).</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="max">Max (Number) <span class="text-danger"></span></label>
                                <input type="number" class="form-control form-control-lg @error('max') is-invalid @enderror"
                                    id="max" name="max" placeholder="Max"
                                    value="{{ isset($customFiled) ? $customFiled->max : old('max') ?? 0 }}" />
                                @error('max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="maxHelp" class="form-text">Applicable on only numbers (Negative allowed).</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="minlength">Min length (Characters) <span class="text-danger"></span></label>
                                <input type="number" class="form-control form-control-lg @error('minlength') is-invalid @enderror"
                                    id="minlength" name="minlength" placeholder="Min length" min="0"
                                    value="{{ isset($customFiled) ? $customFiled->minlength : old('minlength') ?? 0 }}" />
                                @error('minlength')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="minlengthHelp" class="form-text">Applicable on only text & textarea.</div>
                                @enderror
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                                <label class="form-label fs-5" for="maxlength">Max length (Characters) <span class="text-danger"></span></label>
                                <input type="number" class="form-control form-control-lg @error('maxlength') is-invalid @enderror"
                                    id="maxlength" name="maxlength" placeholder="Max length" min="0"
                                    value="{{ isset($customFiled) ? $customFiled->maxlength : old('maxlength') ?? 0 }}" />
                                @error('maxlength')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div id="maxlengthHelp" class="form-text">Applicable on only text & textarea.</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
