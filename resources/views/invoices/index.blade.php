<x-app-layout>
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="h3 mb-1 text-gray-800 fw-bold">Tax Invoices</h2>
                <p class="text-muted mb-0">Manage your company's tax invoices, proformas, and client billing records.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('invoices.create') }}" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Create Tax Invoice
                </a>
            </div>
        </div>

        <!-- Session Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter & Search Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('invoices.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by invoice number, company name, booking number, or status..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-secondary fw-semibold">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>

                <!-- Excel Export by Invoice Date Range -->
                <form action="{{ route('invoices.export') }}" method="GET" id="invoiceExportForm" class="row g-2 align-items-end border-top pt-3 mt-3">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <div class="col-md-3">
                        <label for="export_from_date" class="form-label fw-semibold small text-muted mb-1">From Invoice Date</label>
                        <input type="date" name="from_date" id="export_from_date" class="form-control" value="{{ old('from_date', request('from_date')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="export_to_date" class="form-label fw-semibold small text-muted mb-1">To Invoice Date</label>
                        <input type="date" name="to_date" id="export_to_date" class="form-control" value="{{ old('to_date', request('to_date')) }}">
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-outline-success fw-semibold d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-file-excel"></i> Export to Excel
                        </button>
                    </div>
                    <div class="col-md-3 small text-muted">
                        Leave both dates empty to export all invoices{{ request('search') ? ' matching the current search' : '' }}.
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices List Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 fw-bold text-muted border-bottom">
                            <tr>
                                <th class="ps-4">
                                    <a href="{{ route('invoices.index', ['sort' => 'billno', 'direction' => request('direction') == 'asc' && request('sort') == 'billno' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
                                        Invoice No
                                        <i class="fas fa-sort-amount-{{ request('sort') == 'billno' && request('direction') == 'asc' ? 'up' : 'down' }} fs-8"></i>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('invoices.index', ['sort' => 'billdate', 'direction' => request('direction') == 'asc' && request('sort') == 'billdate' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
                                        Date
                                        <i class="fas fa-sort-amount-{{ request('sort') == 'billdate' && request('direction') == 'asc' ? 'up' : 'down' }} fs-8"></i>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('invoices.index', ['sort' => 'company_name', 'direction' => request('direction') == 'asc' && request('sort') == 'company_name' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
                                        Client Name
                                        <i class="fas fa-sort-amount-{{ request('sort') == 'company_name' && request('direction') == 'asc' ? 'up' : 'down' }} fs-8"></i>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('invoices.index', ['sort' => 'booking_no', 'direction' => request('direction') == 'asc' && request('sort') == 'booking_no' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1">
                                        Booking No
                                        <i class="fas fa-sort-amount-{{ request('sort') == 'booking_no' && request('direction') == 'asc' ? 'up' : 'down' }} fs-8"></i>
                                    </a>
                                </th>
                                <th class="text-end">
                                    <a href="{{ route('invoices.index', ['sort' => 'grand_total', 'direction' => request('direction') == 'asc' && request('sort') == 'grand_total' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1 float-end">
                                        Grand Total
                                        <i class="fas fa-sort-amount-{{ request('sort') == 'grand_total' && request('direction') == 'asc' ? 'up' : 'down' }} fs-8"></i>
                                    </a>
                                </th>
                                <th class="text-center">
                                    <a href="{{ route('invoices.index', ['sort' => 'status', 'direction' => request('direction') == 'asc' && request('sort') == 'status' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-decoration-none text-muted d-inline-flex align-items-center gap-1 mx-auto">
                                        Status
                                        <i class="fas fa-sort-amount-{{ request('sort') == 'status' && request('direction') == 'asc' ? 'up' : 'down' }} fs-8"></i>
                                    </a>
                                </th>
                                <th class="text-center pe-4" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr class="border-bottom">
                                    <td class="ps-4 fw-bold text-dark">
                                        {{ $invoice->billno }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($invoice->billdate)->format('d-M-Y') }}
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $invoice->company_name }}
                                        <div class="fs-8 text-muted fw-normal">{{ $invoice->acode }}</div>
                                    </td>
                                    <td>
                                        @if ($invoice->booking_no)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 py-1 px-2">
                                                {{ $invoice->booking_no }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-8">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        {{ number_format($invoice->grand_total, 2) }}
                                        <div class="fs-8 text-muted fw-normal">{{ $invoice->currency ?? 'INR' }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($invoice->status == 'Paid')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1.5 px-3 fw-bold">
                                                Paid
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 py-1.5 px-3 fw-bold">
                                                UnPaid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View Invoice">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit Invoice">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice? This will also delete all associated particulars.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete Invoice">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <i class="fas fa-file-invoice fs-1 text-muted opacity-50"></i>
                                            <span class="fw-semibold">No invoices found</span>
                                            <p class="fs-7 mb-0">Search for another keyword or create a new invoice to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($invoices->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
    <script>
        // Keep the export range valid: "To" can't be earlier than "From".
        (function () {
            const fromDate = document.getElementById('export_from_date');
            const toDate = document.getElementById('export_to_date');
            const syncMin = () => { toDate.min = fromDate.value || ''; };
            fromDate.addEventListener('change', syncMin);
            syncMin();
        })();
    </script>
</x-app-layout>
