@extends('addresses.layout')

@section('content')
<div class="px-3 py-3">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h2 class="h4 font-weight-bold text-dark mb-0">Edit Address: {{ $address->CompanyName }}</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('addresses.update', $address->Id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Row 1: Type | Company Code --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="Type" class="form-label fw-bold">User Type</label>
                        <select class="form-select form-control" id="Type" name="Type">
                            <option value="client" {{ old('Type', $address->Type) == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="vendor" {{ old('Type', $address->Type) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            <option value="both"   {{ old('Type', $address->Type) == 'both'   ? 'selected' : '' }}>Both</option>
                            <option value="shipper" {{ old('Type', $address->Type) == 'shipper' ? 'selected' : '' }}>Shipper</option>
                            <option value="consignee" {{ old('Type', $address->Type) == 'consignee' ? 'selected' : '' }}>Consignee</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="AccountCode" class="form-label fw-bold">
                            Company Code <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="AccountCode"
                               name="AccountCode"
                               value="{{ old('AccountCode', $address->AccountCode) }}"
                               required>
                    </div>
                </div>

                {{-- Row 2: Company Name --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="CompanyName" class="form-label fw-bold">
                            Company Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="CompanyName"
                               name="CompanyName"
                               value="{{ old('CompanyName', $address->CompanyName) }}"
                               required>
                    </div>
                    <div class="col-md-6">
                        <label for="Country" class="form-label fw-bold">
                            Country <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-control" id="Country" name="Country" required>
                            <option value="">Select Country</option>
                            <option value="India" {{ old('Country', $address->Country) == 'India' ? 'selected' : '' }}>India</option>
                            <option value="Other Than India" {{ old('Country', $address->Country) == 'Other Than India' ? 'selected' : '' }}>Other Than India</option>
                        </select>
                    </div>
                </div>

                {{-- Row 3: Address Line 1 | Address Line 2 --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label id="ALine1Label" for="ALine1" class="form-label fw-bold">
                            Address Line 1 <span id="ALine1Star" class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="ALine1"
                               name="ALine1"
                               value="{{ old('ALine1', $address->ALine1) }}"
                               maxlength="100">
                        <small id="ALine1Help" class="text-muted"></small>
                    </div>
                    <div class="col-md-6">
                        <label id="ALine2Label" for="ALine2" class="form-label fw-bold">
                            Address Line 2 <span id="ALine2Star" class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="ALine2"
                               name="ALine2"
                               value="{{ old('ALine2', $address->ALine2) }}"
                               maxlength="100">
                        <small id="ALine2Help" class="text-muted"></small>
                    </div>
                </div>

                {{-- Row 4: Location | Pincode --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label id="LocationLabel" for="Location" class="form-label fw-bold">
                            Location <span id="LocationStar" class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="Location"
                               name="Location"
                               value="{{ old('Location', $address->Location) }}">
                    </div>
                    <div class="col-md-6">
                        <label id="PincodeLabel" for="Pincode" class="form-label fw-bold">
                            Pincode <span id="PincodeStar" class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="Pincode"
                               name="Pincode"
                               inputmode="numeric"
                               value="{{ old('Pincode', $address->Pincode) }}">
                        <small id="PincodeHelp" class="text-muted"></small>
                    </div>
                </div>

                {{-- State row --}}
                <div class="row mb-3" id="stateRow">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="ddl_StateName" class="form-label fw-bold">
                            State <span id="StateStar" class="text-danger">*</span>
                        </label>
                        <select name="ddl_StateName" id="ddl_StateName" class="form-select form-control">
                            <option value="">---Select State---</option>
                            <option value="99">CENTRE JURISDICTION</option>
                            <option value="97">OTHER TERRITORY</option>
                            <option value="35">ANDAMAN AND NICOBAR ISLANDS</option>
                            <option value="37">ANDHRA PRADESH</option>
                            <option value="28">ANDHRA PRADESH(BEFORE DIVISION)</option>
                            <option value="12">ARUNACHAL PRADESH</option>
                            <option value="18">ASSAM</option>
                            <option value="10">BIHAR</option>
                            <option value="04">CHANDIGARH</option>
                            <option value="22">CHATTISGARH</option>
                            <option value="26">DADRA AND NAGAR HAVELI</option>
                            <option value="25">DAMAN AND DIU</option>
                            <option value="07">DELHI</option>
                            <option value="30">GOA</option>
                            <option value="24">GUJARAT</option>
                            <option value="06">HARYANA</option>
                            <option value="02">HIMACHAL PRADESH</option>
                            <option value="01">JAMMU AND KASHMIR</option>
                            <option value="20">JHARKHAND</option>
                            <option value="29">KARNATAKA</option>
                            <option value="32">KERALA</option>
                            <option value="38">LADAKH</option>
                            <option value="31">LAKSHADWEEP ISLANDS</option>
                            <option value="23">MADHYA PRADESH</option>
                            <option value="27">MAHARASHTRA</option>
                            <option value="14">MANIPUR</option>
                            <option value="17">MEGHALAYA</option>
                            <option value="15">MIZORAM</option>
                            <option value="13">NAGALAND</option>
                            <option value="21">ODISHA</option>
                            <option value="34">PUDUCHERRY</option>
                            <option value="03">PUNJAB</option>
                            <option value="08">RAJASTHAN</option>
                            <option value="11">SIKKIM</option>
                            <option value="33">TAMIL NADU</option>
                            <option value="36">TELANGANA</option>
                            <option value="16">TRIPURA</option>
                            <option value="09">UTTAR PRADESH</option>
                            <option value="05">UTTARAKHAND</option>
                            <option value="19">WEST BENGAL</option>
                        </select>
                        <input type="hidden" id="State" name="State" value="{{ old('State', $address->State) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="StateCode" class="form-label fw-bold">State Code</label>
                        <input type="text" class="form-control" id="StateCode" name="StateCode"
                               value="{{ old('StateCode', $address->StateCode) }}" readonly>
                    </div>
                </div>

                {{-- Row 5: GST No | Credit Days --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label id="GSTNoLabel" for="GSTNo" class="form-label fw-bold">GST No</label>
                        <input type="text"
                               class="form-control"
                               id="GSTNo"
                               name="GSTNo"
                               value="{{ old('GSTNo', $address->GSTNo) }}">
                        <small id="GSTNoHelp" class="text-muted"></small>
                    </div>
                    <div class="col-md-6">
                        <label for="CreditDays" class="form-label fw-bold">Credit Days</label>
                        <input type="number"
                               class="form-control"
                               id="CreditDays"
                               name="CreditDays"
                               value="{{ old('CreditDays', $address->CreditDays) }}">
                    </div>
                </div>

                {{-- Row 6: PAN | Email --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="PAN" class="form-label fw-bold">PAN</label>
                        <input type="text" class="form-control" id="PAN" name="PAN" value="{{ old('PAN', $address->PAN) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="Email" class="form-label fw-bold">Email - ID</label>
                        <input type="email" class="form-control" id="Email" name="Email" value="{{ old('Email', $address->Email) }}">
                    </div>
                </div>

                {{-- Row 7: Contact Name | Phone --}}
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="ContactName" class="form-label fw-bold">Contact Name</label>
                        <input type="text" class="form-control" id="ContactName" name="ContactName" value="{{ old('ContactName', $address->ContactName) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="Phone" class="form-label fw-bold">Phone</label>
                        <input type="text" class="form-control" id="Phone" name="Phone" value="{{ old('Phone', $address->Phone) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">Update Address</button>
                    <a href="{{ route('addresses.index') }}" class="btn btn-light ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        var stateSelect = document.getElementById('ddl_StateName');
        var stateHidden = document.getElementById('State');
        var stateCodeInput = document.getElementById('StateCode');
        var countrySelect = document.getElementById('Country');
        var pincodeInput = document.getElementById('Pincode');
        var gstInput = document.getElementById('GSTNo');

        function updateState() {
            var opt = stateSelect.options[stateSelect.selectedIndex];
            if (stateSelect.value) {
                stateCodeInput.value = stateSelect.value;
                stateHidden.value = opt.text;
            } else {
                stateCodeInput.value = '';
                stateHidden.value = '';
            }
        }

        function handleCountryChange() {
            var country = countrySelect.value;
            var aline1 = document.getElementById('ALine1');
            var aline2 = document.getElementById('ALine2');
            var location = document.getElementById('Location');

            if (country === 'India') {
                stateSelect.disabled = false;
                stateSelect.required = true;
                document.getElementById('StateStar').style.display = '';
                stateCodeInput.readOnly = true;

                aline1.required = true;
                aline1.maxLength = 100;
                document.getElementById('ALine1Star').style.display = '';
                document.getElementById('ALine1Help').textContent = 'Required (max 100 characters)';

                aline2.required = true;
                aline2.maxLength = 100;
                document.getElementById('ALine2Star').style.display = '';
                document.getElementById('ALine2Help').textContent = 'Required (max 100 characters)';

                location.required = true;
                document.getElementById('LocationStar').style.display = '';

                if (pincodeInput.value === '999999') pincodeInput.value = '';
                pincodeInput.required = true;
                pincodeInput.readOnly = false;
                pincodeInput.maxLength = 6;
                document.getElementById('PincodeStar').style.display = '';
                document.getElementById('PincodeHelp').textContent = '6 digits mandatory';

                document.getElementById('GSTNoLabel').innerHTML = 'GST No <span class="text-danger">*</span>';
                if (gstInput.value === 'URD') gstInput.value = '';
                gstInput.readOnly = false;
                gstInput.required = true;
                gstInput.maxLength = 16;
                document.getElementById('GSTNoHelp').textContent = 'Exactly 16 characters required';
            } else if (country !== '') {
                stateSelect.disabled = true;
                stateSelect.required = false;
                stateSelect.value = '';
                document.getElementById('StateStar').style.display = 'none';
                stateHidden.value = '';
                stateCodeInput.value = '91';
                stateCodeInput.readOnly = true;

                aline1.required = false;
                aline1.maxLength = 60;
                document.getElementById('ALine1Star').style.display = 'none';
                document.getElementById('ALine1Help').textContent = 'Optional (max 60 characters)';

                aline2.required = false;
                aline2.maxLength = 60;
                document.getElementById('ALine2Star').style.display = 'none';
                document.getElementById('ALine2Help').textContent = 'Optional (max 60 characters)';

                location.required = false;
                document.getElementById('LocationStar').style.display = 'none';

                pincodeInput.value = '999999';
                pincodeInput.readOnly = true;
                pincodeInput.required = false;
                document.getElementById('PincodeStar').style.display = 'none';
                document.getElementById('PincodeHelp').textContent = 'Default 999999 (non-editable for non-India)';

                document.getElementById('GSTNoLabel').textContent = 'GST No';
                gstInput.value = 'URD';
                gstInput.readOnly = true;
                gstInput.required = false;
                document.getElementById('GSTNoHelp').textContent = 'Defaults to URD for non-India';
            } else {
                stateSelect.disabled = false;
                stateSelect.required = false;
                stateCodeInput.readOnly = true;

                aline1.required = false;
                document.getElementById('ALine1Help').textContent = '';
                aline2.required = false;
                document.getElementById('ALine2Help').textContent = '';
                location.required = false;

                pincodeInput.readOnly = false;
                pincodeInput.required = false;
                document.getElementById('PincodeHelp').textContent = '';

                gstInput.readOnly = false;
                gstInput.required = false;
                document.getElementById('GSTNoHelp').textContent = '';
            }
        }

        pincodeInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });

        stateSelect.addEventListener('change', updateState);
        countrySelect.addEventListener('change', handleCountryChange);

        // Elements above this script already exist in the DOM, so initialize immediately.
        if (stateCodeInput.value) {
            stateSelect.value = stateCodeInput.value;
        }
        handleCountryChange();
    })();
</script>
@endsection
