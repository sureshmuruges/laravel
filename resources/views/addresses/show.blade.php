@extends('addresses.layout')

@section('content')
    <div class="px-3 py-3">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h2 class="h4 font-weight-bold text-dark mb-0">Address Details: {{ $address->CompanyName }}</h2>
                <a href="{{ route('addresses.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Basic Info</h6>
                        <div class="p-3 bg-light rounded shadow-sm">
                            <div class="mb-2"><strong>Company Code:</strong> {{ $address->AccountCode }}</div>
                            <div class="mb-2"><strong>Company Name:</strong> {{ $address->CompanyName }}</div>
                            <div class="mb-2"><strong>User Type:</strong> <span
                                    class="badge bg-secondary text-capitalize">{{ $address->Type }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Location Information</h6>
                        <div class="p-3 bg-light rounded shadow-sm">
                            <div class="mb-2"><strong>Address:</strong> {{ $address->ALine1 }}
                                {{ $address->ALine2 ? ', ' . $address->ALine2 : '' }}
                            </div>
                            <div class="mb-2"><strong>Location:</strong> {{ $address->Location }}</div>
                            <div class="mb-2"><strong>Pincode / Zip:</strong> {{ $address->Pincode }}</div>
                            <div class="mb-2"><strong>State:</strong> {{ $address->State }} ({{ $address->StateCode }})
                            </div>
                            <div class="mb-2"><strong>Country:</strong> {{ $address->Country }}</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Compliance & Credit</h6>
                        <div class="p-3 bg-light rounded shadow-sm">
                            <div class="mb-2"><strong>GST No:</strong> {{ $address->GSTNo }}</div>
                            <div class="mb-2"><strong>PAN:</strong> {{ $address->PAN }}</div>
                            <div class="mb-2"><strong>Credit Days:</strong> <span
                                    class="badge bg-info text-dark">{{ $address->CreditDays }} days</span></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Contact Details</h6>
                        <div class="p-3 bg-light rounded shadow-sm">
                            <div class="mb-2"><strong>Contact Person:</strong> {{ $address->ContactName }}</div>
                            <div class="mb-2"><strong>Phone:</strong> {{ $address->Phone }}</div>
                            <div class="mb-2"><strong>Email:</strong> {{ $address->Email }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Audit Logs</h6>
                        <div class="p-3 bg-light rounded shadow-sm d-flex gap-4">
                            <div><strong>Created By:</strong> {{ $address->CreateBy ?: 'System' }}</div>
                            <div><strong>Created At:</strong> {{ $address->CreateDate }}</div>
                            @if($address->ModifyBy)
                                <div><strong>Modified By:</strong> {{ $address->ModifyBy }}</div>
                                <div><strong>Modified At:</strong> {{ $address->ModifyDate }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-top pt-4">
                    <a href="{{ route('addresses.edit', $address->Id) }}" class="btn btn-primary px-4 me-2">
                        Edit
                    </a>
                    <form action="{{ route('addresses.destroy', $address->Id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this address?');"
                        style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-4">
                            <i class="bi bi-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection