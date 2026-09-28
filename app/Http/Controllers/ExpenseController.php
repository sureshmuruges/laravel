<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Booking;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('JobNo', 'like', "%{$search}%")
                  ->orWhere('Reference', 'like', "%{$search}%")
                  ->orWhere('Description', 'like', "%{$search}%")
                  ->orWhere('CompanyName', 'like', "%{$search}%")
                  ->orWhere('MAWB_MBL', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['id', 'JobNo', 'Date', 'CompanyName', 'Total', 'Currency', 'Reference'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $expenses = $query->paginate(20)->withQueryString();

        return view('expenses.index', compact('expenses', 'sortField', 'sortDirection'));
    }

    /**
     * Export the expenses list as an Excel-compatible CSV file.
     */
    public function export(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('JobNo', 'like', "%{$search}%")
                  ->orWhere('Reference', 'like', "%{$search}%")
                  ->orWhere('Description', 'like', "%{$search}%")
                  ->orWhere('CompanyName', 'like', "%{$search}%")
                  ->orWhere('MAWB_MBL', 'like', "%{$search}%");
            });
        }

        $expenses = $query->get();

        $columns = ['Id', 'Job No', 'Date', 'Reference', 'Description', 'Company Name', 'MAWB/MBL', 'Currency', 'Total'];

        return response()->streamDownload(function () use ($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->id,
                    $expense->JobNo,
                    $expense->Date,
                    $expense->Reference,
                    $expense->Description,
                    $expense->CompanyName,
                    $expense->MAWB_MBL,
                    $expense->Currency,
                    $expense->Total,
                ]);
            }
            fclose($file);
        }, 'expenses.csv');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookings = Booking::orderBy('Id', 'desc')->take(15)->pluck('BookingNo');
        return view('expenses.create', compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Category' => 'nullable|string',
            'JobNo' => 'required|string',
            'Date' => 'nullable|date',
            'Reference' => 'nullable|string',
            'Description' => 'nullable|string',
            'AccountCode' => 'nullable|string',
            'CompanyName' => 'nullable|string',
            'MAWB_MBL' => 'nullable|string',
            'Currency' => 'nullable|string',
            'ExRate' => 'nullable|numeric',
            'Total' => 'nullable|numeric',
            'Month' => 'nullable|string'
        ]);

        // CCode is required by DB but not in form as per user request
        $validatedData['CCode'] = $request->input('CCode', '1'); 

        Expense::create($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $bookings = Booking::orderBy('Id', 'desc')->take(15)->pluck('BookingNo');
        return view('expenses.edit', compact('expense', 'bookings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $validatedData = $request->validate([
            'Category' => 'nullable|string',
            'JobNo' => 'required|string',
            'Date' => 'nullable|date',
            'Reference' => 'nullable|string',
            'Description' => 'nullable|string',
            'AccountCode' => 'nullable|string',
            'CompanyName' => 'nullable|string',
            'MAWB_MBL' => 'nullable|string',
            'Currency' => 'nullable|string',
            'ExRate' => 'nullable|numeric',
            'Total' => 'nullable|numeric',
            'Month' => 'nullable|string'
        ]);

        $expense->update($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
