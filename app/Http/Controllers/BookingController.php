<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request)
    {
        $query = Booking::query();

        // Search functionality for all text fields
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('BookingNo', 'like', '%' . $search . '%')
                    ->orWhere('companyname', 'like', '%' . $search . '%')
                    ->orWhere('shipper', 'like', '%' . $search . '%')
                    ->orWhere('origin', 'like', '%' . $search . '%')
                    ->orWhere('Destination', 'like', '%' . $search . '%')
                    ->orWhere('MAWB_MBL', 'like', '%' . $search . '%')
                    ->orWhere('HAWB_HBL', 'like', '%' . $search . '%')
                    ->orWhere('Consignee', 'like', '%' . $search . '%')
                    ->orWhere('accode_companyname', 'like', '%' . $search . '%')
                    ->orWhere('acode_Shipper', 'like', '%' . $search . '%')
                    ->orWhere('accode_consignee', 'like', '%' . $search . '%')
                    ->orWhere('Vessel', 'like', '%' . $search . '%')
                    ->orWhere('Reference', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sortField = $request->input('sort', 'Id');
        $sortDirection = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'Id', 'BookingNo', 'booking_date', 'companyname', 'shipper', 
            'origin', 'Destination', 'MAWB_MBL', 'HAWB_HBL', 'Consignee', 
            'ETD', 'ETA', 'Pieces', 'Reference'
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'Id';
        }
        $query->orderBy($sortField, $sortDirection);

        $bookings = $query->paginate(15)->withQueryString();

        return view('bookings.index', compact('bookings', 'sortField', 'sortDirection'));
    }

    /**
     * Export the bookings list as an Excel-compatible CSV file.
     */
    public function export(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('BookingNo', 'like', '%' . $search . '%')
                    ->orWhere('companyname', 'like', '%' . $search . '%')
                    ->orWhere('shipper', 'like', '%' . $search . '%')
                    ->orWhere('origin', 'like', '%' . $search . '%')
                    ->orWhere('Destination', 'like', '%' . $search . '%')
                    ->orWhere('MAWB_MBL', 'like', '%' . $search . '%')
                    ->orWhere('HAWB_HBL', 'like', '%' . $search . '%')
                    ->orWhere('Consignee', 'like', '%' . $search . '%')
                    ->orWhere('accode_companyname', 'like', '%' . $search . '%')
                    ->orWhere('acode_Shipper', 'like', '%' . $search . '%')
                    ->orWhere('accode_consignee', 'like', '%' . $search . '%')
                    ->orWhere('Vessel', 'like', '%' . $search . '%')
                    ->orWhere('Reference', 'like', '%' . $search . '%');
            });
        }

        $bookings = $query->get();

        $columns = ['Id', 'Booking No', 'Category', 'Booking Date', 'Company', 'Shipper', 'Consignee', 'Origin', 'Destination', 'MAWB/MBL', 'HAWB/HBL', 'Reference', 'Active'];

        return response()->streamDownload(function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->Id,
                    $booking->BookingNo,
                    $booking->Category,
                    $booking->booking_date,
                    $booking->companyname,
                    $booking->shipper,
                    $booking->Consignee,
                    $booking->origin,
                    $booking->Destination,
                    $booking->MAWB_MBL,
                    $booking->HAWB_HBL,
                    $booking->Reference,
                    $booking->Active ? 'Yes' : 'No',
                ]);
            }
            fclose($file);
        }, 'bookings.csv');
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $nextBookingNo = $this->generateNextBookingNo();
        $addresses = \App\Models\Address::orderBy('CompanyName')->get(['Id', 'CompanyName', 'AccountCode']);
        return view('bookings.create', compact('nextBookingNo', 'addresses'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Category' => 'nullable|string',
            'booking_date' => 'required|date',
            'companyname' => 'nullable|string',
            'shipper' => 'nullable|string',
            'origin' => 'nullable|string',
            'Destination' => 'nullable|string',
            'MAWB_MBL' => 'nullable|string',
            'HAWB_HBL' => 'nullable|string',
            'Consignee' => 'nullable|string',
            'Pieces' => 'nullable|integer',
            'ETD' => 'nullable|string',
            'ETA' => 'nullable|string',
            'accode_companyname' => 'nullable|string',
            'acode_Shipper' => 'nullable|string',
            'accode_consignee' => 'nullable|string',
            'IATA' => 'nullable|string',
            'SBNo' => 'nullable|string',
            'SBDate' => 'nullable|date',
            'ShipperInvoice' => 'nullable|string',
            'Line' => 'nullable|string',
            'IGM_EGM' => 'nullable|string',
            'CBM' => 'nullable|numeric',
            'GrWeight' => 'nullable|numeric',
            'ChWeight' => 'nullable|numeric',
            'Vessel' => 'nullable|string',
            'Volume' => 'nullable|string',
            'FCL' => 'nullable|string',
            'TOS' => 'nullable|string',
            'IEC' => 'nullable|string',
            'OOC' => 'nullable|string',
            'Asses' => 'nullable|string',
            'LUT' => 'nullable|string',
            'CFS' => 'nullable|string',
            'SalesRep' => 'nullable|string',
            'Reference' => 'nullable|string',
            'Month' => 'nullable|string',
            'Year' => 'nullable|string',
            'Active' => 'nullable|boolean'
        ]);

        // Default Category if empty
        if (empty($validatedData['Category'])) {
            $validatedData['Category'] = 'client address';
        }

        // Generate BookingNo
        $validatedData['BookingNo'] = $this->generateNextBookingNo();
        
        // Add audit fields if needed
        $validatedData['CreateBy'] = auth()->user()->name ?? 'System';

        Booking::create($validatedData);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load('expenses');
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        $addresses = \App\Models\Address::orderBy('CompanyName')->get(['Id', 'CompanyName', 'AccountCode']);
        return view('bookings.edit', compact('booking', 'addresses'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validatedData = $request->validate([
            'Category' => 'nullable|string',
            'booking_date' => 'required|date',
            'companyname' => 'nullable|string',
            'shipper' => 'nullable|string',
            'origin' => 'nullable|string',
            'Destination' => 'nullable|string',
            'MAWB_MBL' => 'nullable|string',
            'HAWB_HBL' => 'nullable|string',
            'Consignee' => 'nullable|string',
            'Pieces' => 'nullable|integer',
            'ETD' => 'nullable|string',
            'ETA' => 'nullable|string',
            'accode_companyname' => 'nullable|string',
            'acode_Shipper' => 'nullable|string',
            'accode_consignee' => 'nullable|string',
            'IATA' => 'nullable|string',
            'SBNo' => 'nullable|string',
            'SBDate' => 'nullable|date',
            'ShipperInvoice' => 'nullable|string',
            'Line' => 'nullable|string',
            'IGM_EGM' => 'nullable|string',
            'CBM' => 'nullable|numeric',
            'GrWeight' => 'nullable|numeric',
            'ChWeight' => 'nullable|numeric',
            'Vessel' => 'nullable|string',
            'Volume' => 'nullable|string',
            'FCL' => 'nullable|string',
            'TOS' => 'nullable|string',
            'IEC' => 'nullable|string',
            'OOC' => 'nullable|string',
            'Asses' => 'nullable|string',
            'LUT' => 'nullable|string',
            'CFS' => 'nullable|string',
            'SalesRep' => 'nullable|string',
            'Reference' => 'nullable|string',
            'Month' => 'nullable|string',
            'Year' => 'nullable|string',
            'Active' => 'nullable|boolean'
        ]);

        $validatedData['ModifyBy'] = auth()->user()->name ?? 'System';

        $booking->update($validatedData);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    /**
     * Private helper to generate BookingNo (Year + sequential number).
     */
    private function generateNextBookingNo(): string
    {
        $companyDetail = \App\Models\CompanyDetail::getActive();
        $prefix = ($companyDetail && !empty($companyDetail->booking_code_prefix))
            ? $companyDetail->booking_code_prefix
            : (string) (2026 + 1); // Default: year-based, e.g. 2027

        $prefixLength = strlen($prefix);

        // Get the latest booking number starting with the prefix
        $lastBooking = Booking::where('BookingNo', 'like', $prefix . '%')
            ->orderBy('BookingNo', 'desc')
            ->first();

        if ($lastBooking) {
            $lastNum = (int) substr($lastBooking->BookingNo, $prefixLength);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}
