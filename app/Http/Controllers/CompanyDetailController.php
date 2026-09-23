<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CompanyDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['id', 'company_name', 'email', 'telephone', 'state_code', 'gst_number', 'pan', 'tan', 'is_active'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }

        $companyDetails = CompanyDetail::orderBy($sortField, $sortDirection)->get();
        return view('company_details.index', compact('companyDetails', 'sortField', 'sortDirection'));
    }

    /**
     * Export the company profiles list as an Excel-compatible CSV file.
     */
    public function export()
    {
        $companyDetails = CompanyDetail::orderBy('id', 'desc')->get();

        $columns = ['Id', 'Company Name', 'Address', 'Email', 'Telephone', 'State Code', 'GST Number', 'PAN', 'TAN', 'Active'];

        return response()->streamDownload(function () use ($companyDetails, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($companyDetails as $detail) {
                fputcsv($file, [
                    $detail->id,
                    $detail->company_name,
                    $detail->address,
                    $detail->email,
                    $detail->telephone,
                    $detail->state_code,
                    $detail->gst_number,
                    $detail->pan,
                    $detail->tan,
                    $detail->is_active ? 'Yes' : 'No',
                ]);
            }
            fclose($file);
        }, 'company_details.csv');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company_details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'state_code' => 'nullable|string|max:10',
            'gst_number' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:50',
            'tan' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'company_code_prefix' => 'nullable|string|max:10',
            'company_code_enabled' => 'nullable|boolean',
            'booking_code_prefix' => 'nullable|string|max:20',
            'tax_invoice_prefix' => 'nullable|string|max:20',
            'pdf_logo_address_layout' => 'nullable|in:logo_left_address_right,logo_left_address_center,address_left_logo_right',
            'pdf_line_items_rows' => 'nullable|integer|min:1|max:50',
            'terms_conditions' => 'nullable|string',
            'irn_qr_enabled' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active');
        $data['company_code_enabled'] = $request->has('company_code_enabled');
        $data['company_code_prefix'] = $validated['company_code_prefix'] ?? 'CEH';
        $data['irn_qr_enabled'] = $request->has('irn_qr_enabled');
        $data['pdf_logo_address_layout'] = $validated['pdf_logo_address_layout'] ?? 'logo_left_address_right';
        $data['pdf_line_items_rows'] = $validated['pdf_line_items_rows'] ?? 10;
        $data['tax_invoice_prefix'] = $validated['tax_invoice_prefix'] ?? 'CSHL';

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Create target directory if it doesn't exist
            $targetDir = public_path('uploads/logos');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            $file->move($targetDir, $filename);
            $data['logo_path'] = 'uploads/logos/' . $filename;
        }

        if ($data['is_active']) {
            // Deactivate all other records
            CompanyDetail::query()->update(['is_active' => false]);
        }

        CompanyDetail::create($data);

        return redirect()->route('company_details.index')->with('success', 'Company details created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyDetail $companyDetail)
    {
        return view('company_details.show', compact('companyDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyDetail $companyDetail)
    {
        return view('company_details.edit', compact('companyDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanyDetail $companyDetail)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'state_code' => 'nullable|string|max:10',
            'gst_number' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:50',
            'tan' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'company_code_prefix' => 'nullable|string|max:10',
            'company_code_enabled' => 'nullable|boolean',
            'booking_code_prefix' => 'nullable|string|max:20',
            'tax_invoice_prefix' => 'nullable|string|max:20',
            'pdf_logo_address_layout' => 'nullable|in:logo_left_address_right,logo_left_address_center,address_left_logo_right',
            'pdf_line_items_rows' => 'nullable|integer|min:1|max:50',
            'terms_conditions' => 'nullable|string',
            'irn_qr_enabled' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_active'] = $request->has('is_active');
        $data['company_code_enabled'] = $request->has('company_code_enabled');
        $data['company_code_prefix'] = $validated['company_code_prefix'] ?? 'CEH';
        $data['irn_qr_enabled'] = $request->has('irn_qr_enabled');
        $data['pdf_logo_address_layout'] = $validated['pdf_logo_address_layout'] ?? 'logo_left_address_right';
        $data['pdf_line_items_rows'] = $validated['pdf_line_items_rows'] ?? 10;
        $data['tax_invoice_prefix'] = $validated['tax_invoice_prefix'] ?? 'CSHL';

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($companyDetail->logo_path && File::exists(public_path($companyDetail->logo_path))) {
                File::delete(public_path($companyDetail->logo_path));
            }

            $file = $request->file('logo');
            $filename = 'logo_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $targetDir = public_path('uploads/logos');
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            $file->move($targetDir, $filename);
            $data['logo_path'] = 'uploads/logos/' . $filename;
        }

        if ($data['is_active']) {
            // Deactivate all other records
            CompanyDetail::where('id', '!=', $companyDetail->id)->update(['is_active' => false]);
        }

        $companyDetail->update($data);

        return redirect()->route('company_details.index')->with('success', 'Company details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyDetail $companyDetail)
    {
        // Delete logo file if exists
        if ($companyDetail->logo_path && File::exists(public_path($companyDetail->logo_path))) {
            File::delete(public_path($companyDetail->logo_path));
        }

        $companyDetail->delete();

        return redirect()->route('company_details.index')->with('success', 'Company details deleted successfully.');
    }
}
