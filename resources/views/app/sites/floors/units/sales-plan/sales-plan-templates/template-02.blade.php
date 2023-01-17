<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('app-assets') }}/js/printing/app.css">
    <title>J7 Global Payment Plan</title>
    <style>
        @media print {

            /* * { margin: 0 !important; padding: 0 !important; } */
            html,
            body {
                height: auto;
                overflow: hidden;
                background: #FFF;
                font-size: 8.5pt;
            }

            .template {
                width: auto;
                left: 0;
                top: 0;
                page-break-after: avoid;
            }

            .page-break {
                page-break-inside: always;
            }

            .installmenttable {
                page-break-inside: auto;
            }

            .installmenttable tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>

    <div id="printable" class="template">
        <table style="width:100%; margin-top: 0.5rem; margin-bottom: 0.5rem" class="template">
            <tr class="d-flex" style="align-items:start; display: flex;">
                <th style="width:33%; text-align:start;">
                    <img width="60%" height="" src="{{ asset('app-assets') }}/images/logo/j7global-logo.png"
                        alt="logo">
                </th>
                <th style="width:33%; ">
                    <h1 style="margin-top: 0; text-align: center;">PAYMENT PLAN</h1>

                </th>
                <th style="width:33%; text-align:end;">
                    <img width="100px" height="" src="{{ $data['qrCodeimg'] }}" alt="qr code">
                    <br>
                    <h2 style="margin-inline-end: 20px">{{ $data['pp_serial_no'] ?? $data['serial_no'] }}</h2>
                </th>
            </tr>
        </table>
        <br>
        <br>

        <table class="table">
            <tr style="font-size: 14px;">
                <td style="border: 0px solid #eee!important; text-align:start; width: 26%;">
                    <p class="m-0"><strong>{{ $data['client_name'] }}</strong> - Flat No :
                        <strong>{{ $data['unit_no'] }}</strong>
                    </p>
                    <p class="m-0">Contact #: {{ $data['contact'] }}</p>
                    <p class="m-0">Plan Effected From:
                        <strong>{{ date_format(new DateTime($data['validity']), 'D d-M-Y') }}</strong>
                    </p>
                </td>
                <td style="text-align:center; border: 0px solid #eee!important;">
                    <p class="m-0">Total Installments : {{ count($data['instalments']) }}</p>
                    <p class="mt-0"><span class="text-danger fw-bold">Remaining Installments :</span>
                        {{ $data['remaining_installments'] }}
                    </p>
                    <p class="m-0">Total Amount : {{ number_format($data['total'], 2) }}</p>
                    <p class="m-0">Total Paid Amount: - {{ number_format($data['paid_amount'], 2) }}</p>

                    <p class="m-0">Total Remaining Amount: {{ number_format($data['remaing_amount'], 2) }}</p>
                </td>
                <td style="text-align:center; border: 0px solid #eee!important;">
                    <p class="m-0">Invoice # : -</p>
                    <p>Invoice Date : -</p>
                    <p class="m-0">Account # :-</p>
                    <p class="m-0">Sale Voucher # :-</p>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <table class="installmenttable" style=" width:100%; text-transform: uppercase; border-collapse: collapse;">
            <tr>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    NO
                </th>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Due Date
                </th>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Detail
                </th>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Total Amount
                </th>
                <th style="  border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    PAID AMOUNT
                </th>
                <th style="  border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    REMAINING AMOUNT
                </th>
                <th style="  border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    STATUS
                </th>
            </tr>
            <tbody>
                @foreach ($data['instalments'] as $key => $instalment)
                    <tr>
                        <th style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                            {{ $loop->index + 1 }}
                        </th>
                        <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                            {{ date_format(new DateTime($instalment->date), 'd/m/Y') }}
                        </td>
                        <td style=" white-space: nowrap; border: 1px solid black;text-align: center; padding: 6px;">
                            @if ($instalment->details)
                                {{ $instalment->details }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="white-space: nowrap;  border: 1px solid black;text-align: end; padding: 6px;">
                            {{ number_format($instalment->amount, 2) }}
                        </td>
                        <td style="white-space: nowrap;  border: 1px solid black;text-align: end; padding: 6px;">
                            {{ number_format($instalment->paid_amount, 2) }}
                        </td>
                        <td style="white-space: nowrap;  border: 1px solid black;text-align: end; padding: 6px;">
                            {{ number_format($instalment->remaining_amount, 2) }}
                        </td>
                        @if ($instalment->status == 'paid')
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span
                                    style="color: green; font-weight: bold;">{{ Str::of($instalment->status)->replace('_', ' ')->title() }}</span>
                            </td>
                        @elseif($instalment->status == 'partially_paid')
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span
                                    style="color: rgb(255, 123, 0); font-weight: bold;">{{ Str::of($instalment->status)->replace('_', ' ')->title() }}
                                    {{ \Carbon\Carbon::parse($instalment->date)->isPast() ? ', Due' : '' }}</span>
                            </td>
                        @elseif($instalment->status == 'unpaid' && \Carbon\Carbon::parse($instalment->date)->isPast())
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span style="color: red; font-weight: bold;">Due</span>
                            </td>
                        @else
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span>{{ Str::of($instalment->status)->replace('_', ' ')->title() }}</span>
                            </td>
                        @endif
                    </tr>
                @endforeach

            </tbody>
        </table>
        <br><br>
        <p class="fw-bold">I hereby acknowledge that I have read and understand the foregoing information and that my
            signature below signifies my agreement to comply with the above Payment Schedule.</p>
        <br>

        <h3 style="text-align: start;">
            Contact Person
        </h3>

        <table style="width:50%;text-transform: uppercase;">
            <tr>
                <th style="text-align: start;"><strong>Name </strong></th>
                <td style="text-align: center;"> <u>{{ $data['sales_person_name'] }}</u></td>
            </tr>
            <tr style="margin-top: 20px;">
                <th style="text-align: start;">Number </th>
                <td style="text-align: center;"><u> {{ $data['sales_person_phone_no'] }}</u></td>
            </tr>

        </table>
        <br>
        <table class="mt-2" width="100%">
            <td width="50%">
                <h3><strong>Creation Date : </strong>
                    {{ \Carbon\Carbon::parse($data['created_date'])->format('d-M-Y , h:i:s a') }}</h3>
                <h3><strong>Print Date : </strong> {{ date_format(new DateTime(), ' d-M-Y , h:i:s a') }}</h3>
            </td>
            <td style="text-align: end" width="50%">
                <h3><strong>Approve By: </strong> <u>{{ $data['approveBy'] }}</u></h3>
                <h3 style="text-align: center"><strong>Sign : </strong> </h3>
            </td>
        </table>
        {{-- <table class="table installmenttable" style="border-color: #fff; margin-top: 50px;">
            <tr>
                <td class="text-center">
                    <hr width="50%">

                    <p class="m-0">{{ $data['client_name'] }} Flat No {{ $data['unit_no'] }}</p>
                    <p class="m-0">Print Date: {{ date_format(new DateTime(), ' d-M-Y , h:i:s a') }}</p>
                    <p class="m-0">Customer</p>
                </td>
                <td class="text-center">
                    <hr width="50%">
                    <p class="m-0">Authorized Officer's signature / Stamp</p>
                </td>
            </tr>
        </table> --}}
    </div>

    <!-- <button type="button" id="print">Print</button> -->

    <script src="{{ asset('app-assets') }}/js/printing/jQuery.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/printing/jQuery.print.min.js"></script>
    <script>
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
                // printDelay: 1000, // variable print delay
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
