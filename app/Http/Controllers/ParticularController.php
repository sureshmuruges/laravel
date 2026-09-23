<?php

namespace App\Http\Controllers;

use App\Models\Particular;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticularController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Particular::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('particulars', 'like', "%{$search}%")
                  ->orWhere('hsn', 'like', "%{$search}%");
        }

        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'desc');

        $allowedSorts = ['id', 'particulars', 'hsn', 'gst', 'igst', 'cgst', 'sgst', 'except_particulars', 'is_service', 'active'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $particulars = $query->paginate(50)->withQueryString();

        return view('particulars.index', compact('particulars', 'sortField', 'sortDirection'))
            ->with('i', (request()->input('page', 1) - 1) * 50);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('particulars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'particulars' => 'required|string|max:150',
            'hsn' => 'nullable|digits_between:1,10',
            'gst' => 'nullable|numeric',
            'igst' => 'nullable|numeric',
            'cgst' => 'nullable|numeric',
            'sgst' => 'nullable|numeric',
            'is_service' => 'nullable|in:Y,N',
            'active' => 'nullable|in:Y,N',
        ]);

        $data['except_particulars'] = $request->has('except_particulars') ? 1 : 0;
        $data['is_service'] = $data['is_service'] ?? 'Y';
        $data['active'] = $data['active'] ?? 'Y';

        Particular::create($data);
       
        return redirect()->route('particulars.index')
                        ->with('success','Particular created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Particular $particular)
    {
        return view('particulars.show', compact('particular'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Particular $particular)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('particulars.edit', compact('particular'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Particular $particular)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'particulars' => 'required|string|max:150',
            'hsn' => 'nullable|digits_between:1,10',
            'gst' => 'nullable|numeric',
            'igst' => 'nullable|numeric',
            'cgst' => 'nullable|numeric',
            'sgst' => 'nullable|numeric',
            'is_service' => 'nullable|in:Y,N',
            'active' => 'nullable|in:Y,N',
        ]);

        $data['except_particulars'] = $request->has('except_particulars') ? 1 : 0;
        $data['is_service'] = $data['is_service'] ?? 'Y';
        $data['active'] = $data['active'] ?? 'Y';

        $particular->update($data);
      
        return redirect()->route('particulars.index')
                        ->with('success','Particular updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Particular $particular)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $particular->delete();
       
        return redirect()->route('particulars.index')
                        ->with('success','Particular deleted successfully');
    }

    /**
     * Export the particulars list in CSV format.
     */
    public function export(Request $request)
    {
        $fileName = 'particulars.csv';
        
        $query = Particular::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('particulars', 'like', "%{$search}%")
                  ->orWhere('hsn', 'like', "%{$search}%");
        }

        $particulars = $query->get();

        $columns = ['Id', 'Particulars', 'HSN', 'GST', 'IGST', 'CGST', 'SGST', 'ExcepParticulars', 'IsService', 'Active'];

        return response()->streamDownload(function () use ($particulars, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($particulars as $particular) {
                fputcsv($file, [
                    $particular->id,
                    $particular->particulars,
                    $particular->hsn,
                    $particular->gst,
                    $particular->igst,
                    $particular->cgst,
                    $particular->sgst,
                    $particular->except_particulars ? 1 : 0,
                    $particular->is_service === 'Y' ? 'Y' : 'N',
                    $particular->active === 'Y' ? 'Y' : 'N',
                ]);
            }
            fclose($file);
        }, $fileName);
    }
}
