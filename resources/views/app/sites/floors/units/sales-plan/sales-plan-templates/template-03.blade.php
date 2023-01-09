<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Sales Plan</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- <script src="{{ asset('app-assets') }}/js/scripts/pages/app-invoice-print.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('#date').empty();
            $('#date').append('Date : ' + today + '');
            $('#btn').hide();
            window.print();

            $("#btn").click(function() {
                $('#btn').hide();
                window.print();
                $('#btn').show();
            });
        });
    </script>
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
    <a href="#" id="btn">Print</a>

    <table style="width:100%;" class="template">
        <tr class="d-flex" style="align-items:start; display: flex;">
            <th style="width:33%; text-align:start;">
                <img width="60%" height="" src="{{ asset('app-assets') }}/images/logo/j7global-logo.png"
                    alt="logo">
            </th>
            <th style="width:33%; ">
                <h1 style="margin-top: 0;">Payment Plan</h1>

            </th>
            <th style="width:33%; text-align:end;">
                {{-- <div style=" background-image: url('{{asset('app-assets')}}/images/qr_code_border.png');background-repeat: no-repeat; position: relitve;"> --}}
                <img width="70px" height="" src="{{ $data['qrCodeimg'] }}" alt="qr code">
                {{-- </div> --}}
                <br>
                <h2 style="margin-inline-end: 10px">{{ $data['pp_serial_no'] ?? $data['serial_no'] }}</h2>
            </th>
        </tr>
    </table>

    <div style="width: 95%;  margin:auto;" class="template">

        <h3 style="text-align:start;text-transform: uppercase;">
            1 . PRIMARY DATA
        </h3>

        <table style=" width:100%;text-transform: uppercase;">
            <tr>
                <th style="text-align: start; white-space: nowrap;">Unit No </th>
                <td style="text-align: center; border-bottom: 1px solid black;">
                    @if ($data['unit_no'])
                        {{ $data['unit_no'] }}
                    @else
                        -
                    @endif
                </td>
                <th style="">&nbsp;&nbsp;&nbsp;Floor </th>
                <td style="text-align: center;border-bottom: 1px solid black;">
                    @if ($data['floor_short_label'])
                        &nbsp;&nbsp;{{ $data['floor_short_label'] }}&nbsp;
                    @else
                        -
                    @endif
                </td>
                <th style="text-align: start;">&nbsp;&nbsp;&nbsp;Category </th>
                <td style="white-space: nowrap; text-align: center;border-bottom: 1px solid black;">
                    @if ($data['category'])
                        {{ $data['category'] }}
                    @else
                        -
                    @endif
                </td>
                <th style="text-align: start; white-space: nowrap;">&nbsp;&nbsp;&nbsp;Size (sq.ft) </th>
                <td style="text-align: center;border-bottom: 1px solid black;">
                    @if ($data['size'])
                        {{ $data['size'] }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th style="text-align: start; white-space: nowrap;" colspan="2">Client Name </th>
                <td style="text-align: center; border-bottom: 1px solid black;" colspan="2">
                    @if ($data['client_name'])
                        {{ $data['client_name'] }}
                    @else
                        -
                    @endif
                </td>
                <th style=" white-space: nowrap;" colspan="2">Unit Orientation </th>
                <td style="text-align: start; border-bottom: 1px solid black;" colspan="2">

                </td>
            </tr>
        </table>

        <h3 style="text-align:start;">
            2 . Pricing
        </h3>

        <table style="width:100%;text-transform: uppercase;text-align: start; float:left;">
            <tr style="text-align: start;">
                <th style="text-align: start;">Rate </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    @if ($data['rate'])
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ number_format($data['rate'], 2) }}
                        &nbsp;&nbsp;
                    @else
                        -
                    @endif

                </td>
                <th style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($data['rate'] * $data['size'], 2) }}
                    &nbsp;&nbsp;
                </td>
            </tr>
            @if ($data['additional_costs'])
                @php
                    $totalAdditionalCost = 0.0;
                    $totalDiscount = 0.0;
                @endphp
                @foreach ($data['additional_costs'] as $additionalCost)
                    @isset($additionalCost->unit_percentage)
                        @php
                            $totalAdditionalCost += ($additionalCost->unit_percentage / 100) * ($data['rate'] * $data['size']);
                            
                        @endphp
                    @endisset

                    <tr style="text-align: start;">
                        <th style="text-align: start; white-space: nowrap;">
                            @isset($additionalCost->name)
                                {{ $additionalCost->name }}
                            @endisset

                        </th>
                        <td style="text-align: end; border-bottom: 1px solid black;">
                            @if (isset($additionalCost->unit_percentage))
                                {{ $additionalCost->unit_percentage }} %
                            @else
                                -
                            @endif
                            &nbsp;&nbsp;
                        </td>
                        <th style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                        <td style="text-align: end; border-bottom: 1px solid black;">
                            &nbsp;&nbsp;
                            @if (isset($additionalCost->unit_percentage))
                                {{ number_format(($additionalCost->unit_percentage / 100) * ($data['rate'] * $data['size']), 2) }}
                            @else
                                -
                            @endif
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <th style="text-align: start; ">Discounts </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    @if ($data['discount_percentage'])
                        {{ $data['discount_percentage'] }} %
                    @else
                        -
                    @endif
                    &nbsp;&nbsp;
                </td>
                <th style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;
                    @if ($data['discount_total'])
                        {{ number_format($data['discount_total'], 2) }}
                    @else
                        -
                    @endif
                    &nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <th style="text-align: start; " colspan="2"> </th>

                <th style="text-align: start;">&nbsp;&nbsp;&nbsp;Total </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;
                    {{ number_format($data['amount'], 2) }}
                    &nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <th style="text-align: start; ">Down Payment % </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    @if ($data['down_payment_percentage'])
                        {{ $data['down_payment_percentage'] }} %
                    @else
                        0.0 %
                    @endif
                    &nbsp;&nbsp;
                </td>
                <th style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                <td style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;
                    @if ($data['down_payment_total'])
                        {{ number_format($data['down_payment_total'], 2) }}
                    @else
                        -
                    @endif
                    &nbsp;&nbsp;
                </td>
            </tr>

        </table>
        <br><br><br>
        <h3 style="text-align: start; margin-top: 50px;" class="mt-1">
            4 . Installment Details
        </h3>

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


        <h3 style="text-align: start;">
            4 . Contact Person
        </h3>

        <table style="width:80%;text-transform: uppercase;">
            <tr>
                <th style="text-align: start;"><strong>Name </strong></th>
                <td> <u>{{ $data['sales_person_name'] }}</u></td>
                <th style="text-align: start; white-space: nowrap;">Number </th>
                <td><u> {{ $data['sales_person_phone_no'] }}</u></td>
            </tr>

        </table>
        <br>
        <table class="mt-2" width="100%">
            <td width="50%">
                <h3><strong>Creation Date : </strong> {{ \Carbon\Carbon::parse($data['created_date'])->format('d-M-Y , h:i:s a') }}</h3>
                <h3><strong>Print Date : </strong> {{ date_format(new DateTime(), ' d-M-Y , h:i:s a') }}</h3>
            </td>
            <td style="text-align: end" width="50%">
                <h3><strong>Approve By: </strong> <u>{{ $data['approveBy'] }}</u></h3>
                <h3 style="text-align: center"><strong>Sign : </strong> </h3>
            </td>
        </table>
    </div>

</body>

</html>
