<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::count();
        $ordersAmountTotal = Order::sum('total');
        $products = Product::count();

        $purchases = Purchase::count();
        $purchasesAmountTotal = Purchase::sum('total_amount');
        $todayPurchases = Purchase::whereDate('date', today()->format('Y-m-d'))->count();
        $todayProducts = Product::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayQuotations = Quotation::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayOrders = Order::whereDate('created_at', today()->format('Y-m-d'))->count();

        $categories = Category::count();
        $quotations = Quotation::count();
        $quotationsAmountTotal = Quotation::sum('total_amount');

        return view('dashboard', [
            'products' => $products,
            'orders' => $orders,
            'ordersAmountTotal' => $ordersAmountTotal,
            'purchases' => $purchases,
            'purchasesAmountTotal' => $purchasesAmountTotal,
            'todayPurchases' => $todayPurchases,
            'todayProducts' => $todayProducts,
            'todayQuotations' => $todayQuotations,
            'todayOrders' => $todayOrders,
            'categories' => $categories,
            'quotations' => $quotations,
            'quotationsAmountTotal' => $quotationsAmountTotal
        ]);
    }
}
