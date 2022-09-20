@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'sites.receipts.create', encryptParams($site_id)) }}
@endsection

@section('page-title', 'Create Receipts')

@section('page-vendor')
@endsection

@section('page-css')
@endsection

@section('custom-css')
    <style>
        #otherValueDiv {
            display: none;
        }

        #installmentValueDiv {
            display: none;
        }

        #otherPurposeValueDiv {
            display: none;
        }

        #instllmentTableDiv {
            display: none;
        }

        #paidInstllmentTableDiv {
            display: none;
        }

        .onlineValueDiv {
            display: none;
        }

        #chequeValueDiv {
            display: none;
        }

        #modeOfPaymentDiv{
            display: none;
        }

    </style>
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
    <form id="receiptForm" action="{{ route('sites.receipts.store', ['site_id' => encryptParams($site_id)]) }}"
        method="post" class=" repeater">
        @csrf
        <div class="row">
            <div id="loader" class="col-lg-9 col-md-9 col-sm-9 position-relative">
                {{ view('app.sites.receipts.form-fields', [
                    'site_id' => $site_id,
                    'units' => $units,
                ]) }}
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 position-relative">
                <div class="card sticky-md-top top-lg-100px top-md-100px top-sm-0px"
                    style="border: 2px solid #7367F0; border-style: dashed; border-radius: 0;">
                    <div class="card-body g-1">
                        {{-- <div class="d-block mb-1">
                            <button
                                class="btn text-nowrap w-100 btn-relief-outline-primary waves-effect waves-float waves-light me-1 mb-1"
                                type="button" data-repeater-create>
                                <i data-feather="plus" class="me-25"></i>
                                <span class="text-nowrap">Receipt Form</span>
                            </button>
                        </div>
                        <hr> --}}
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
@endsection

@section('vendor-js')
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-repeater.min.js"></script>
@endsection

@section('page-js')
@endsection

@section('custom-js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.repeater').repeater({

                show: function () {
                    $(this).slideDown();
                },

                hide: function(deleteElement) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Are you sure you want to delete this receipt form!!!',
                        showCancelButton: true,
                        cancelButtonText: '{{ __('lang.commons.no_cancel') }}',
                        confirmButtonText: '{{ __('lang.commons.yes_delete') }}',
                        confirmButtonClass: 'btn-danger',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).slideUp(deleteElement);
                        }
                    });
                },
                isFirstItemUndeletable: true
            })
            var e = $("#unit_id");
            e.wrap('<div class="position-relative"></div>');
            e.select2({
                dropdownAutoWidth: !0,
                dropdownParent: e.parent(),
                width: "100%",
                containerCssClass: "select-lg",
            })
        });



        $(document).ready(function() {

            $(".other-mode-of-payment").click(function() {
                $('#otherValueDiv').css("display", "block");
                $('.onlineValueDiv').css("display", "none");
                $('#chequeValueDiv').css("display", "none");
            });

            $(".cheque-mode-of-payment").click(function() {
                $('#otherValueDiv').css("display", "none");
                $('.onlineValueDiv').css("display", "none");
                $('#chequeValueDiv').css("display", "block");
            });

            $(".online-mode-of-payment").click(function() {
                $('#otherValueDiv').css("display", "none");
                $('.onlineValueDiv').css("display", "block");
                $('#chequeValueDiv').css("display", "none");
            });


            $(".mode-of-payment").click(function() {
                $('#otherValueDiv').css("display", "none");
                $('.onlineValueDiv').css("display", "none");
                $('#chequeValueDiv').css("display", "none");
            });

            $(".other-purpose").click(function() {
                $('#otherPurposeValueDiv').css("display", "block");
                $('#installmentValueDiv').css("display", "none");
            });

            $(".installment-purpose").click(function() {
                $('#installmentValueDiv').css("display", "block");
                $('#otherPurposeValueDiv').css("display", "none");
            });

            $(".purpose").click(function() {
                $('#otherPurposeValueDiv').css("display", "none");
                $('#installmentValueDiv').css("display", "none");
            });

        });

        function setIds(a) {
            // $(':input[name="receipts[0][unit_name]"]').empty()
            // $(':input[name="receipts[0][unit_name]"]').append('<option value="0" selected>asdsd</option>');

            var unit_id= a.name;
            // const unit_type= a.name.replace("unit_id", "unit_type");
            // const unit_name= a.name.replace("unit_id", "unit_name");
            // const floor= a.name.replace("unit_id", "floor");

            // const unit_name_attr = $('.unit_name').attr('id');
            // const unit_type_attr = $('.unit_type').attr('id');
            // const floor_attr = $('.floor').attr('id');

            // alert($('.unit_name').attr("id"))

            $('.unit_id').attr('id', unit_id);

            // if ( unit_name_attr !== 'undefined' && unit_name_attr !== false) {
            //     $('.unit_name').attr('id', unit_name);
            // }

            // if ( unit_type_attr !== 'undefined' && unit_type_attr !== false) {
            //     $('.unit_type').attr('id', unit_type);
            // }

            // if ( floor_attr !== 'undefined' && floor_attr !== false) {
            //     $('.floor').attr('id', floor);
            // }

        }

        function setAmountIds(a){
            let elements = document.getElementsByName(a.name);
        }

        // $('*[name="receipts[0][amount_in_numbers]"]').on('focusout', function() {
        //     alert('asd')
        // });


        $('.amountToBePaid').on('focusout', function() {

            var amount = $(this).val();
            var unit_id = $(this).attr('unit_id');
            if(amount <= 0){
                toastr.error('Invalid Amount.',
                            "Error!", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 2e3,
                                closeButton: !0,
                                tapToDismiss: !1,
                            });
            }
            if(unit_id == null || unit_id == 'undefined'){
                toastr.error('Please Select Unit Number first.',
                            "Error!", {
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                timeOut: 2e3,
                                closeButton: !0,
                                tapToDismiss: !1,
                            });
            }
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.receipts.ajax-get-unpaid-installments', ['site_id' => encryptParams($site_id)]) }}";
            if(amount > 0 && unit_id > 0){
                showBlockUI('#loader');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        'unit_id': unit_id,
                        'amount': amount,
                        '_token': _token
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#paidInstllmentTableDiv').css("display", "block");
                            $('#instllmentTableDiv').css("display", "block");
                            $('#modeOfPaymentDiv').css("display", "block");
                            $('#paid_dynamic_total_installment_rows').empty();
                            $('#dynamic_total_installment_rows').empty();
                            $('#installments').empty();
                            var total_installments = 1;
                            var order = null;

                            for(var i = 0; i <= response.already_paid.length; i++){
                                if( response.already_paid[i] != null){
                                    var d = response.already_paid[i]['details']

                                $('#paid_dynamic_total_installment_rows').append('<tr class="text-nowrap">',
                                    '<td class="text-nowrap text-center">'+(i+1)+'</td>',
                                    '<td class="text-nowrap text-center">' + response.already_paid[i]['details'] + '</td>',
                                    // '<td class="text-nowrap text-center">'+response.total_calculated_installments[i]['date']+'</td>',
                                    '<td class="text-nowrap text-center">' + response
                                    .already_paid[i]['amount'] + '</td>',
                                    '<td class="text-nowrap text-center">' + response
                                    .already_paid[i]['paid_amount'] + '</td>',
                                    '<td class="text-nowrap text-center">' + response
                                    .already_paid[i]['remaining_amount'] + '</td>',
                                    '</tr>',
                                    '<td class="text-nowrap text-center">' + response.already_paid[i]['status'] + '</td>',
                                    '</tr>', );
                                }
                            }

                            for (i = 0; i <= response.total_calculated_installments.length; i++) {
                                if(response.total_calculated_installments[i] != null){
                                if( response.total_calculated_installments[i]['installment_order'] == 0 ){
                                    order = 'Down Payment';
                                }
                                else{
                                    order = response.total_calculated_installments[i]['installment_order'];
                                }
                                $('#dynamic_total_installment_rows').append('<tr class="text-nowrap">',
                                    '<td class="text-nowrap text-center">'+(i+1)+'</td>',
                                    '<td class="text-nowrap text-center">' + order + '</td>',
                                    // '<td class="text-nowrap text-center">'+response.total_calculated_installments[i]['date']+'</td>',
                                    '<td class="text-nowrap text-center">' + response
                                    .total_calculated_installments[i]['amount'] + '</td>',
                                    '<td class="text-nowrap text-center">' + response
                                    .total_calculated_installments[i]['paid_amount'] + '</td>',
                                    '<td class="text-nowrap text-center">' + response
                                    .total_calculated_installments[i]['remaining_amount'] + '</td>',
                                    '</tr>',
                                    '<td class="text-nowrap text-center">' + response.total_calculated_installments[i]['partially_paid'] + '</td>',
                                    '</tr>', );
                            }}
                            hideBlockUI('#loader');

                        } else {
                            hideBlockUI('#loader');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        hideBlockUI('#loader');
                    }
                });
            }
        });

        function getUnitTypeAndFloor(unit_id,id) {
            var unit_type= id.replace("unit_id", "unit_type");
            var unit_name=id.replace("unit_id", "unit_name");
            var floor= id.replace("unit_id", "floor");
            // $(':input[name="receipts[1][floor]"]').empty();
            // $( "input[name*='receipts[1][floor]']" ).empty()
            var _token = '{{ csrf_token() }}';
            let url =
                "{{ route('sites.receipts.ajax-get-unit-type-and-unit-floor', ['site_id' => encryptParams($site_id)]) }}";
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: {
                    'unit_id': unit_id,
                    '_token': _token
                },
                success: function(response) {
                    if (response.success) {
                        $('.amountToBePaid').attr('unit_id', response.unit_id);
                        $(':input[name="'+unit_type+'"]').empty();
                        $('.amountToBePaid').empty();
                        $(':input[name="'+floor+'"]').empty()
                        $(':input[name="'+unit_name+'"]').empty();
                        $(':input[name="'+unit_type+'"]').append('<option value="0" selected>' + response.unit_type +
                        '</option>');
                        $(':input[name="'+floor+'"]').append('<option value="0" selected>' + response.unit_floor + '</option>');
                        $(':input[name="'+unit_name+'"]').append('<option value="0" selected>' + response.unit_name +
                        '</option>');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something Went Wrong!!',
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $("#saveButton").click(function() {
            $("#receiptForm").submit();
                // var unit_id = $(".unit_id").val();
                // var amountToBePaid = $("#amountToBePaid").val();
                // var mode_of_payment = $('.check_class').val();

                // $('.allErrors').empty();

                // if (unit_id == 0) {
                //     $('.unit_id').after(
                //     '<span class="error allErrors text-danger">Unit is Required</span>');
                // }

                // if (amountToBePaid == '') {
                //     $('.amountToBePaid').after(
                //     '<span class="error allErrors text-danger">Amount is Required</span>');
                // }
                // if (mode_of_payment == '') {
                //     $('.mode_of_payment').after(
                //     '<span class="error allErrors text-danger">Mode Of Payment is Required</span>');
                // }

                // if (unit_id != 0 && amountToBePaid != '' && mode_of_payment != '') {
                //     $("#receiptForm").submit();
                // }
        });
    </script>
@endsection
