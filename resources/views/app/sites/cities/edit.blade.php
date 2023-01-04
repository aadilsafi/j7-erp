@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.cities.edit', $site_id) }}
@endsection

@section('page-title', 'Edit City')

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
                <h2 class="content-header-title float-start mb-0">Edit City</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.cities.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form id="cityForm" class="form form-vertical" enctype="multipart/form-data"
        action="{{ route('sites.settings.cities.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($city->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">
                <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body">
                        @csrf
                        @method('put')
                        {{ view('app.sites.cities.form-fields', ['city' => $city], ['country' => $country]) }}
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
                            Update City
                        </button>
                        <a href="{{ route('sites.users.index', ['site_id' => encryptParams($site_id)]) }}"
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

            $("#saveButton").click(function() {
                var name = $("#name").val();
                var email = $("#email").val();
                var phone_no = $("#phone_no").val();
                $('.allErrors').empty();


                if (name == '') {
                    $('#name').after(
                        '<span class="error allErrors text-danger">Name is Required</span>');
                }

                if (email == '') {
                    $('#email').after(
                        '<span class="error allErrors text-danger">Email is Required</span>');
                }

                if (phone_no == '') {
                    $('#phone_no').after(
                        '<span class="error allErrors text-danger">Phone Number is Required</span>');
                }

                if (phone_no.toString().length != 11) {
                    $('#phone_no').after(
                        '<span class="error allErrors text-danger">Enter 11 Digits Phone Number</span>');
                }

                if (phone_no.toString().length = 11 && name != '' &&
                    email != '') {
                    $("#userForm").submit();
                }
            });
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
        var firstLoad = 0;
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

                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'countryId': $(this).val(),
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success == true) {

                            $.each(response.states, function(key, state) {
                                $("#state_id").append('<option value="' + state.id + '">' +
                                    state.name + '</option>');
                            });
                            if (firstLoad == 0) {
                                $("#state_id").val({{ $city->state_id }}).trigger('change')
                                firstLoad++;
                            }

                        } else {
                            console.log(response.status);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);

                    }
                });
            }
        });
        country.val({{ $city->country_id }}).trigger('change')
    </script>
@endsection

@section('custom-js')
@endsection
