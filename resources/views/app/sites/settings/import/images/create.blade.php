@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.settings.import') }}
@endsection

@section('page-title', 'Import Images')
@section('page-vendor')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/pages/app-ecommerce.min.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Import Images</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.settings.import') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <form class="form form-vertical" action="{{ route('sites.settings.import.images.store', ['site_id' => $site_id]) }}"
        enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row py-1">

            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="position-relative">
                        <div class="card-body">
                            <div class="d-block mb-1">
                                <label class="form-label fs-5" for="type_name"> </label>
                                <input id="attachment" type="file" class="filepond" name="attachment[]" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: 10 !important;">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <button type="submit" value="save"
                                class="btn w-100 btn-relief-outline-success waves-effect waves-float waves-light buttonToBlockUI mb-1">
                                <i data-feather='save'></i>
                                Save Images
                            </button>

                            <a href="{{ route('sites.settings.import.images.cancel', ['site_id' => $site_id]) }}"
                                class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                                <i data-feather='x'></i>
                                {{ __('lang.commons.cancel') }}
                            </a>
                        </div>
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
    <script src="{{ asset('app-assets') }}/js/scripts/extensions/ext-component-clipboard.min.js"></script>

    {{-- <script src="{{ asset('app-assets') }}/js/scripts/pages/app-ecommerce-wishlist.min.js"></script> --}}
    <!-- END: Page JS-->
@endsection

@section('custom-js')
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }

            "use strict";
            btnCopy = $(".btn-copy"),
                btnCopy.on("click", (function() {
                    id = ($(this).attr('id')),
                        userText = $("#copy-to-clipboard-input-" + id),
                        userText.select(),
                        document.execCommand("copy"),
                        toastr.success("",
                            "Copied to clipboard!")
                }));
        });

        FilePond.registerPlugin(
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
            storeAsFile: false,
            server: {
                process: '{{ route('ajax-import-image.save-file') }}',
                revert: '{{ route('ajax-import-image.revert-file') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            allowMultiple: true,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });

        $('#attachment').on('FilePond:processfiles', (function(file) {
            $('#saveImages').attr('disabled', false);
        }));
    </script>
@endsection
