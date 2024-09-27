<?php

namespace App\Http\Controllers;

use App\Livewire\Tables\LabourTable;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\LabourWork;
use App\Models\Product;

class LabourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labours = Labour::all();

        return view('labours.index', [
            'labours' => $labours,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('labours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
        ]);
        
        Labour::create([
            "name" => $request->name,
            "address" => $request->address,
            "phone" => $request->phone,
        ]);

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Labour $labour)
    {
        $labourData = $labour->toArray();

        $labourWorks = $labour->labourWorks()->with('product')->paginate(10);

        return view('labours.show', [
            "labour" => $labourData,
            "labourWorks" => $labourWorks
        ]);
    }

    public function edit(Labour $labour)
    {
       return view('labours.edit', [
            'labour' => $labour
        ]);
    }

    public function update(Request $request, Labour $labour)
    {
        $labour->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()
        ->route('labours.index')
        ->with('success', 'Labour has been updated!');
    }

    public function destroy(Labour $labour)
    {
        $labour->delete();

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour has been deleted!');
    }

    function labourWork(){
        $labours = Labour::all();
        $products = Product::all();
        return view('labours.labour_work',compact('labours','products'));        
    }

    function addWork(Request $request){
        $validatedData = $request->validate([
            'labourID'   => 'required|exists:labours,id',  // Make sure the labour exists
            'pID'     => 'required|exists:products,id',    // Make sure the item exists
            'pieces'      => 'nullable|integer|min:1',      // Optional, defaults to 1
            'payment'     => 'nullable|numeric|min:0',      // Optional, defaults to 0
            'description' => 'nullable|string',             // Optional description
        ]);

        // Create a new LabourWork record
        LabourWork::create([
            'labour_id'   => $validatedData['labourID'],
            'p_id'     => $validatedData['pID'],
            'pieces'      => $validatedData['pieces'] ?? 1, // Default to 1 if not provided
            'payment'     => $validatedData['payment'] ?? 0, // Default to 0 if not provided
            'description' => $validatedData['description'] ?? null, // Optional
        ]);

        return redirect()
            ->route('labours.index')
            ->with('success', 'Labour Work has been added!');
    }

    public function edit_work($id)
    {
        $labourWork = LabourWork::findOrFail($id);
        $products = Product::all(); // Get all products to populate the dropdown

        return view('labours.edit_work', compact('labourWork', 'products'));
    }

    // Update the labour work
    public function update_work(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'p_id' => 'required|exists:products,id',
            'pieces' => 'required|integer',
            'payment' => 'required|numeric',
            'description' => 'required|string',
        ]);

        // Find the existing labour work
        $labourWork = LabourWork::findOrFail($id);

        // Update the labour work with new data
        $labourWork->update([
            'p_id' => $request->p_id,
            'pieces' => $request->pieces,
            'payment' => $request->payment,
            'description' => $request->description,
        ]);

        // Redirect with a success message
        return redirect()->route('labours.show', $labourWork->labour_id)
            ->with('success', 'Labour work updated successfully');
    }
    public function destroy_work($id)
    {
        $labourWork = LabourWork::findOrFail($id);
        $labourWork->delete();
        return response()->json(['success' => 'Labour work deleted successfully']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $labourWorks = LabourWork::whereHas('product', function ($q) use ($query) {
            $q->where('name', 'like', "%$query%");
        })->get();

        return response()->json([
            'html' => view('labours.work_table', compact('labourWorks'))->render()
        ]);
    }
}
