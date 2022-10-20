<div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="name">Name</label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                    id="name" name="name" placeholder="Name"
                    value="{{ isset($floor) ? $floor->name : old('name') }}" />
                @error('name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="floor_order">Floor Order</label>
                <input type="text" class="form-control form-control-lg @error('floor_order') is-invalid @enderror"
                    id="floor_order" name="floor_order" placeholder="Floor Order"
                    value="{{ isset($floor) ? $floor->order : old('order') ?? $floorOrder }}" readonly />
                @error('name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- <div class="row mb-1">
    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="width">Width (sqft)</label>
        <input type="text" class="form-control form-control-lg @error('width') is-invalid @enderror" id="width"
            name="width" placeholder="Width (sqft)" value="{{ isset($floor) ? $floor->width : (old('width') ?? 0) }}" />
        @error('width')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
        <label class="form-label fs-5" for="length">Length (sqft)</label>
        <input type="text" class="form-control form-control-lg @error('length') is-invalid @enderror" id="length"
            name="length" placeholder="Length (sqft)" value="{{ isset($floor) ? $floor->length : (old('length') ?? 0) }}" />
        @error('length')
            <div class="invalid-tooltip">{{ $message }}</div>
        @enderror
    </div>
</div> --}}

        <div class="row mb-1">
            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="floor_area">Floor Area (sqft)</label>
                <input type="text" class="form-control form-control-lg @error('floor_area') is-invalid @enderror"
                    id="floor_area" name="floor_area" placeholder="Floor Area (sqft)"
                    value="{{ isset($floor) ? $floor->floor_area : old('floor_area') ?? 0 }}" />
                @error('floor_area')
                    <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 position-relative">
                <label class="form-label fs-5" for="short_label">Short label</label>
                <input type="text" class="form-control form-control-lg @error('short_label') is-invalid @enderror"
                    id="short_label" name="short_label" placeholder="Short label"
                    value="{{ isset($floor) ? $floor->short_label : old('short_label') ?? $floorOrder . $floorShortLable }}" />
                @error('short_label')
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
