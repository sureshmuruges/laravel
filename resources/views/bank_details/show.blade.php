@extends('bank_details.layout')

@section('content')
    <div class="px-4 py-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h3 class="h5 font-weight-bold text-dark mb-0">{{ $bankDetail->bank_name }}</h3>
            <a href="{{ route('bank_details.edit', $bankDetail->id) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-pencil me-1"></i> Edit</a>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width: 180px;">Account Holder</th><td>{{ $bankDetail->account_name ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Account Number</th><td class="fw-bold">{{ $bankDetail->account_number }}</td></tr>
                    <tr><th class="text-muted">IFSC</th><td>{{ $bankDetail->ifsc ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">SWIFT</th><td>{{ $bankDetail->swift ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">Branch</th><td>{{ $bankDetail->branch ?? 'N/A' }}</td></tr>
                    <tr><th class="text-muted">UPI ID</th><td>{{ $bankDetail->upi_id ?? 'N/A' }}</td></tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td>
                            @if ($bankDetail->is_default)
                                <span class="badge bg-primary">Default</span>
                            @endif
                            <span class="badge {{ $bankDetail->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $bankDetail->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 text-center">
                @if ($bankDetail->upi_qr_path)
                    <div class="text-muted small mb-2">Scan to Pay</div>
                    <img src="{{ asset($bankDetail->upi_qr_path) }}" alt="UPI QR" class="img-thumbnail" style="max-height: 180px;">
                @else
                    <div class="text-muted small"><i class="bi bi-qr-code" style="font-size: 32px;"></i><br>No QR uploaded</div>
                @endif
            </div>
        </div>
    </div>
@endsection
