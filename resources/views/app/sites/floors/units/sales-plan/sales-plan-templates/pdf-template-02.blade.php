<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('app-assets') }}/js/printing/app.css">
    <title>J7 Global Payment Plan</title>
</head>
<style>
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
</style>

<body>

    <div id="printable" class="template">
        <table style="width:100%; border-color: #fff;" class="template table table-bordered">
            <tr>
                <th style="width:33%; text-align:start;">
                    <br>
                    <img width="50%" height="50px" src="data:image/png;base64,{{ $image }}" alt="logo">

                </th>
                <th style="width:33%; text-align:center;">
                    <h1>PAYMENT PLAN</h1>
                </th>
                <th style="width:33%; text-align:end;">
                </th>
            </tr>
        </table>

        <table class="table" width="100%">
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

        <table class="table table-bordered text-center installmenttable" width="100%">
            <thead>
                <tr>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        Sr. No.</th>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        INSTALLMENT</th>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        DUE DATE</th>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        TOTAL AMOUNT</th>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        PAID AMOUNT</th>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        REMAINING AMOUNT</th>
                    <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                        STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['instalments'] as $key => $installment)
                    <tr>
                        <td
                            style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                            {{ $loop->index + 1 }}</td>
                        <td style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"
                            class="text-start">{{ $installment->details }}</td>
                        <td
                            style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                            {{ date_format(new DateTime($installment->date), 'd/m/Y') }}</td>
                        <td
                            style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                            {{ number_format($installment->amount, 2) }}</td>
                        <td
                            style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                            {{ number_format($installment->paid_amount, 2) }}
                        </td>
                        <td
                            style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                            {{ number_format($installment->remaining_amount, 2) }}
                        </td>
                        @if ($installment->status == 'paid')
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span
                                    style="color: green; font-weight: bold;">{{ Str::of($installment->status)->replace('_', ' ')->title() }}</span>
                            </td>
                        @elseif($installment->status == 'partially_paid')
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span
                                    style="color: rgb(255, 123, 0); font-weight: bold;">{{ Str::of($installment->status)->replace('_', ' ')->title() }}
                                    {{ \Carbon\Carbon::parse($installment->date)->isPast() ? ', Due' : '' }}</span>
                            </td>
                        @elseif($installment->status == 'unpaid' && \Carbon\Carbon::parse($installment->date)->isPast())
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span style="color: red; font-weight: bold;">Due</span>
                            </td>
                        @else
                            <td style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 6px;">
                                <span>{{ Str::of($installment->status)->replace('_', ' ')->title() }}</span>
                            </td>
                        @endif
                    </tr>
                @endforeach

            </tbody>
        </table>
        <p class="fw-bold">I hereby acknowledge that I have read and understand the foregoing information and that my
            signature below signifies my agreement to comply with the above Payment Schedule.</p>

        <table class="table" style="border-color: #fff; margin-top: 100px;" width="100%">
            <tr>
                <td style="width:50%; text-align:start;">
                    <hr width="50%">

                    <p class="m-0">{{ $data['client_name'] }} Flat No {{ $data['unit_no'] }}</p>
                    <p class="m-0">Updated Date: {{ date_format(new DateTime(), ' d-M-Y , h:i:s a') }}</p>
                    <p class="m-0">Customer</p>
                </td>
                <td style="width:50%; text-align:end;">
                    <hr width="50%">
                    <p class="m-0">Authorized Officer's signature / Stamp</p>
                </td>
            </tr>
        </table>
    </div>


    <script src="{{ asset('app-assets') }}/js/printing/jQuery.min.js"></script>
    <script></script>
</body>

</html>
