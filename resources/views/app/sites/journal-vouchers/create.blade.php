@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.settings.journal-vouchers.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Journal Voucher')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public_assets/admin') }}/vendors/css/forms/select/select2.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
@endsection

@section('custom-css')
    <style>
        .custom_row div p {
            margin: 0;
            padding: 1rem;
            font-weight: 700;

        }

        .custom_row div input {
            margin: 0;
            padding: 1rem;
            font-weight: 700;

        }

        .custom_row {
            background-color: #f3f2f7;
        }
    </style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Create Journal Vouchers</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('sites.settings.journal-vouchers.create', encryptParams($site_id)) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form class="form form-vertical"
        action="{{ route('sites.settings.journal-vouchers.store', ['site_id' => encryptParams($site_id)]) }}" method="POST"
        id="journalVouchers">

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 position-relative">

                @csrf

                {{ view('app.sites.journal-vouchers.form-fields', [
                    'fifthLevelAccount' => $fifthLevelAccount,
                    'stakeholders' => $stakeholders,
                    'journal_serial_number' => $journal_serial_number,
                    'origin_number' => $origin_number,
                ]) }}

            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 position-relative">
                <div class="sticky-md-top top-lg-100px top-md-100px top-sm-0px" style="z-index: auto">
                    <div class="card" style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="d-block">
                                    <label class="form-label fs-5" for="created_date">Creation Date<span
                                            class="text-danger">*</span></label>
                                    <input id="created_date" type="date" required placeholder="YYYY-MM-DD"
                                        name="created_date" class="form-control form-control-md" />
                                </div>
                                {{-- <div class="d-block">
                                    <label class="form-label fs-5" for="created_date">Total Debit<span
                                            class="text-danger">*</span></label>
                                    <input readonly id="total_debit" type="text" required placeholder="Total Debit"
                                        name="total_debit" class="form-control form-control-md" />
                                </div>

                                <div class="d-block">
                                    <label class="form-label fs-5" for="created_date">Total Credit<span
                                            class="text-danger">*</span></label>
                                    <input readonly id="total_credit" type="text" required placeholder="Total Credit"
                                        name="total_credit" class="form-control form-control-md" />
                                </div> --}}

                                <hr>
                                <div class="col-md-12">
                                    <a id="saveButton" href="#"
                                        class="btn text-nowrap w-100 btn-relief-outline-success waves-effect waves-float waves-light me-1 mb-1">
                                        <i data-feather='save'></i>
                                        Save
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <a href="{{ route('sites.settings.journal-vouchers.index', ['site_id' => encryptParams($site_id)]) }}"
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
    <script src="{{ asset('app-assets') }}/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $("#created_date").flatpickr({
                defaultDate: "today",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $("#voucher_date").flatpickr({
                defaultDate: "today",
                // minDate: "today",
                altInput: !0,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

        });
    </script>

    <script>
        $(".journal-voucher-entries-list").repeater({
            // initEmpty: true,
            show: function() {
                $(this).slideDown(), feather && feather.replace({
                    width: 14,
                    height: 14
                })
                // initializeSelect2();
                $(".voucher_date").flatpickr({
                    defaultDate: "today",
                    // minDate: "today",
                    altInput: !0,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });

            },
            hide: function(e) {
                $(this).slideUp(e)
            }

        });


        $(document).on('change', '.debitInput', function(e) {
            let sum = 0;
            $('.debitInput').each(index => {
                let value = $("input[name='journal-voucher-entries[" + index + "][debit]']").val();
                if (value > 0) {
                    sum = parseFloat(sum) + parseFloat(value);
                }
            });
            $('#total_debit').val(sum.toLocaleString());
        });

        $(document).on('change', '.creditInput', function(e) {
            let sum = 0;
            $('.creditInput').each(index => {
                let value = $("input[name='journal-voucher-entries[" + index + "][credit]']").val();
                if (value > 0) {
                    sum = parseFloat(sum) + parseFloat(value);
                }
            });
            $('#total_credit').val(sum.toLocaleString());
        });


        var validator = $("#journalVouchers").validate({
            rules: {
                'name': {
                    required: true,
                },
                'serial_number': {
                    required: true,
                },
                'user_name': {
                    required: true,
                },
                'remarks': {
                    required: true,
                },
                'voucher_name': {
                    required: true,
                },
                'total_debit': {
                    required: true,
                },
                'total_credit': {
                    required: true,
                },
                // 'journal-voucher-entries[0][account_number]': {
                //     required: true
                // },
                // 'journal-voucher-entries[0][debit]': {
                //     required: true
                // },
                // 'journal-voucher-entries[0][credit]': {
                //     required: true
                // },
                // 'journal-voucher-entries[0][voucher_date]': {
                //     required: true
                // },
                // 'journal-voucher-entries[0][remarks]': {
                //     required: true
                // },
            },
            errorClass: 'is-invalid text-danger',
            errorElement: "span",
            wrapper: "div",
            submitHandler: function(form) {

                let total_debit = $('#total_debit').val();
                let total_credit = $('#total_credit').val();

                $('.is-invalid').removeClass('is-invalid');
                $('.errorClass').remove();

                if (total_debit == total_credit) {
                    form.submit();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Sum Of All Debit Amount is not equal to All Credit Amount!!',
                    });
                }
            }
        });

        $("#saveButton").click(function() {
            $("#journalVouchers").submit();
        });
    </script>



@endsection
