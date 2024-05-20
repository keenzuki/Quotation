<!DOCTYPE html>
<html lang="en">

<body>
    <main>
        @include('reports.pdf_header')
        <div class="card">
            <div class="card-body">
                <h3 id="tableHead" style="text-transform: uppercase; text-align: left; margin: 3px;">Payment
                </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: left">Date</th>
                            <th style="text-align: left">Mode</th>
                            {{-- <th style="text-align: left">Receipt No</th> --}}
                            <th style="text-align: left">Ref</th>
                            <th style="text-align: right">Amount (Kes)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td style="text-align: left">
                                {{ \Carbon\Carbon::parse($payment->created_at)->format('D d M, Y') }}</td>
                            <td style="text-align: left">{{ $payment->mode }}</td>
                            {{-- <td style="text-align: left">{{ $payment->sys_ref }}</td> --}}
                            <td style="text-align: left">{{ $payment->reference ? $payment->reference : 'N/A' }}</td>
                            <td style="text-align: right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <h5 style="text-align: left; font-size:20px;">Total</h5>
                            </td>
                            <td>
                                <h5 style="text-align: right; font-size:20px;">
                                    {{ number_format($payment->amount, 2, '.', ',') }}</h5>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                {{--   <hr style="padding: 0px;">
                <h3 id="tableHead" style="text-transform: uppercase; text-align: left; margin-top:30px;">Transactions
                </h3>
                 <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: left">Date Paid</th>
                            <th style="text-align: left">Mode</th>
                            <th style="text-align: left">Reference</th>
                            <th style="text-align: right">Receipt Number</th>
                            <th style="text-align: right">Amount (Kes)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: left">
                                    {{ \Carbon\Carbon::parse($payment->date)->format('D d M, y') }}</td>
                                <td style="text-align: left">{{ $payment->mode }}</td>
                                <td style="text-align: left">{{ $payment->reference }}</td>
                                <td style="text-align: right">{{ $payment->sys_ref }}</td>
                                <td style="text-align: right">{{ number_format($payment->amount, 2, '.', ',') }}</td>
                            </tr>
                            <?php $total += $payment->amount; ?>
                        @empty
                            <td id="noPayment" colspan="7">No Payments Made</td>
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
        @include('reports.pdf_footer')
    </main>
</body>

</html>
