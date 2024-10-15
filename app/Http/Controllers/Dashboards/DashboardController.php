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
use Illuminate\Support\Facades\DB;

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

        $mostSellingProducts = DB::query()
        ->fromSub(function ($query) {
            $query->from('order_details')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->unionAll(
                    DB::table('quotation_details')
                        ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                        ->groupBy('product_id')
                );
        }, 'combined_results')->limit(10)
        ->join('products', 'combined_results.product_id', '=', 'products.id') // Join with products table
        ->select('products.uuid', 'products.name', 'combined_results.product_id', DB::raw('SUM(total_quantity) as total_quantity'))
        ->groupBy('combined_results.product_id', 'products.name', 'products.uuid')
        ->orderBy('total_quantity', 'desc')
        ->get();
        $totalQuantity = $mostSellingProducts->sum('total_quantity');
        
        $mostSellingProducts = $mostSellingProducts->map(function ($product) use ($totalQuantity) {
            $product->percentage = ($product->total_quantity / $totalQuantity) * 100;
            return $product;
        });
        
        // dd($mostSellingProducts);
        

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
            'quotationsAmountTotal' => $quotationsAmountTotal,
            'mostSellingProducts' => $mostSellingProducts,
        ]);
    }
}
