<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BankDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['id', 'bank_name', 'account_name', 'account_number', 'ifsc', 'swift', 'branch', 'upi_id', 'is_default', 'is_active'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }

        $bankDetails = BankDetail::orderBy($sortField, $sortDirection)->get();
        return view('bank_details.index', compact('bankDetails', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank_details.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('upi_qr')) {
            $data['upi_qr_path'] = $this->storeQrImage($request);
        }

        if ($data['is_default']) {
            BankDetail::query()->update(['is_default' => false]);
        }

        BankDetail::create($data);

        return redirect()->route('bank_details.index')->with('success', 'Bank details created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BankDetail $bankDetail)
    {
        return view('bank_details.show', compact('bankDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankDetail $bankDetail)
    {
        return view('bank_details.edit', compact('bankDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankDetail $bankDetail)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('upi_qr')) {
            $this->deleteQrImage($bankDetail);
            $data['upi_qr_path'] = $this->storeQrImage($request);
        } elseif ($request->boolean('remove_upi_qr')) {
            $this->deleteQrImage($bankDetail);
            $data['upi_qr_path'] = null;
        }

        if ($data['is_default']) {
            BankDetail::where('id', '!=', $bankDetail->id)->update(['is_default' => false]);
        }

        $bankDetail->update($data);

        return redirect()->route('bank_details.index')->with('success', 'Bank details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankDetail $bankDetail)
    {
        $this->deleteQrImage($bankDetail);

        $bankDetail->delete();

        return redirect()->route('bank_details.index')->with('success', 'Bank details deleted successfully.');
    }

    /**
     * Validate the bank form and normalise the checkbox fields.
     */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:150',
            'account_name' => 'nullable|string|max:150',
            'account_number' => 'required|string|max:50',
            'ifsc' => 'nullable|string|max:20',
            'swift' => 'nullable|string|max:20',
            'branch' => 'nullable|string|max:255',
            'upi_id' => 'nullable|string|max:100',
            'upi_qr' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        unset($validated['upi_qr']);
        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        // The default bank must always be selectable on invoices.
        if ($validated['is_default']) {
            $validated['is_active'] = true;
        }

        return $validated;
    }

    private function storeQrImage(Request $request): string
    {
        $file = $request->file('upi_qr');
        $filename = 'upi_qr_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $targetDir = public_path('uploads/bank_qr');
        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $file->move($targetDir, $filename);
        return 'uploads/bank_qr/' . $filename;
    }

    private function deleteQrImage(BankDetail $bankDetail): void
    {
        // Only remove uploaded files, never the bundled images/upi_qr.png.
        if ($bankDetail->upi_qr_path
            && str_starts_with($bankDetail->upi_qr_path, 'uploads/')
            && File::exists(public_path($bankDetail->upi_qr_path))) {
            File::delete(public_path($bankDetail->upi_qr_path));
        }
    }
}
