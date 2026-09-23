@extends('company_details.layout')

@section('content')
    <div class="px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h5 font-weight-bold text-dark mb-0">Manage Company Profiles</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('company_details.export') }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
                </a>
                <a href="{{ route('company_details.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add Company Profile
                </a>
            </div>
        </div>

        <div class="table-responsive">
            @php
                if (!function_exists('sortIcon')) {
                    function sortIcon($field)
                    {
                        $sortField = request('sort', 'id');
                        $sortDirection = request('direction', 'desc');
                        if ($sortField === $field) {
                            return $sortDirection === 'asc' ? ' ↑' : ' ↓';
                        }
                        return '';
                    }
                }
                if (!function_exists('sortUrl')) {
                    function sortUrl($field)
                    {
                        $sortField = request('sort', 'id');
                        $sortDirection = request('direction', 'desc');
                        $direction = ($sortField === $field && $sortDirection === 'asc') ? 'desc' : 'asc';
                        return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
                    }
                }
            @endphp
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-3">Logo</th>
                        <th class="py-3 px-3"><a href="{{ sortUrl('company_name') }}" class="text-dark text-decoration-none fw-bold">Company Name{!! sortIcon('company_name') !!}</a></th>
                        <th class="py-3 px-3">
                            <a href="{{ sortUrl('email') }}" class="text-dark text-decoration-none fw-bold">Email{!! sortIcon('email') !!}</a>
                            /
                            <a href="{{ sortUrl('telephone') }}" class="text-dark text-decoration-none fw-bold">Phone{!! sortIcon('telephone') !!}</a>
                        </th>
                        <th class="py-3 px-3">
                            <a href="{{ sortUrl('state_code') }}" class="text-dark text-decoration-none fw-bold">State Code{!! sortIcon('state_code') !!}</a>
                            /
                            <a href="{{ sortUrl('gst_number') }}" class="text-dark text-decoration-none fw-bold">GST{!! sortIcon('gst_number') !!}</a>
                        </th>
                        <th class="py-3 px-3">
                            <a href="{{ sortUrl('pan') }}" class="text-dark text-decoration-none fw-bold">PAN{!! sortIcon('pan') !!}</a>
                            /
                            <a href="{{ sortUrl('tan') }}" class="text-dark text-decoration-none fw-bold">TAN{!! sortIcon('tan') !!}</a>
                        </th>
                        <th class="py-3 px-3"><a href="{{ sortUrl('is_active') }}" class="text-dark text-decoration-none fw-bold">Status{!! sortIcon('is_active') !!}</a></th>
                        <th class="py-3 px-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companyDetails as $detail)
                        <tr>
                            <td class="py-3 px-3">
                                @if($detail->logo_path)
                                    <img src="{{ asset($detail->logo_path) }}" alt="{{ $detail->company_name }} Logo" class="img-thumbnail" style="max-height: 50px; max-width: 80px; object-fit: contain;">
                                @else
                                    <span class="text-muted" style="font-size: 11px;">No Logo</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <div class="fw-bold text-dark">{{ $detail->company_name }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div style="font-size: 12px; line-height: 1.4;">
                                    <strong>Email:</strong> {{ $detail->email ?? 'N/A' }}<br>
                                    <strong>Phone:</strong> {{ $detail->telephone ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div style="font-size: 12px; line-height: 1.4;">
                                    <strong>State Code:</strong> {{ $detail->state_code ?? 'N/A' }}<br>
                                    <strong>GSTIN:</strong> {{ $detail->gst_number ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div style="font-size: 12px; line-height: 1.4;">
                                    <strong>PAN:</strong> {{ $detail->pan ?? 'N/A' }}<br>
                                    <strong>TAN:</strong> {{ $detail->tan ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                @if($detail->is_active)
                                    <span class="badge bg-success text-white px-2 py-1.5 rounded" style="font-size: 11px;"><i class="bi bi-patch-check-fill me-1"></i>Active</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1.5 rounded" style="font-size: 11px;">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('company_details.show', $detail->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('company_details.edit', $detail->id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('company_details.destroy', $detail->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this company profile?');"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($companyDetails->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-building-slash display-6 d-block mb-3"></i>
                                No company profiles created yet. Click "Add Company Profile" to get started.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
