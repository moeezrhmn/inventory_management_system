<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use App\Mail\StockAlert;
use App\Models\OrderPaymentInstallment;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::count();
        // dd($orders);
        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $products = Product::with(['category', 'unit'])->get();

        $customers = Customer::get(['id', 'name']);

        $carts = Cart::content();

        return view('orders.create', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    public function store(OrderStoreRequest $request)
    {
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'payment_type' => $request->payment_type,
            'pay' => $request->pay,
            'order_date' => Carbon::now()->format('Y-m-d'),
            'order_status' => OrderStatus::PENDING->value,
            'total_products' => Cart::count(),
            'sub_total' => Cart::subtotal(),
            'vat' => Cart::tax(),
            'total' => Cart::total(),
            'invoice_no' => IdGenerator::generate([
                'table' => 'orders',
                'field' => 'invoice_no',
                'length' => 10,
                'prefix' => 'INV-'
            ]),
            'due' => (Cart::total() - $request->pay),
            'user_id' => auth()->id(),
            'uuid' => Str::uuid(),
        ]);

        // add installment
        OrderPaymentInstallment::create([
            'order_id' => $order['id'],
            'payment' => $request->pay
        ]);

        // Create Order Details
        $contents = Cart::content();
        $oDetails = [];

        foreach ($contents as $content) {
            $oDetails['order_id'] = $order['id'];
            $oDetails['product_id'] = $content->id;
            $oDetails['quantity'] = $content->qty;
            $oDetails['unitcost'] = $content->price;
            $oDetails['total'] = $content->subtotal;
            $oDetails['created_at'] = Carbon::now();

            OrderDetails::insert($oDetails);
        }

        // Delete Cart Sopping History
        Cart::destroy();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been created!');
    }

    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->loadMissing(['customer', 'details'])->get();
        return view('orders.show', [
            'order' => $order
        ]);
    }

    public function update($uuid, Request $request)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        // TODO refactoring
        // Reduce the stock
        // $products = OrderDetails::where('order_id', $order->id)->get();

        // $stockAlertProducts = [];

        // foreach ($products as $product) {
        //     $productEntity = Product::where('id', $product->product_id)->first();
        //     $newQty = $productEntity->quantity - $product->quantity;
        //     if ($newQty < $productEntity->quantity_alert) {
        //         $stockAlertProducts[] = $productEntity;
        //     }
        //     $productEntity->update(['quantity' => $newQty]);
        // }

        // if (count($stockAlertProducts) > 0) {
        //     $listAdmin = [];
        //     foreach (User::all('email') as $admin) {
        //         $listAdmin [] = $admin->email;
        //     }
        //     Mail::to($listAdmin)->send(new StockAlert($stockAlertProducts));
        // }
        $order->update([
            'order_status' => OrderStatus::COMPLETE,
            'due' => '0',
            'pay' => $order->total
        ]);

        return redirect()
            ->route('orders.complete')
            ->with('success', 'Order has been completed!');
    }
    public function delivered_order($uuid, Request $request)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        // TODO refactoring
        // Reduce the stock
        $products = OrderDetails::where('order_id', $order->id)->get();

        $stockAlertProducts = [];

        foreach ($products as $product) {
            $productEntity = Product::where('id', $product->product_id)->first();
            $newQty = $productEntity->quantity - $product->quantity;
            if ($newQty < $productEntity->quantity_alert) {
                $stockAlertProducts[] = $productEntity;
            }
            $productEntity->update(['quantity' => $newQty]);
        }

        if (count($stockAlertProducts) > 0) {
            $listAdmin = [];
            foreach (User::all('email') as $admin) {
                $listAdmin [] = $admin->email;
            }
            Mail::to($listAdmin)->send(new StockAlert($stockAlertProducts));
        }
        $order->update([
            'order_status' => OrderStatus::DELIVERED,
        ]);

        return redirect()
            ->route('orders.pending')
            ->with('success', 'Order has been delivered!');
    }

    public function destroy($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->delete();
    }

    public function downloadInvoice($uuid)
    {
        $order = Order::with(['customer', 'details'])->where('uuid', $uuid)->firstOrFail();
        // TODO: Need refactor
        //dd($order);

        //$order = Order::with('customer')->where('id', $order_id)->first();
        // $order = Order::
        //     ->where('id', $order)
        //     ->first();

        return view('orders.print-invoice', [
            'order' => $order,
        ]);
    }

    public function cancel(Order $order)
    {
        $order->update([
            'order_status' => 2
        ]);
        $orders = Order::count();

        return redirect()
            ->route('orders.index', [
                'orders' => $orders
            ])
            ->with('success', 'Order has been canceled!');
    }

    public function order_installments_add(Request $request){

        $order_id  = $request->order_id;
        $payment = (float) $request->payment ?? 0;
        $reference = $request->reference;
        
        OrderPaymentInstallment::create([
            'order_id' => $order_id,
            'payment' => $payment,
            'reference' => $reference
        ]);

        $order = Order::find($order_id);
        if($order){
            $order_pay = $order->pay;
            $order->pay = $order_pay + $payment;
            $order->due = $order->due -  $payment;
            $order->save();
        }
        return redirect()->back()->withSuccess('Order Payment has been added.');;
    }

}
