<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $query = Address::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('CompanyName', 'like', '%' . $search . '%')
                    ->orWhere('AccountCode', 'like', '%' . $search . '%')
                    ->orWhere('Location', 'like', '%' . $search . '%')
                    ->orWhere('State', 'like', '%' . $search . '%')
                    ->orWhere('Country', 'like', '%' . $search . '%')
                    ->orWhere('GSTNo', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->input('sort', 'Id');
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'Id',
            'CompanyName',
            'AccountCode',
            'Location',
            'State',
            'Country',
            'CreditDays',
            'GSTNo'
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'Id';
        }
        $query->orderBy($sortField, $sortDirection);

        // Pagination
        $addresses = $query->paginate(10)->withQueryString();

        return view('addresses.index', compact('addresses', 'sortField', 'sortDirection'));
    }

    /**
     * Export the addresses list as an Excel-compatible CSV file.
     */
    public function export(Request $request)
    {
        $query = Address::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('CompanyName', 'like', '%' . $search . '%')
                    ->orWhere('AccountCode', 'like', '%' . $search . '%')
                    ->orWhere('Location', 'like', '%' . $search . '%')
                    ->orWhere('State', 'like', '%' . $search . '%')
                    ->orWhere('Country', 'like', '%' . $search . '%')
                    ->orWhere('GSTNo', 'like', '%' . $search . '%');
            });
        }

        $addresses = $query->get();

        $columns = ['Id', 'Type', 'Company Code', 'Company Name', 'Address Line 1', 'Address Line 2', 'Location', 'Pincode', 'State Code', 'State', 'Country', 'GST No', 'PAN', 'Contact Name', 'Phone', 'Email', 'Credit Days'];

        return response()->streamDownload(function () use ($addresses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($addresses as $address) {
                fputcsv($file, [
                    $address->Id,
                    $address->Type,
                    $address->AccountCode,
                    $address->CompanyName,
                    $address->ALine1,
                    $address->ALine2,
                    $address->Location,
                    $address->Pincode,
                    $address->StateCode,
                    $address->State,
                    $address->Country,
                    $address->GSTNo,
                    $address->PAN,
                    $address->ContactName,
                    $address->Phone,
                    $address->Email,
                    $address->CreditDays,
                ]);
            }
            fclose($file);
        }, 'addresses.csv');
    }

    public function create()
    {
        // Generate next AccountCode (AO01, AO02, ...)
        $nextAccountCode = $this->generateNextAccountCode();

        return view('addresses.create', compact('nextAccountCode'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateAddress($request);

        Address::create($validatedData);

        return redirect()->route('addresses.index')->with('success', 'Address created successfully.');
    }

    public function show(Address $address)
    {
        return view('addresses.show', compact('address'));
    }

    public function edit(Address $address)
    {
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $validatedData = $this->validateAddress($request, $address->Id);

        $address->update($validatedData);

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Address deleted successfully.');
    }

    /**
     * Shared validation and default setting logic for store and update.
     */
    private function validateAddress(Request $request, $id = null)
    {
        $isIndia = $request->input('Country') === 'India';

        if ($isIndia) {
            // India – all address fields are REQUIRED
            $rules = [
                'Type' => 'nullable|string',
                'AccountCode' => 'nullable|string|max:10',
                'CompanyName' => 'required|string|max:100',
                'ALine1' => 'required|string|max:100',
                'ALine2' => 'required|string|max:100',
                'Location' => 'required|string|max:100',
                'Pincode' => 'required|digits:6',
                'Country' => 'required|string',
                'State' => 'required|string',
                'StateCode' => 'required|string',
                'GSTNo' => 'required|string|max:16',
                'PAN' => 'nullable|string|max:15',
                'Email' => 'nullable|email|max:100',
                'ContactName' => 'nullable|string|max:100',
                'Phone' => 'nullable|string|max:50',
                'CreditDays' => 'nullable|integer',
                'CreateBy' => 'nullable|string|max:50',
            ];
        } else {
            // Other than India – address fields are OPTIONAL, ALine1/ALine2 max 60 chars
            $rules = [
                'Type' => 'nullable|string',
                'AccountCode' => 'nullable|string|max:10',
                'CompanyName' => 'required|string|max:100',
                'ALine1' => 'nullable|string|max:60',
                'ALine2' => 'nullable|string|max:60',
                'Location' => 'nullable|string|max:100',
                'Pincode' => 'nullable|string',
                'Country' => 'required|string',
                'State' => 'nullable|string',
                'StateCode' => 'nullable|string',
                'GSTNo' => 'nullable|string',
                'PAN' => 'nullable|string|max:15',
                'Email' => 'nullable|email|max:100',
                'ContactName' => 'nullable|string|max:100',
                'Phone' => 'nullable|string|max:50',
                'CreditDays' => 'nullable|integer',
                'CreateBy' => 'nullable|string|max:50',
            ];
        }

        $validatedData = $request->validate($rules);

        // --- Defaults & overrides ---

        // 1. Type defaults to 'client'
        if (empty($validatedData['Type'])) {
            $validatedData['Type'] = 'client';
        }

        // 2. AccountCode: auto-generate if not provided (only on creation usually, but we'll check if it's empty)
        if (empty($validatedData['AccountCode'])) {
            $validatedData['AccountCode'] = $this->generateNextAccountCode();
        }

        // 3. CreditDays defaults to 30
        if (empty($validatedData['CreditDays'])) {
            $validatedData['CreditDays'] = 30;
        }

        // 4. Non-India overrides
        if (!$isIndia) {
            $validatedData['GSTNo'] = 'URD';
            $validatedData['Pincode'] = '999999';
            $validatedData['StateCode'] = '91';
            $validatedData['State'] = '';
        }

        return $validatedData;
    }

    // -------------------------------------------------------------------------
    // Helper: Generate next sequential AccountCode (AO01, AO02, ...)
    // -------------------------------------------------------------------------
    private function generateNextAccountCode(): string
    {
        $prefix = 'AO';

        $companyDetail = \App\Models\CompanyDetail::getActive();
        if ($companyDetail && $companyDetail->company_code_enabled && !empty($companyDetail->company_code_prefix)) {
            $prefix = $companyDetail->company_code_prefix;
        }

        // Get the highest numeric suffix of existing codes starting with the prefix
        $prefixLength = strlen($prefix);
        $last = Address::where('AccountCode', 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING(AccountCode, {$prefixLength} + 1) AS UNSIGNED) DESC")
            ->value('AccountCode');

        if ($last) {
            $num = (int) substr($last, $prefixLength);
            $next = $num + 1;
        } else {
            $next = 1;
        }

        return $prefix . str_pad($next, 2, '0', STR_PAD_LEFT);
    }
}
