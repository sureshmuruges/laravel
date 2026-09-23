@extends('company_details.layout')

@section('content')
    <div class="px-4 py-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div>
                <h3 class="h5 font-weight-bold text-dark mb-1">Company Profile Details</h3>
                <p class="text-muted small mb-0">View full configuration details for this company profile.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('company_details.edit', $companyDetail->id) }}" class="btn btn-warning text-white btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit Profile
                </a>
                <form action="{{ route('company_details.destroy', $companyDetail->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this company profile?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Logo Section -->
            <div class="col-md-4 text-center">
                <div class="card bg-light border p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 11px; letter-spacing: 1px;">Company Logo</h6>
                    @if($companyDetail->logo_path)
                        <img src="{{ asset($companyDetail->logo_path) }}" alt="{{ $companyDetail->company_name }} Logo" class="img-fluid rounded shadow-sm bg-white p-2" style="max-height: 150px; object-fit: contain;">
                    @else
                        <div class="text-muted py-4">
                            <i class="bi bi-building display-3 mb-2 d-block"></i>
                            <span>No Logo Uploaded</span>
                        </div>
                    @endif

                    <div class="mt-4">
                        @if($companyDetail->is_active)
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fs-7"><i class="bi bi-patch-check-fill me-1"></i>Active Profile</span>
                        @else
                            <span class="badge bg-secondary text-white px-3 py-2 rounded-pill fs-7">Inactive Profile</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="col-md-8">
                <div class="card h-100 border">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title text-dark fw-bold mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 border-bottom pb-2">
                                <span class="text-muted small d-block">Company Name</span>
                                <span class="fw-bold text-dark fs-5">{{ $companyDetail->company_name }}</span>
                            </div>
                            <div class="col-12 border-bottom pb-2">
                                <span class="text-muted small d-block">Address</span>
                                <span class="text-dark" style="white-space: pre-line;">{{ $companyDetail->address }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">Email Address</span>
                                <span class="text-dark">{{ $companyDetail->email ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">Telephone / Phone</span>
                                <span class="text-dark">{{ $companyDetail->telephone ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">State Code</span>
                                <span class="text-dark">{{ $companyDetail->state_code ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">GST Number</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->gst_number ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">PAN Number</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->pan ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">TAN Number</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->tan ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">Account Code Prefix</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->company_code_enabled ? ($companyDetail->company_code_prefix ?? 'CEH') : 'AO (default)' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">Booking / Job Code Prefix</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->booking_code_prefix ?: 'Year-based (default)' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">Tax Invoice Prefix</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->tax_invoice_prefix ?? 'CSHL' }}</span>
                            </div>
                            <div class="col-md-6 border-bottom pb-2">
                                <span class="text-muted small d-block">IRN & QR Code on PDF</span>
                                <span class="text-dark fw-semibold">{{ $companyDetail->irn_qr_enabled ? 'Enabled' : 'Disabled' }}</span>
                            </div>
                            <div class="col-12 border-bottom pb-2">
                                <span class="text-muted small d-block">Terms & Conditions</span>
                                <span class="text-dark" style="white-space: pre-line;">{{ $companyDetail->terms_conditions ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
