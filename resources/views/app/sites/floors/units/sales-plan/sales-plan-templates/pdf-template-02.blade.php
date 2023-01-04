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
                    <img width="60%" height="50px" src="data:image/png;base64,{{ $image }}" alt="logo">
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
                <td style="border: 0px solid #eee!important;">
                    <p class="m-0">{{ $data['client_name'] }} Flat No {{ $data['unit_no'] }}</p>
                    <p class="m-0">Customer ID : 00000084</p>
                    <p class="m-0">Contact #: {{ $data['contact'] }}</p>
                    <p class="m-0">Plan Effected From:{{ date_format(new DateTime($data['validity']), 'D d-M-Y') }}
                    </p>
                </td>
                <td style="border: 1px solid #eee!important;">
                    <p class="m-0">Total Installments : {{ count($data['instalments']) }}</p>
                    <p class="mt-0"><span class="text-danger fw-bold">Remaining Installments :</span>
                        {{ count($data['instalments']) }}
                    </p>
                    <p class="m-0">Total Amount : {{ number_format($data['total']) }}</p>
                    <p class="m-0">Total Paid Amount: - </p>
                    <p class="m-0"><span class="text-danger fw-bold">Due Amount:</span>
                        {{ number_format($data['amount']) }}
                    </p>
                    <p class="m-0">Total Remaining Amount: {{ number_format($data['total']) }}</p>
                </td>
                <td style="border: 1px solid #eee!important;">
                    <p class="m-0">Invoice # : -</p>
                    <p>Invoice Date : {{ date_format(new DateTime($data['validity']), 'D d-M-Y') }}</p>
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
                    <td>-</td>
                    <td>-</td>
                    <td><span class="fw-bold text-success">UnPaid</span></td>
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


    <script src="{{ asset('app-assets') }}/js/printing/jQuery.min.js"></script>
    <script>

    </script>
</body>

</html>