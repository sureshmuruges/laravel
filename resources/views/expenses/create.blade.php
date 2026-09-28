@extends('expenses.layout')

@section('content')
<div class="px-2">
    <div class="mb-4 d-flex justify-content-between">
        <h2 class="h3 font-weight-bold text-dark mb-0">Record New Expense</h2>
    </div>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        
        {{-- Hidden CCode as requested --}}
        <input type="hidden" name="CCode" value="1">

        <div class="row g-4 mb-4">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Basic Information</h5>
            
            <div class="col-md-4">
                <label for="Category" class="form-label fw-semibold">Category</label>
                <select name="Category" id="Category" class="form-select shadow-sm">
                    <option value="">Select Category</option>
                    <option value="Transportation">Transportation</option>
                    <option value="Food & Dining">Food & Dining</option>
                    <option value="Office Supplies">Office Supplies</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Miscellaneous">Miscellaneous</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="Date" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" name="Date" id="Date" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="col-md-4">
                <label for="JobNo" class="form-label fw-semibold">Job No <span class="text-danger">*</span></label>
                <select name="JobNo" id="JobNo" class="form-select shadow-sm" required>
                    <option value="">Select Job No</option>
                    @foreach($bookings as $bookingNo)
                        <option value="{{ $bookingNo }}" {{ request('JobNo') == $bookingNo ? 'selected' : '' }}>{{ $bookingNo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Company & Reference</h5>

            <div class="col-md-4">
                <label for="CompanyName" class="form-label">Company Name</label>
                <input type="text" name="CompanyName" id="CompanyName" class="form-control shadow-sm" placeholder="Enter Company Name">
            </div>

            <div class="col-md-4">
                <label for="Reference" class="form-label">Reference</label>
                <input type="text" name="Reference" id="Reference" class="form-control shadow-sm" placeholder="Enter Reference No">
            </div>

            <div class="col-md-4">
                <label for="AccountCode" class="form-label">Account Code</label>
                <input type="text" name="AccountCode" id="AccountCode" class="form-control shadow-sm" placeholder="Enter Account Code">
            </div>

            <div class="col-md-6">
                <label for="MAWB_MBL" class="form-label">MAWB / MBL</label>
                <input type="text" name="MAWB_MBL" id="MAWB_MBL" class="form-control shadow-sm">
            </div>

            <div class="col-md-6">
                <label for="Month" class="form-label">Month</label>
                <input type="text" name="Month" id="Month" class="form-control shadow-sm" value="{{ date('F') }}">
            </div>
        </div>

        <div class="row g-4 mb-4">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Billing Details</h5>

            <div class="col-md-3">
                <label for="Currency" class="form-label">Currency</label>
                <input type="text" name="Currency" id="Currency" class="form-control shadow-sm" value="INR">
            </div>

            <div class="col-md-3">
                <label for="ExRate" class="form-label">Exchange Rate</label>
                <input type="number" step="0.01" name="ExRate" id="ExRate" class="form-control shadow-sm" value="1.00">
            </div>

            <div class="col-md-3">
                <label for="Total" class="form-label">Total Amount <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="Total" id="Total" class="form-control shadow-sm" required>
            </div>
            
            <div class="col-md-12">
                <label for="Description" class="form-label">Description</label>
                <textarea name="Description" id="Description" class="form-control shadow-sm" rows="3" placeholder="Additional details..."></textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 pt-3 mb-5">
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary px-5">Cancel</a>
            <button type="submit" class="btn btn-primary px-5 shadow">Save Expense</button>
        </div>
    </form>
</div>
@endsection
