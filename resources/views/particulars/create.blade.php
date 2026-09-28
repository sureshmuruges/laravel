@extends('particulars.layout')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
    <h2 class="h3 font-weight-bold text-dark mb-0">Add New Particular</h2>
    <a href="{{ route('particulars.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <strong class="font-weight-bold">Whoops!</strong>
        <span>There were some problems with your input.</span>
        <ul class="mt-2 mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('particulars.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Particulars <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="particulars" id="particulars" value="{{ old('particulars') }}" maxlength="150" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">HSN<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="hsn" id="hsn" value="{{ old('hsn') }}" inputmode="numeric" pattern="[0-9]*" maxlength="10" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">GST</label>
                            <input type="text" inputmode="decimal" class="form-control" name="gst" id="gst" value="{{ old('gst', '0') }}" placeholder="Enter GST %">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">IGST</label>
                            <input type="text" class="form-control" name="igst" id="igst" value="{{ old('igst', '0') }}" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">CGST</label>
                            <input type="text" class="form-control" name="cgst" id="cgst" value="{{ old('cgst', '0') }}" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">SGST</label>
                            <input type="text" class="form-control" name="sgst" id="sgst" value="{{ old('sgst', '0') }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="except_particulars" value="1" id="except_particulars">
                                <label class="form-check-label" for="except_particulars">Except Particular</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold d-block">IS Service</label>
                            <select class="form-select" name="is_service">
                                <option value="Y" {{ old('is_service', 'Y') == 'Y' ? 'selected' : '' }}>Yes</option>
                                <option value="N" {{ old('is_service') == 'N' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold d-block">Active</label>
                            <select class="form-select" name="active">
                                <option value="Y" {{ old('active', 'Y') == 'Y' ? 'selected' : '' }}>Yes</option>
                                <option value="N" {{ old('active') == 'N' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-check-circle me-1"></i> Submit
                        </button>
                    </div>
                </form>

                <script>
                    document.getElementById('hsn').addEventListener('input', function () {
                        this.value = this.value.replace(/\D/g, '').slice(0, 10);
                    });

                    function recalcGst() {
                        const gst = parseFloat(document.getElementById('gst').value) || 0;
                        document.getElementById('igst').value = gst.toFixed(2);
                        document.getElementById('cgst').value = (gst / 2).toFixed(2);
                        document.getElementById('sgst').value = (gst / 2).toFixed(2);
                    }
                    document.getElementById('gst').addEventListener('input', recalcGst);
                    document.getElementById('gst').addEventListener('keydown', function (e) {
                        if (e.key === 'Enter') { e.preventDefault(); recalcGst(); }
                    });
                    recalcGst();
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
