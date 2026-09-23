@extends('particulars.layout')

@section('content')
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
        <h2 class="h3 font-weight-bold text-dark mb-0">Particulars</h2>
        <a href="{{ route('particulars.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="mb-3 border-bottom pb-2">
                        <strong class="font-weight-bold text-dark">CCode:</strong>
                        <span class="text-dark">{{ $particular->c_code }}</span>
                    </div>
                    <div class="mb-3 border-bottom pb-2">
                        <strong class="font-weight-bold text-dark">Particulars:</strong>
                        <span class="text-dark">{{ $particular->particulars }}</span>
                    </div>
                    <div class="mb-3 border-bottom pb-2">
                        <strong class="font-weight-bold text-dark">HSN:</strong>
                        <span class="text-dark">{{ $particular->hsn }}</span>
                    </div>

                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-6 col-md-3"><strong>GST:</strong> {{ $particular->gst }}</div>
                        <div class="col-6 col-md-3"><strong>IGST:</strong> {{ $particular->igst }}</div>
                        <div class="col-6 col-md-3"><strong>CGST:</strong> {{ $particular->cgst }}</div>
                        <div class="col-6 col-md-3"><strong>SGST:</strong> {{ $particular->sgst }}</div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-12 col-md-4">
                            <strong>Except Particular:</strong>
                            <span class="badge {{ $particular->except_particulars ? 'bg-success' : 'bg-secondary' }}">
                                {{ $particular->except_particulars ? 'Y' : 'N' }}
                            </span>
                        </div>
                        <div class="col-12 col-md-4">
                            <strong>IS Service:</strong>
                            <span class="badge {{ $particular->is_service === 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $particular->is_service === 'Y' ? 'Y' : 'N' }}
                            </span>
                        </div>
                        <div class="col-12 col-md-4">
                            <strong>Active:</strong>
                            <span class="badge {{ $particular->active === 'Y' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $particular->active === 'Y' ? 'Y' : 'N' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
