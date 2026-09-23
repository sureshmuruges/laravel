<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceParticularController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\InvoiceParticular::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('BillNo', 'like', "%{$search}%")
                  ->orWhere('ProformaInvoiceNo', 'like', "%{$search}%")
                  ->orWhere('Particulars', 'like', "%{$search}%")
                  ->orWhere('HSN', 'like', "%{$search}%");
            });
        }

        $sortField = $request->input('sort', 'Id');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['Id', 'BillNo', 'ProformaInvoiceNo', 'Particulars', 'TaxAmount', 'Total', 'CreateDate'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('Id', 'desc');
        }

        $particulars = $query->paginate(20)->withQueryString();

        return view('invoice_particulars.index', compact('particulars', 'sortField', 'sortDirection'));
    }

    /**
     * Export the invoice particulars list as an Excel-compatible CSV file.
     */
    public function export(Request $request)
    {
        $query = \App\Models\InvoiceParticular::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('BillNo', 'like', "%{$search}%")
                  ->orWhere('ProformaInvoiceNo', 'like', "%{$search}%")
                  ->orWhere('Particulars', 'like', "%{$search}%")
                  ->orWhere('HSN', 'like', "%{$search}%");
            });
        }

        $particulars = $query->get();

        $columns = ['Id', 'Bill No', 'Particulars', 'HSN', 'Tax Amount', 'Total', 'Date'];

        return response()->streamDownload(function () use ($particulars, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($particulars as $item) {
                fputcsv($file, [
                    $item->Id,
                    $item->BillNo,
                    $item->Particulars,
                    $item->HSN,
                    $item->TaxAmount,
                    $item->Total,
                    $item->CreateDate,
                ]);
            }
            fclose($file);
        }, 'invoice_particulars.csv');
    }

    public function create()
    {
        $products = \App\Models\Particular::select('id', 'particulars', 'hsn', 'gst', 'igst', 'cgst', 'sgst', 'is_service', 'except_particulars')
            ->where('active', 1)
            ->orderBy('particulars')
            ->get();
        return view('invoice_particulars.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'HSN' => 'nullable|numeric',
            'Particulars' => 'nullable|string|max:200',
            'Additional' => 'nullable|string|max:200',

            'NonTaxAmount' => 'nullable|numeric',
            'NonTaxAmt_NonINR' => 'nullable|numeric',
            'TaxAmount' => 'nullable|numeric',
            'IGST' => 'nullable|numeric',
            'IGSTValue' => 'nullable|numeric',
            'SGST' => 'nullable|numeric',
            'SGSTValue' => 'nullable|numeric',
            'CGST' => 'nullable|numeric',
            'CGSTValue' => 'nullable|numeric',
            'Total' => 'nullable|numeric',
            'IsService' => 'nullable|string|max:3',
            'ExceptionalParticulars' => 'nullable|string|max:3',
        ]);

        // Hardcoded & auto-generated fields
        $validatedData['InvoiceType'] = 'Standard';
        $validatedData['ProformaInvoiceNo'] = \Illuminate\Support\Str::random(16);
        $validatedData['BillNo'] = \Illuminate\Support\Str::random(16);
        $validatedData['CreditNoteNo'] = \Illuminate\Support\Str::random(16);
        $validatedData['CreateDate'] = now();
        $validatedData['CreateBy'] = \Illuminate\Support\Facades\Auth::user()->name ?? 'System';
        $validatedData['Month'] = date('m');
        $validatedData['Year'] = date('Y');

        \App\Models\InvoiceParticular::create($validatedData);

        return redirect()->route('invoice_particulars.index')->with('success', 'Particular added successfully.');
    }

    public function edit(\App\Models\InvoiceParticular $invoice_particular)
    {
        $products = \App\Models\Particular::select('id', 'particulars', 'hsn', 'gst', 'igst', 'cgst', 'sgst', 'is_service', 'except_particulars')
            ->where('active', 1)
            ->orderBy('particulars')
            ->get();
        return view('invoice_particulars.edit', compact('invoice_particular', 'products'));
    }

    public function update(Request $request, \App\Models\InvoiceParticular $invoice_particular)
    {
        $validatedData = $request->validate([
            'HSN' => 'nullable|numeric',
            'Particulars' => 'nullable|string|max:200',
            'Additional' => 'nullable|string|max:200',

            'NonTaxAmount' => 'nullable|numeric',
            'NonTaxAmt_NonINR' => 'nullable|numeric',
            'TaxAmount' => 'nullable|numeric',
            'IGST' => 'nullable|numeric',
            'IGSTValue' => 'nullable|numeric',
            'SGST' => 'nullable|numeric',
            'SGSTValue' => 'nullable|numeric',
            'CGST' => 'nullable|numeric',
            'CGSTValue' => 'nullable|numeric',
            'Total' => 'nullable|numeric',
            'IsService' => 'nullable|string|max:3',
            'ExceptionalParticulars' => 'nullable|string|max:3',
        ]);

        $validatedData['ModifyDate'] = now();
        $validatedData['ModifyBy'] = \Illuminate\Support\Facades\Auth::user()->name ?? 'System';

        $invoice_particular->update($validatedData);

        return redirect()->route('invoice_particulars.index')->with('success', 'Particular updated successfully.');
    }

    public function destroy(\App\Models\InvoiceParticular $invoice_particular)
    {
        $invoice_particular->delete();
        return redirect()->route('invoice_particulars.index')->with('success', 'Particular deleted successfully.');
    }
}
