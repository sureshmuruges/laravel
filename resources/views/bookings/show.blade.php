@extends('bookings.layout')

@section('content')
<div class="px-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 font-weight-bold text-dark mb-0">Booking Details: {{ $booking->BookingNo }}</h2>
        <a href="{{ route('bookings.edit', $booking->Id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit Booking
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card bg-light border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">General Information</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="40%">Category:</th><td>{{ $booking->Category }}</td></tr>
                        <tr><th>Booking Date:</th><td>{{ $booking->booking_date }}</td></tr>
                        <tr><th>Active:</th><td><span class="badge {{ $booking->Active ? 'bg-success' : 'bg-danger' }}">{{ $booking->Active ? 'Yes' : 'No' }}</span></td></tr>
                        <tr><th>Company Name:</th><td>{{ $booking->companyname }}</td></tr>
                        <tr><th>Shipper:</th><td>{{ $booking->shipper }}</td></tr>
                        <tr><th>Consignee:</th><td>{{ $booking->Consignee }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">Transport & Port</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="40%">Origin:</th><td>{{ $booking->origin }}</td></tr>
                        <tr><th>Destination:</th><td>{{ $booking->Destination }}</td></tr>
                        <tr><th>Vessel:</th><td>{{ $booking->Vessel }}</td></tr>
                        <tr><th>IATA:</th><td>{{ $booking->IATA }}</td></tr>
                        <tr><th>ETD:</th><td>{{ $booking->ETD }}</td></tr>
                        <tr><th>ETA:</th><td>{{ $booking->ETA }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">Manifest Details</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="40%">MAWB / MBL:</th><td>{{ $booking->MAWB_MBL }}</td></tr>
                        <tr><th>HAWB / HBL:</th><td>{{ $booking->HAWB_HBL }}</td></tr>
                        <tr><th>Line:</th><td>{{ $booking->Line }}</td></tr>
                        <tr><th>IGM / EGM:</th><td>{{ $booking->IGM_EGM }}</td></tr>
                        <tr><th>Pieces:</th><td>{{ $booking->Pieces }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">Physical Metrics</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="40%">CBM:</th><td>{{ $booking->CBM }}</td></tr>
                        <tr><th>Gross Weight:</th><td>{{ $booking->GrWeight }}</td></tr>
                        <tr><th>Charge Weight:</th><td>{{ $booking->ChWeight }}</td></tr>
                        <tr><th>Volume:</th><td>{{ $booking->Volume }}</td></tr>
                        <tr><th>FCL:</th><td>{{ $booking->FCL }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary mb-3">Billing & Misc</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="50%">S/B No:</th><td>{{ $booking->SBNo }}</td></tr>
                                <tr><th>S/B Date:</th><td>{{ $booking->SBDate }}</td></tr>
                                <tr><th>Shipper Invoice:</th><td>{{ $booking->ShipperInvoice }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="50%">TOS:</th><td>{{ $booking->TOS }}</td></tr>
                                <tr><th>IEC:</th><td>{{ $booking->IEC }}</td></tr>
                                <tr><th>OOC:</th><td>{{ $booking->OOC }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="50%">Sales Rep:</th><td>{{ $booking->SalesRep }}</td></tr>
                                <tr><th>Reference:</th><td>{{ $booking->Reference }}</td></tr>
                                <tr><th>Audit:</th><td>{{ $booking->CreateBy }} on {{ $booking->CreateDate }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0">Record of Expenses</h4>
            <a href="{{ route('expenses.create', ['JobNo' => $booking->BookingNo]) }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle me-1"></i> Add Expense
            </a>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Date</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">CompanyName</th>
                            <th class="py-3">Description</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Curr.</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($booking->expenses as $expense)
                            <tr>
                                <td class="py-3">{{ $expense->Date ? \Carbon\Carbon::parse($expense->Date)->format('d-m-Y') : 'N/A' }}</td>
                                <td class="py-3"><span class="badge bg-soft-primary text-primary border border-primary">{{ $expense->Category }}</span></td>
                                <td class="py-3">{{ $expense->CompanyName }}</td>
                                <td class="py-3 small text-muted">{{ Str::limit($expense->Description, 50) }}</td>
                                <td class="py-3 fw-bold text-danger">{{ number_format($expense->Total, 2) }}</td>
                                <td class="py-3 small text-uppercase">{{ $expense->Currency }}</td>
                                <td class="py-3">
                                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-sm btn-link text-info p-0 me-2" title="View Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-link text-primary p-0" title="Edit Expense">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted small">
                                    No expenses recorded for this job.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($booking->expenses->count() > 0)
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end py-3">Total Job Expenses:</th>
                                <th class="py-3 text-danger fw-bold" colspan="3">
                                    {{ number_format($booking->expenses->sum('Total'), 2) }}
                                    <span class="small fw-normal ms-1">{{ $booking->expenses->first()->Currency ?? 'INR' }}</span>
                                </th>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
