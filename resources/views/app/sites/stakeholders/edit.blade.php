@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.stakeholders.edit', $site_id) }}
@endsection

@section('page-title', 'Edit Stakeholder')

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

        / the background color of the file and file panel (used when dropping an image) / .filepond--item-panel {
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
                <h2 class="content-header-title float-start mb-0">Edit Stakeholder</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.stakeholders.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="stakeholderForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.stakeholders.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($stakeholder->id)]) }}"
        method="POST">

        <div class="card-header">
        </div>

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card-body" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    @csrf
                    @method('put')
                    {{ view('app.sites.stakeholders.form-fields', ['stakeholders' => $stakeholders, 'stakeholder' => $stakeholder]) }}
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        <div class="d-block mb-1">
                            <label class="form-label fs-5" for="type_name">CNIC Attachment</label>
                            <input id="attachment" type="file" class="filepond @error('attachment') is-invalid @enderror"
                                name="attachment[]" multiple accept="image/png, image/jpeg, image/gif" />
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <hr>
                        <a id="saveButton" href="#"
                            class="btn   w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1">
                            <i data-feather='save'></i>
                            Update Stakeholder
                        </a>
                        <a href="{{ route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script>
        var editImage = "";
        let imageArray = [];
        editImage = <?php echo json_encode($stakeholder->attachment); ?>;
        if(editImage != null){
         imageArray = editImage.split(',');
        }
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageValidateSize,
            FilePondPluginImageCrop,
        );

        FilePond.create(document.getElementById('attachment'), {

            files: [{
                    source: '{{ asset('app-assets') }}/stakeholder/cnic/attachments/' + imageArray[0],
                },
                {
                    source: '{{ asset('app-assets') }}/stakeholder/cnic/attachments/' + imageArray[1],
                },
            ],
            styleButtonRemoveItemPosition: 'right',
            // imageValidateSizeMinWidth: 1000,
            // imageValidateSizeMinHeight: 1000,
            imageCropAspectRatio: '1:1',
            acceptedFileTypes: ['image/png', 'image/jpeg'],
            maxFileSize: '1536KB',
            ignoredFiles: ['.ds_store', 'thumbs.db', 'desktop.ini'],
            storeAsFile: true,
            allowMultiple: true,
            maxFiles: 2,
            minFiles:2,
            required: true,
            checkValidity: true,
            credits: {
                label: '',
                url: ''
            }
        });
    </script>
@endsection

@section('page-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#saveButton").click(function() {
                var full_name = $("#full_name").val();
                var father_name = $("#father_name").val();
                var occupation = $("#occupation").val();
                var designation = $("#designation").val();
                var address = $("#address").val();
                var cnic = $("#cnic").val();
                var contact = $("#contact").val();
                $('.allErrors').empty();


                if (full_name == '') {
                    $('#full_name').after(
                        '<span class="error allErrors text-danger">Full Name is Required</span>');
                }

                if (father_name == '') {
                    $('#father_name').after(
                        '<span class="error allErrors text-danger">Father Name is Required</span>');
                }

                if (occupation == '') {
                    $('#occupation').after(
                        '<span class="error allErrors text-danger">Occupation is Required</span>');
                }

                if (designation == '') {
                    $('#designation').after(
                        '<span class="error allErrors text-danger">Designation is Required</span>');
                }

                 // if (!$.isNumeric(cnic)) {
                //     $('#cnic').after(
                //     '<span class="error allErrors text-danger">Enter Numeric Value</span>');
                // }

                if (cnic.toString().length != 13) {
                    $('#cnic').after(
                    '<span class="error allErrors text-danger">Enter 13 Digits Numeric Value</span>');
                }
                if (!$.isNumeric(contact)) {
                    $('#contact').after(
                        '<span class="error allErrors text-danger">Enter Numeric Value</span>');
                }
                if (cnic.toString().length == 13 && $.isNumeric(contact) && full_name != '' && father_name != '' &&
                    occupation != '' && designation != '') {
                    $("#stakeholderForm").submit();
                }
            });
        });
    </script>
@endsection

@section('custom-js')
@endsection
