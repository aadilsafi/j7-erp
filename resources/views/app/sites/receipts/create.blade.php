@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.receipts.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Receipts')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/filepond.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Receipts</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.receipts.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<form id="receiptForm" action="{{ route('sites.receipts.store', ['site_id' => encryptParams($site_id)]) }}" method="post" class="invoice-repeater">
    @csrf
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-9 position-relative">
            {{ view('app.sites.receipts.form-fields') }}
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
            <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                <div class="card-body">
                    <div class="d-block mb-1">
                        <button class="btn text-nowrap w-100 btn-relief-outline-primary waves-effect waves-float waves-light me-1 mb-1" type="button" data-repeater-create>
                            <i data-feather="plus" class="me-25"></i>
                            <span class="text-nowrap">Receipt Form</span>
                        </button>
                    </div>
                    <hr>
                    <a id="saveButton" href="#"
                        class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                        <i data-feather='save'></i>
                        Save Receipts
                    </a>

                    <a href="{{ route('sites.receipts.index', ['site_id' => encryptParams($site_id)]) }}"
                        class="btn w-100 btn-relief-outline-danger waves-effect waves-float waves-light">
                        <i data-feather='x'></i>
                        {{ __('lang.commons.cancel') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>





            {{-- <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <form action="#" class="invoice-repeater">
                                @csrf
                                <div class="row">
                                    {{ view('app.sites.receipts.form-fields') }}

                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <button class="btn btn-icon btn-primary" type="button" data-repeater-create>
                                            <i data-feather="plus" class="me-25"></i>
                                            <span>Add New</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}
@endsection

        @section('vendor-js')
            <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
            <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.preview.min.js"></script>
            <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.typevalidation.min.js"></script>
            <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagecrop.min.js"></script>
            <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.imagesizevalidation.min.js"></script>
            <script src="{{ asset('app-assets') }}/vendors/filepond/plugins/filepond.filesizevalidation.min.js"></script>
            <script src="{{ asset('app-assets') }}/vendors/filepond/filepond.min.js"></script>
            <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>
        @endsection

        @section('page-js')
        @endsection

        @section('custom-js')

            <script type="text/javascript">
                $(document).ready(function() {
                    $("#saveButton").click(function() {
                        $("#receiptForm").submit();
                    });
                });
            </script>
        @endsection
