<x-app-layout>
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-1 text-gray-800 fw-bold">Edit Tax Invoice</h2>
                <p class="text-muted mb-0">Modify details for Invoice {{ $invoice->billno }}, manage line items, and adjust payments.</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary px-3 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
            @csrf
            @method('PUT')

            <!-- Hidden input to store particulars list as JSON -->
            <input type="hidden" name="particulars_json" id="particulars_json" value="[]">

            <!-- 1. HEADER FIELDS & CLIENT SELECT -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-user-tie me-2"></i>1. Client & Invoice Info</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Type</label>
                            <select name="invoice_type" class="form-select">
                                <option value="TaxInvoice" {{ $invoice->invoice_type == 'TaxInvoice' ? 'selected' : '' }}>Tax Invoice</option>
                                <option value="Proforma" {{ $invoice->invoice_type == 'Proforma' ? 'selected' : '' }}>Proforma Invoice</option>
                                <option value="CreditNote" {{ $invoice->invoice_type == 'CreditNote' ? 'selected' : '' }}>Credit Note</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Category</label>
                            <input type="text" name="invoice_category" class="form-control" value="{{ old('invoice_category', $invoice->invoice_category) }}" placeholder="e.g. Standard, Export">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" name="billdate" id="billdate" class="form-control" value="{{ old('billdate', $invoice->billdate) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice No. <span class="text-danger">*</span></label>
                            <input type="text" name="billno" class="form-control fw-bold" value="{{ old('billno', $invoice->billno) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-danger">Client Name-ACode *</label>
                            <select id="client_select" class="form-select" required>
                                <option value="">-- Select Client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->Id }}" 
                                            data-acode="{{ $client->AccountCode }}"
                                            data-company="{{ $client->CompanyName }}"
                                            data-aline1="{{ $client->ALine1 }}"
                                            data-aline2="{{ $client->ALine2 }}"
                                            data-location="{{ $client->Location }}"
                                            data-pincode="{{ $client->Pincode }}"
                                            data-gst="{{ $client->GSTNo }}"
                                            data-pan="{{ $client->PAN }}"
                                            data-state="{{ $client->State }}"
                                            data-statecode="{{ $client->StateCode }}"
                                            data-phone="{{ $client->Phone }}"
                                            data-email="{{ $client->Email }}"
                                            data-creditdays="{{ $client->CreditDays ?? 30 }}"
                                            {{ $invoice->acode == $client->AccountCode ? 'selected' : '' }}>
                                        {{ $client->CompanyName }} ({{ $client->AccountCode }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Account Code</label>
                            <input type="text" name="acode" id="acode" class="form-control" value="{{ $invoice->acode }}" readonly required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Credit Days</label>
                            <input type="number" name="credit_days" id="credit_days" class="form-control" min="0" value="{{ $invoice->credit_days ?? 30 }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $invoice->due_date }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Place of Supply *</label>
                            <input type="text" name="po_supply" id="po_supply" class="form-control" value="{{ $invoice->po_supply }}" placeholder="State/Country" required>
                        </div>

                        <input type="hidden" name="company_name" id="company_name" value="{{ $invoice->company_name }}">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Address Line 1</label>
                            <input type="text" name="aline1" id="aline1" class="form-control" value="{{ $invoice->aline1 }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Address Line 2</label>
                            <input type="text" name="aline2" id="aline2" class="form-control" value="{{ $invoice->aline2 }}">
                        </div>
                        <div class="col-md-4">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">City</label>
                                    <input type="text" name="location" id="location" class="form-control" value="{{ $invoice->location }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Pincode</label>
                                    <input type="text" name="pincode" id="pincode" class="form-control" value="{{ $invoice->pincode }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">GST No.</label>
                            <input type="text" name="gst_no" id="gst_no" class="form-control" value="{{ $invoice->gst_no }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">PAN</label>
                            <input type="text" name="pan" id="pan" class="form-control" value="{{ $invoice->pan }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">State Name</label>
                            <input type="text" name="state" id="state" class="form-control" value="{{ $invoice->state }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">State Code</label>
                            <input type="text" name="state_code" id="state_code" class="form-control" value="{{ $invoice->state_code }}" placeholder="Ex: 33">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ $invoice->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $invoice->email }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. OPTIONAL BOOKING DATA & OPERATIONS FIELDS -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-ship me-2"></i>2. Operation Fields & Booking Link <span class="text-muted fs-7">(Optional)</span></h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Link Booking No.</label>
                            <select id="booking_select" class="form-select">
                                <option value="">-- Select Booking to Auto-fill --</option>
                                @foreach ($bookings as $booking)
                                    <option value="{{ $booking->BookingNo }}"
                                            data-mawb="{{ $booking->MAWB_MBL }}"
                                            data-hawb="{{ $booking->HAWB_HBL }}"
                                            data-sbno="{{ $booking->SBNo }}"
                                            data-sbdate="{{ $booking->SBDate }}"
                                            data-origin="{{ $booking->origin }}"
                                            data-dest="{{ $booking->Destination }}"
                                            data-grweight="{{ $booking->GrWeight }}"
                                            data-chweight="{{ $booking->ChWeight }}"
                                            data-volume="{{ $booking->Volume }}"
                                            data-pieces="{{ $booking->Pieces }}"
                                            data-igmegm="{{ $booking->IGM_EGM }}"
                                            data-cbm="{{ $booking->CBM }}"
                                            data-shinv="{{ $booking->ShipperInvoice }}"
                                            data-line="{{ $booking->Line }}"
                                            data-vessel="{{ $booking->Vessel }}"
                                            data-consignee="{{ $booking->Consignee }}"
                                            data-shipper="{{ $booking->shipper }}"
                                            data-salesrep="{{ $booking->SalesRep }}"
                                            data-month="{{ $booking->Month }}"
                                            data-year="{{ $booking->Year }}"
                                            {{ $invoice->booking_no == $booking->BookingNo ? 'selected' : '' }}>
                                        {{ $booking->BookingNo }} ({{ $booking->companyname }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="booking_no" id="booking_no_hidden" value="{{ $invoice->booking_no }}">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Proforma Date</label>
                            <input type="date" name="proforma_invoice_date" class="form-control" value="{{ $invoice->proforma_invoice_date }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Proforma Invoice No</label>
                            <input type="text" name="proforma_invoice_no" class="form-control" value="{{ $invoice->proforma_invoice_no }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">MBL/AWB</label>
                            <input type="text" name="guarantee_l1" id="mbl_awb" class="form-control" value="{{ $invoice->guarantee_l1 }}" placeholder="MAWB/MBL">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">HBL/AWB</label>
                            <input type="text" name="guarantee_l2" id="hbl_awb" class="form-control" value="{{ $invoice->guarantee_l2 }}" placeholder="HAWB/HBL">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">BE/SB No.</label>
                            <input type="text" name="guarantee_l3" id="be_sb_no" class="form-control" value="{{ $invoice->guarantee_l3 }}" placeholder="SB No.">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">BE/SB Date</label>
                            <input type="date" name="exten_date" id="be_sb_date" class="form-control" value="{{ $invoice->exten_date }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Line/Vessel</label>
                            <input type="text" name="guarantee_l4" id="line_vessel" class="form-control" value="{{ $invoice->guarantee_l4 }}" placeholder="Shipping/Airline & Vessel">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Origin/POL</label>
                            <input type="text" name="category" id="origin_pol" class="form-control" value="{{ $invoice->category }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Dest/POD</label>
                            <input type="text" name="stype" id="dest_pod" class="form-control" value="{{ $invoice->stype }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Gr Weight</label>
                            <input type="text" name="hcode" id="gr_weight" class="form-control" value="{{ $invoice->hcode }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Ch Wt/Volume</label>
                            <input type="text" name="remarks" id="ch_wt_vol" class="form-control" value="{{ $invoice->remarks }}" placeholder="e.g., 500 Kgs / LCL">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Pkgs</label>
                            <input type="text" name="taxsch" id="pkgs" class="form-control" value="{{ $invoice->taxsch }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">IRN (GST e-Invoice)</label>
                            <input type="text" name="irn" id="egm_igm" class="form-control" value="{{ $invoice->irn }}" maxlength="64" placeholder="64-character IRN">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">CBM</label>
                            <input type="text" name="version" id="cbm" class="form-control" value="{{ $invoice->version }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Shipper Inv.</label>
                            <input type="text" name="shipper_invoice" id="shipper_inv" class="form-control" value="{{ $invoice->shipper_invoice }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Shipper/Consignee</label>
                            <input type="text" name="shipper_consignee" id="shipper_consignee" class="form-control" value="{{ $invoice->shipper_consignee }}" placeholder="Name details">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Sales Rep</label>
                            <select name="hcode" id="sales_rep" class="form-select">
                                <option value="BRANCH" {{ $invoice->hcode == 'BRANCH' ? 'selected' : '' }}>BRANCH</option>
                                <option value="HEAD OFFICE" {{ $invoice->hcode == 'HEAD OFFICE' ? 'selected' : '' }}>HEAD OFFICE</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Currency & Ex. Rate</label>
                            <div class="input-group">
                                <select name="currency" id="currency" class="form-select" style="max-width: 110px;">
                                    <option value="INR" {{ $invoice->currency == 'INR' ? 'selected' : '' }}>INR</option>
                                    <option value="USD" {{ $invoice->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ $invoice->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                </select>
                                <input type="number" step="0.0001" name="ex_rate" id="ex_rate" class="form-control" value="{{ $invoice->ex_rate ?? '1.0000' }}" placeholder="1.0000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. PARTICULARS TABLE (DYNAMIC LINES) -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-list-ol me-2"></i>3. Invoice Particulars</h5>
                    <span class="badge bg-light text-dark border fw-normal py-1.5 px-2.5 rounded-pill" id="rows_count">0 Rows</span>
                </div>
                <div class="card-body p-0 border-top">
                    <!-- Particulars Table -->
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="particularsTable">
                            <thead class="table-light text-uppercase fs-7 fw-bold text-muted border-bottom">
                                <tr>
                                    <th class="ps-4" style="width: 60px;">Id</th>
                                    <th>Particulars</th>
                                    <th>Additional</th>
                                    <th class="text-end" style="width: 130px;">Non-Tax Amt</th>
                                    <th class="text-end" style="width: 130px;">Tax Amt</th>
                                    <th class="text-center" style="width: 100px;">CGST</th>
                                    <th class="text-center" style="width: 100px;">SGST</th>
                                    <th class="text-center" style="width: 100px;">IGST</th>
                                    <th class="text-end" style="width: 130px;">Total</th>
                                    <th class="text-center pe-4" style="width: 130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="particulars_tbody">
                                <!-- Dynamic Rows Go Here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Particular Adding Form Row -->
                    <div class="bg-light p-3 border-top">
                        <div class="row g-2 align-items-end">
                            <input type="hidden" id="edit_index" value="-1">
                            <div class="col-md-3">
                                <label class="form-label fs-7 fw-bold text-muted text-uppercase mb-1">Particulars Name</label>
                                <select id="part_select" class="form-select select2">
                                    <option value="">-- Select Particular --</option>
                                    @foreach ($particularsMaster as $p)
                                        <option value="{{ $p->particulars }}" 
                                                data-hsn="{{ $p->hsn }}" 
                                                data-gst="{{ $p->gst }}"
                                                data-igst="{{ $p->igst }}"
                                                data-cgst="{{ $p->cgst }}"
                                                data-sgst="{{ $p->sgst }}"
                                                data-isservice="{{ $p->is_service === 'Y' ? 'Y' : '' }}"
                                                data-except="{{ $p->except_particulars ? 'Y' : '' }}">
                                            {{ strtoupper($p->particulars) }} ({{ $p->hsn }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fs-7 fw-bold text-muted text-uppercase mb-1">Additional Details</label>
                                <input type="text" id="part_additional" class="form-control" placeholder="e.g. Rate info or notes">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-7 fw-bold text-muted text-uppercase mb-1">Non-Tax Amount</label>
                                <input type="number" step="0.01" min="0" id="part_non_tax" class="form-control text-end" value="0.00">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fs-7 fw-bold text-muted text-uppercase mb-1">Taxable Amount</label>
                                <input type="number" step="0.01" min="0" id="part_tax" class="form-control text-end" value="0.00">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="button" id="btn_add_particular" class="btn btn-dark fw-bold py-2">
                                    <i class="fas fa-plus me-1"></i> Add Row
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. TOTALS & GRAND FINALS PANEL -->
            <div class="row g-4 mb-4">
                <!-- Left Details: Remarks, Bank, Signature -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-file-contract me-2"></i>4. Payment & E-Signature</h5>
                        </div>
                        <div class="card-body pt-0 d-flex flex-column gap-3">
                            <div>
                                <label class="form-label fw-semibold">Select Bank</label>
                                <select name="bank" class="form-select">
                                    <option value="YES Bank" {{ $invoice->bank == 'YES Bank' ? 'selected' : '' }}>YES Bank (Default)</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->CompanyName }}" {{ $invoice->bank == $bank->CompanyName ? 'selected' : '' }}>{{ $bank->CompanyName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label fw-semibold">Select Signature Authority</label>
                                <select name="hcode" class="form-select">
                                    <option value="E-Signature" {{ $invoice->hcode == 'E-Signature' ? 'selected' : '' }}>E-Signature (Default)</option>
                                    @foreach ($signatures as $sig)
                                        <option value="{{ $sig->CompanyName }}" {{ $invoice->hcode == $sig->CompanyName ? 'selected' : '' }}>{{ $sig->CompanyName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow-1">
                                <label class="form-label fw-semibold">Remarks</label>
                                <textarea name="remarks" class="form-control h-100" rows="3" placeholder="Enter terms, conditions, or general remarks...">{{ $invoice->remarks }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Totals: Live Computations -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 bg-white h-100 border-start border-4 border-primary">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="card-title mb-0 text-primary fw-bold"><i class="fas fa-calculator me-2"></i>5. Totals & Computations</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="d-flex flex-column gap-2.5">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">Total Non-Taxable Amount:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="total_non_tax" id="total_non_tax" class="form-control text-end fw-bold bg-light" value="{{ $invoice->total_non_tax }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">Total Taxable Amount:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="total_tax" id="total_tax" class="form-control text-end fw-bold bg-light" value="{{ $invoice->total_tax }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">IGST Value:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="igst_value" id="igst_value" class="form-control text-end fw-bold bg-light text-danger" value="{{ $invoice->igst_value }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">CGST Value:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="cgst_value" id="cgst_value" class="form-control text-end fw-bold bg-light text-warning" value="{{ $invoice->cgst_value }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">SGST Value:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="sgst_value" id="sgst_value" class="form-control text-end fw-bold bg-light text-warning" value="{{ $invoice->sgst_value }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 bg-light p-2 rounded">
                                    <span class="text-dark fw-bold fs-6">Sub-Total (Before Rounding):</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="total" id="total_sum" class="form-control text-end fw-bold border-0 bg-transparent text-dark" value="{{ $invoice->total }}" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="sub_total" id="sub_total" value="{{ $invoice->sub_total }}">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold">Round Off Difference:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="round_off" id="round_off" class="form-control text-end fw-bold bg-light text-muted" value="{{ $invoice->round_off }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 bg-primary bg-opacity-10 p-2 rounded">
                                    <span class="text-primary fw-extrabold fs-5">Grand Total:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="1" name="grand_total" id="grand_total" class="form-control text-end fw-extrabold fs-5 text-primary border-primary border-2" value="{{ (int)$invoice->grand_total }}" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-muted fw-semibold text-success">Advance Paid:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="advance" id="advance" class="form-control text-end fw-bold border-success text-success" value="{{ $invoice->advance }}" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center bg-danger bg-opacity-10 p-2 rounded">
                                    <span class="text-danger fw-bold fs-6">Balance Due:</span>
                                    <div style="width: 180px;">
                                        <input type="number" step="0.01" name="balance" id="balance" class="form-control text-end fw-bold text-danger bg-transparent border-0" value="{{ $invoice->balance }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. INVOICE STATUS & SUBMIT BUTTONS -->
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-bold text-muted">Invoice Status:</span>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_unpaid" value="UnPaid" {{ $invoice->status == 'UnPaid' ? 'checked' : '' }}>
                            <label class="form-check-label text-danger fw-bold" for="status_unpaid">UnPaid</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_paid" value="Paid" {{ $invoice->status == 'Paid' ? 'checked' : '' }}>
                            <label class="form-check-label text-success fw-bold" for="status_paid">Paid</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('invoices.index') }}" class="btn btn-light px-4 py-2 fw-semibold border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                            <i class="fas fa-save me-1.5"></i> Update Invoice
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // 1. Storage for dynamic particulars line items, initialized with existing database particulars
        let particularsList = {!! json_encode($existingParticulars->map(fn($p) => [
            'particulars' => $p->Particulars,
            'hsn' => $p->HSN,
            'gst_rate' => ($p->IGST > 0) ? (float)$p->IGST : (($p->CGST > 0) ? (float)($p->CGST * 2) : 18.00),
            'non_tax_amount' => (float)$p->NonTaxAmount,
            'non_tax_amt_non_inr' => (float)$p->NonTaxAmt_NonINR,
            'tax_amount' => (float)$p->TaxAmount,
            'is_service' => $p->IsService,
            'exceptional_particulars' => $p->ExceptionalParticulars,
            'cgst' => (float)$p->CGST,
            'cgst_value' => (float)$p->CGSTValue,
            'sgst' => (float)$p->SGST,
            'sgst_value' => (float)$p->SGSTValue,
            'igst' => (float)$p->IGST,
            'igst_value' => (float)$p->IGSTValue,
            'total' => (float)$p->Total,
            'additional' => $p->Additional,
        ])) !!};

        // Master data objects
        const clients = @json($clients->keyBy('Id'));
        const bookings = @json($bookings->keyBy('BookingNo'));

        // Element references
        const clientSelect = document.getElementById('client_select');
        const bookingSelect = document.getElementById('booking_select');
        
        // Client details fields
        const acodeField = document.getElementById('acode');
        const companyNameField = document.getElementById('company_name');
        const aline1Field = document.getElementById('aline1');
        const aline2Field = document.getElementById('aline2');
        const locationField = document.getElementById('location');
        const pincodeField = document.getElementById('pincode');
        const gstNoField = document.getElementById('gst_no');
        const panField = document.getElementById('pan');
        const stateField = document.getElementById('state');
        const stateCodeField = document.getElementById('state_code');
        const phoneField = document.getElementById('phone');
        const emailField = document.getElementById('email');
        const creditDaysField = document.getElementById('credit_days');
        const billdateField = document.getElementById('billdate');
        const dueDateField = document.getElementById('due_date');
        const poSupplyField = document.getElementById('po_supply');

        // Operations fields
        const mblAwbField = document.getElementById('mbl_awb');
        const hblAwbField = document.getElementById('hbl_awb');
        const beSbNoField = document.getElementById('be_sb_no');
        const beSbDateField = document.getElementById('be_sb_date');
        const lineVesselField = document.getElementById('line_vessel');
        const originPolField = document.getElementById('origin_pol');
        const destPodField = document.getElementById('dest_pod');
        const grWeightField = document.getElementById('gr_weight');
        const chWtVolField = document.getElementById('ch_wt_vol');
        const pkgsField = document.getElementById('pkgs');
        const egmIgmField = document.getElementById('egm_igm');
        const cbmField = document.getElementById('cbm');
        const shipperInvField = document.getElementById('shipper_inv');
        const shipperConsigneeField = document.getElementById('shipper_consignee');
        const salesRepField = document.getElementById('sales_rep');
        const bookingNoHiddenField = document.getElementById('booking_no_hidden');

        // Particulars inputs
        const partSelect = document.getElementById('part_select');
        const partAdditional = document.getElementById('part_additional');
        const partNonTax = document.getElementById('part_non_tax');
        const partTax = document.getElementById('part_tax');
        const btnAddParticular = document.getElementById('btn_add_particular');
        const editIndexInput = document.getElementById('edit_index');

        // Invoice totals
        const totalNonTaxField = document.getElementById('total_non_tax');
        const totalTaxField = document.getElementById('total_tax');
        const igstValueField = document.getElementById('igst_value');
        const cgstValueField = document.getElementById('cgst_value');
        const sgstValueField = document.getElementById('sgst_value');
        const totalSumField = document.getElementById('total_sum');
        const subTotalField = document.getElementById('sub_total');
        const roundOffField = document.getElementById('round_off');
        const grandTotalField = document.getElementById('grand_total');
        const advanceField = document.getElementById('advance');
        const balanceField = document.getElementById('balance');

        // --- A. Dynamic Due Date Calculation ---
        function calculateDueDate() {
            const billdateStr = billdateField.value;
            const creditDays = parseInt(creditDaysField.value) || 0;
            if (!billdateStr) return;

            const billdateObj = new Date(billdateStr);
            billdateObj.setDate(billdateObj.getDate() + creditDays);

            const yyyy = billdateObj.getFullYear();
            let mm = billdateObj.getMonth() + 1; // Months start at 0
            let dd = billdateObj.getDate();

            if (mm < 10) mm = '0' + mm;
            if (dd < 10) dd = '0' + dd;

            dueDateField.value = `${yyyy}-${mm}-${dd}`;
        }
        billdateField.addEventListener('change', calculateDueDate);
        creditDaysField.addEventListener('input', calculateDueDate);

        // --- B. Client Selection Handler ---
        clientSelect.addEventListener('change', function () {
            const clientVal = this.value;
            if (!clientVal) {
                // Clear fields
                acodeField.value = '';
                companyNameField.value = '';
                aline1Field.value = '';
                aline2Field.value = '';
                locationField.value = '';
                pincodeField.value = '';
                gstNoField.value = '';
                panField.value = '';
                stateField.value = '';
                stateCodeField.value = '';
                phoneField.value = '';
                emailField.value = '';
                creditDaysField.value = '30';
                poSupplyField.value = '';
                calculateDueDate();
                recalcAllInvoiceTotals();
                return;
            }

            const client = clients[clientVal];
            acodeField.value = client.AccountCode || '';
            companyNameField.value = client.CompanyName || '';
            aline1Field.value = client.ALine1 || '';
            aline2Field.value = client.ALine2 || '';
            locationField.value = client.Location || '';
            pincodeField.value = client.Pincode || '';
            gstNoField.value = client.GSTNo || '';
            panField.value = client.PAN || '';
            stateField.value = client.State || '';
            stateCodeField.value = client.StateCode || '';
            phoneField.value = client.Phone || '';
            emailField.value = client.Email || '';
            creditDaysField.value = client.CreditDays || '30';
            poSupplyField.value = client.State || 'TAMIL NADU';

            calculateDueDate();
            recalcAllInvoiceTotals(); // Recalculate GST because state code could change!
        });

        // --- C. Booking Selection Handler ---
        bookingSelect.addEventListener('change', function () {
            const bookingVal = this.value;
            bookingNoHiddenField.value = bookingVal;
            if (!bookingVal) {
                // Clear operations fields
                mblAwbField.value = '';
                hblAwbField.value = '';
                beSbNoField.value = '';
                beSbDateField.value = '';
                lineVesselField.value = '';
                originPolField.value = '';
                destPodField.value = '';
                grWeightField.value = '';
                chWtVolField.value = '';
                pkgsField.value = '';
                egmIgmField.value = '';
                cbmField.value = '';
                shipperInvField.value = '';
                shipperConsigneeField.value = '';
                return;
            }

            const opt = this.options[this.selectedIndex];
            mblAwbField.value = opt.dataset.mawb || '';
            hblAwbField.value = opt.dataset.hawb || '';
            beSbNoField.value = opt.dataset.sbno || '';
            beSbDateField.value = opt.dataset.sbdate || '';
            
            // Format Line and Vessel
            let lv = '';
            if (opt.dataset.line) lv += opt.dataset.line;
            if (opt.dataset.vessel) lv += (lv ? ' / ' : '') + opt.dataset.vessel;
            lineVesselField.value = lv;

            originPolField.value = opt.dataset.origin || '';
            destPodField.value = opt.dataset.dest || '';
            grWeightField.value = opt.dataset.grweight || '';
            
            let cwv = '';
            if (opt.dataset.chweight) cwv += opt.dataset.chweight + ' Kgs';
            if (opt.dataset.volume) cwv += (cwv ? ' / ' : '') + opt.dataset.volume;
            chWtVolField.value = cwv;

            pkgsField.value = opt.dataset.pieces || '';
            egmIgmField.value = opt.dataset.igmegm || '';
            cbmField.value = opt.dataset.cbm || '';
            shipperInvField.value = opt.dataset.shinv || '';
            
            // Format Shipper/Consignee
            let sc = '';
            if (opt.dataset.shipper) sc += 'Shipper: ' + opt.dataset.shipper;
            if (opt.dataset.consignee) sc += (sc ? ' \n' : '') + 'Consignee: ' + opt.dataset.consignee;
            shipperConsigneeField.value = sc;

            if (opt.dataset.salesrep) {
                salesRepField.value = opt.dataset.salesrep;
            }
        });

        // --- D. Particulars Calculations (Indian GST Rules) ---
        const BUSINESS_STATE_CODE = '33';

        function getTaxRates(optionElement) {
            if (!optionElement || !optionElement.value) {
                return { gst: 18.00, igst: 18.00, cgst: 9.00, sgst: 9.00 };
            }
            return {
                gst: parseFloat(optionElement.dataset.gst) || 0,
                igst: parseFloat(optionElement.dataset.igst) || 0,
                cgst: parseFloat(optionElement.dataset.cgst) || 0,
                sgst: parseFloat(optionElement.dataset.sgst) || 0,
            };
        }

        // --- E. Dynamic Sub-Table Management ---
        function renderParticularsTable() {
            const tbody = document.getElementById('particulars_tbody');
            tbody.innerHTML = '';
            
            particularsList.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = 'border-bottom';
                
                tr.innerHTML = `
                    <td class="ps-4 text-muted fs-7">${index + 1}</td>
                    <td class="fw-bold text-dark">${item.particulars} <div class="fs-8 text-muted fw-normal">HSN: ${item.hsn || '-'}</div></td>
                    <td>${item.additional || '-'}</td>
                    <td class="text-end fw-semibold">${parseFloat(item.non_tax_amount).toFixed(2)}</td>
                    <td class="text-end fw-semibold">${parseFloat(item.tax_amount).toFixed(2)}</td>
                    <td class="text-center text-warning fs-7">${parseFloat(item.cgst_value).toFixed(2)} <div class="fs-9 text-muted">${item.cgst}%</div></td>
                    <td class="text-center text-warning fs-7">${parseFloat(item.sgst_value).toFixed(2)} <div class="fs-9 text-muted">${item.sgst}%</div></td>
                    <td class="text-center text-danger fs-7">${parseFloat(item.igst_value).toFixed(2)} <div class="fs-9 text-muted">${item.igst}%</div></td>
                    <td class="text-end fw-bold text-dark">${parseFloat(item.total).toFixed(2)}</td>
                    <td class="text-center pe-4">
                        <div class="d-flex align-items-center justify-content-center gap-1.5">
                            <button type="button" onclick="editParticularRow(${index})" class="btn btn-outline-secondary btn-sm p-1 d-inline-flex align-items-center justify-content-center rounded-2" title="Select Row">
                                <i class="fas fa-edit fs-7"></i>
                            </button>
                            <button type="button" onclick="deleteParticularRow(${index})" class="btn btn-outline-danger btn-sm p-1 d-inline-flex align-items-center justify-content-center rounded-2" title="Delete Row">
                                <i class="fas fa-trash fs-7"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            document.getElementById('rows_count').innerText = `${particularsList.length} Rows`;
            document.getElementById('particulars_json').value = JSON.stringify(particularsList);
        }

        // Recalculate CGST/SGST/IGST values for a single item based on current state code
        function calculateItemGst(item) {
            const clientStateCode = stateCodeField.value.trim();
            const baseTax = parseFloat(item.tax_amount) || 0;
            const nonTax = parseFloat(item.non_tax_amount) || 0;
            
            let cgstVal = 0;
            let sgstVal = 0;
            let igstVal = 0;

            if (baseTax > 0) {
                if (clientStateCode === BUSINESS_STATE_CODE) {
                    item.cgst = item.gst_rate / 2;
                    item.sgst = item.gst_rate / 2;
                    item.igst = 0;

                    cgstVal = +(baseTax * item.cgst / 100).toFixed(2);
                    sgstVal = +(baseTax * item.sgst / 100).toFixed(2);
                } else {
                    item.cgst = 0;
                    item.sgst = 0;
                    item.igst = item.gst_rate;

                    igstVal = +(baseTax * item.igst / 100).toFixed(2);
                }
            } else {
                item.cgst = 0;
                item.sgst = 0;
                item.igst = 0;
            }

            item.cgst_value = cgstVal;
            item.sgst_value = sgstVal;
            item.igst_value = igstVal;
            item.total = +(baseTax + nonTax + cgstVal + sgstVal + igstVal).toFixed(2);
        }

        // Recalculate GST and Totals across all line items
        function recalcAllInvoiceTotals() {
            let sumNonTax = 0;
            let sumTax = 0;
            let sumCgst = 0;
            let sumSgst = 0;
            let sumIgst = 0;

            particularsList.forEach(item => {
                calculateItemGst(item);
                
                sumNonTax += parseFloat(item.non_tax_amount) || 0;
                sumTax += parseFloat(item.tax_amount) || 0;
                sumCgst += parseFloat(item.cgst_value) || 0;
                sumSgst += parseFloat(item.sgst_value) || 0;
                sumIgst += parseFloat(item.igst_value) || 0;
            });

            renderParticularsTable();

            totalNonTaxField.value = sumNonTax.toFixed(2);
            totalTaxField.value = sumTax.toFixed(2);
            cgstValueField.value = sumCgst.toFixed(2);
            sgstValueField.value = sumSgst.toFixed(2);
            igstValueField.value = sumIgst.toFixed(2);

            const subTotal = sumNonTax + sumTax + sumCgst + sumSgst + sumIgst;
            totalSumField.value = subTotal.toFixed(2);
            subTotalField.value = subTotal.toFixed(2);

            const grandTotal = Math.round(subTotal);
            grandTotalField.value = grandTotal;

            const roundOff = +(grandTotal - subTotal).toFixed(2);
            roundOffField.value = roundOff.toFixed(2);

            const advance = parseFloat(advanceField.value) || 0;
            const balance = +(grandTotal - advance).toFixed(2);
            balanceField.value = balance.toFixed(2);
        }

        // Add particular row button click
        btnAddParticular.addEventListener('click', function () {
            if (!partSelect.value) {
                alert('Please select a Particular name.');
                return;
            }

            const opt = partSelect.options[partSelect.selectedIndex];
            const rates = getTaxRates(opt);
            const nonTaxAmount = parseFloat(partNonTax.value) || 0;
            const taxAmount = parseFloat(partTax.value) || 0;

            if (nonTaxAmount === 0 && taxAmount === 0) {
                alert('Please enter either a Non-Tax Amount or a Taxable Amount.');
                return;
            }

            const item = {
                particulars: opt.value,
                hsn: opt.dataset.hsn || '',
                gst_rate: rates.gst,
                non_tax_amount: nonTaxAmount,
                non_tax_amt_non_inr: 0.00,
                tax_amount: taxAmount,
                is_service: opt.dataset.isservice || '',
                exceptional_particulars: opt.dataset.except || '',
                cgst: 0,
                cgst_value: 0,
                sgst: 0,
                sgst_value: 0,
                igst: 0,
                igst_value: 0,
                total: 0,
                additional: partAdditional.value || ''
            };

            const editIndex = parseInt(editIndexInput.value);
            if (editIndex > -1) {
                particularsList[editIndex] = item;
                editIndexInput.value = '-1';
                btnAddParticular.innerHTML = '<i class="fas fa-plus me-1"></i> Add Row';
                btnAddParticular.className = 'btn btn-dark fw-bold py-2';
            } else {
                particularsList.push(item);
            }

            partSelect.value = '';
            partAdditional.value = '';
            partNonTax.value = '0.00';
            partTax.value = '0.00';

            recalcAllInvoiceTotals();
        });

        // Edit row
        window.editParticularRow = function (index) {
            const item = particularsList[index];
            partSelect.value = item.particulars;
            partAdditional.value = item.additional || '';
            partNonTax.value = item.non_tax_amount.toFixed(2);
            partTax.value = item.tax_amount.toFixed(2);
            
            editIndexInput.value = index;
            btnAddParticular.innerHTML = '<i class="fas fa-save me-1"></i> Save Row';
            btnAddParticular.className = 'btn btn-warning fw-bold py-2 text-dark';
        };

        // Delete row
        window.deleteParticularRow = function (index) {
            if (confirm('Are you sure you want to delete this row?')) {
                particularsList.splice(index, 1);
                recalcAllInvoiceTotals();
            }
        };

        advanceField.addEventListener('input', function () {
            const grandTotal = parseFloat(grandTotalField.value) || 0;
            const advance = parseFloat(this.value) || 0;
            balanceField.value = (grandTotal - advance).toFixed(2);
        });

        document.querySelectorAll('input[type="number"]').forEach(function (el) {
            el.addEventListener('keypress', function (e) {
                if (!/[\d.]/.test(e.key) && e.key !== 'Backspace') e.preventDefault();
            });
        });

        // Initial setup on page load - triggers calculations of loaded particulars
        recalcAllInvoiceTotals();
        calculateDueDate();
    </script>
</x-app-layout>
