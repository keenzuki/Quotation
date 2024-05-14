<div class="table-responsive">
    <table class="table table-striped table-hover table-borderless table-primary align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Sys Ref</th>
                <th>Ref</th>
                <th>Mode</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            @forelse ($payments as $payment)
                <tr class="table-primary">
                    <td scope="row">{{ ($payments->currentPage() - 1) * $payments->perPage() + $loop->iteration }}
                    </td>
                    <td>{{ $payment->sys_ref }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>{{ $payment->mode }}</td>
                    <td>{{ $payment->client }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>
                        @if ($payment->status == 1)
                            <span class="bg-danger badge">Unallocated</span>
                        @elseif ($payment->status == 2)
                            <span class="bg-warning badge">Partially Allocated</span>
                        @elseif ($payment->status == 3)
                            <span class="bg-success badge">Fully Allocated</span>
                        @else
                            <span class="bg-info badge">N/A</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="triggerId"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="triggerId">
                            {{-- <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#paymentDetails">
                                <i class="fs-5 bi bi-eye text-success"></i> view Payment
                            </button> --}}
                            <button type="button" class="dropdown-item view-payment-btn"
                                data-payment-id="{{ $payment->id }}">
                                <i class="fs-5 bi bi-eye text-success"></i> View Payment
                            </button>

                            <button type="button" class="dropdown-item edit-payment-btn"
                                data-editpayment-id="{{ $payment->id }}">
                                <i class="fs-5 bi bi-pencil-square text-success"></i> Edit Payment
                            </button>
                            <a class="dropdown-item"
                                href="{{ route('agent.processpayment', ['payment' => $payment->id]) }}"><i
                                    class="fs-5 bi bi-check-circle text-info"></i>
                                Process Payment</a>

                            @if ($payment->status == 1)
                                <hr>
                                <form class="dropdown-item" action="" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger" type="submit"
                                        onclick="return confirm('Please Confirm you want to delete the draft')"><i
                                            class="fs-5 bi bi-trash text-danger"></i>
                                        Delete Payment</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <td colspan="8" class="text-center text-info">No data available</td>
            @endforelse
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>
