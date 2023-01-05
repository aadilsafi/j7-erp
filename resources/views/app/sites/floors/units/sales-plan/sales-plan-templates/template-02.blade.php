<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('app-assets') }}/js/printing/app.css">
    <title>J7 Global Payment Plan</title>
</head>

<body>

    <div id="printable" class="template">
        <table class="table table-bordered" style="border-color: #fff;">
            <tr>
                <td class="text-start">
                    <img src="{{ asset('app-assets') }}/images/logo/j7global-logo.png" width="200" alt="asdasdasd">
                </td>
                <td class="text-center">
                    <h1>PAYMENT PLAN</h1>
                </td>
                <td class="text-end">
                    <p>Print Date:{{ date_format(new DateTime(), ' d-M-Y , h:i:s a') }}</p>
                    <p>User: {{ Auth::user()->name }}</p>
                </td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <td style="border: 0px solid #eee!important; text-align:center;">
                    <p class="m-0"><strong>{{ $data['client_name'] }}</strong> - Flat No :
                        <strong>{{ $data['unit_no'] }}</strong>
                    </p>
                    <p class="m-0">Contact #: {{ $data['contact'] }}</p>
                    <p class="m-0">Plan Effected From:
                        <strong>{{ date_format(new DateTime($data['validity']), 'D d-M-Y') }}</strong>
                    </p>
                </td>
                <td style="border: 1px solid #eee!important; text-align:center;">
                    <p class="m-0">Total Installments : {{ count($data['instalments']) }}</p>
                    <p class="mt-0"><span class="text-danger fw-bold">Remaining Installments :</span>
                        {{ $data['remaining_installments'] }}
                    </p>
                    <p class="m-0">Total Amount : {{ number_format($data['total'], 2) }}</p>
                    <p class="m-0">Total Paid Amount: - {{ number_format($data['paid_amount'], 2) }}</p>

                    <p class="m-0">Total Remaining Amount: {{ number_format($data['remaing_amount'], 2) }}</p>
                </td>
                <td style="border: 1px solid #eee!important; text-align:center;">
                    <p class="m-0">Invoice # : -</p>
                    <p>Invoice Date : -</p>
                    <p class="m-0">Account # :-</p>
                    <p class="m-0">Sale Voucher # :-</p>
                </td>
            </tr>
        </table>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>INSTALLMENT</th>
                    <th>DUE DATE</th>
                    <th>TOTAL AMOUNT</th>
                    <th>PAID AMOUNT</th>
                    <th>REMAINING AMOUNT</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['instalments'] as $key => $installment)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="text-start">{{ $installment->details }}</td>
                        <td> {{ date_format(new DateTime($installment->date), 'd/m/Y') }}</td>
                        <td>{{ number_format($installment->amount) }}</td>
                        <td> {{ number_format($installment->paid_amount, 2) }}</td>
                        <td> {{ number_format($installment->remaining_amount, 2) }}</td>
                        <td>{{ Str::of($installment->status)->replace('_', ' ')->title() }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <p class="fw-bold">I hereby acknowledge that I have read and understand the foregoing information and that my
            signature below signifies my agreement to comply with the above Payment Schedule.</p>

        <table class="table installmenttable" style="border-color: #fff; margin-top: 100px;">
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
        </table>
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
