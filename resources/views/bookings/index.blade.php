@extends('bookings.layout')

@section('content')
    <div class="px-2">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <h2 class="h3 font-weight-bold text-dark mb-0">Bookings List</h2>

            <form action="{{ route('bookings.index') }}" method="GET" class="d-flex align-items-center w-100 w-md-auto">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search Bookings..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-3">
                        <i class="bi bi-search">Search</i>
                    </button>
                </div>
            </form>

            <a href="{{ route('bookings.export', ['search' => request('search')]) }}" class="btn btn-outline-success px-4">
                <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
            </a>

            <a href="{{ route('bookings.create') }}" class="btn btn-success px-4">
                <i class="bi bi-plus-circle me-1"></i> New Booking
            </a>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3"><x-sort-link field="Id" default="Id">ID</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="BookingNo" default="Id">Booking No</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="booking_date" default="Id">Date</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="companyname" default="Id">Company</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="shipper" default="Id">Shipper</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="origin" default="Id">Origin</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="Destination" default="Id">Dest.</x-sort-link></th>
                        <th class="py-3"><x-sort-link field="Reference" default="Id">Ref</x-sort-link></th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="py-3">{{ $booking->Id }}</td>
                            <td class="py-3 fw-bold text-primary">{{ $booking->BookingNo }}</td>
                            <td class="py-3">{{ $booking->booking_date }}</td>
                            <td class="py-3 text-truncate" style="max-width: 150px;">{{ $booking->companyname }}</td>
                            <td class="py-3 text-truncate" style="max-width: 150px;">{{ $booking->shipper }}</td>
                            <td class="py-3">{{ $booking->origin }}</td>
                            <td class="py-3">{{ $booking->Destination }}</td>
                            <td class="py-3">{{ $booking->Reference }}</td>
                            <td class="py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <a href="{{ route('bookings.show', $booking->Id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('bookings.edit', $booking->Id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('bookings.destroy', $booking->Id) }}" method="POST"
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
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection