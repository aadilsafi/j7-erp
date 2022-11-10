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

    <div class="">
        <a href="{{ route('sites.settings.import.images.create', ['site_id' => $site_id]) }}"
            class="btn btn-primary waves-effect">
            <span>Upload Images</span>
        </a>
    </div>


    <div class="row mt-2">
        <div class="py-2">
            <h3>Receipts Images</h3>
        </div>
        @foreach (File::glob(public_path('app-assets') . '/images/Import/*') as $key => $path)
            <div class="col-3 col-md-3 col-lg-3 col-12">
                <div class="card ecommerce-card text-center">
                    <div class="card-body">
                        <img src="{{ str_replace(public_path(), '', $path) }}" height="200px" width="200px">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-6 col-12 pe-sm-0">
                                <div class="mb-1">
                                    <input type="text" class="form-control" readonly
                                        id="copy-to-clipboard-input-{{ $key }}"
                                        value={{ str_replace(public_path('app-assets/images/Import/'), '', $path) }} />
                                </div>
                            </div>
                            <div class="col-sm-2 col-12">
                                <button class="btn btn-outline-primary btn-copy" id="{{ $key }}">Copy!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


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
