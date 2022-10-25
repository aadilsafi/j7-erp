<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-12 col-md-12 col-sm-12 position-relative">
                <label class="form-label fs-5" for="lead-source-name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg @error('lead_source_name') is-invalid @enderror"
                    id="lead-source-name" name="lead_source_name" placeholder="Name"
                    value="{{ isset($leadSource) ? $leadSource->name : old('lead_source_name') }}" />
                @error('lead_source_name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if (isset($customFields) && count($customFields) > 0)
            <hr>
            <div class="row mb-1 g-1">
                @forelse ($customFields as $field)
                    {!! $field !!}
                @empty
                @endforelse
            </div>
        @endif
    </div>
</div>
