<x-app-layout>
	<div class="container-fluid px-4 py-4">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h2 class="h3 mb-0 text-gray-800">Add New Particular</h2>
			<a href="{{ route('invoice_particulars.index') }}" class="btn btn-secondary">
				<i class="fas fa-arrow-left"></i>
				Back to List
			</a>
		</div>

		<div class="card shadow-sm border-0">
			<div
				class="card-body p-4">
				@if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

				<form action="{{ route('invoice_particulars.store') }}" method="POST">
					@csrf

					<div class="row g-3 mb-4">
						{{-- Particulars dropdown from products table --}}
						<div class="col-md-6">
							<label class="form-label fw-semibold">Particulars
								<span class="text-danger">*</span>
							</label>
							<select name="Particulars" id="particulars_select" class="form-select" required>
								<option value="">-- Select Particular --</option>
								@foreach ($products as $product)
                                    <option
                                        value="{{ strtoupper($product->particulars) }}" data-hsn="{{ $product->hsn }}" data-igst="{{ $product->igst }}" data-cgst="{{ $product->cgst }}" data-sgst="{{ $product->sgst }}" data-gst="{{ $product->gst }}" data-isservice="{{ $product->is_service === 'Y' ? 'Y' : 'N' }}" data-exceptparticulars="{{ $product->except_particulars ? 'Y' : 'N' }}" {{ old('Particulars') == strtoupper($product->particulars) ? 'selected' : '' }}>{{ strtoupper($product->particulars) }}
                                    </option>
                                @endforeach
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label fw-semibold">Additional</label>
							<input type="text" name="Additional" class="form-control" value="{{ old('Additional') }}" placeholder="Additional details">
						</div>

						<div class="col-md-3">
							<label class="form-label fw-semibold">Tax Amount</label>
							<input type="number" step="0.01" min="0" name="TaxAmount" id="TaxAmount" class="form-control" value="{{ old('TaxAmount') }}" placeholder="0.00" oninput="calcTotals()">
						</div>

						<div class="col-md-3">
							<label class="form-label fw-semibold">Non Tax Amount (Non INR)</label>
							<input type="number" step="0.01" min="0" name="NonTaxAmt_NonINR" id="NonTaxAmt_NonINR" class="form-control" value="{{ old('NonTaxAmt_NonINR', '0.00') }}" placeholder="0.00" oninput="calcTotals()">
						</div>

						<div class="col-md-3">
							<label class="form-label fw-bold text-success fs-6">Total Amount</label>
							<input type="number" step="0.01" name="Total" id="Total" class="form-control fw-bold border-success" value="{{ old('Total', '0.00') }}" readonly>
						</div>
					</div>

					{{-- Hidden Fields for backend storage --}}
					<input type="hidden" name="HSN" id="hsn_field" value="{{ old('HSN') }}">
					<input type="hidden" name="IsService" id="IsService" value="{{ old('IsService') }}">
					<input type="hidden" name="ExceptionalParticulars" id="ExceptionalParticulars" value="{{ old('ExceptionalParticulars') }}">
					
					<input type="hidden" name="NonTaxAmount" id="NonTaxAmount" value="{{ old('NonTaxAmount', '0.00') }}">
					<input type="hidden" name="IGST" id="IGST" value="{{ old('IGST', '0.00') }}">
					<input type="hidden" name="IGSTValue" id="IGSTValue" value="{{ old('IGSTValue', '0.00') }}">
					<input type="hidden" name="SGST" id="SGST" value="{{ old('SGST', '0.00') }}">
					<input type="hidden" name="SGSTValue" id="SGSTValue" value="{{ old('SGSTValue', '0.00') }}">
					<input type="hidden" name="CGST" id="CGST" value="{{ old('CGST', '0.00') }}">
					<input type="hidden" name="CGSTValue" id="CGSTValue" value="{{ old('CGSTValue', '0.00') }}">

					<div class="mt-3 pt-3 border-top text-end">
						<a href="{{ route('invoice_particulars.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
						<button type="submit" class="btn btn-primary px-5">Save Particular</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	{{-- Pass products data to JS --}}
	<script>
		const productsData = @json($products->keyBy('id'));
		
		    document.getElementById('particulars_select').addEventListener('change', function () {
		        const selected = this.options[this.selectedIndex];
		        document.getElementById('hsn_field').value  = selected.dataset.hsn  || '';
		        document.getElementById('IGST').value        = selected.dataset.igst || '0.00';
		        document.getElementById('SGST').value        = selected.dataset.sgst || '0.00';
		        document.getElementById('CGST').value        = selected.dataset.cgst || '0.00';
		        document.getElementById('IsService').value   = selected.dataset.isservice === 'Y' ? 'Y' : '';
		        document.getElementById('ExceptionalParticulars').value = selected.dataset.exceptparticulars === 'Y' ? 'Y' : '';
		        calcTotals();
		    });
		
		    function calcTotals() {
		        recalcTax();
		    }
		
		    function recalcTax() {
		        const taxAmount = parseFloat(document.getElementById('TaxAmount').value) || 0;
		        const nonTaxAmountNonInr = parseFloat(document.getElementById('NonTaxAmt_NonINR').value) || 0;
		        const nonTaxAmount = parseFloat(document.getElementById('NonTaxAmount').value) || 0;
		        const base = taxAmount;
		
		        const igst = parseFloat(document.getElementById('IGST').value) || 0;
		        const sgst = parseFloat(document.getElementById('SGST').value) || 0;
		        const cgst = parseFloat(document.getElementById('CGST').value) || 0;
		
		        const igstVal = +(base * igst / 100).toFixed(2);
		        const sgstVal = +(base * sgst / 100).toFixed(2);
		        const cgstVal = +(base * cgst / 100).toFixed(2);
		
		        document.getElementById('IGSTValue').value = igstVal.toFixed(2);
		        document.getElementById('SGSTValue').value = sgstVal.toFixed(2);
		        document.getElementById('CGSTValue').value = cgstVal.toFixed(2);
		
		        // Total = base + max(IGST, SGST+CGST) — whichever tax applies + nonTaxAmount + nonTaxAmountNonInr
		        const taxApplied = Math.max(igstVal, sgstVal + cgstVal);
		        document.getElementById('Total').value = (base + nonTaxAmount + nonTaxAmountNonInr + taxApplied).toFixed(2);
		    }
		
		    // Decimal-only validation on number fields
		    document.querySelectorAll('input[type="number"]').forEach(function(el) {
		        el.addEventListener('keypress', function(e) {
		            if (!/[\d.]/.test(e.key) && e.key !== 'Backspace') e.preventDefault();
		        });
		    });
		</script>
		</x-app-layout>

