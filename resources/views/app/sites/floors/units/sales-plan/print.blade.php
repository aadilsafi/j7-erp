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
        $(document).ready(function(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('#date').empty();
            $('#date').append('Date : '+today+'');
            $("#btn").click(function () {
                $('#btn').hide();
                window.print();
                $('#btn').show();
            });
    });
    </script>
    <style>
    /* @page {
        size: auto;
        margin: 14.5mm 0 10mm 0;
    } */
    </style>
</head>
<body>
    <a href="#" id="btn">Print</a>
    <table style="width:100%;" class="printTable">
        <tr>
            <th style="width:33%; text-align:start;" >
                <br>
                <img width="60%" height="50px" src="{{ asset('app-assets') }}/images/logo/signature-logo.png" alt="logo">
            </th>
            <th style="width:33%; ">
                <h1 >Sales Plan</h1>
            </th>
            <th id="date" style="width:33%; text-align:end;">
                Date : ____________
            </th>
        </tr>
    </table>
    <div class="printTable" style="width: 100%;">
        <table style="width:35%">
            <tr>
                <th>
                    <h3>
                        1 . Primary Data
                    </h3>
                </th>
            </tr>
        </table>

        <table style="margin-left: 130px; width:70%" >
            <tr >
                <th  style="text-align: start; white-space: nowrap;">Unit No </th>
                <td  style="text-align: center; border-bottom: 1px solid black;">
                      {{ $data['unit_no'] }}
                </td>
                <th  style="">&nbsp;&nbsp;&nbsp;Floor </th>
                <td  style="text-align: center;border-bottom: 1px solid black;">
                    &nbsp;&nbsp;{{ $data['floor_short_label'] }}&nbsp;
                </td>
                <th  style="text-align: start;">&nbsp;&nbsp;&nbsp;Category </th>
                <td  style="white-space: nowrap; text-align: center;border-bottom: 1px solid black;">
                      {{ $data['category']}}
                </td>
                <th  style="text-align: start; white-space: nowrap;">&nbsp;&nbsp;&nbsp;Size (sq.ft) </th>
                <td  style="text-align: center;border-bottom: 1px solid black;">
                      {{ $data['size']}}
                </td>
            </tr>
            <tr>
                <th  style="text-align: start; white-space: nowrap;" colspan="2">Client Name </th>
                <td  style="text-align: center; border-bottom: 1px solid black;" colspan="2">
                    {{ $data['client_name'] }}
                </td>
                <th  style=" white-space: nowrap;" colspan="2">Unit Orientation </th>
                <td  style="text-align: start; border-bottom: 1px solid black;" colspan="2"> </td>
            </tr>
        </table>

        <table style="margin-left: -28px; width:35%">
            <tr>
                <th>
                    <h3>
                        2 . Pricing
                    </h3>
                </th>
            </tr>
        </table>

        <table style="margin-left: 130px; width:70%" >
            <tr>
                <th  style="text-align: start;">Rate </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($data['rate']) }}&nbsp;&nbsp;
                </td>
                <th  style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($data['rate'] * $data['size'] ) }}
                    &nbsp;&nbsp;
                </td>
            </tr>
            @if ($data['additional_costs'])
                @foreach ( $data['additional_costs'] as $additionalCost )
                    <tr>
                        <th  style="text-align: start; white-space: nowrap;">
                            @if ($additionalCost->additionalCost->name)
                            {{ $additionalCost->additionalCost->name }}
                            @endif

                        </th>
                        <td  style="text-align: end; border-bottom: 1px solid black;">
                            @if ($additionalCost->additionalCost->site_percentage)
                            {{ $additionalCost->additionalCost->site_percentage }} %
                            @else
                            -
                            @endif
                            &nbsp;&nbsp;
                        </td>
                        <th  style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                        @php
                            $charges = 0;
                        @endphp
                        <td  style="text-align: end; border-bottom: 1px solid black;">
                            &nbsp;&nbsp;
                            @if ($additionalCost->additionalCost->site_percentage)
                            {{ number_format(($additionalCost->additionalCost->site_percentage  / 100) * ( $data['rate'] * $data['size']))}}
                                @php
                                    $charges =($additionalCost->additionalCost->site_percentage  / 100) * ( $data['rate'] * $data['size']);
                                @endphp
                            @else
                            -
                            @endif
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <th  style="text-align: start; ">Discounts </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    0.0 % &nbsp;&nbsp;
                </td>
                <th  style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp; - &nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <th  style="text-align: start; " colspan="2"> </th>

                <th  style="text-align: start;">&nbsp;&nbsp;&nbsp;Total </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;

                    - &nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <th  style="text-align: start; ">Down Payment % </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    0.0 % &nbsp;&nbsp;
                </td>
                <th  style="text-align: start;">&nbsp;&nbsp;&nbsp;Amount </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp; - &nbsp;&nbsp;
                </td>
            </tr>

        </table>

        <table style=" width:35%">
            <tr>
                <th>
                    <h3>
                        3 . Sales Resource
                    </h3>
                </th>
            </tr>
        </table>

        <table style="margin-left: 130px; width:70%" >
            <tr>
                <th  style="text-align: start; white-space: nowrap;">Sales Person </th>
                <td  style="text-align: end; border-bottom: 1px solid black;  white-space: nowrap;">
                    {{ $data['sales_person_name'] }}
                    &nbsp;&nbsp;
                </td>
                <th  style="text-align: start;"> &nbsp;&nbsp;&nbsp;Status </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ $data['sales_person_status'] }}&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <th  style="text-align: start; white-space: nowrap;">Contact NO </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    {{ $data['sales_person_phone_no'] }}&nbsp;&nbsp;
                </td>
                <th  style="text-align: start; white-space: nowrap;">&nbsp;&nbsp;&nbsp;Sales Type </th>
                <td  style="text-align: end; border-bottom: 1px solid black;">
                    {{ $data['sales_person_sales_type'] }}&nbsp;&nbsp;
                </td>
            </tr>
            <tr>
                <th  style="text-align: start; white-space: nowrap;">Indirect Source </th>
                <td  style="text-align: end; border-bottom: 1px solid black;"> {{ $data['indirect_source'] }} </td>
            </tr>
        </table>

        <table style="margin-left:10px; width:35%">
            <tr>
                <th>
                    <h3>
                        4 . Installment Details
                    </h3>
                </th>
            </tr>
        </table>

        <table  style="margin-left: 130px; width:70%; " >
            <tr>
                <th  style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    NO
                </th>
                <th  style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Date
                </th>
                <th  style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Detail
                </th>
                <th  style=" border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Amount
                </th>
                <th  style="  border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">
                    Remarks
                </th>
            </tr>
            @foreach ($data['instalments'] as $key => $instalment )
                <tr>
                    <th   style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 8px;">
                        {{ $key + 1 }}
                    </th>
                    <td   style="white-space: nowrap;  border: 1px solid black;text-align: center; padding: 8px;">
                        {{  date_format (new DateTime($instalment->date), 'd/m/Y') }}
                    </td>
                    <td   style=" white-space: nowrap; border: 1px solid black;text-align: center; padding: 8px;">
                        @if($instalment->details)
                            {{ $instalment->details }}
                        @else
                            -
                        @endif
                    </td>
                    <td   style="white-space: nowrap;  border: 1px solid black;text-align: end; padding: 8px;">
                        {{ number_format($instalment->amount) }}
                    </td>
                    <td   style="white-space: nowrap; border: 1px solid black;text-align: center; padding: 8px;">
                        @if ($instalment->remarks )
                            {{ $instalment->remarks }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
                <tr>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                    <th style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">{{ number_format($data['rate'] * $data['size']) }}</th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                </tr>
        </table>

        <table style="margin-left:10px; width:35%">
            <tr>
                <th>
                    <h3>
                        5 . Availability Note
                    </h3>
                </th>
            </tr>
        </table>

        <table style="margin-left: 130px; width:70%" >
            <tr>
                <th  style="text-align: start;">Availability </th>
                <th  style="text-align: start;"> _______________</th>
                <th  style="text-align: start; white-space: nowrap;">Verified By GM Sales </th>
                <th  style="text-align: start;"> _______________</th>
            </tr>
            <tr>
                <th  style="text-align: start; white-space: nowrap;">Client Sign </th>
                <th  style="text-align: start;"> _______________</th>
                <th  style="text-align: start; white-space: nowrap;">Authorized Sign </th>
                <th  style="text-align: start;"> _______________</th>
            </tr>
        </table>

    </div>

</body>
</html>

