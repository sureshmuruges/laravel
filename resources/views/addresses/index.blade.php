@extends('addresses.layout')

@section('content')
    <div class="px-3 py-3">

        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
            <h2 class="h3 font-weight-bold text-dark mb-0">Address Management</h2>

            <form action="{{ route('addresses.index') }}" method="GET" class="d-flex align-items-center">
                <input type="text" name="search" class="form-control me-2" placeholder="Search Company or ACode..."
                    value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary">Search</button>
            </form>

            <div class="d-flex gap-2">
                <a href="{{ route('addresses.export', ['search' => request('search')]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
                </a>
                <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create New Address
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    @php
                        if (!function_exists('sortIcon')) {
                            function sortIcon($field)
                            {
                                $sortField = request('sort', 'Id');
                                $sortDirection = request('direction', 'desc');
                                if ($sortField === $field) {
                                    return $sortDirection === 'asc' ? '↑' : '↓';
                                }
                                return '';
                            }
                        }
                        if (!function_exists('sortUrl')) {
                            function sortUrl($field)
                            {
                                $sortField = request('sort', 'Id');
                                $sortDirection = request('direction', 'desc');
                                $direction = ($sortField === $field && $sortDirection === 'asc') ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]);
                            }
                        }
                    @endphp
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-3"><a href="{{ sortUrl('Id') }}"
                                        class="text-dark text-decoration-none">Id {{ sortIcon('Id') }}</a></th>
                                <th class="py-3 px-3"><a href="{{ sortUrl('AccountCode') }}"
                                        class="text-dark text-decoration-none">Company Code {{ sortIcon('AccountCode') }}</a></th>
                                <th class="py-3 px-3"><a href="{{ sortUrl('CompanyName') }}"
                                        class="text-dark text-decoration-none">Company Name
                                        {{ sortIcon('CompanyName') }}</a></th>
                                <th class="py-3 px-3"><a href="{{ sortUrl('Country') }}"
                                        class="text-dark text-decoration-none">Country {{ sortIcon('Country') }}</a></th>
                                <th class="py-3 px-3"><a href="{{ sortUrl('State') }}"
                                        class="text-dark text-decoration-none">State {{ sortIcon('State') }}</a></th>
                                <th class="py-3 px-3"><a href="{{ sortUrl('GSTNo') }}"
                                        class="text-dark text-decoration-none">GST No {{ sortIcon('GSTNo') }}</a></th>
                                <th class="py-3 px-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($addresses as $address)
                                <tr>
                                    <td class="py-3 px-3">{{ $address->Id }}</td>
                                    <td class="py-3 px-3">{{ $address->AccountCode }}</td>
                                    <td class="py-3 px-3">{{ $address->CompanyName }}</td>
                                    <td class="py-3 px-3">{{ $address->Country }}</td>
                                    <td class="py-3 px-3">{{ $address->State }}</td>
                                    <td class="py-3 px-3">{{ $address->GSTNo }}</td>
                                    <td class="py-3 px-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('addresses.show', $address->Id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('addresses.edit', $address->Id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('addresses.destroy', $address->Id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this address?');"
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
                            @if($addresses->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No addresses found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3 mt-md-4">
            {!! $addresses->links() !!}
        </div>
    </div>
@endsection