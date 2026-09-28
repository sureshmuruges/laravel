<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Particular;
use App\Models\InvoiceParticular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('billno', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('booking_no', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['id', 'billno', 'billdate', 'company_name', 'booking_no', 'grand_total', 'status'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('invoices.index', compact('invoices', 'sortField', 'sortDirection'));
    }

    /**
     * Export the invoices list as an Excel-compatible CSV file.
     */
    public function export(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('billno', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('booking_no', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $invoices = $query->get();

        $columns = ['Id', 'Invoice No', 'Invoice Date', 'Client Name', 'Booking No', 'Grand Total', 'Currency', 'Status'];

        return response()->streamDownload(function () use ($invoices, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->id,
                    $invoice->billno,
                    $invoice->billdate,
                    $invoice->company_name,
                    $invoice->booking_no,
                    $invoice->grand_total,
                    $invoice->currency,
                    $invoice->status,
                ]);
            }
            fclose($file);
        }, 'invoices.csv');
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        // Load active clients from address table (where Type = 'client')
        $clients = Address::where('Type', 'client')
            ->orderBy('CompanyName')
            ->get();

        // Load active bookings
        $bookings = Booking::where('Active', true)
            ->orderBy('BookingNo', 'desc')
            ->get();

        // Load active particulars
        $particularsMaster = Particular::where('active', 1)
            ->orderBy('particulars')
            ->get();

        // Load banks and signatures from address table
        // Address table might store banks and signatures under Type 'bank' or 'signature'
        $banks = Address::where('Type', 'bank')
            ->orderBy('CompanyName')
            ->get();

        $signatures = Address::where('Type', 'signature')
            ->orderBy('CompanyName')
            ->get();

        return view('invoices.create', compact('clients', 'bookings', 'particularsMaster', 'banks', 'signatures'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_type' => 'nullable|string|max:100',
            'invoice_category' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'taxsch' => 'nullable|string|max:100',
            'stype' => 'nullable|string|max:100',
            'irn' => 'nullable|string|max:64',
            'booking_no' => 'nullable|string|max:100',
            'proforma_invoice_no' => 'nullable|string|max:100',
            'proforma_invoice_date' => 'nullable|date',
            'billno' => 'nullable|string|max:100',
            'billdate' => 'required|date',
            'credit_note_no' => 'nullable|string|max:100',
            'credit_note_date' => 'nullable|date',
            'shipper_invoice' => 'nullable|string|max:100',
            'shipper_consignee' => 'nullable|string',
            'acode' => 'required|string|max:50',
            'company_name' => 'required|string|max:200',
            'aline1' => 'nullable|string|max:255',
            'aline2' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|string|max:100',
            'gst_no' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
            'state_code' => 'nullable|string|max:10',
            'po_supply' => 'nullable|string|max:200',
            'guarantee_l1' => 'nullable|string|max:200',
            'guarantee_l2' => 'nullable|string|max:200',
            'guarantee_l3' => 'nullable|string|max:200',
            'guarantee_l4' => 'nullable|string|max:200',
            
            // Financial values
            'total_non_tax' => 'required|numeric',
            'total_tax' => 'required|numeric',
            'sub_total' => 'required|numeric',
            'igst_value' => 'required|numeric',
            'sgst_value' => 'required|numeric',
            'cgst_value' => 'required|numeric',
            'total' => 'required|numeric',
            'total_non_inr' => 'nullable|numeric',
            'round_off' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'advance' => 'nullable|numeric',
            'balance' => 'required|numeric',
            
            // Status and metadata
            'status' => 'required|string|max:50',
            'currency' => 'nullable|string|max:50',
            'ex_rate' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'exten_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'credit_days' => 'nullable|integer',
            'bank' => 'nullable|string|max:200',
            'hcode' => 'nullable|string|max:100',
            'total_expense' => 'nullable|numeric',
            
            // Line items JSON
            'particulars_json' => 'required|string'
        ]);

        // Auto-generate invoice number if empty
        if (empty($validated['billno'])) {
            $validated['billno'] = Invoice::generateInvoiceNumber($validated['billdate']);
        }

        // Set month & year from billdate
        try {
            $billDateObj = new \DateTime($validated['billdate']);
            $validated['month'] = $billDateObj->format('m');
            $validated['year'] = $billDateObj->format('Y');
        } catch (\Exception $e) {
            $validated['month'] = date('m');
            $validated['year'] = date('Y');
        }

        $validated['created_by'] = Auth::user()->name ?? 'System';

        // Create Invoice
        $invoice = Invoice::create($validated);

        // Parse and save line items
        $particulars = json_decode($request->input('particulars_json'), true);
        if (is_array($particulars)) {
            foreach ($particulars as $p) {
                InvoiceParticular::create([
                    'InvoiceType' => $validated['invoice_type'] ?? 'TaxInvoice',
                    'ProformaInvoiceNo' => $validated['proforma_invoice_no'] ?? null,
                    'BillNo' => $invoice->billno,
                    'CreditNoteNo' => $validated['credit_note_no'] ?? null,
                    'HSN' => $p['hsn'] ?? null,
                    'Particulars' => $p['particulars'] ?? '',
                    'Additional' => $p['additional'] ?? null,
                    'NonTaxAmount' => $p['non_tax_amount'] ?? 0.00,
                    'NonTaxAmt_NonINR' => $p['non_tax_amt_non_inr'] ?? 0.00,
                    'TaxAmount' => $p['tax_amount'] ?? 0.00,
                    'IGST' => $p['igst'] ?? 0.00,
                    'IGSTValue' => $p['igst_value'] ?? 0.00,
                    'SGST' => $p['sgst'] ?? 0.00,
                    'SGSTValue' => $p['sgst_value'] ?? 0.00,
                    'CGST' => $p['cgst'] ?? 0.00,
                    'CGSTValue' => $p['cgst_value'] ?? 0.00,
                    'Total' => $p['total'] ?? 0.00,
                    'IsService' => $p['is_service'] ?? '',
                    'ExceptionalParticulars' => $p['exceptional_particulars'] ?? '',
                    'Month' => $invoice->month,
                    'Year' => $invoice->year,
                    'CreateDate' => now(),
                    'CreateBy' => Auth::user()->name ?? 'System',
                ]);
            }
        }

        return redirect()->route('invoices.index')->with('success', "Invoice {$invoice->billno} created successfully.");
    }

    /**
     * Display the specified invoice (Print-ready page).
     */
    public function show(Invoice $invoice)
    {
        // Load associated particulars
        $particulars = InvoiceParticular::where('BillNo', $invoice->billno)->get();

        return view('invoices.show', compact('invoice', 'particulars'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        $clients = Address::where('Type', 'client')
            ->orderBy('CompanyName')
            ->get();

        $bookings = Booking::where('Active', true)
            ->orderBy('BookingNo', 'desc')
            ->get();

        $particularsMaster = Particular::where('active', 1)
            ->orderBy('particulars')
            ->get();

        $banks = Address::where('Type', 'bank')
            ->orderBy('CompanyName')
            ->get();

        $signatures = Address::where('Type', 'signature')
            ->orderBy('CompanyName')
            ->get();

        // Get existing line items
        $existingParticulars = InvoiceParticular::where('BillNo', $invoice->billno)->get();

        return view('invoices.edit', compact('invoice', 'clients', 'bookings', 'particularsMaster', 'banks', 'signatures', 'existingParticulars'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_type' => 'nullable|string|max:100',
            'invoice_category' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'taxsch' => 'nullable|string|max:100',
            'stype' => 'nullable|string|max:100',
            'irn' => 'nullable|string|max:64',
            'booking_no' => 'nullable|string|max:100',
            'proforma_invoice_no' => 'nullable|string|max:100',
            'proforma_invoice_date' => 'nullable|date',
            'billno' => 'required|string|max:100',
            'billdate' => 'required|date',
            'credit_note_no' => 'nullable|string|max:100',
            'credit_note_date' => 'nullable|date',
            'shipper_invoice' => 'nullable|string|max:100',
            'shipper_consignee' => 'nullable|string',
            'acode' => 'required|string|max:50',
            'company_name' => 'required|string|max:200',
            'aline1' => 'nullable|string|max:255',
            'aline2' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|string|max:100',
            'gst_no' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
            'state_code' => 'nullable|string|max:10',
            'po_supply' => 'nullable|string|max:200',
            'guarantee_l1' => 'nullable|string|max:200',
            'guarantee_l2' => 'nullable|string|max:200',
            'guarantee_l3' => 'nullable|string|max:200',
            'guarantee_l4' => 'nullable|string|max:200',
            
            // Financial values
            'total_non_tax' => 'required|numeric',
            'total_tax' => 'required|numeric',
            'sub_total' => 'required|numeric',
            'igst_value' => 'required|numeric',
            'sgst_value' => 'required|numeric',
            'cgst_value' => 'required|numeric',
            'total' => 'required|numeric',
            'total_non_inr' => 'nullable|numeric',
            'round_off' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'advance' => 'nullable|numeric',
            'balance' => 'required|numeric',
            
            // Status and metadata
            'status' => 'required|string|max:50',
            'currency' => 'nullable|string|max:50',
            'ex_rate' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'exten_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'credit_days' => 'nullable|integer',
            'bank' => 'nullable|string|max:200',
            'hcode' => 'nullable|string|max:100',
            'total_expense' => 'nullable|numeric',
            
            // Line items JSON
            'particulars_json' => 'required|string'
        ]);

        // Set month & year from billdate
        try {
            $billDateObj = new \DateTime($validated['billdate']);
            $validated['month'] = $billDateObj->format('m');
            $validated['year'] = $billDateObj->format('Y');
        } catch (\Exception $e) {
            $validated['month'] = date('m');
            $validated['year'] = date('Y');
        }

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $oldBillNo = $invoice->billno;

        // Update Invoice
        $invoice->update($validated);

        // Delete old associated particulars in case the billno has changed
        InvoiceParticular::where('BillNo', $oldBillNo)->delete();

        // Save new line items
        $particulars = json_decode($request->input('particulars_json'), true);
        if (is_array($particulars)) {
            foreach ($particulars as $p) {
                InvoiceParticular::create([
                    'InvoiceType' => $validated['invoice_type'] ?? 'TaxInvoice',
                    'ProformaInvoiceNo' => $validated['proforma_invoice_no'] ?? null,
                    'BillNo' => $invoice->billno,
                    'CreditNoteNo' => $validated['credit_note_no'] ?? null,
                    'HSN' => $p['hsn'] ?? null,
                    'Particulars' => $p['particulars'] ?? '',
                    'Additional' => $p['additional'] ?? null,
                    'NonTaxAmount' => $p['non_tax_amount'] ?? 0.00,
                    'NonTaxAmt_NonINR' => $p['non_tax_amt_non_inr'] ?? 0.00,
                    'TaxAmount' => $p['tax_amount'] ?? 0.00,
                    'IGST' => $p['igst'] ?? 0.00,
                    'IGSTValue' => $p['igst_value'] ?? 0.00,
                    'SGST' => $p['sgst'] ?? 0.00,
                    'SGSTValue' => $p['sgst_value'] ?? 0.00,
                    'CGST' => $p['cgst'] ?? 0.00,
                    'CGSTValue' => $p['cgst_value'] ?? 0.00,
                    'Total' => $p['total'] ?? 0.00,
                    'IsService' => $p['is_service'] ?? '',
                    'ExceptionalParticulars' => $p['exceptional_particulars'] ?? '',
                    'Month' => $invoice->month,
                    'Year' => $invoice->year,
                    'CreateDate' => now(),
                    'CreateBy' => Auth::user()->name ?? 'System',
                ]);
            }
        }

        return redirect()->route('invoices.index')->with('success', "Invoice {$invoice->billno} updated successfully.");
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $billNo = $invoice->billno;
        
        // Delete associated particulars
        InvoiceParticular::where('BillNo', $billNo)->delete();
        
        // Delete the invoice itself
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', "Invoice {$billNo} deleted successfully.");
    }

    /**
     * Generate and download the PDF for the specified invoice.
     */
    public function pdf(Invoice $invoice)
    {
        $particulars = InvoiceParticular::where('BillNo', $invoice->billno)->get();

        $companyDetail = \App\Models\CompanyDetail::getActive();
        $showIrnQr = $companyDetail ? (bool) $companyDetail->irn_qr_enabled : true;

        // Fetch QR code via cURL to bypass allow_url_fopen limitations on shared hosts
        $qrCodeBase64 = null;
        if ($showIrnQr) {
            try {
                $qrData = "Invoice No: " . $invoice->billno . "\nGSTIN: 29AHWPT9984H1ZV\nAmount: " . $invoice->grand_total . "\nIRN: " . ($invoice->irn ?? '');
                $url = "https://api.qrserver.com/v1/create-qr-code/?size=70x70&data=" . urlencode($qrData);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $qrCodeData = curl_exec($ch);
                curl_close($ch);

                if ($qrCodeData) {
                    $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCodeData);
                }
            } catch (\Exception $e) {
                $qrCodeBase64 = null;
            }
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice', 'particulars', 'qrCodeBase64'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setWarnings(false);
        $pdf->setOption('isRemoteEnabled', true);
        
        return $pdf->download("Invoice_{$invoice->billno}.pdf");
    }
}

