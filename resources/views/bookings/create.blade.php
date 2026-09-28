@extends('bookings.layout')

@section('content')
<div class="px-2">
    <div class="mb-4">
        <h2 class="h3 font-weight-bold text-dark mb-0">Create New Booking</h2>
    </div>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf

        <div class="row g-4 mb-5">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Basic Information</h5>
            
            <div class="col-md-3">
                <label for="Category" class="form-label fw-semibold">Category</label>
                <select name="Category" id="Category" class="form-select shadow-sm">
                    <option value="client address" selected>Client Address</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="BookingNo" class="form-label fw-semibold">Booking No (Auto-generated)</label>
                <input type="text" id="BookingNo" class="form-control shadow-sm bg-light" value="{{ $nextBookingNo }}" readonly>
            </div>

            <div class="col-md-3">
                <label for="booking_date" class="form-label fw-semibold">Booking Date <span class="text-danger">*</span></label>
                <input type="date" name="booking_date" id="booking_date" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="col-md-3">
                <label for="Active" class="form-label fw-semibold">Active</label>
                <select name="Active" id="Active" class="form-select shadow-sm">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Company & Parties Details</h5>

            <div class="col-md-4">
                <label for="companyname" class="form-label">Company Name</label>
                <input type="text" name="companyname" id="companyname" class="form-control shadow-sm" list="addressNameList" placeholder="Search Or Type...">
            </div>

            <div class="col-md-4">
                <label for="shipper" class="form-label">Shipper</label>
                <input type="text" name="shipper" id="shipper" class="form-control shadow-sm" list="addressNameList" placeholder="Search Or Type...">
            </div>

            <div class="col-md-4">
                <label for="Consignee" class="form-label">Consignee</label>
                <input type="text" name="Consignee" id="Consignee" class="form-control shadow-sm" list="addressNameList" placeholder="Search Or Type...">
            </div>

            <datalist id="addressNameList">
                @foreach ($addresses as $addr)
                    <option value="{{ $addr->CompanyName }}"></option>
                @endforeach
            </datalist>

            {{-- Acode fields are auto-filled from the selected address above; kept out of the UI per request, still saved to DB. --}}
            <input type="hidden" name="accode_companyname" id="accode_companyname">
            <input type="hidden" name="acode_Shipper" id="acode_Shipper">
            <input type="hidden" name="accode_consignee" id="accode_consignee">
        </div>

        <div class="row g-4 mb-5">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Transport & Logistics</h5>

            <div class="col-md-3">
                <label for="origin" class="form-label">Origin</label>
                <input type="text" name="origin" id="origin" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="Destination" class="form-label">Destination</label>
                <input type="text" name="Destination" id="Destination" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="MAWB_MBL" class="form-label">MAWB / MBL</label>
                <input type="text" name="MAWB_MBL" id="MAWB_MBL" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="HAWB_HBL" class="form-label">HAWB / HBL</label>
                <input type="text" name="HAWB_HBL" id="HAWB_HBL" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="ETD" class="form-label">ETD</label>
                <input type="text" name="ETD" id="ETD" class="form-control shadow-sm" placeholder="Estimated Time Departure">
            </div>

            <div class="col-md-3">
                <label for="ETA" class="form-label">ETA</label>
                <input type="text" name="ETA" id="ETA" class="form-control shadow-sm" placeholder="Estimated Time Arrival">
            </div>

            <div class="col-md-3">
                <label for="Vessel" class="form-label">Vessel</label>
                <input type="text" name="Vessel" id="Vessel" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="IATA" class="form-label">IATA</label>
                <input type="text" name="IATA" id="IATA" class="form-control shadow-sm">
            </div>
        </div>

        <div class="row g-4 mb-5">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Shipment Metrics</h5>

            <div class="col-md-2">
                <label for="Pieces" class="form-label">Pieces</label>
                <input type="number" name="Pieces" id="Pieces" class="form-control shadow-sm">
            </div>

            <div class="col-md-2">
                <label for="CBM" class="form-label">CBM</label>
                <input type="number" step="0.001" name="CBM" id="CBM" class="form-control shadow-sm">
            </div>

            <div class="col-md-2">
                <label for="GrWeight" class="form-label">Gross Weight</label>
                <input type="number" step="0.001" name="GrWeight" id="GrWeight" class="form-control shadow-sm">
            </div>

            <div class="col-md-2">
                <label for="ChWeight" class="form-label">Charge Weight</label>
                <input type="number" step="0.001" name="ChWeight" id="ChWeight" class="form-control shadow-sm">
            </div>

            <div class="col-md-2">
                <label for="Volume" class="form-label">Volume</label>
                <input type="text" name="Volume" id="Volume" class="form-control shadow-sm">
            </div>

            <div class="col-md-2">
                <label for="FCL" class="form-label">FCL</label>
                <input type="text" name="FCL" id="FCL" class="form-control shadow-sm">
            </div>
        </div>

        <div class="row g-4 mb-5">
            <h5 class="text-primary border-bottom pb-2 fw-bold">Documentation & Misc</h5>

            <div class="col-md-3">
                <label for="SBNo" class="form-label">S/B No</label>
                <input type="text" name="SBNo" id="SBNo" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="SBDate" class="form-label">S/B Date</label>
                <input type="date" name="SBDate" id="SBDate" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="ShipperInvoice" class="form-label">Shipper Invoice</label>
                <input type="text" name="ShipperInvoice" id="ShipperInvoice" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="Line" class="form-label">Line</label>
                <input type="text" name="Line" id="Line" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="IGM_EGM" class="form-label">IGM / EGM</label>
                <input type="text" name="IGM_EGM" id="IGM_EGM" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="TOS" class="form-label">TOS</label>
                <input type="text" name="TOS" id="TOS" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="IEC" class="form-label">IEC</label>
                <input type="text" name="IEC" id="IEC" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="OOC" class="form-label">OOC</label>
                <input type="text" name="OOC" id="OOC" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="Asses" class="form-label">Asses</label>
                <input type="text" name="Asses" id="Asses" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="LUT" class="form-label">LUT</label>
                <input type="text" name="LUT" id="LUT" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="CFS" class="form-label">CFS</label>
                <input type="text" name="CFS" id="CFS" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="SalesRep" class="form-label">Sales Rep</label>
                <input type="text" name="SalesRep" id="SalesRep" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="Reference" class="form-label">Reference</label>
                <input type="text" name="Reference" id="Reference" class="form-control shadow-sm">
            </div>

            <div class="col-md-3">
                <label for="Month" class="form-label">Month</label>
                <input type="text" name="Month" id="Month" class="form-control shadow-sm" value="{{ date('m') }}">
            </div>

            <div class="col-md-3">
                <label for="Year" class="form-label">Year</label>
                <input type="text" name="Year" id="Year" class="form-control shadow-sm" value="{{ date('Y') }}">
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 pb-5">
            <button type="reset" class="btn btn-outline-secondary px-5">Reset</button>
            <button type="submit" class="btn btn-primary px-5 shadow">Save Booking</button>
        </div>
    </form>
</div>

<script>
    (function () {
        var addressByName = {};
        @foreach ($addresses as $addr)
            addressByName[{{ Illuminate\Support\Js::from(strtolower($addr->CompanyName)) }}] = {{ Illuminate\Support\Js::from($addr->AccountCode) }};
        @endforeach

        function wireAutoPopulate(textFieldId, hiddenFieldId) {
            var textField = document.getElementById(textFieldId);
            var hiddenField = document.getElementById(hiddenFieldId);
            textField.addEventListener('input', function () {
                var match = addressByName[this.value.trim().toLowerCase()];
                hiddenField.value = match || '';
            });
        }

        wireAutoPopulate('companyname', 'accode_companyname');
        wireAutoPopulate('shipper', 'acode_Shipper');
        wireAutoPopulate('Consignee', 'accode_consignee');
    })();
</script>
@endsection
