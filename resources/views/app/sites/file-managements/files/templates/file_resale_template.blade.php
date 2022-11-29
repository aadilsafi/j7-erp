<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap-extended.min.css">
    <title>Request Resale Form</title>

    <style>
        * {
            font-size: small;
            color: black;
        }

        .table-bordered {
            border: 1px solid black;
        }

        h3,
        h2,
        h3,
        h4,
        h5 {
            color: black;
        }

        input[type=checkbox] {
            -ms-transform: scale(2);
            /* IE */
            -moz-transform: scale(2);
            /* FF */
            -webkit-transform: scale(2);
            /* Safari and Chrome */
            -o-transform: scale(2);
            /* Opera */
            padding: 5px;
        }

        .customLabel {
            padding: 5px;
        }

        .title {
            font-size: 22px;
        }

        .title2 {
            font-size: 17px;
        }
    </style>
</head>

<body>

    <div id="printable" class="bg-light mx-2 my-1">


        <div class="row mt-3">
            <div class="col">
                {{-- <img height="40px" width="70%" src="{{ asset('app-assets') }}/images/receipts/logo_j7Global.png" alt="logo"> --}}
            </div>
            <div class="col">
                <span class="title">RESALE REQUEST</span> <strong class="title"> FORM</strong>

                <div class="row mt-2">
                    <div class="col-2">
                        <strong> Date: </strong>
                    </div>
                    <div class="col-6" style="border-bottom: 1px solid black">{{ $file_resale->created_date }}
                    </div>
                </div>
            </div>
        </div>

        <div style="border-bottom: 1px solid black" class="mt-2"></div>

        {{-- <div class="row mt-5">
            <div class="col-3 mt-2" style="width: fit-content;">
                <h5>J7 Emporium:*</h5>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" class="mx-1">
            </div>
            <div class="col-3 mt-2" style="width: fit-content;">
                <h5>J7 Emporium Ext:*</h5>
            </div>
            <div class="col-3 mt-2">
                <input type="checkbox" class="mx-1">
            </div>
        </div> --}}


        <div class="row mt-3 text-justify">
            <div class="col-1" style="width: fit-content; height:min-content">
                <p>I / We </p>
            </div>
            <div class="col-2 text-center">
                <p style="border-bottom: 1px solid black;">
                    {{ $customer->full_name }}
                </p>
            </div>
            <div class="col text-nowrap" style="width: fit-content;">
                <p>are requesting Sign Marketing team to resale my unit </p>
            </div>
            <div class="col text-center">
                <p style="border-bottom: 1px solid black;">
                    {{ $unit->floor_unit_number }}
                </p>
            </div>
            <div class="col-4 text-nowrap" style="width: fit-content;">
                <p>Which was booked by me in J7 Global, Mumtaz City, Islamabad. I also agree to pay </p>
            </div>
            <div class="col-1 text-center" style="width: fit-content;">
                <p style="border-bottom: 1px solid black;">
                    {{ number_format($file_resale->marketing_service_charges) }}
                </p>
            </div>
            <div class="col text-nowrap" style="width: fit-content;">
                <p>sign marketing</p>
            </div>
            <p>
                service charges at the time of unit sale. Further details of the unit are as under:
            </p>

        </div>

        <!-- <div style="border-bottom: 1px solid black" class="my-2"></div> -->
        <hr class="mt-4 mb-4" style="border-bottom: 1px solid black">

        <div class="row text-nowrap g-1 mt-2">
            <div class="col" style="width: fit-content;">
                <h4>Owner Name:*</h4>
            </div>
            <div class="col text-center">
                <p style="border-bottom: 1px solid black">
                    {{ $customer->full_name }}
                </p>
            </div>
            <div class="col" style="width: fit-content;">
                <h4>Unit No:*</h4>
            </div>
            <div class="col text-center">
                <p style="border-bottom: 1px solid black">
                    {{ $unit->floor_unit_number }}
                </p>
            </div>
            <div class="col" style="width: fit-content;">
                <h4> Size Of Unit:*</h4>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $unit->gross_area }}
                </div>
            </div>
            <div class="col" style="width: fit-content;">
                <h4>Floor:*</h4>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $unit->floor->short_label }}</div>
            </div>
        </div>

        <div class="row mt-1 text-nowrap g-1">
            <div class="col" style="width: fit-content;">
                <h4>Previous Sold Rate/sqft:*</h4>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $unit->price_sqft }}</div>
            </div>
            <div class="col-2">
                <h4>Total Amount:*</h4>
            </div>
            <div class="col-4 text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $salesPlan->total_price }}</div>
            </div>
        </div>
        <div class="row mt-1 text-nowrap g-1">
            <div class="col" style="width: fit-content;">
                <h4>DP Received:*</h4>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $salesPlan->down_payment_total }}</div>
            </div>
            <div class="col-1" style="width: fit-content;">
                <h4>DP%:*</h4>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $salesPlan->down_payment_percentage }} %
                </div>
            </div>
            <div class="col" style="width: fit-content;">
                <h4>Date Of Purchase :*</h4>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black">
                    {{ $salesPlan->approved_date }}</div>
            </div>
        </div>

        <div class="row g-1 mt-1">
            <div class="col-4" style="width: fit-content;">
                <h4>installment received:*</h4>
            </div>
            <div class="col-2">
                <div class="row g-0" style="width: fit-content;">
                    <div class="col-6">
                        <input type="checkbox" class="mx-1 p-1 untouch"
                            {{ count($installmentsRecevied) > 0 ? 'checked' : '' }}>
                    </div>
                    <div class="col-6">
                        <input type="checkbox" class="mx-1 p-1 untouch"
                            {{ count($installmentsRecevied) == 0 ? 'checked' : '' }}>
                    </div>
                    <div class="col-6">
                        <h3 class="customLabel">Yes</h3>
                    </div>
                    <div class="col-6">
                        <h3 class="customLabel">No</h3>
                    </div>
                </div>
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black"
                    class="{{ count($installmentsRecevied) == 0 ? 'mt-1' : '' }}">
                    @foreach ($installmentsRecevied as $pending)
                        {{ !$loop->last ? Str::replace('Installment', '', $pending) : $pending }}
                        {{ !$loop->last ? ' , ' : null }}
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row g-1">
            <div class="col-4" style="width: fit-content;">
                <h4>installment Pending:*</h4>
            </div>
            <div class="col-2">
                <div class="row g-0" style="width: fit-content;">
                    <div class="col-6">
                        <input type="checkbox" class="mx-1 p-1 untouch" {{ count($unpaid) > 0 ? 'checked' : '' }}>
                    </div>
                    <div class="col-6">
                        <input type="checkbox" class="mx-1 p-1 untouch" {{ count($unpaid) == 0 ? 'checked' : '' }}>
                    </div>
                    <div class="col-6">
                        <h3 class="customLabel">Yes</h3>
                    </div>
                    <div class="col-6">
                        <h3 class="customLabel">No</h3>
                    </div>
                </div>
            </div>
            <div class="col text-center">
                <div style="border-bottom: 1px solid black" class="{{ count($unpaid) == 0 ? 'mt-1' : '' }}">
                    @foreach ($unpaid as $pending)
                        {{ !$loop->last ? Str::replace('Installment', '', $pending) : $pending }}
                        {{ !$loop->last ? ' , ' : null }}
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <strong class="title2">RESALE MODE:*</strong>
        </div>

        <div class="row mt-1">
            <div class="col-6 text-start">
                <div class="row g-1 mt-1">
                    <div class="col-4" style="width: fit-content;">
                        <h4>Premium Model:*</h4>
                    </div>
                    <div class="col-2">
                        <input type="checkbox" class="mx-1 p-1 untouch"
                            {{ $file_resale->premium_demand != null ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="row g-1 mt-1">
                    <div class="col-4" style="width: fit-content;">
                        <h4>New Rate Mode:*</h4>
                    </div>
                    <div class="col-2">
                        <input type="checkbox" class="mx-1 p-1 untouch"
                            {{ $file_resale->new_resale_rate != null ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="col-6 text-end">
                <div class="row g-1 mt-2">
                    <div class="col-4" style="width: fit-content;">
                        <h4>Premium Demand:*</h4>
                    </div>
                    <div class="col text-center">
                        <div style="border-bottom: 1px solid black">
                            {{ $file_resale->premium_demand }}</div>
                    </div>
                </div>
                <div class="row g-1 mt-2">
                    <div class="col-4" style="width: fit-content;">
                        <h4>Resale Rate:*</h4>
                    </div>
                    <div class="col text-center">
                        <div style="border-bottom: 1px solid black">
                            {{ $file_resale->new_resale_rate }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-5 text-start">
                <div class="row">
                    <div class="col-4 text-nowrap">
                        <strong>Client Signature: </strong>
                    </div>
                    <div class="col text-end">
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>
                </div>
            </div>
            <div class="col"></div>
            <div class="col-6 text-end">
                <div class="row">
                    <div class="col-4" style="width:fit-content">
                        <strong>GM Signature </strong>
                    </div>
                    <div class="col-8 text-end">
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>
                    <div class="col-3 mt-2 text-start">
                        <strong>Director</strong>
                    </div>
                    <div class="col-9 mt-2 text-end">
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- <button type="button" id="print">Print</button> -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/app.min.css">
    <script src="{{ asset('app-assets') }}/js/printing/jQuery.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/printing/jQuery.print.min.js"></script>
    <script>
        $('.untouch').css("pointer-events", "none")
        $(document).ready(function() {

            $("#printable").printThis({
                debug: false, // show the iframe for debugging
                importCSS: true, // import parent page css
                importStyle: true, // import style tags
                printContainer: true, // print outer container/$.selector
                loadCSS: "", // path to additional css file - use an array [] for multiple
                pageTitle: "", // add title to print page
                removeInline: false, // remove inline styles from print elements
                removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
                printDelay: 1000, // variable print delay
                header: null, // prefix to html
                footer: null, // postfix to html
                base: false, // preserve the BASE tag or accept a string for the URL
                formValues: true, // preserve input/form values
                canvas: false, // copy canvas content
                doctypeString: '<!DOCTYPE html>', // enter a different doctype for older markup
                removeScripts: false, // remove script tags from print content
                copyTagClasses: true, // copy classes from the html & body tag
                copyTagStyles: true, // copy styles from html & body tag (for CSS Variables)
                beforePrintEvent: null, // function for printEvent in iframe
                beforePrint: null, // function called before iframe is filled
                afterPrint: null // function called before iframe is removed
            });

            $("#print").click(function() {});
        });
    </script>
</body>

</html>
