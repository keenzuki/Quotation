<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    {{-- <link rel="stylesheet" href="css/bootstrap5.min.css"> --}}
    <style>
        #heading1 {
            text-align: center;
        }

        #heading1 h3 {
            text-transform: uppercase;
            /* text-decoration: underline; */
            margin-top: 7px;
            margin-bottom: 5px;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 5px;
            width: 100%;
        }

        .table th,
        .table td {
            font-size: 20px;
            text-transform: capitalize;
            justify-content: space-evenly;
        }

        .table th {
            background-color: rgb(216, 209, 204);
            /* justify-content: center; */
            font-size: 20px;
        }

        /* .table thead {
            border-bottom: 1px solid #000;
        } */

        .table tbody tr {
            /* text-align: center; */
            font-size: 20px;
        }

        /* .table tbody tr:nth-child(odd) {
            background-color: #f0f0f0;
        } */

        .table tfoot tr {
            background-color: #fff;
        }

        .table tfoot {
            border-top: 1px solid #413f3f;
        }

        /* #heading2 {
            border-bottom: 1px solid #413f3f;
        } */

        .table tfoot tr td h5 {
            margin: 1px;
            /* font-size: 20px; */
        }

        .table tfoot td:first-child {
            text-align: center;
        }

        /* table tr td span {
            text-decoration: underline;
        } */

        table tr td p {
            margin: 0px;
        }

        #tableHead {
            margin-bottom: 2px;
            text-align: center;
        }

        table #noPayment {
            font-size: 18px;
            text-align: center;
        }

        #pdfFooter .col p,
        #pdfFooter .col h4 {
            padding: 1px;
            margin: 1px;
        }

        #pdfFooter .col {
            padding: 10px;
        }
    </style>
</head>
<header>
    <div id="heading1">
        <img src="images/InspireLogo.jpg" width="300px" />
        <h3 style="padding: 5px;">{{ $title }}</h3>
    </div>
    <table id="heading2" style="width: 100%; border-collapse: collapse; border-bottom: 1px solid #413f3f;">
        <tr>
            <td style="max-width: 50px; vertical-align: top; overflow-wrap: break-word;">
                <span class="fs-2">From</span>
                <p>Inspire Hub Limited</p>
                <p style="color: blue;">billing@inspirehub.co.ke
                </p>
                {{-- <p>P O Box 96</p> --}}
            </td>
            <td style="max-width: 50px; vertical-align: top; overflow-wrap: break-word;">
                <span>To</span>
                <p>{{ $client->name }}</p>
                <p style="color: blue;">{{ $client->email }}</p>
                {{-- <p>{{ $client->address }}</p> --}}
            </td>
            <td style="max-width: 50px; vertical-align: top; overflow-wrap: break-word; text-align:right;">
                <span>{{ $type }} Details</span>
                <p><small>Ref: </small>{{ $reference }}</p>
                <p>Date: {{ \Carbon\Carbon::parse($date)->format('D d M, Y') }}</p>
            </td>
        </tr>
    </table>
</header>
