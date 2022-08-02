@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.floors.units.edit', encryptParams($site->id), encryptParams($floor->id)) }}
@endsection

@section('page-title', 'Edit Unit')

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
                <h2 class="content-header-title float-start mb-0">Edit Unit</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.floors.units.edit', encryptParams($site->id), encryptParams($floor->id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form class="form form-vertical"
            action="{{ route('sites.floors.units.update', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id), 'id' => encryptParams($unit->id)]) }}"
            method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">
                @method('PUT')
                @csrf
                {{ view('app.sites.floors.units.form-fields', [
                    'site' => $site,
                    'floor' => $floor,
                    'siteConfiguration' => $siteConfiguration,
                    'additionalCosts' => $additionalCosts,
                    'types' => $types,
                    'statuses' => $statuses,
                    'unit' => $unit,
                    'bulkOptions' => false,
                ]) }}

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    Update Unit
                </button>
                <a href="{{ route('sites.floors.units.index', ['site_id' => encryptParams($site->id), 'floor_id' => encryptParams($floor->id)]) }}"
                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    {{ __('lang.commons.cancel') }}
                </a>
            </div>

        </form>
    </div>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {

            $('#is_corner').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#corner_id').attr('disabled', false);
                } else {
                    $('#corner_id').attr('disabled', true);
                }
            });

            $('#is_facing').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#facing_id').attr('disabled', false);
                } else {
                    $('#facing_id').attr('disabled', true);
                }
            });

            $('#is_corner').trigger('change');
            $('#is_facing').trigger('change');

        });
    </script>
@endsection
