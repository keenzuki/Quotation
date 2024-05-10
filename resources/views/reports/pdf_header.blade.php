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
            text-decoration: underline;
            margin: 0px;
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
            justify-content: center;
            font-size: 20px;
        }

        .table thead {
            border-bottom: 1px solid #000;
        }

        .table tbody tr {
            text-align: center;
            font-size: 20px;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #e6f3fe;
        }

        .table tfoot tr {
            background-color: #fff;
        }

        .table tfoot {
            border-top: 1px solid #000;
        }

        .table tfoot tr td h5 {
            margin: 1px;
            font-size: 20px;
        }

        .table tfoot td:first-child {
            text-align: center;
        }

        table tr td span {
            text-decoration: underline;
        }

        table tr td p {
            margin: 0px;
        }
    </style>
</head>
<header>
    <div id="heading1">
        <img src="images/InspireLogo.jpg" width="300px" />
        <h3 class="text-uppercase">{{ $title }}</h3>
    </div>
    <table id="heading2" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="max-width: 50px; vertical-align: top; overflow-wrap: break-word;">
                <span class="fs-2">From</span>
                <p>InspireHub</p>
                <p style="color: blue;">support@inspirehub.co.ke
                </p>
                <p>P O Box 96</p>
            </td>
            <td style="max-width: 50px; vertical-align: top; overflow-wrap: break-word;">
                <span>To</span>
                <p>{{ $client->name }}</p>
                <p style="color: blue;">{{ $client->email }}</p>
                <p>{{ $client->address }}</p>
            </td>
            <td style="max-width: 50px; vertical-align: top; overflow-wrap: break-word;">
                <span>Invoice</span>
                <p><small>Ref: </small>{{ $invoice->reference }}</p>
                <p>Date: {{ \Carbon\Carbon::parse($invoice->created_at)->format('D d M, Y') }}</p>
            </td>
        </tr>
    </table>
</header>
