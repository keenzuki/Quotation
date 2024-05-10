<div class="table-responsive">
    <table class="table table-striped table-hover table-borderless table-primary align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Ref</th>
                <th>Title</th>
                <th>Client</th>
                <th>Price (Ksh)</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($invoices as $invoice)
                <tr class="table-primary">
                    <td scope="row">{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->iteration }}
                    </td>
                    <td>{{ $invoice->reference }} </td>
                    <td>{{ $invoice->title }}</td>
                    <td>{{ $invoice->client }}</td>
                    <td>{{ number_format($invoice->cost, 2, '.', ',') }}</td>
                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="dropdown open">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="triggerId"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                invoice
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                <a class="dropdown-item"
                                    href="{{ route('agent.viewinvoice', ['invoice' => $invoice->id]) }}"><i
                                        class="bi bi-tv text-secondary"></i> View Invoice</a>
                                <a class="dropdown-item"
                                    href="{{ route('agent.editinvoice', ['invoice' => $invoice->id]) }}"><i
                                        class="bi bi-pencil-square text-info"></i> Edit
                                    invoice</a>
                                <a class="dropdown-item"
                                    href="{{ route('agent.editinvoice', ['invoice' => $invoice->id]) }}"><i
                                        class="bi bi-cash-coin text-primary"></i> Make Payment</a>
                                <hr>
                                <form class="dropdown-item"
                                    action="{{ route('agent.deleteinvoice', ['invoice' => $invoice->id]) }}"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger" type="submit"
                                        onclick="return confirm('Please Confrrm you want to delete this Invoice')"><i
                                            class="bi bi-trash text-danger"></i>
                                        Delete</button>
                                </form>
                            </div>

                        </div>
                    </td>
                </tr>
            @empty
                <td class="text-center text-info">No data available</td>
            @endforelse
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
