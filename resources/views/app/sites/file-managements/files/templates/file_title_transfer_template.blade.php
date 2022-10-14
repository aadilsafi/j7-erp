<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap-extended.min.css">
    <title>File Title Transfer Form</title>

    <style>
        * {
            font-size: small;
            color: black;
        }

        tr td {
            padding-left: 5px;
            border: 1px solid black;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            color: black;
        }
    </style>
</head>

<body>

    <div id="printable" class="bg-light m-1">
        <table style="width:100%; margin-bottom:5px;" class="template">
            <tr>
                <th style="width:33%; text-align:start;">
                    <br>
                    <img height="50px" src="{{ asset('app-assets') }}/images/receipts/logo_j7Global.png" alt="logo">
                </th>
                <th style="width:48%; ">
                </th>
                <th style="width:33%; text-align:start;">
                    <br>
                    <img height="50px" src="{{ asset('app-assets') }}/images/logo/signature-logo.png" alt="logo">
                </th>
            </tr>
        </table>

        <div class="row mt-3">
            <h2 class="text-center">File Title Transfer Form</h2>

            <table class="mt-1">
                <tr height="30px">
                    <td width="60%">
                        <b>Refund Data</b>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong>Payment Due Date</strong>
                            </div>
                            <div class="col">
                                22-Aug-2022
                            </div>
                        </div>
                    </td>
                </tr>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-4">
                                <strong>Transfer Charges</strong>
                            </div>
                            <div class="col">
                                120
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col-7">
                                <strong>Amount To Be Paid</strong>
                            </div>
                            <div class="col">
                                6,968,852
                            </div>
                        </div>
                    </td>
                </tr>
                <tr height="30px">
                    <td width="40%">
                        <div class="row">
                            <div class="col-4">
                                <strong>Paid Amount</strong>
                            </div>
                            <div class="col">
                                6,968,975
                            </div>
                        </div>
                    </td>

                    <td width="40%">
                        <div class="row">
                            <div class="col-7">
                                <strong>Amount Remarks</strong>
                            </div>
                            <div class="col">
                                ok
                            </div>
                        </div>
                    </td>
                </tr>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-4">
                                <strong>Unit Area</strong>
                            </div>
                            <div class="col">
                                523
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col-7">

                            </div>
                            <div class="col">

                            </div>
                        </div>
                    </td>
                </tr>
            </table>


            <table class="mt-1">
                <tr height="30px">
                    <td colspan="2">
                        <b>Unit Information </b>
                    </td>

                </tr>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-3">
                                <strong>Unit No:</strong>
                            </div>
                            <div class="col">
                                GF-01
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong>Unit Name:</strong>
                            </div>
                            <div class="col">
                                Unit 1
                            </div>
                        </div>
                    </td>
                </tr>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-3">
                                <strong>Unit Type</strong>
                            </div>
                            <div class="col">
                                Executive Suits
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong>Gross Area</strong>
                            </div>
                            <div class="col">
                                523
                            </div>
                        </div>
                    </td>
                </tr>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-3">
                                <strong>Price Per Sqft</strong>
                            </div>
                            <div class="col">
                                Executive Suits
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong>Total Price</strong>
                            </div>
                            <div class="col">
                                13,598,000
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="mt-1">
                <tr height="30px">
                    <td width="60%">
                        <b>Customer Information </b>
                    </td>

                </tr>
            </table>

            <table>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-3">
                                <strong>Name:</strong>
                            </div>
                            <div class="col">
                                Zain Ali
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong>Father Name</strong>
                            </div>
                            <div class="col">
                                Hassan Raza
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-3">
                                <strong>Designation</strong>
                            </div>
                            <div class="col">
                                Engineer
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong>CNIC:</strong>
                            </div>
                            <div class="col">
                                13302-0516802-7
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>
                <tr height="30px">
                    <td width="60%">
                        <div class="row">
                            <div class="col-3">
                                <strong>NTN</strong>
                            </div>
                            <div class="col">
                                123456789012
                            </div>
                        </div>
                    </td>
                    <td width="40%">
                        <div class="row">
                            <div class="col">
                                <strong> Contact</strong>
                            </div>
                            <div class="col">
                                0512226044
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <table>
                <tr height="40px">
                    <td colspan="2">
                        <div class="row">
                            <div class="col-2">
                                <strong> Address:</strong>
                            </div>
                            <div class="col">
                                House No.380, Street No.114, Sector D-12/2, Islamabad.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="m-1 text-center">
                <h3>Installments Details</h3>
            </div>

            <table>
                <tr height="70px" class="text-center">
                    <th style="border: 1px solid black;">
                        Sr.
                    </th>
                    <th style="border: 1px solid black;">
                        INSTALLMENT
                    </th>
                    <th style="border: 1px solid black;">
                        DUE DATE
                    </th>
                    <th style="border: 1px solid black;">
                        TOTAL AMOUNT
                    </th>
                    <th style="border: 1px solid black;">
                        PAID AMOUNT
                    </th>
                    <th style="border: 1px solid black;">
                        REMAINING AMOUNT
                    </th>
                    <th style="border: 1px solid black;">
                        Status
                    </th>
                </tr>

                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>Downpayment</td>
                    <td>December 18, 2022</td>
                    <td>3,399,500</td>
                    <td>3,399,500</td>
                    <td>0</td>
                    <td>Paid</td>
                </tr>
            </table>

            <div class="row mt-3">
                <div class="col-4 text-center">
                    <tr>
                        <th>
                            ___________________
                            <br>
                            <b><small>Senior Accountant - A/R</small></b>
                        </th>
                    </tr>
                </div>
                <div class="col-4 text-center">
                    <tr>
                        <th>
                            ___________________
                            <br>
                            <b> <small>Manager Sales</small></b>
                        </th>
                    </tr>
                </div>
                <div class="col-4 text-center">
                    <tr>
                        <th>
                            ___________________
                            <br>
                            <b><small>Project Finance Manager </small></b>
                        </th>
                    </tr>
                </div>
            </div>
        </div>
    </div>

    <!-- <button type="button" id="print">Print</button> -->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/app.min.css">
    <script src="{{ asset('app-assets') }}/js/printing/jQuery.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/printing/jQuery.print.min.js"></script>
    <script>
        $(document).ready(function () {

            $("#printable").printThis({
                debug: false, // show the iframe for debugging
                importCSS: true, // import parent page css
                importStyle: true, // import style tags
                printContainer: true, // print outer container/$.selector
                loadCSS: "", // path to additional css file - use an array [] for multiple
                pageTitle: "", // add title to print page
                removeInline: false, // remove inline styles from print elements
                removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
                printDelay: 1000, // variable print delay
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

            $("#print").click(function () { });
        });
    </script>
</body>

</html>