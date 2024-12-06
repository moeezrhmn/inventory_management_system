<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\WarehouseItem;
use App\Models\WarehouseItemTransaction;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::all();

        return view('warehouses.index', [
            'warehouses' => $warehouses,
        ]);
    }

    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required'
        ]);

        Warehouse::create([
            "name" => $request->name,
            "location" => $request->location,
            "description" => $request->description,
        ]);

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Warehouse has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse_record = $warehouse->toArray();

        $warehouseData = $warehouse->items()->with(['unit', 'transactions'])->paginate(10);

        return view('warehouses.show', [
            "warehouse" => $warehouse_record,
            "warehouseData" => $warehouseData
        ]);
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', [
            'warehouse' => $warehouse
        ]);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $warehouse->update([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Warehouse has been updated!');
    }

    public function destroy(Warehouse $warehouse)
    {
        try {
            // $warehouse->delete();
            return response()->json(['success' => 'Warehouse has been deleted!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    function wcreate($warehouse_id){
        $units = Unit::all();
        return view('warehouses.add-detail', compact('warehouse_id', 'units'));
    }

    function wstore(Request $request)
    {
        $request->validate([
            'item' => 'required',
            'pieces' => 'nullable',
        ]);

        WarehouseItem::create([
            'warehouse_id' => $request->warehouse_id,
            'item_name' => $request->item,
            'pieces' => 0,
            'unit_id' => $request->unit_id,
            'measurements' => $request->measurements,
        ]);

        return redirect()->route('warehouses.show', $request->warehouse_id)
            ->with('success', 'Warehouse detail added successfully!');
    }
    public function wedit($id)
    {
        $warehouseItem = WarehouseItem::findOrFail($id); 
        $itemStock = WarehouseItemTransaction::get_stock($warehouseItem->id);
        $units = Unit::all(); 
        return view('warehouses.edit-table', compact('warehouseItem', 'units', 'itemStock'));
    }

    public function wupdate(Request $request, $id)
    {

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id', 
            'pieces' => 'nullable',
            'measurements' => 'required',
        ]);
        
        $warehouseItem = WarehouseItem::findOrFail($id);
        // $warehouseItem->update([
        //     'item_name' => $request->item_name,
        //     'pieces' => $request->pieces,
        //     'unit_id' => $request->unit_id,
        //     'measurements' => $request->measurements,
        // ]);
        $warehouseItem->update($validated);

        return redirect()->route('warehouses.show', $warehouseItem->warehouse_id) ->with('success', 'Item updated successfully!');
    }
    public function wdestroy($id)
    {
        $WarehouseItem = WarehouseItem::findOrFail($id);
        $WarehouseItem->delete();
        return response()->json(['success' => 'WarehouseItem work deleted successfully']);
    }

    public function wsearch(Request $request)
    {
        $query = $request->input('query');
        $warehouseData = WarehouseItem::where('item_name', 'like', "%$query%")->get();

        return response()->json([
            'html' => view('warehouses.detail_table', compact('warehouseData'))->render()
        ]);
    }


    // WAREHOUSE ITEMS TRANSACTIONS
    public function warehouse_item_transactions($warehouse_item_id){

        $warehouseItem = WarehouseItem::find($warehouse_item_id);
        $suppliers = Supplier::all();
        if(!$warehouseItem){
            return redirect('/warehouses');
        }
        $warehouseItemTransactions = WarehouseItemTransaction::with('supplier')->where('warehouse_item_id', $warehouse_item_id)->paginate(10);
        // dd($warehouseItemTransactions->supplier);

        return view('warehouses.warehouse_item_transactions', compact('warehouseItemTransactions', 'warehouseItem', 'suppliers'));
    }
    public function create_warehouse_item_transaction(Request $request){
        
        $validated = $request->validate([
            'warehouse_item_id' => 'required',
            'quantity' => 'required|integer',
            'reference' => 'nullable|string' 
        ]);

        WarehouseItemTransaction::create($validated);

        return redirect()->back()->with('success', 'Transaction created successfully.');
    }
    public function warehouse_item_transaction_purchase(Request $request){
        
        // dd($request->all());
        $validated = $request->validate([
            'warehouse_item_id' => 'required',
            'supplier_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string', 
            'per_piece_price' => 'required',
            'total_payment' => 'required',
            'total_paid' => 'integer',
        ]);
        $payments_record = [];
        $new_payment = [
            'amount' => $validated['total_paid'],
            'date' => now(),
            'reference' =>  '',
        ];
        $payments_record[] = $new_payment; 
        $validated['payments_record'] = json_encode($payments_record);
        WarehouseItemTransaction::create($validated);

        return redirect()->back()->with('success', 'Purchase added successfully.');
    }
    public function warehouse_item_transaction_change_total_paid(Request $request){
        
        $validated = $request->validate([
            'transaction_id' => 'required',
            'total_paid' => 'integer',
        ]);
        $warehouseItemTransaction = WarehouseItemTransaction::find($validated['transaction_id']);
        $warehouseItemTransaction->update([
            'total_paid' => $validated['total_paid']
        ]);
        return response()->json(['message'=>'Total Paid updated successfully!']);
    }
    public function warehouse_item_transaction_add_payment(Request $request){
        
        $validated = $request->validate([
            'transaction_id' => 'required',
            'amount' => 'required|numeric|',
            'date' => 'required|date',
            'reference' => 'nullable|string',
        ]);
    
        $warehouseItemTransaction = WarehouseItemTransaction::find($validated['transaction_id']);
        if (!$warehouseItemTransaction) {
            return redirect()->back()->withErrors('Transaction not found!');
        }
    
        $payments_record = json_decode($warehouseItemTransaction->payments_record, true) ?? [];
        $new_payment = [
            'amount' => $validated['amount'],
            'date' => $validated['date'],
            'reference' => $validated['reference'] ?? '',
        ];
        $payments_record[] = $new_payment; 
    
        $warehouseItemTransaction->update([
            'payments_record' => json_encode($payments_record),
        ]);
    
        // Calculate total_paid
        $total_paid = array_reduce($payments_record, function ($carry, $record) {
            return $carry + (float) ($record['amount'] ?? 0);
        }, 0);
    
        $warehouseItemTransaction->update([
            'total_paid' => $total_paid,
        ]);
    
        return redirect()->back()->with('success', 'Payment added successfully!');
    }
    
    public function warehouse_item_transaction_get_payments(Request $request){
        $id = $request->transaction_id;
        $warehouseItemTransaction = WarehouseItemTransaction::find($id);
        $payments_record = json_decode($warehouseItemTransaction->payments_record, true);
        return response()->json(['payments_record'=>$payments_record]);
    }

    public function delete_warehouse_item_transaction($transaction_id){

        $warehouseItemTransaction = WarehouseItemTransaction::find($transaction_id);
        if(!$warehouseItemTransaction) return response()->json(['message' => 'warehouse item does not exist!'], 400);

        $warehouseItemTransaction->delete();

        return redirect()->back()->with('success', 'Warehouse item deleted successfully.');
    }
}
