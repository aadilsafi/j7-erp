<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="lead-source-name">Name</label>
                <input type="text" class="form-control form-control-lg @error('lead_source_name') is-invalid @enderror"
                    id="lead-source-name" name="lead_source_name" placeholder="Name"
                    value="{{ isset($leadSource) ? $leadSource->name : old('lead_source_name') }}" />
                @error('lead_source_name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
