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
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
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
</head>
<body>
    <a href="#" id="btn">Print</a>
    <table style="width:100%;" id="logoTable">
        <tr>
            <th style="width:33%; text-align:start;" >
                <br>
                <img width="60%" height="50px" src="app-assets/images/logo/signature-logo.png" alt="logo">
            </th>
            <th style="width:33%; ">
                <h1 >Sales Plan</h1>
            </th>
            <th id="date" style="width:33%; text-align:end;">
                Date : ____________
            </th>
        </tr>
    </table>

    <div style="width: 100%;">
        <table style="width:35%">
            <tr>
                <th>
                    <h3>
                        1 . Primary Data
                    </h3>
                </th>
            </tr>
        </table>

        {{-- <table style="width:80%; margin-left:86px;">
            <tr>
                <th style="width:15%;">
                    Unit No _________
                </th>
                <th style="width:15%;">
                    Floor _________
                </th>
                <th style="width:15%;">
                    Category _________
                </th>
                <th style="width:15%;">
                    Size _________
                </th>
            </tr>
        </table>

        <table style="width:80%; margin-left:83px;">
            <tr>
                <th style=" width:35%;">
                    Client Name ______________________
                </th>
                <th style="width:35%;">
                    Unit Orientation  ___________________
                </th>
            </tr>
        </table> --}}

        <table style="margin-left: 130px; width:70%" >
            <tr >
                <th  style="text-align: start;">Unit No </th>
                <th  style="text-align: start;"> _______</th>
                <th  style="">Floor </th>
                <th  style="text-align: start;"> _______</th>
                <th  style="text-align: start;">Category </th>
                <th  style="text-align: start;"> _______</th>
                <th  style="">Size </th>
                <th  style="text-align: start;"> _______</th>
            </tr>
            <tr>
                <th  style="text-align: start;" colspan="2">Client Sign </th>
                <th  style="text-align: start;" colspan="2"> ________________</th>
                <th  style="text-align: start;" colspan="2">Authorized Sign </th>
                <th  style="text-align: start;" colspan="2"> ________________</th>
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
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Amount </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
            <tr>
                <th  style="text-align: start;">Highway Charges </th>
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Amount </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
            <tr>
                <th  style="text-align: start;">Corner Charges </th>
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Amount </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
            <tr>
                <th  style="text-align: start;">Discounts </th>
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Amount </th>
                <th  style="text-align: start;"> ____________________</th>
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
                <th  style="text-align: start;">Sales Person </th>
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Status </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
            <tr>
                <th  style="text-align: start;">Contact NO </th>
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Sales Type </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
            <tr>
                <th  style="text-align: start;">Indirect Source </th>
                <th  style="text-align: start;"> ____________________</th>
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
                <th  style=" border: 1px solid black;text-align: left; padding: 8px;">NO</th>
                <th  style=" border: 1px solid black;text-align: left; padding: 8px;">Date</th>
                <th  style=" border: 1px solid black;text-align: left; padding: 8px;">Detail</th>
                <th  style=" border: 1px solid black;text-align: left; padding: 8px;">Amount</th>
                <th  style="  border: 1px solid black;text-align: left; padding: 8px;">Remarks</th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
            </tr>
            <tr>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"></th>
                <th   style="  border: 1px solid black;text-align: left; padding: 8px;"> </th>
                <th   style=" border: 1px solid black;text-align: left; padding: 8px;"> </th>
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
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Verified By GM Sales </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
            <tr>
                <th  style="text-align: start;">Client Sign </th>
                <th  style="text-align: start;"> ____________________</th>
                <th  style="text-align: start;">Authorized Sign </th>
                <th  style="text-align: start;"> ____________________</th>
            </tr>
        </table>

    </div>

</body>
</html>

