<!DOCTYPE html>
<html lang="en">

<body>
    <main>
        @include('reports.pdf_header')
        <div class="card">
            <div class="card-body">
                <h2 id="tableHead" style="text-transform: uppercase; text-align: left;">Invoice Items</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: left">Item</th>
                            <th style="text-align: center">Qty</th>
                            <th style="text-align: right">Unit</th>
                            <th style="text-align: right">Price (Kes)</th>
                            <th style="text-align: right">Subtotal (Kes)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $invoiced = 0; ?>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: left">{{ $sale->name }}</td>
                                <td style="text-align: center">{{ $sale->quantity }}</td>
                                <td style="text-align: right">{{ $sale->unit }}</td>
                                <td style="text-align: right">{{ number_format($sale->price, 2, '.', ',') }}</td>
                                <td style="text-align: right">
                                    {{ number_format($sale->price * $sale->quantity, 2, '.', ',') }}</td>
                            </tr>
                            <?php $invoiced += $sale->price * $sale->quantity; ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <h5 style="text-align: right;">TOTAL</h5>
                            </td>
                            <td>
                                <h5 style="text-align: right;">{{ number_format($invoiced, 2, '.', ',') }}</h5>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <hr style="padding: 0px;">
                {{-- <h2 id="tableHead" style="text-transform: uppercase; text-align: left; margin-top:30px;">Transactions
                </h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: left">Date Paid</th>
                            <th style="text-align: left">Mode</th>
                            <th style="text-align: left">Reference</th>
                            <th style="text-align: right">Receipt</th>
                            <th style="text-align: right">Amount (Kes)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: left">
                                    {{ \Carbon\Carbon::parse($payment->date)->format('y m D') }}</td>
                                <td style="text-align: left">{{ $payment->mode }}</td>
                                <td style="text-align: left">{{ $payment->reference }}</td>
                                <td style="text-align: right">{{ $payment->reference }}</td>
                                <td style="text-align: right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
                            </tr>
                            <?php $total += $payment->amount; ?>
                        @empty
                            <td id="noPayment" colspan="5">No Payments Made</td>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right">
                                <h5>Total</h5>
                            </td>
                            <td style="text-align: right">
                                <h5>{{ number_format($total, 2, '.', ',') }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right">
                                <h5>Due</h5>
                            </td>
                            <td style="text-align: right">
                                <h5>{{ number_format($invoiced - $total, 2, '.', ',') }}</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="2">
                                <hr style="padding: 0px;">
                            </td>

                        </tr>
                    </tfoot>
                </table> --}}
            </div>
        </div>
    </main>
</body>

</html>
