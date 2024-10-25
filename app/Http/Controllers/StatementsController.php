<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Quotation;
use PDF;
use Illuminate\Http\Request;

class StatementsController extends Controller
{
    public $selectedCustomer = null;
    public $startDate = null;
    public $endDate = null;



    public function index()
    {
        $customers = Customer::all();
        return view('statements.index', compact('customers'));
    }

    public function report_pdf(Request $request)
    {
        $validated = $request->validate([
            'customer_id'=>'required',
            'start_date'=>'nullable',
            'end_date'=>'nullable',
        ]);
        $customer = Customer::find($validated['customer_id']);
        $orders = Order::with(['details', 'installments'])
            ->where('customer_id', $validated['customer_id'])
            ->when($validated['start_date'], function ($query) use ($validated) {
                $query->whereDate('created_at', '>=', $validated['start_date']);
            })
            ->when($validated['end_date'], function ($query) use ($validated) {
                $query->whereDate('created_at', '<=', $validated['end_date']);
            })
            ->get()->toArray();

        $quotations = Quotation::query()
            ->where('customer_id', $validated['customer_id'] )
            ->when($validated['start_date'], function ($query) use ($validated) {
                $query->whereDate('created_at', '>=', $validated['start_date']);
            })
            ->when($validated['end_date'], function ($query) use ($validated) {
                $query->whereDate('created_at', '<=', $validated['end_date']);
            })
            ->get()->toArray();
            $data = [
                'customer' => $customer,
                'orders' => $orders,
                'quotations' => $quotations,
            ];
            
            // dd($orders, $quotations);
            $pdf = PDF::loadView('statements.report_pdf', $data)->setPaper('a4', 'landscape');
            return $pdf->stream();
    }
}
