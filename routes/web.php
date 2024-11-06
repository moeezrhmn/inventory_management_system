<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Order\DueOrderController;
use App\Http\Controllers\Order\OrderCompleteController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderPendingController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductExportController;
use App\Http\Controllers\Product\ProductImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Purchase\PurchaseController;
use App\Http\Controllers\Quotation\QuotationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LabourController;
use App\Http\Controllers\StatementsController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('php/', function () {
    return phpinfo();
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard/', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    // Route::resource('/users', UserController::class); //->except(['show']);
    Route::put('/user/change-password/{user_id}', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::get('/profile/store-settings', [ProfileController::class, 'store_settings'])->name('profile.store.settings');
    Route::post('/profile/store-settings', [ProfileController::class, 'store_settings_store'])->name('profile.store.settings.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/quotations', QuotationController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/labours', LabourController::class);
    Route::resource('/warehouses', WarehouseController::class);
    Route::resource('/units', UnitController::class);

    // WAREHOUSE ITEM TRANSACTIONS
    Route::get('/warehouses-item-transactions/{warehouse_item_id}', [WarehouseController::class, 'warehouse_item_transactions'])->name('warehouse.transactions.view');
    Route::post('/create-warehouses-item-transaction/remove-stock', [WarehouseController::class, 'create_warehouse_item_transaction'])->name('warehouse.transactions.create');
    
    Route::post('/create-warehouses-item-transaction/add-purchase', [WarehouseController::class, 'warehouse_item_transaction_purchase'])->name('warehouse.transactions.purchase');

    Route::post('/warehouses-item-transaction/change-total-paid', [WarehouseController::class, 'warehouse_item_transaction_change_total_paid'])->name('warehouse.transactions.change_total_paid');

    Route::delete('/delete-warehouses-item-transaction/{transaction_id}', [WarehouseController::class, 'delete_warehouse_item_transaction'])->name('warehouse.transactions.delete');

    Route::get('/labourwork', [LabourController::class, 'labourWork'])->name('labours.work');
    Route::post('/addwork', [LabourController::class, 'addWork'])->name('labours.addwork');
    Route::get('/labour-work/{id}/edit_work', [LabourController::class, 'edit_work'])->name('labourwork.edit');
    Route::put('/labour-work/{id}', [LabourController::class, 'update_work'])->name('labourwork.update');
    Route::delete('/labour-works/{id}', [LabourController::class, 'destroy_work'])->name('labourwork.destroy');
    Route::get('/labourwork/search', [LabourController::class, 'search'])->name('labourwork.search');

    Route::get('/w-detail-create/{warehouse_id}/create', [WarehouseController::class, 'wcreate'])->name('w_create');
    Route::get('/w-detail-edit/{id}/edit_detail', [WarehouseController::class, 'wedit'])->name('w_edit');
    Route::post('/w-detail-store', [WarehouseController::class, 'wstore'])->name('w_store');
    Route::put('/w-detail-update/{id}', [WarehouseController::class, 'wupdate'])->name('w_update');
    Route::delete('/w-detail-delete/{id}', [WarehouseController::class, 'wdestroy'])->name('w-destroy');
    Route::get('/warehouse/search', [WarehouseController::class, 'wsearch'])->name('warehouse.search');
    // Route Products
    Route::get('products/import/', [ProductImportController::class, 'create'])->name('products.import.view');
    Route::post('products/import/', [ProductImportController::class, 'store'])->name('products.import.store');
    Route::get('products/export/', [ProductExportController::class, 'create'])->name('products.export.store');
    Route::resource('/products', ProductController::class);

    // Route POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/cart/add', [PosController::class, 'addCartItem'])->name('pos.addCartItem');
    Route::post('/pos/cart/update/{rowId}', [PosController::class, 'updateCartItem'])->name('pos.updateCartItem');
    Route::delete('/pos/cart/delete/{rowId}', [PosController::class, 'deleteCartItem'])->name('pos.deleteCartItem');

    //Route::post('/pos/invoice', [PosController::class, 'createInvoice'])->name('pos.createInvoice');
    Route::post('invoice/create/', [InvoiceController::class, 'create'])->name('invoice.create');

    // Route Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pending', OrderPendingController::class)->name('orders.pending');
    Route::get('/orders/complete', OrderCompleteController::class)->name('orders.complete');

    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');

    // Route Installments
    Route::post('/orders/installements/add', [OrderController::class, 'order_installments_add'])->name('orders.installments.add');

    // SHOW ORDER
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/update/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/cancel/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');

    // DUES
    Route::get('due/orders/', [DueOrderController::class, 'index'])->name('due.index');
    Route::get('due/order/view/{order}', [DueOrderController::class, 'show'])->name('due.show');
    Route::get('due/order/edit/{order}', [DueOrderController::class, 'edit'])->name('due.edit');
    Route::put('due/order/update/{order}', [DueOrderController::class, 'update'])->name('due.update');

    // TODO: Remove from OrderController
    Route::get('/orders/details/{order_id}/download', [OrderController::class, 'downloadInvoice'])->name('order.downloadInvoice');


    // Route Purchases
    Route::get('/purchases/approved', [PurchaseController::class, 'approvedPurchases'])->name('purchases.approvedPurchases');
    Route::get('/purchases/report', [PurchaseController::class, 'purchaseReport'])->name('purchases.purchaseReport');
    Route::get('/purchases/report/export', [PurchaseController::class, 'getPurchaseReport'])->name('purchases.getPurchaseReport');
    Route::post('/purchases/report/export', [PurchaseController::class, 'exportPurchaseReport'])->name('purchases.exportPurchaseReport');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

    //Route::get('/purchases/show/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

    //Route::get('/purchases/edit/{purchase}', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('/purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::post('/purchases/update/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/delete/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.delete');

    // Route Quotations
    // Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    // Route::post('/quotations/complete/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::delete('/quotations/delete/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.delete');

    // Route User
    Route::group(['middleware' => ['permission:see users']], function() {

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/show/{user_id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/edit/{user_id}', [UserController::class, 'edit'])->name('users.edit');
        
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/update/{user_id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{user_id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Route Permission 
        Route::put('/users/permissions/update/{user_id}', [UserController::class, 'users_permissions_update'])->name('users.permissions.update');
    });

    // Statements Route
    Route::group(['middleware' => ['permission:see users']], function (){
        Route::get('/statements',[StatementsController::class, 'index'])->name('statements.index');
        Route::post('/statements/report-pdf',[StatementsController::class, 'report_pdf'])->name('statements.report_pdf');
    });
});


require __DIR__.'/auth.php';

Route::get('test/', function (){
    return view('test');
});
