<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Sales Plan</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/pages/app-invoice-print.min.js"></script>
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
        });
    </script>
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
</head>

<body>
    <table style="width:100%;" class="template">
        <tr>
            <th style="width:33%; text-align:start;">
                <br>
                <img width="60%" height="50px" src="data:image/png;base64,{{ $image }}" alt="logo">

            </th>
            <th style="width:33%; ">
                <h1>Sales Plan</h1>
            </th>
            <th style="width:33%; text-align:end;">


            </th>
        </tr>
    </table>

    <div style="width: 90%;  margin:auto;" class="template">

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
                <th style=" white-space: nowrap;" colspan="2">Client Number</th>
                <td style="text-align: center; border-bottom: 1px solid black;" colspan="2">
                    @if ($data['client_number'])
                        {{ $data['client_number'] }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>

        <h3 style="text-align:start;">
            2 . Pricing
        </h3>

        <table style="width:100%;text-transform: uppercase;text-align: start;">
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


        <h3 style="text-align: start;">
            3 . Installment Details
        </h3>

        <table class="installmenttable" style=" width:100%; text-transform: uppercase; border-collapse: collapse;">
            <tr>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    NO
                </th>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Date
                </th>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Detail
                </th>
                <th style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Amount
                </th>
                <th style="  border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Remarks
                </th>
            </tr>
            @php
                $totalInstallmentAmount = 0;
            @endphp
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
                    <td style="border: 1px solid black;text-align: center; padding: 6px;">
                        @if ($instalment->remarks)
                            {{ $instalment->remarks }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @php
                    $totalInstallmentAmount = $totalInstallmentAmount + $instalment->amount;
                @endphp
            @endforeach
            <tr>
                <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                <th style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">
                    {{ number_format($totalInstallmentAmount, 2) }}</th>
                <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
            </tr>
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

    </div>

</body>

</html>
