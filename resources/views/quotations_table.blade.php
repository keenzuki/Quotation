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
            @forelse ($quotations as $quotation)
                <tr class="table-primary">
                    <td scope="row">{{ ($quotations->currentPage() - 1) * $quotations->perPage() + $loop->iteration }}
                    </td>
                    <td>{{ $quotation->reference }} </td>
                    <td>{{ $quotation->title }}</td>
                    <td>{{ $quotation->client }}</td>
                    <td>{{ number_format($quotation->cost, 2, '.', ',') }}</td>
                    <td>{{ $quotation->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="dropdown open">

                            @if ($quotation->status == 1)
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="triggerId"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Draft
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                    <a class="dropdown-item text-primary"
                                        href="{{ route('agent.editdraft', ['quotation' => $quotation->id]) }}"><i
                                            class="bi bi-check-circle text-primary"></i> Complete</a>
                                    <hr>
                                    <form class="dropdown-item"
                                        action="{{ route('agent.deletequotation', ['quotation' => $quotation->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-danger" type="submit"
                                            onclick="return confirm('Please Confrrm you want to delete the draft')"><i
                                                class="bi bi-trash text-danger"></i>
                                            Delete</button>
                                    </form>
                                </div>
                            @elseif ($quotation->status == 2)
                                <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="triggerId"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Quotation
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                                    <a class="dropdown-item text-success"
                                        href="{{ route('agent.updateQ2I', ['quotation' => $quotation->id]) }}"
                                        onclick="return confirm('Do you really want to convert this Quotation to Invoice direct?')"><i
                                            class="bi bi-pen text-success"></i> Make
                                        Invoice</a>
                                    <a class="dropdown-item text-info"
                                        href="{{ route('agent.editquotation', ['quotation' => $quotation->id]) }}"><i
                                            class="bi bi-pencil-square text-info"></i> Edit
                                        Quotation</a>
                                    <hr>
                                    <form class="dropdown-item"
                                        action="{{ route('agent.deletequotation', ['quotation' => $quotation->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-danger" type="submit"
                                            onclick="return confirm('Please Confrrm you want to delete the draft')"><i
                                                class="bi bi-trash text-danger"></i>
                                            Delete</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <td colspan="7" class="text-center text-info">No data available</td>
            @endforelse
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
