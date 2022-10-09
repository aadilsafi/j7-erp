<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap-extended.min.css">
    <title>J7 Global Application Form</title>

    <style>
        * {
            font-size: small;
            color: black;
        }

        h1,
        h2,
        h3,
        h4 {
            color: black;
        }
    </style>
</head>

<body>

    <div id="printable" class="bg-light m-1 text-uppercase">
        <div class="row text-center mt-2">
            <div class="col-7">
                <h4>APPLICATION FORM</h4>
            </div>
            <div class="col">
                <p>Photo</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-8 row text-start">
                <div class="col-4">
                    <small>Registration NO</small>
                </div>
                <div class="col-2">
                    <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    <!-- <p>________</p> -->
                </div>
                <div class="col-4">
                    <small>APPLICATION No</small>
                </div>
                <div class="col-2">
                    <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    <!-- <p>________</p> -->

                </div>
            </div>

        </div>
        <div class="row text-start mt-1">
            <div class="col-9">
                <div style="border: 2px solid black;">
                    UNIT DETAILS
                </div>
                <div class="row text-start">
                    <div class="col-3 mt-1">
                        Unit
                    </div>
                    <div class="col-3 mt-1">
                        <!-- <p><u></u></p> -->
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>

                    <div class="col-3 mt-1">
                        Unit Type
                    </div>
                    <div class="col-3 mt-1">
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>

                    <div class="col-3 mt-1">
                        Size
                    </div>
                    <div class="col-3 mt-1">
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>

                    <div class="col-3 mt-1">
                        Floor
                    </div>
                    <div class="col-3 mt-1">
                        <div style="border-bottom: 1px solid black" class="mt-1"></div>
                    </div>

                </div>
            </div>
            <div class="col">

            </div>
        </div>
        <div class="row mt-1">
            <div>
                <table>
                    <tr>
                        <div style="border: 1px solid black;">
                            CLIENT DETAILS
                        </div>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-2" style="height:40px">
            <div class="col-3">
                Name
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-4" style="width:fit-content">
                FATHER/HUSBAND NAME
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                CNIC/PASSORT NO.
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                MAIL ADDRESS
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                RESIDENTIAL ADDRESS
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                CONTACT NO.
            </div>
            <div class="col-3 text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
            <div class="col-2">
                CONTACT NO.
            </div>
            <div class="col-4 text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
            <div class="col-3 mt-1">
                Email
            </div>
            <div class="col-3 text-end mt-1">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
            <div class="col-2 mt-1">
                Occupation
            </div>
            <div class="col-4 text-end mt-1">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>

        <div class="row text-start mt-1">
            <div class="col-12">
                <div style="border: 2px solid black; padding: 1px 3px;">
                    NEXT KIN DETAILS
                </div>
            </div>
        </div>
        <div class="row mt-2" style="height:40px">
            <div class="col-3">
                Name
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-4" style="width:fit-content">
                FATHER/HUSBAND NAME
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                CNIC/PASSORT NO.
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                MAIL ADDRESS
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                RESIDENTIAL ADDRESS
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:40px">
            <div class="col-3">
                CONTACT NO.
            </div>
            <div class="col text-end">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <h4>
                    <u><b>DECALARATION:</b></u>
                </h4>
                <p><small>I / WE CONFIRM THAT THE ABOVE PARTICULARS ARE TRUE TO THE BEST OF MY / OUR
                        KNOWLEDGE AND THAT I / WE AGREED TO THE TERMS & CONDITIONS AS LAID ON THE BACK OF THIS FORM FOR
                        THE
                        PURCHASE OF THE UNIT AT J7 GLOBAL AND THAT I / WE SHALL PAY THE INSTALLEMNTS AS PER YOUR
                        SCHEDULE OF
                        DEMAND.</small></p>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col">
                <table style="text-transform: uppercase;">
                    <tr>
                        <th style="text-align: center; ">
                            ____________________________
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: center;">
                            Date
                        </th>
                    </tr>
                </table>
            </div>
            <div class="col text-end">
                <table style="text-transform: uppercase;">
                    <tr>
                        <th style="text-align: center; ">
                            _____________________________
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: center;">
                            APPLICANT SIGNATURE
                        </th>
                    </tr>
                </table>
            </div>
        </div>

        <p style="page-break-after: always;">&nbsp;</p>
        <p style="page-break-before: always;">&nbsp;</p>


        <div class="row mt-5">
            <div class="col-12">
                <h4>
                    <u><b>ABANDONMENT OF THE PROJECT:</b></u>
                </h4>
                <p><small>THAT IF FOR ANY REASON, THE PROJECT IS ABANDONED, COMPANY SHALL REFUND THE AMOUNT RECEIVED
                        FROM
                        THE
                        ALLOTEE WITHIN THE EARLIEST CONVENIENCE OF THE COMPANY. IT IS HOWEVER, CLEARLY UNDERSTOOD THAT
                        IN
                        SUCH
                        AN EVENTUALITY, THE ALLOTTEES SHALL NOT BE ENTITLED TO ANY CLAIM AS DAMAGE, INTERESTS OR PROFIT
                        ETC.
                        OF
                        WHATEVER NATURE.</small></p>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-12">
                <h4>
                    <b><u>DECLARATION BY APPLICANT:</u></b>
                </h4>
                <div class="row text-start">
                    <div class="col-3">
                        I / WE
                    </div>
                    <div class="col-3">
                        <u>
                            <p>Gulraiz Khan</p>
                        </u>
                    </div>
                    <div class="col-3">
                        S/O, D/O, W/O
                    </div>
                    <div class="col-3">
                        <u>
                            <p>Aziz Ur Rehman</p>
                        </u>
                    </div>
                </div>
                <small>DO HEREBY DECLARE THAT I / WE HAVE READ/UNDERSTOOD THE TERMS AND CONDITIONS OF BOOKING/ALLOCATION
                    OF
                    THE
                    PROJECT AND ACCEPT THE SAME FUTURE DECLARE THAT I / WE SHALL ABIDE BY ALL THE EXISTING RULES,
                    REGULATIONS,
                    CONDITIONS, REQUIREMENT ETC. OR WHICH MAY BE PRESCRIBED AND APPROVED BY THE COMPANY.
                </small>
            </div>

        </div>

        <div style="border-bottom: 1px solid black" class="mt-3"></div>
        <div style="border-bottom: 1px solid black; margin-top: 3px;"></div>

        <div class="row text-start mt-2">
            <div class="col-12">
                <b>FOR OFFICE USE ONLY</b>
            </div>
        </div>
        <div class="row mt-1" style="height:30px">
            <div class="col-3">
                Unit
            </div>
            <div class="col-3 text-center">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
            <div class="col-3 text-center">
                FLOOR
            </div>
            <div class="col-3 text-center">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:30px">
            <div class="col-3">
                TOTAL COST (PKR)
            </div>
            <div class="col-3 text-center">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:30px">
            <div class="col-3">
                AMOUNT PAID (PKR)
            </div>
            <div class="col-3 text-center">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:30px">
            <div class="col-3">
                CHEQUE / P.O. NO.
            </div>
            <div class="col-3 text-center">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row" style="height:30px">
            <div class="col-3">
                DATE
            </div>
            <div class="col-3 text-center">
                <div style="border-bottom: 1px solid black" class="mt-1"></div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <table style="text-transform: uppercase;">
                    <tr>
                        <th style="text-align: center; ">
                            <div style="border-bottom: 1px solid black" class="mt-3"></div>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: start;">
                            <small> AUTHORISED SIGNATURE FOR DEVELOPERS</small>
                            <br>
                            <small>DATE</small> <b><u>22-AUG_2022</u></b>
                        </th>
                    </tr>
                </table>
            </div>
            <div class="col-2">

            </div>
            <div class="col text-end">
                <table style="text-transform: uppercase;" width="100%">
                    <tr>
                        <th>
                            <div style="border-bottom: 1px solid black" class="mt-3"></div>
                        </th>
                    </tr>
                    <tr>
                        <th style="text-align: start;">
                            <small>READ, UNDERSTOOD & SIGNED</small>
                            <br>
                            <small>DATE</small> <b><u>22-AUG_2022</u></b>
                        </th>
                    </tr>
                </table>
            </div>
        </div>

        <br><br>
    </div>

    <!-- <button type="button" id="print">Print</button> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/bootstrap-icons/font/bootstrap-icons.css">
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

            $("#print").click(function () { });
        });
    </script>
</body>

</html>