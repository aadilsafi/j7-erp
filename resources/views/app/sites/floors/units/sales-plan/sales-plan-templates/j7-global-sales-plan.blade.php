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
            $('#date').append(''+today+'');
            $('#btn').hide();
                window.print();
            $("#btn").click(function () {
                $('#btn').hide();
                window.print();
                $('#btn').show();
            });
        });
    </script>
    <style>
        @media print {
        /* * { margin: 0 !important; padding: 0 !important; } */
            html, body {
                height:auto;
                overflow: hidden;
                background: #FFF;
                font-size: 8.5pt;
            }
            .template { width: auto; left:0; top:0; page-break-after: avoid;}
            .page-break {
                page-break-after: always;
            }
            .installmenttable{
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
    <div>
        <h1 style="text-align: center;  font-size: 50px; text-transform: uppercase;">J7 GLOBAL</h1>
        <h3 style="text-align: center; text-transform: uppercase;">Installment Plan</h3>
        <hr>
    </div>
    {{-- <table style="width:100%;" class="printTable">
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
    </table> --}}
    <div class="printTable" style="width: 100%; text-transform: uppercase;">

        <h3>Primary Data</h3>

        <table style="width:100%; text-transform: uppercase;">
            <tr >
                <th  style="text-align: start; white-space: nowrap;">Unit No </th>
                <td  style="text-align: center; border-bottom: 1px solid black;">
                      {{ $data['unit_no'] }}
                </td>
                <th  style="text-align: center;">Category </th>
                <td  style="white-space: nowrap; text-align: center;border-bottom: 1px solid black;">
                      {{ $data['category']}}
                </td>
                <th  style="">Floor </th>
                <td colspan="3" style="text-align: center;border-bottom: 1px solid black;">
                    &nbsp;&nbsp;{{ $data['floor_short_label'] }}&nbsp;
                </td>

            </tr>
            <tr>
                <th  style="text-align: start; white-space: nowrap;">Size (sq.ft) </th>
                <td  style="text-align: center;border-bottom: 1px solid black;">
                      {{ $data['size']}}
                </td>
                <th  style="text-align: center; white-space: nowrap;">Rate </th>
                <td  style="text-align: center;border-bottom: 1px solid black;">
                    {{ number_format($data['rate']) }}
                </td>
                <th  style="text-align: center; white-space: nowrap;">Corner </th>
                <td  style="text-align: center;border-bottom: 1px solid black;">
                    No
                </td>
                <th  style="text-align: center; white-space: nowrap;">Kashmir Highway </th>
                <td  style="text-align: center; border-bottom: 1px solid black;">
                    No
                </td>
            </tr>
            <tr>
                <th colspan="2" style="text-align: start; white-space: nowrap;">Price Of Unit</th>
                <td colspan="2" style="text-align: center;border-bottom: 1px solid black;">
                    {{ number_format($data['rate'] * $data['size'] ) }}
                </td>
                <th style="text-align: center; white-space: nowrap;">Date</th>
                <td id="date" colspan="3" style="text-align: center;border-bottom: 1px solid black;">

                </td>

            </tr>

        </table>

        <table style="width:100%; margin-top:50px; text-transform: uppercase;">
            <tr style="">
                <th colspan="2" style="text-align: end; white-space: nowrap;"></th>
                <td  colspan="2" style="text-align: center;">
                </td>
                <th colspan="2" style="text-align: center; white-space: nowrap;">Unit Price</th>
                <td  colspan="2" style="text-align: center;border-bottom: 1px solid black;">
                    {{ number_format($data['rate'] * $data['size'] ) }}
                </td>
            </tr>
            <tr>
                <th colspan="2" style="text-align: center; white-space: nowrap;">Down Payment %</th>
                <td  colspan="2" style="text-align: center;border-bottom: 1px solid black;">
                    {{ $data['down_payment_percentage'] }}%
                </td>
                <th colspan="2" style="text-align: center; white-space: nowrap;">Down Payment</th>
                <td colspan="2" style="text-align: center;border-bottom: 1px solid black;">
                    {{ $data['down_payment_total'] }}
                </td>
            </tr>
            {{-- <tr>
                <th colspan="2" style="text-align: center; white-space: nowrap;">No. of Installlments</th>
                <td  colspan="2" style="text-align: center;border-bottom: 1px solid black;">
                        0.0%
                </td>
                <th colspan="2" style="text-align: center; white-space: nowrap;">Installment</th>
                <td colspan="2" style="text-align: center;border-bottom: 1px solid black;">
                        10,000
                </td>
            </tr> --}}
        </table>

        <h3 style="text-transform: uppercase; text-align:center;">Installment Detail</h3>

        <table class="installmenttable"  style=" width:100%; text-transform: uppercase; border-collapse: collapse;" >
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
            @php
                $totalInstallmentAmount = 0;
            @endphp
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
                @php
                    $totalInstallmentAmount = $totalInstallmentAmount +  $instalment->amount;
                @endphp
            @endforeach
                <tr>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                    <th colspan="2" style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">Total</th>
                    <th style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">{{ number_format($totalInstallmentAmount) }}</th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"></th>
                </tr>
                <tr>
                    <th colspan="4" style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">Down PAyment {{ $data['down_payment_percentage'] }}%</th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"> {{ number_format($data['down_payment_total'])}}</th>
                </tr>
                {{-- <tr>
                    <th colspan="4" style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">Own</th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"> 800,000</th>
                </tr> --}}
                <tr>
                    <th colspan="4" style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">Total Amount</th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;">{{ number_format($data['rate'] * $data['size']) }}</th>
                </tr>
                <tr>
                    <th colspan="4" style="border: 1px solid black;text-align: end; padding: 8px; text-transform: uppercase;">Authorized Sign</th>
                    <th style="border: 1px solid black;text-align: center; padding: 8px; text-transform: uppercase;"> </th>
                </tr>
        </table>

    </div>

</body>
</html>

