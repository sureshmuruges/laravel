<x-app-layout>
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Invoice Particulars</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('invoice_particulars.export', ['search' => request('search')]) }}" class="btn btn-outline-success">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
            <a href="{{ route('invoice_particulars.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Particular
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">List of Particulars</h6>
            
            <form action="{{ route('invoice_particulars.index') }}" method="GET" class="d-flex" style="width: 300px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm">Search</i>
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th><a href="{{ route('invoice_particulars.index', ['sort' => 'Id', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">ID</a></th>
                            <th><a href="{{ route('invoice_particulars.index', ['sort' => 'BillNo', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">Bill No</a></th>
                            <th><a href="{{ route('invoice_particulars.index', ['sort' => 'Particulars', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">Particulars</a></th>
                            <th><a href="{{ route('invoice_particulars.index', ['sort' => 'TaxAmount', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">Tax Amount</a></th>
                            <th><a href="{{ route('invoice_particulars.index', ['sort' => 'Total', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">Total</a></th>
                            <th><a href="{{ route('invoice_particulars.index', ['sort' => 'CreateDate', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}">Date</a></th>
                            <th class="text-center" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($particulars as $item)
                        <tr>
                            <td>{{ $item->Id }}</td>
                            <td>{{ $item->BillNo }}</td>
                            <td>{{ $item->Particulars }}</td>
                            <td>{{ $item->TaxAmount }}</td>
                            <td>{{ number_format($item->Total, 2) }}</td>
                            <td>{{ $item->CreateDate ? \Carbon\Carbon::parse($item->CreateDate)->format('d/m/Y') : '' }}</td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('invoice_particulars.edit', $item->Id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('invoice_particulars.destroy', $item->Id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this particular?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No particulars found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $particulars->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
