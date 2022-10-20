@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.file-managements.file-buy-back.preview', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Preview File Buy Back ')

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
                <h2 class="content-header-title float-start mb-0">Preview File Buy Back</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.file-managements.file-buy-back.preview', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="fileRefundForm" enctype="multipart/form-data" action="#" class="">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-12 col-md-12 col-sm-12 position-relative">
                {{ view('app.sites.file-managements.files.files-actions.file-buy-back.form-fields', [
                    'site_id' => $site_id,
                    'unit' => $unit,
                    'customer' => $customer,
                    'buy_back_file' => $buy_back_file,
                    'labels' => $labels,
                    'total_paid_amount' => $total_paid_amount,
                    'salesPlan'=>$salesPlan,
                ]) }}
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
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/wNumb.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/nouislider.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/moment-range.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/additional-methods.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
@endsection

@section('custom-js')
    <script type="text/javascript">
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );
        $(".expenses-list").repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown(), feather && feather.replace({
                    width: 14,
                    height: 14
                })
            },
            hide: function(e) {
                $(this).slideUp(e)
            }
        });

        var files = [];

        @if (isset($images))

            @foreach ($images as $image)
                files.push({
                    source: '{{ $image }}',
                });
            @endforeach
        @endif

        function initializeFilePond() {
            const inputElements = document.querySelectorAll('input.filepond');
            Array.from(inputElements).forEach((inputElement, key) => {
                console.log(files[key]);

                // create a FilePond instance at the input element location
                FilePond.create(inputElement, {
                    files: [files[key]],
                    styleButtonRemoveItemPosition: 'right',
                    imageCropAspectRatio: '1:1',
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    maxFileSize: '1536KB',
                    ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
                    storeAsFile: true,
                    allowMultiple: true,
                    maxFiles: 1,
                    checkValidity: true,
                    credits: {
                        label: '',
                        url: ''
                    }
                });
            });
        }
        initializeFilePond();

        $("#saveButton").click(function() {
            $("#fileRefundForm").submit();
        });
    </script>
@endsection
