@extends('app.layout.layout')


@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.blacklisted-stakeholders.edit', $site_id) }}
@endsection

@section('page-title', 'Edit BlackList Stack Holder')

@section('page-vendor')
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Edit BlackList StackHolder</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.blacklisted-stakeholders.edit', $site_id) }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <form class="form form-vertical"
        action="{{ route('sites.blacklisted-stakeholders.update', ['site_id' => encryptParams($site_id), 'id' => encryptParams($stakeholder->id)]) }}"
        method="POST">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @method('PUT')
                @csrf
                {{ view('app.sites.stakeholders.blacklisted-stakeholders.form-fields', [
                    'blacklist' => $stakeholder,
                    'country' => $country,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-12">
                                    @can('sites.floors.update')
                                        <button type="submit"
                                            class="btn btn-relief-outline-success w-100 waves-effect waves-float waves-light buttonToBlockUI me-1">
                                            <i data-feather='save'></i>
                                            Update
                                        </button>
                                    @endcan

                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.blacklisted-stakeholders.index', ['site_id' => encryptParams($site_id)]) }}"
                                        class="btn btn-relief-outline-danger w-100 waves-effect waves-float waves-light">
                                        <i data-feather='x'></i>
                                        {{ __('lang.commons.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        $('#country').val('{{ $stakeholder->country_id }}');

        $("#country").trigger('change');

        let firstLoad = true;
        var residential_country = $("#country");

        residential_country.wrap('<div class="position-relative"></div>');
        residential_country.select2({
            dropdownAutoWidth: !0,
            dropdownParent: residential_country.parent(),
            width: "100%",
            containerCssClass: "select-lg",
        }).change(function() {

            var token = '{{ csrf_token() }}';
            var idCountry = this.value;
            let url = "{{ url('ajax-get-states') }}"
            var cntyUrl = url + "/" + idCountry;
            $.ajax({
                type: "POST",
                url: cntyUrl,
                data: {
                    token: token
                },
                dataType: 'json',
                success: function(result) {

                    $("#state").empty();
                    $("#city").empty();
                    $.each(result.states, function(key, value) {
                        $("#state").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    if (firstLoad) {
                        $("#state").val('{{ $stakeholder->state_id }}')
                        firstLoad = false
                    }

                    $('#city').html('<option value=""Select City </option>');
                }
            });
        });

        //State Dropdown Change Event

        $('#state').on('change', function() {

            var token = '{{ csrf_token() }}';
            var stateId = this.value;
            let url = "{{ url('ajax-get-cities') }}"
            var stateUrl = url + "/" + stateId;
            $.ajax({
                type: "POST",
                url: stateUrl,
                data: {
                    token: token
                },
                dataType: 'json',
                success: function(result) {
                    console.log(result)
                    $("#city").empty();
                    $.each(result.cities, function(key, value) {
                        $("#city").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });


                }
            });
        });
    </script>
@endsection
