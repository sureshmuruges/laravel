@extends('particulars.layout')

@section('content')
    <div
        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
        <h2 class="h3 font-weight-bold text-dark mb-0">Particulars Management</h2>

        <form action="{{ route('particulars.index') }}" method="GET" class="d-flex align-items-center">
            <input type="text" name="search" class="form-control me-2" placeholder="Search..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>

        <div class="d-flex gap-2">
            <a href="{{ route('particulars.export', ['search' => request('search')]) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
            </a>
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('particulars.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Particular
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            @php
                                $sortLink = function ($field, $label) use ($sortField, $sortDirection) {
                                    $nextDir = ($sortField === $field && $sortDirection === 'asc') ? 'desc' : 'asc';
                                    $icon = $sortField === $field ? ($sortDirection === 'asc' ? 'up' : 'down') : 'down';
                                    $url = route('particulars.index', ['sort' => $field, 'direction' => $nextDir, 'search' => request('search')]);
                                    return '<a href="' . $url . '" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">' . $label . ' <i class="bi bi-sort-' . ($icon === 'up' ? 'up' : 'down') . '-alt"></i></a>';
                                };
                            @endphp
                            <th class="py-3 px-3">{!! $sortLink('id', 'Id') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('particulars', 'Particulars') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('hsn', 'HSN') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('gst', 'GST') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('igst', 'IGST') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('cgst', 'CGST') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('sgst', 'SGST') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('except_particulars', 'Except Particular') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('is_service', 'IsService') !!}</th>
                            <th class="py-3 px-3">{!! $sortLink('active', 'Active') !!}</th>
                            <th class="py-3 px-3">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($particulars as $particular)
                            <tr>
                                <td class="py-3 px-3">{{ $particular->id }}</td>
                                <td class="py-3 px-3">{{ $particular->particulars }}</td>
                                <td class="py-3 px-3">{{ $particular->hsn }}</td>
                                <td class="py-3 px-3">{{ $particular->gst }}</td>
                                <td class="py-3 px-3">{{ $particular->igst }}</td>
                                <td class="py-3 px-3">{{ $particular->cgst }}</td>
                                <td class="py-3 px-3">{{ $particular->sgst }}</td>
                                <td class="py-3 px-3">{{ $particular->except_particulars ? 'Y' : 'N' }}</td>
                                <td class="py-3 px-3">{{ $particular->is_service === 'Y' ? 'Yes' : 'No' }}</td>
                                <td class="py-3 px-3">{{ $particular->active === 'Y' ? 'Yes' : 'No' }}</td>
                                <td class="py-3 px-3 text-nowrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('particulars.show', $particular->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(Auth::user()->role === 'super_admin')
                                            <a href="{{ route('particulars.edit', $particular->id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <form action="{{ route('particulars.destroy', $particular->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="return confirm('Are you sure?')" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 mt-md-4">
        {!! $particulars->appends(['search' => request('search'), 'sort' => $sortField, 'direction' => $sortDirection])->links() !!}
    </div>
@endsection
