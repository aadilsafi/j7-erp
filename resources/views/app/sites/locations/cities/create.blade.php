@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.cities.create', $site_id) }}
@endsection

@section('page-title', 'Create City')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
    <style>
        .filepond--drop-label {
            color: #7367F0 !important;
        }

        .filepond--item-panel {
            background-color: #7367F0;
        }

        .filepond--panel-root {
            background-color: #e3e0fd;
        }

        /* .filepond--item {
                        width: calc(20% - 0.5em);
                    } */
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create City</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.cities.create', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="cityForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.settings.cities.store', ['site_id' => encryptParams($site_id)]) }}" method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card">
                    <div class="card-body" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        @csrf
                        {{ view('app.sites.locations.cities.form-fields', ['country' => $country]) }}
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        {{-- <div class="d-block mb-1">
                        <label class="form-label fs-5" for="type_name">CNIC Attachment</label>
                        <input id="attachment" type="file" class="filepond @error('attachment') is-invalid @enderror"
                            name="attachment[]" multiple accept="image/png, image/jpeg, image/gif" />
                        @error('attachment')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr> --}}

                        <button type="submit"
                            class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                            <i data-feather='save'></i>
                            Save City
                        </button>

                        <a href="{{ route('sites.settings.cities.index', ['site_id' => encryptParams($site_id)]) }}"
                            class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                            <i data-feather='x'></i>
                            {{ __('lang.commons.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>

@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        FilePond.create(document.getElementById('attachment'), {
            styleButtonRemoveItemPosition: 'right',
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            maxFiles: 2,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var e = $("#role_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            });

        });
        var country = $("#country_id");
        country.wrap('<div class="position-relative"></div>');
        country.select2({
            dropdownAutoWidth: !0,
            dropdownParent: country.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            $('#state_id').empty();
            $('#state_id').html('<option value=0>Select State</option>');

            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('ajax-get-states', ['countryId' => ':countryId']) }}"
                .replace(':countryId', $(this).val());
            if ($(this).val() > 0) {
                showBlockUI('#cityForm');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'countryId': $(this).val(),
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success==true) {

                            $.each(response.states, function(key, state) {
                                console.log(state.id);
                                $("#state_id").append('<option value="' +state.id + '">' + state.name + '</option>');
                            });
                            hideBlockUI('#cityForm');

                        } else {
                            console.log(response.status);

                            hideBlockUI('#cityForm');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#cityForm');
                    }
                });
            }
        });
    </script>
@endsection