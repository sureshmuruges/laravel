@extends('bank_details.layout')

@section('content')
    <div class="px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h5 font-weight-bold text-dark mb-0">Manage Bank Accounts</h3>
            <a href="{{ route('bank_details.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Bank Account
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-3"><x-sort-link field="bank_name">Bank Name</x-sort-link></th>
                        <th class="py-3 px-3"><x-sort-link field="account_number">A/c No</x-sort-link></th>
                        <th class="py-3 px-3"><x-sort-link field="ifsc">IFSC</x-sort-link></th>
                        <th class="py-3 px-3"><x-sort-link field="swift">SWIFT</x-sort-link></th>
                        <th class="py-3 px-3"><x-sort-link field="branch">Branch</x-sort-link></th>
                        <th class="py-3 px-3"><x-sort-link field="upi_id">UPI</x-sort-link></th>
                        <th class="py-3 px-3"><x-sort-link field="is_active">Status</x-sort-link></th>
                        <th class="py-3 px-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bankDetails as $bank)
                        <tr>
                            <td class="py-3 px-3">
                                <div class="fw-bold text-dark">{{ $bank->bank_name }}</div>
                                @if ($bank->account_name)
                                    <div class="text-muted" style="font-size: 12px;">{{ $bank->account_name }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 fw-semibold">{{ $bank->account_number }}</td>
                            <td class="py-3 px-3">{{ $bank->ifsc ?? 'N/A' }}</td>
                            <td class="py-3 px-3">{{ $bank->swift ?? 'N/A' }}</td>
                            <td class="py-3 px-3" style="font-size: 12px;">{{ $bank->branch ?? 'N/A' }}</td>
                            <td class="py-3 px-3">{{ $bank->upi_id ?? 'N/A' }}</td>
                            <td class="py-3 px-3">
                                @if ($bank->is_default)
                                    <span class="badge bg-primary text-white px-2 py-1.5 rounded" style="font-size: 11px;"><i class="bi bi-star-fill me-1"></i>Default</span>
                                @endif
                                @if ($bank->is_active)
                                    <span class="badge bg-success text-white px-2 py-1.5 rounded" style="font-size: 11px;">Active</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1.5 rounded" style="font-size: 11px;">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('bank_details.show', $bank->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('bank_details.edit', $bank->id) }}" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('bank_details.destroy', $bank->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this bank account?');"
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-bank display-6 d-block mb-3"></i>
                                No bank accounts added yet. Click "Add Bank Account" to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
