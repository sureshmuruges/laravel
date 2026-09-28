@extends('bank_details.layout')

@section('content')
    <div class="px-4 py-4">
        <div class="border-bottom pb-3 mb-4">
            <h3 class="h5 font-weight-bold text-dark mb-1">Edit Bank Account</h3>
            <p class="text-muted small mb-0">Changes apply to every invoice PDF that uses this bank account.</p>
        </div>

        <form action="{{ route('bank_details.update', $bankDetail->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('bank_details._form')

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('bank_details.index') }}" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Update Bank Account</button>
            </div>
        </form>
    </div>
@endsection
