<div class="table-responsive">
    <table class="table table-striped table-hover table-borderless table-primary align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($invoices as $invoice)
                <tr class="table-primary">
                    <td scope="row">{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->iteration }}
                    </td>
                    <td>{{ $invoice->name }}</td>
                    <td>{{ $invoice->phone }}</td>
                    <td>{{ $invoice->address }}</td>
                    <td>
                        <div class="dropdown open">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="triggerId"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="triggerId">
                                <a class="dropdown-item"
                                    href="{{ route('agent.clientprofile', ['client' => $client->id]) }}">profile</a>
                                <a class="dropdown-item"
                                    href="{{ route('agent.createquotation', ['client' => $client->id]) }}">Make
                                    Quotation</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">After divider action</a>
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