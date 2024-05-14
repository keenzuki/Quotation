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
                        <a class="btn btn-primary"
                            href="{{ route('agent.clientprofile', ['client' => $client->id]) }}">profile</a>
                    </td>
                </tr>
            @empty
                <td colspan="5" class="text-center text-info">No data available</td>
            @endforelse
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
