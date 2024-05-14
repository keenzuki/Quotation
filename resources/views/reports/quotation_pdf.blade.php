<!DOCTYPE html>
<html lang="en">

<body>
    <main>
        @include('reports.pdf_header')
        <div class="card">
            <div class="card-body">
                <h3 id="tableHead" style="text-transform: uppercase; text-align: left;">Quotation Items</h3>
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
                <div id="quotation_notes">
                    <span style="font-weight: bolder; padding:0px;">NOTES</span>
                    <p style="padding:0px; margin-top:0px;">{!! nl2br(e($quotation->details)) !!}</p>
                </div>
            </div>
        </div>
        @include('reports.pdf_footer')
    </main>
</body>

</html>
