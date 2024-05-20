@extends('layouts.app')
<style>
    .content {
        font-family: 'Times New Roman', Times, serif;
        color: black;
    }
</style>
@section('content')
    <div class="col content">
        @include('components.success')
        <div class="mt-3 ms-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <small>{{ __('Welcome Back ' . Auth::user()->role->name) }} &#x2764;</small>
                {{-- @if (session()->has('admin'))
                    <a href="{{ route('adminprofile') }}" class="btn btn-primary">Go back</a>
                @endif --}}
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12" style="max-height: 300px; overflow:auto; position:relative;">
                            <nav class="position-sticky top-0 bg-white text-uppercase fs-5 p-2">
                                Most Recent Clients
                            </nav>
                            <table class="table table-striped table-hover table-primary align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Client Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @forelse ($clients as $key=>$client)
                                        <tr class="table-primary">
                                            <td scope="row">{{ $key + 1 }}</td>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->phone }}</td>
                                            <td>{{ $client->address }}</td>
                                            <td>{{ $client->created_at->format('D d M, Y') }}</td>
                                        </tr>
                                    @empty
                                        <td colspan="5" class="text-center text-info">No data available</td>
                                    @endforelse
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>
                        <div class="col-12" style="max-height: 300px; overflow:auto; position:relative;">
                            <nav class="position-sticky top-0 bg-white text-uppercase fs-5 p-2">
                                Most Recent Quotations
                            </nav>
                            <table class="table table-striped table-hover table-primary align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Ref</th>
                                        <th>Title</th>
                                        <th>Price</th>
                                        <th>Client</th>
                                        <th>Rep</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @forelse ($quots as $key=>$quot)
                                        <tr class="table-primary">
                                            <td scope="row">{{ $key + 1 }}</td>
                                            <td>{{ $quot->reference }}</td>
                                            <td>{{ $quot->title }}</td>
                                            <td>{{ $quot->cost }}</td>
                                            <td>{{ $quot->client->name }}</td>
                                            <td>{{ $quot->client->agent->fname }}</td>
                                            <td>{{ $quot->created_at->format('D d M, Y') }}</td>
                                        </tr>
                                    @empty
                                        <td colspan="5" class="text-center text-info">No data available
                                        </td>
                                    @endforelse
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>
                        <div class="col-12" style="max-height: 300px; overflow:auto; position:relative;">
                            <nav class="position-sticky top-0 bg-white text-uppercase fs-5 p-2">
                                Most Recent Invoices
                            </nav>
                            <table class="table table-striped table-hover table-primary align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Ref</th>
                                        <th>Title</th>
                                        <th>Price</th>
                                        <th>Client</th>
                                        <th>Rep</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @forelse ($invs as $key=>$inv)
                                        <tr class="table-primary">
                                            <td scope="row">{{ $key + 1 }}</td>
                                            <td>{{ $inv->reference }}</td>
                                            <td>{{ $inv->title }}</td>
                                            <td>{{ $inv->cost }}</td>
                                            <td>{{ $inv->client->name }}</td>
                                            <td>{{ $inv->client->agent->fname }}</td>
                                            <td>{{ $inv->created_at->format('D d M, Y') }}</td>
                                        </tr>
                                    @empty
                                        <td colspan="7" class="text-center text-info">No data available</td>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-12" style="max-height: 300px; overflow:auto; position:relative;">
                            <nav class="position-sticky top-0 bg-white text-uppercase fs-5 p-2">
                                Most Recent Payments
                            </nav>
                            <table class="table table-striped table-hover table-primary align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Sys Ref</th>
                                        <th>Ref</th>
                                        <th>Amount</th>
                                        <th>Client</th>
                                        <th>Rep</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @forelse ($pays as $key=>$pay)
                                        <tr class="table-primary">
                                            <td scope="row">{{ $key + 1 }}</td>
                                            <td>{{ $pay->sys_ref }}</td>
                                            <td>{{ $pay->reference }}</td>
                                            <td>{{ $pay->amount }}</td>
                                            <td>{{ $pay->client->name }}</td>
                                            <td>{{ $pay->client->agent->fname }}</td>
                                            <td>{{ $pay->created_at->format('D d M, Y') }}</td>
                                        </tr>
                                    @empty
                                        <td colspan="5" class="text-center text-info">No data available
                                        </td>
                                    @endforelse
                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>


                        </div>
                        {{-- <div class="col-12" style="max-height: 300px; overflow:auto;">
                                                            <table class="table table-striped table-hover table-primary align-middle">
                                    <thead class="table-light">
                                        <caption class="caption-top">
                                            Table Name
                                        </caption>
                                        <tr>
                                            <th>Column 1</th>
                                            <th>Column 2</th>
                                            <th>Column 3</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr class="table-primary">
                                            <td scope="row">Item</td>
                                            <td>Item</td>
                                            <td>Item</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td scope="row">Item</td>
                                            <td>Item</td>
                                            <td>Item</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            

                        </div>
                        <div class="col-12" style="max-height: 300px; overflow:auto;">
                                                            <table class="table table-striped table-hover table-primary align-middle">
                                    <thead class="table-light">
                                        <caption class="caption-top">
                                            Table Name
                                        </caption>
                                        <tr>
                                            <th>Column 1</th>
                                            <th>Column 2</th>
                                            <th>Column 3</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr class="table-primary">
                                            <td scope="row">Item</td>
                                            <td>Item</td>
                                            <td>Item</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td scope="row">Item</td>
                                            <td>Item</td>
                                            <td>Item</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            

                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
