<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Receipt</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    {{-- <script src="{{ asset('app-assets') }}/js/scripts/pages/app-invoice-print.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('.date').append(today);
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
        #borders span {
            display: inline-block;
            border: 1px solid black;
            padding: 2px;
            border-collapse: collapse;
            /* margin:2px; */
            /*if you want space between numbers*/
        }

        #cnic {
            border: 1px solid black;
            border-collapse: collapse;
        }

        #cnic td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        @media print {

            /* * { margin: 0 !important; padding: 0 !important; } */
            html,
            body {
                height: auto;
                overflow: hidden;
                background: #FFF;
                font-size: 9pt;
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
    {{-- @dd($preview_data); --}}
    <table style="width:100%; margin-bottom:5px;" class="template">
        <tr>
            <th style="width:33%; text-align:start;">
                <br>
                <img width="75%" height="50px" src="{{ asset('app-assets') }}/images/receipts/logo_j7Global.png"
                    alt="logo">
            </th>
            <th style="width:48%; ">
            </th>
            <th style="width:20%; text-align:start;">
                Plot No MC-E1,
                Blue Zone, Quaid Rd W, Mumtaz City Islamabad, Rawalpindi, Punjab 44000
                UAN: 033-111-11-033
            </th>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:5px;" class="template">

        <tr>
            <th style="">
                <h1>J7 GLOBAL</h1>
                <h2 style="font-weight: normal;"> &nbsp; RECEIPT &nbsp;</h2>
            </th>
        </tr>
    </table>

    <div style="width: 100%;  margin:auto;" class="template">

        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:11%;  ">Serial No.</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">

                </td>
                <th style="text-align: center; width:8%; ">CC No.</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">

                </td>
                <th style="text-align: center; width:8%;">Date</th>
                <td class="date" style="text-align: center;  width:20%; border-bottom: 1px solid black; ">
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:9%;  ">Unit No.</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['unit_name'] }}
                </td>
                <th style="text-align: center; width:10%; ">Unit Type</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['unit_type'] }}
                </td>
                <th style="text-align: center; width:7%;">Floor</th>
                <td style="text-align: center;  width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['unit_floor'] }}
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:17%; ">Received With Thanks From MR./ MRS./ MISS</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['name'] }}
                </td>
            </tr>

        </table>


        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:4%;  ">CNIC</th>
                <td>
                    <table id="cnic" style="width: 90%;">
                        <tr style="text-align: center;">
                            <td style="width: 6%;">{{ $preview_data['cnic'][0] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][1] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][2] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][3] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][4] }}</td>
                            <td style="width: 6%;">-</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][5] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][6] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][7] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][8] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][9] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][10] }}</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][11] }}</td>
                            <td style="width: 6%;">-</td>
                            <td style="width: 6%;">{{ $preview_data['cnic'][12] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:2.5%; ">MODE OF PAYMENT</th>
                <td style="text-align: start; width:10%; ">

                    @if ($preview_data['mode_of_payment'] == 'Cash')
                        <input disabled type="checkbox" checked="checked">
                    @else
                        <input disabled type="checkbox">
                    @endif

                    <span class="checkmark" style="margin-left:15px;">Cash</span>

                    @if ($preview_data['mode_of_payment'] == 'Cheque')
                        <input disabled type="checkbox" style="margin-left:15px;" checked="checked">
                    @else
                        <input disabled type="checkbox" style="margin-left:15px;">
                    @endif
                    <span class="checkmark" style="margin-left:15px;">Cheque</span>

                    @if ($preview_data['mode_of_payment'] == 'Online')
                        <input disabled type="checkbox" style="margin-left:15px;" checked="checked">
                    @else
                        <input disabled type="checkbox" style="margin-left:15px;">
                    @endif

                    <span disabled class="checkmark" style="margin-left:15px;">Online</span>

                    @if ($preview_data['mode_of_payment'] == 'Other')
                        <input disabled type="checkbox" style="margin-left:15px;" checked="checked">
                    @else
                        <input disabled type="checkbox" style="margin-left:15px;">
                    @endif
                    <span disabled class="checkmark" style="margin-left:15px;">Other</span>
                    @if ($preview_data['other_value'])
                        <span style=" border-bottom: 1px solid black; ">&nbsp;&nbsp;&nbsp; {{ $preview_data['other_value'] }}&nbsp;&nbsp;&nbsp;</span>
                    @else
                        <span style="">________________________</span>
                    @endif

                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:8%;  ">DD / Pay Order / Cheque No.</th>
                <td style="text-align: start; width:20%;  ">
                    <input disabled style="width: 97%; height:20px; border:1px solid;"
                        value="{{ $preview_data['cheque_no'] }}" type="text">
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:4.5%;  ">
                    Online Instrument No.

                </th>
                <td style="text-align: start; width:20%;  ">
                    <input disabled style="width: 97%; height:20px; border:1px solid;"
                        value="{{ $preview_data['online_instrument_no'] }}" type="text">
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:3%;  ">Drawn on bank</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['drawn_on_bank'] }}
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:2%;  ">Transaction Date</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['transaction_date'] }}
                </td>
            </tr>

        </table>


        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:3.5%;  ">Amount in words</th>
                <td style="text-align: center; width:20%; border-bottom: 1px solid black; ">
                    {{ $preview_data['amount_in_words'] }} Only.
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:4.5%;  ">
                    Amount in figures
                </th>
                <td style="text-align: start; width:20%;  ">
                    <input disabled style="width: 20%; height:20px; border:1px solid; text-align:center;"
                        value="RS {{ number_format($preview_data['amount_in_numbers']) }}" type="text">
                </td>
            </tr>

        </table>

        <br><br>
        <table id="firstTable" style="width:100%; text-transform: uppercase; ">

            <tr style="border: 2px solid;">
                <th style="text-align:start; width:0.5%; ">Purpose</th>
                <td style="text-align: start; width:10%; ">

                    <input disabled type="checkbox" checked="checked">
                    <span class="checkmark" style="margin-left:15px;">Downpayment</span>

                    <input disabled type="checkbox" style="margin-left:15px;">
                    <span class="checkmark" style="margin-left:15px;">Installment</span>
                    <span
                        style="border-bottom: 1px solid black; margin-left:15px;">{{ $preview_data['installment_number'] }}</span>

                    {{-- <input type="checkbox" style="margin-left:15px;">
                    <span class="checkmark" style="margin-left:15px;">Online</span> --}}

                    <input disabled type="checkbox" style="margin-left:15px;">
                    <span class="checkmark" style="margin-left:15px;">Other</span>
                    <span style="">____</span>
                </td>
            </tr>

        </table>

        <br><br><br><br><br>

        <table style="width:100%;text-transform: uppercase;">
            <tr>
                <th style="text-align: end; ">
                    ______________________________________
                </th>
            </tr>
            <tr>
                <th style="text-align: end;">
                    Authorized Signature and stamp
                </th>
            </tr>
        </table>

    </div>

</body>

</html>
