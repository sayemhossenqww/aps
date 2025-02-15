<?php

use App\Http\Controllers\ShipmentBoxController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SupplyController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use \SimpleSoftwareIO\QrCode\Facades\QrCode;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login.attempt');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'show'])->name('home');
    Route::view('/about', 'about.show')->name('about');
    Route::get('/point-of-sale', [\App\Http\Controllers\PointOfSaleController::class, 'show'])->name('pos.show');
    Route::post('logout', [\App\Http\Controllers\Auth\LogOutController::class, 'logout'])->name('logout');


    Route::get('password/confirm', [\App\Http\Controllers\Auth\ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('password/confirm', [\App\Http\Controllers\Auth\ConfirmPasswordController::class, 'confirm']);

    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::get('/categories/{category}/edit', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/categories/{category}/products', [\App\Http\Controllers\CategoryController::class, 'products'])->name('categories.products.index');
    Route::put('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::delete('/categories/{category}/image', [\App\Http\Controllers\CategoryController::class, 'imageDestroy'])->name('categories.image.destroy');
    Route::get('/products/export', [\App\Http\Controllers\ProductController::class, 'export'])->name('products.export');


    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::post('/import-products', [\App\Http\Controllers\ProductController::class, 'import'])->name('products.import');
    Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/products/{product}/image', [\App\Http\Controllers\ProductController::class, 'imageDestroy'])->name('products.image.destroy');


    Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [\App\Http\Controllers\CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/create', [\App\Http\Controllers\CustomerController::class, 'create'])->name('customers.create');
    Route::get('/customers/print', [\App\Http\Controllers\CustomerController::class, 'printInfo'])->name('customers.print.info');
    Route::get('/customers/{customer}/edit', [\App\Http\Controllers\CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'update'])->name('customers.update');
    Route::get('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'show'])->name('customers.show');
    Route::delete('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{customer}/orders', [\App\Http\Controllers\CustomerOrderController::class, 'index'])->name('customers.orders.index');
    Route::get('/customers/{customer}/quotations', [\App\Http\Controllers\CustomerOrderController::class, 'index1'])->name('customers.quotations.index');
    Route::get('/customers/{customer}/payments', [\App\Http\Controllers\CustomerPaymentController::class, 'index'])->name('customers.payments.index');
    Route::post('/customers/{customer}/payments', [\App\Http\Controllers\CustomerPaymentController::class, 'store'])->name('customers.payments.store');
    Route::get('/customers/{customer}/payments/create', [\App\Http\Controllers\CustomerPaymentController::class, 'create'])->name('customers.payments.create');
    Route::get('/customers/{customer}/payments/filter', [\App\Http\Controllers\CustomerPaymentController::class, 'filter'])->name('customers.payments.filter');
    Route::get('/customers/{customer}/payments/filter-print', [\App\Http\Controllers\CustomerPaymentController::class, 'filterPrint'])->name('customers.payments.filter.print');
    Route::get('/customers/{customer}/payments/{payment}/edit', [\App\Http\Controllers\CustomerPaymentController::class, 'edit'])->name('customers.payments.edit');
    Route::get('/customers/{customer}/payments/{payment}/print', [\App\Http\Controllers\CustomerPaymentController::class, 'print'])->name('customers.payments.print');
    Route::put('/customers/{customer}/payments/{payment}', [\App\Http\Controllers\CustomerPaymentController::class, 'update'])->name('customers.payments.update');
    Route::delete('/customers/{customer}/payments/{payment}', [\App\Http\Controllers\CustomerPaymentController::class, 'destroy'])->name('customers.payments.destroy');

    Route::get('/suppliers/{supplier}/payments/{payment}/print', [\App\Http\Controllers\SupplierPaymentController::class, 'print'])->name('suppliers.payments.print');
    Route::delete('/suppliers/{supplier}/payments/{payment}', [\App\Http\Controllers\SupplierPaymentController::class, 'destroy'])->name('suppliers.payments.destroy');

    Route::get('/customers/{customer}/statements', [\App\Http\Controllers\CustomerAccountStatementController::class, 'index'])->name('customers.statements.index');
    Route::get('/customers/{customer}/statements/{statement}/print', [\App\Http\Controllers\CustomerAccountStatementController::class, 'print'])->name('customers.statements.print');
    Route::get('/customers/{customer}/statements/filter', [\App\Http\Controllers\CustomerAccountStatementController::class, 'filter'])->name('customers.statements.filter');
    Route::get('/customers/{customer}/statements/filter-print', [\App\Http\Controllers\CustomerAccountStatementController::class, 'filterPrint'])->name('customers.statements.filter.print');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/quotations', [\App\Http\Controllers\OrderController::class, 'index1'])->name('quotations.index');
    Route::get('/orders/analytics', [\App\Http\Controllers\OrderController::class, 'showAnalytics'])->name('orders.analytics');

    Route::get('/orders/filter', [\App\Http\Controllers\OrderController::class, 'filter'])->name('orders.filter');
    Route::get('/orders/print/stats', [\App\Http\Controllers\OrderController::class, 'printStats'])->name('orders.print.stats');
    Route::get('/orders/print/{order}', [\App\Http\Controllers\OrderController::class, 'print'])->name('orders.print');
    Route::get('/quotations/print/{order}', [\App\Http\Controllers\OrderController::class, 'print1'])->name('quotations.print');
    Route::get('/quotations/convert/{order}', [\App\Http\Controllers\OrderController::class, 'convert'])->name('quotations.convert');

    Route::get('/orders/edit/{order}', [\App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/update/{order}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/quotations/{order}', [\App\Http\Controllers\OrderController::class, 'show1'])->name('quotations.show');
    Route::delete('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');


    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'show'])->name('settings.show');
    Route::put('/settings/pos', [\App\Http\Controllers\SettingsController::class, 'updatePos'])->name('settings.pos.update');
    Route::put('/settings/currency', [\App\Http\Controllers\SettingsController::class, 'updateCurrency'])->name('settings.currency.update');
    Route::put('/settings/identification', [\App\Http\Controllers\SettingsController::class, 'updateIdentification'])->name('settings.identification.update');
    Route::put('/settings/date', [\App\Http\Controllers\SettingsController::class, 'updateDate'])->name('settings.date.update');
    Route::put('/settings/exchange-rate', [\App\Http\Controllers\SettingsController::class, 'updateExchangeRate'])->name('settings.exchange-rate.update');


    Route::get('/change-password', [\App\Http\Controllers\PasswordController::class, 'show'])->name('password.show');
    Route::put('/change-password', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

    Route::get('/drawer', [\App\Http\Controllers\DrawerController::class, 'show'])->name('drawer.show');
    Route::post('/drawer/close', [\App\Http\Controllers\DrawerController::class, 'close'])->name('drawer.close');
    Route::get('/drawer/{drawerHistory}/print', [\App\Http\Controllers\DrawerController::class, 'print'])->name('drawer.print');

    Route::get('/expenses', [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/create', [\App\Http\Controllers\ExpenseController::class, 'create'])->name('expenses.create');
    // Route::get('/expenses/archive', [\App\Http\Controllers\ExpenseController::class, 'archive'])->name('expenses.archive');
    Route::get('/expenses/{expense}/print', [\App\Http\Controllers\ExpenseController::class, 'print'])->name('expenses.print');
    // Route::get('/expenses/{expense}/archive', [\App\Http\Controllers\ExpenseController::class, 'updateArchive'])->name('expenses.update.archive');
    Route::get('/expenses/filter', [\App\Http\Controllers\ExpenseController::class, 'filter'])->name('expenses.filter');
    Route::get('/expenses/filter-print', [\App\Http\Controllers\ExpenseController::class, 'filterPrint'])->name('expenses.filter.print');
    Route::delete('/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');


    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
    Route::get('/payments/{payment}/edit', [\App\Http\Controllers\PaymentController::class, 'edit'])->name('payments.edit');
    Route::post('/payments/{payment}', [\App\Http\Controllers\PaymentController::class, 'update'])->name('payments.update');
    Route::get('/payments/filter', [\App\Http\Controllers\PaymentController::class, 'filter'])->name('payments.filter');
    Route::get('/payments/filter-print', [\App\Http\Controllers\PaymentController::class, 'filterPrint'])->name('payments.filter.print');

    Route::get('/supplier-payments', [\App\Http\Controllers\SupplierPaymentController::class, 'index'])->name('supplier-payments.index');
    Route::post('/supplier-payments', [\App\Http\Controllers\SupplierPaymentController::class, 'store'])->name('supplier-payments.store');
    Route::get('/supplier-payments/create', [\App\Http\Controllers\SupplierPaymentController::class, 'create'])->name('supplier-payments.create');
    Route::get('/supplier-payments/{payment}/edit', [\App\Http\Controllers\SupplierPaymentController::class, 'edit'])->name('supplier-payments.edit');
    Route::post('/supplier-payments/{payment}', [\App\Http\Controllers\SupplierPaymentController::class, 'update'])->name('supplier-payments.update');
    Route::get('/supplier-payments/filter', [\App\Http\Controllers\SupplierPaymentController::class, 'filter'])->name('supplier-payments.filter');
    Route::get('/supplier-payments/filter-print', [\App\Http\Controllers\SupplierPaymentController::class, 'filterPrint'])->name('supplier-payments.filter.print');

    Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [\App\Http\Controllers\SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [\App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [\App\Http\Controllers\SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.destroy');


    Route::get('/purchases', [\App\Http\Controllers\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [\App\Http\Controllers\PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [\App\Http\Controllers\PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [\App\Http\Controllers\PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{purchase}/edit', [\App\Http\Controllers\PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('/purchases/{purchase}/print', [\App\Http\Controllers\PurchaseController::class, 'print'])->name('purchases.print');
    Route::put('/purchases/{purchase}', [\App\Http\Controllers\PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/{purchase}', [\App\Http\Controllers\PurchaseController::class, 'destroy'])->name('purchases.destroy');


    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::get('sales-report', [\App\Http\Controllers\SalesController::class, 'index'])->name('sales.index');
    Route::get('sales-report/filter', [\App\Http\Controllers\SalesController::class, 'filter'])->name('sales.filter');
    Route::get('sales-report/{date}', [\App\Http\Controllers\SalesController::class, 'show'])->name('sales.show');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/create', [\App\Http\Controllers\EmployeeController::class, 'create'])->name('employees.create');
    Route::delete('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::get('/employees/{employee}/edit', [\App\Http\Controllers\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');

    Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'show'])->name('inventory.index');
    Route::post('/inventory/close', [\App\Http\Controllers\InventoryController::class, 'close'])->name('inventory.close');
    Route::get('/inventory/{inventoryHistory}/print', [\App\Http\Controllers\InventoryController::class, 'print'])->name('inventory.print');
    // Route::get('/inventory', [\App\Http\Controllers\InventoryController:class, 'index']))->name('inventory.index');

    //API
    Route::get('/inventory/categories', [\App\Http\Controllers\InventoryController::class, 'getCategories']);
    Route::get('/inventory/products', [\App\Http\Controllers\InventoryController::class, 'getProducts']);
    Route::get('/customers/search/all', [\App\Http\Controllers\CustomerController::class, 'search']);
    Route::post('/customers/create-new', [\App\Http\Controllers\CustomerController::class, 'createNew']);

    Route::get('/database/download', function () {
        return response()->download(database_path('database.sqlite'), 'database.sqlite');
    })->name('database.download');
    Route::get('/storage/link', function () {
        try {
            Artisan::call('storage:link');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        return back()->with('success', 'Storage has been linked');
    })->name('storage.link');

    Route::get('/products/search/all', [\App\Http\Controllers\ProductController::class, 'search']);

    Route::post('/order', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::post('/settings/starting-cash', [\App\Http\Controllers\SettingsController::class, 'updateStartingCashValue']);

    Route::get('/uploads/{path}', [App\Http\Controllers\ImageController::class, 'show'])->where('path', '.*')->name('image.show');

    Route::get('/deliveries', [App\Http\Controllers\DeliveryController::class, 'index'])->name('deliveries');
    Route::post('/deliveries/store', [\App\Http\Controllers\DeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('/deliveries/create', [\App\Http\Controllers\DeliveryController::class, 'create'])->name('deliveries.create');
    Route::get('/deliveries/{delivery}/edit', [\App\Http\Controllers\DeliveryController::class, 'edit'])->name('deliveries.edit');
    Route::put('/deliveries/{delivery}', [\App\Http\Controllers\DeliveryController::class, 'update'])->name('deliveries.update');
    Route::delete('/deliveries/{delivery}', [\App\Http\Controllers\DeliveryController::class, 'destroy'])->name('deliveries.destroy');
    Route::delete('/deliveries/{delivery}/image', [\App\Http\Controllers\DeliveryController::class, 'imageDestroy'])->name('deliveries.image.destroy');
    // Route::get('/deliveries/list', [App\Http\Controllers\DeliveryController::class, 'list'])->name('deliveries.list');
    Route::get('delivery/list', [App\Http\Controllers\DeliveryController::class, 'getDelivery']);
    Route::get('/orders/ispaid/{order}', [\App\Http\Controllers\OrderController::class, 'isStatusPaid'])->name('orders.ispaid');
    //
    Route::get('/shipments', [App\Http\Controllers\ShipmentController::class, 'index'])->name('shipments');
    Route::post('/shipments/store', [\App\Http\Controllers\ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/create', [\App\Http\Controllers\ShipmentController::class, 'create'])->name('shipments.create');
    Route::get('/shipments/{shipments}/edit', [\App\Http\Controllers\ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/shipments/{shipment}', [\App\Http\Controllers\ShipmentController::class, 'update'])->name('shipments.update');
    Route::delete('/shipments/{shipment}', [\App\Http\Controllers\ShipmentController::class, 'destroy'])->name('shipments.destroy');
    Route::post('/shipments/scan-qr', [\App\Http\Controllers\ShipmentController::class, 'scanQRCode'])->name('shipments.scan-qr');
    Route::post('/shipments/sub-package', [\App\Http\Controllers\ShipmentController::class, 'subPackage'])->name('shipments.sub-package');
    Route::put('/sub-package-update/{shipment}', [\App\Http\Controllers\ShipmentController::class, 'subPackageUpdate'])->name('shipments.sub-package-update');
    Route::get('/sub-shipments/{shipment}', [\App\Http\Controllers\ShipmentController::class, 'getShipment'])->name('shipments.sub-package-detial');

    // Route::get('/shipments/{shipment}/add-box', [ShipmentBoxController::class, 'create'])->name('shipments.boxes.create');
    // Route::post('/shipments/{shipment}/add-box', [ShipmentBoxController::class, 'store'])->name('shipments.boxes.store');
    // //Route::delete('/deliveries/{shipments}', [\App\Http\Controllers\ShipmentController::class, 'destroy'])->name('shipments.destroy');
    // //  Route::delete('/deliveries/{delivery}/image', [\App\Http\Controllers\DeliveryController::class, 'imageDestroy'])->name('deliveries.image.destroy');

    // Route::get('/shipments/{shipment}/boxes', [ShipmentBoxController::class, 'index'])->name('shipments.boxes.index');

    Route::prefix('shipments/{shipment}/boxes')->group(function () {
        Route::get('/', [ShipmentBoxController::class, 'index'])->name('shipments.boxes.index');
        Route::get('/create', [ShipmentBoxController::class, 'create'])->name('shipments.boxes.create');
        Route::post('/', [ShipmentBoxController::class, 'store'])->name('shipments.boxes.store');
        Route::get('/{box}/edit', [ShipmentBoxController::class, 'edit'])->name('shipments.boxes.edit'); // EDIT ROUTE
        Route::put('/{box}', [ShipmentBoxController::class, 'update'])->name('shipments.boxes.update');
        Route::delete('/{box}', [ShipmentBoxController::class, 'destroy'])->name('shipments.boxes.destroy');
    });

    Route::prefix('supply')->group(function () {
        Route::get('/', [SupplyController::class, 'index'])->name('supply.index'); // Show Supply Page
        Route::post('/store', [SupplyController::class, 'store'])->name('supply.store'); // Process Supply Submission
    });

    // Route::get('/', [SupplyController::class, 'index'])->name('supply.index'); // Show Supply Page
    // Route::get('/shipments/{shipment}/boxes/delivered', [ShipmentBoxController::class, 'deliveredBoxesIndex'])
    // ->name('shipments.delivered-boxes.index');

    // Route::post('/shipments/{shipment}/boxes/deliver', [ShipmentBoxController::class, 'bulkDeliver'])
    //     ->name('shipments.boxes.deliver');
    Route::post('/shipments/boxes/deliver', [ShipmentBoxController::class, 'deliverBoxes'])->name('shipments.boxes.deliver');
    Route::post('/shipments/{shipmentId}/boxes/{boxId}/deliver', [ShipmentBoxController::class, 'deliverBox'])
    ->name('shipments.boxes.deliver');


});

Route::get('/uploads/{path}', [App\Http\Controllers\ImageController::class, 'show'])->where('path', '.*')->name('image.show');
Route::get('/order-generate-qr/{id}', [\App\Http\Controllers\OrderController::class, 'generateQR'])->name('order.generate-qr');
