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
            @forelse ($clients as $client)
                <tr class="table-primary">
                    <td scope="row">{{ ($clients->currentPage() - 1) * $clients->perPage() + $loop->iteration }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->address }}</td>
                    <td>
                        <div class="dropdown open">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="triggerId"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action
                            </button>
                            <div class="dropdown-menu" aria-labelledby="triggerId">
                                <a class="dropdown-item" href="#">View</a>
                                <a class="dropdown-item" href="#">Quotation</a>
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
