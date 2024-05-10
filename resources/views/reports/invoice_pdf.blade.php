<!DOCTYPE html>
<html lang="en">

<body>
    <main>
        @include('reports.pdf_header')
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sale->name }}</td>
                                <td>{{ number_format($sale->price, 2, '.', ',') }}</td>
                                <td>{{ $sale->quantity }}</td>
                                <td>{{ $sale->unit }}</td>
                                <td>{{ number_format($sale->price * $sale->quantity, 2, '.', ',') }}</td>
                            </tr>
                            <?php $total += $sale->price * $sale->quantity; ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <h5>TOTAL</h5>
                            </td>
                            <td>
                                <h5>Ksh&nbsp;{{ number_format($total, 2, '.', ',') }}</h5>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </main>
</body>

</html>
