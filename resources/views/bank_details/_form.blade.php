@php
    $bank = $bankDetail ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label for="bank_name" class="form-label fw-semibold text-dark">Bank Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $bank?->bank_name) }}" placeholder="e.g. Kotak Mahindra Bank" required>
        @error('bank_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="account_name" class="form-label fw-semibold text-dark">Account Holder Name</label>
        <input type="text" class="form-control @error('account_name') is-invalid @enderror" id="account_name" name="account_name" value="{{ old('account_name', $bank?->account_name) }}" placeholder="e.g. AO LOGISTICS">
        @error('account_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="account_number" class="form-label fw-semibold text-dark">Account Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number', $bank?->account_number) }}" placeholder="e.g. 6450907494" required>
        @error('account_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="ifsc" class="form-label fw-semibold text-dark">IFSC</label>
        <input type="text" class="form-control text-uppercase @error('ifsc') is-invalid @enderror" id="ifsc" name="ifsc" value="{{ old('ifsc', $bank?->ifsc) }}" placeholder="e.g. KKBK0008045">
        @error('ifsc')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="swift" class="form-label fw-semibold text-dark">SWIFT Code</label>
        <input type="text" class="form-control text-uppercase @error('swift') is-invalid @enderror" id="swift" name="swift" value="{{ old('swift', $bank?->swift) }}" placeholder="e.g. KKBKINBBCPC">
        @error('swift')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="branch" class="form-label fw-semibold text-dark">Branch</label>
        <input type="text" class="form-control @error('branch') is-invalid @enderror" id="branch" name="branch" value="{{ old('branch', $bank?->branch) }}" placeholder="e.g. Sahakara Nagar, Bengaluru - 560092">
        @error('branch')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="upi_id" class="form-label fw-semibold text-dark">UPI ID</label>
        <input type="text" class="form-control @error('upi_id') is-invalid @enderror" id="upi_id" name="upi_id" value="{{ old('upi_id', $bank?->upi_id) }}" placeholder="e.g. 9611570671@kotak">
        @error('upi_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="upi_qr" class="form-label fw-semibold text-dark">UPI "Scan to Pay" QR Image</label>
        <input type="file" class="form-control @error('upi_qr') is-invalid @enderror" id="upi_qr" name="upi_qr" accept="image/*">
        <div class="form-text">Shown next to the bank details on the invoice PDF. Leave empty to hide the QR. Max size: 2MB.</div>
        @error('upi_qr')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($bank?->upi_qr_path)
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remove_upi_qr" name="remove_upi_qr" value="1">
                <label class="form-check-label text-danger" for="remove_upi_qr">Remove current QR image</label>
            </div>
        @endif
    </div>

    <div class="col-md-4 text-center">
        <div class="border rounded p-2 d-flex flex-column align-items-center justify-content-center" style="height: 100%; min-height: 100px; background-color: #fafafa;">
            <span class="text-muted small mb-1 d-block">Current QR</span>
            @if ($bank?->upi_qr_path)
                <img src="{{ asset($bank->upi_qr_path) }}" alt="UPI QR" class="img-fluid" style="max-height: 80px; object-fit: contain;">
            @else
                <span class="text-muted small"><i class="bi bi-qr-code" style="font-size: 24px;"></i><br>No QR uploaded</span>
            @endif
        </div>
    </div>

    <div class="col-12">
        <div class="p-3 border rounded d-flex flex-wrap gap-4" style="background-color: #f8f9fa;">
            <div class="form-check form-switch">
                <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="is_default" name="is_default" value="1" {{ old('is_default', $bank?->is_default) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold text-dark" for="is_default">Default bank on new invoices</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input ms-0 me-2" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $bank?->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold text-dark" for="is_active">Active (show in invoice bank dropdown)</label>
            </div>
        </div>
    </div>
</div>
