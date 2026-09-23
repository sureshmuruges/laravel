@extends('expenses.layout')

@section('content')
    <div class="px-2">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <h2 class="h3 font-weight-bold text-dark mb-0">Expense List</h2>

            <form action="{{ route('expenses.index') }}" method="GET" class="d-flex align-items-center w-100 w-md-auto">
                <div class="input-group shadow-sm">
                    <input type="text" name="search" class="form-control" placeholder="Search Expense..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="bi bi-search">Search</i>
                    </button>
                </div>
            </form>

            <a href="{{ route('expenses.export', ['search' => request('search')]) }}" class="btn btn-outline-success px-4 shadow">
                <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
            </a>

            <a href="{{ route('expenses.create') }}" class="btn btn-success px-4 shadow">
                <i class="bi bi-plus-circle me-1"></i> New Expense
            </a>
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

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3"><a href="{{ sortUrl('id') }}"
                                class="text-dark text-decoration-none fw-bold">ID{!! sortIcon('id') !!}</a></th>
                        <th class="py-3"><a href="{{ sortUrl('Date') }}"
                                class="text-dark text-decoration-none fw-bold">Date{!! sortIcon('Date') !!}</a></th>
                        <th class="py-3"><a href="{{ sortUrl('JobNo') }}" class="text-dark text-decoration-none fw-bold">Job
                                No{!! sortIcon('JobNo') !!}</a></th>
                        <th class="py-3"><a href="{{ sortUrl('CompanyName') }}"
                                class="text-dark text-decoration-none fw-bold">Company{!! sortIcon('CompanyName') !!}</a>
                        </th>
                        <th class="py-3"><a href="{{ sortUrl('Total') }}"
                                class="text-dark text-decoration-none fw-bold">Total{!! sortIcon('Total') !!}</a></th>
                        <th class="py-3"><a href="{{ sortUrl('Currency') }}"
                                class="text-dark text-decoration-none fw-bold">Curr{!! sortIcon('Currency') !!}</a></th>
                        <th class="py-3"><a href="{{ sortUrl('Reference') }}"
                                class="text-dark text-decoration-none fw-bold">Reference{!! sortIcon('Reference') !!}</a></th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr class="table-row">
                            <td class="py-3">{{ $expense->id }}</td>
                            <td class="py-3">
                                {{ $expense->Date ? \Carbon\Carbon::parse($expense->Date)->format('d-m-Y') : 'N/A' }}</td>
                            <td class="py-3 fw-bold text-primary">{{ $expense->JobNo }}</td>
                            <td class="py-3 text-truncate" style="max-width: 150px;">{{ $expense->CompanyName }}</td>
                            <td class="py-3 fw-bold">{{ number_format($expense->Total, 2) }}</td>
                            <td class="py-3">{{ $expense->Currency }}</td>
                            <td class="py-3">{{ $expense->Reference }}</td>
                            <td class="py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                        onsubmit="return confirm('Confirm delete?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-cash-stack fs-1 d-block mb-3 text-secondary"></i>
                                No expenses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
@endsection