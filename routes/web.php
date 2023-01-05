<?php

use App\Http\Controllers\Auth\ForgotPassController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RecoverPasswordController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerifyCodeController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Brands\BrandsController;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\Invoice\InvoiceProductsController;
use App\Http\Controllers\Invoice\InvoicesController;
use App\Http\Controllers\Organizations\OrganizationsController;
use App\Http\Controllers\Payments\PaymentMethodsController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Purchases\PurchaseProductsController;
use App\Http\Controllers\Purchases\PurchasesController;
use App\Http\Controllers\Receipts\ReceiptsController;
use App\Http\Controllers\Sales\POSController;
use App\Http\Controllers\Sales\SaleProductsController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Stocks\StocksController;
use App\Http\Controllers\Suppliers\SuppliersController;
use App\Http\Controllers\Units\ProductUnitsController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/', [LoginController::class, 'index'])->name('home');

Route::get('/migrate', function () {
    if(Artisan::call('migrate')){
        return redirect()->route('auth.login.index');
    }else{
        return 'Migration Failed';
    }
});

Route::prefix('/auth/')->name('auth.')->group(function () {

    Route::prefix('login/')->name('login.')->group(function () {

        Route::get('/index', [LoginController::class, 'index'])->name('index');

        Route::post('/store', [LoginController::class, 'store'])->name('store');
    });

    Route::prefix('register/')->name('register.')->group(function () {

        Route::get('/index', [RegistrationController::class, 'index'])->name('index');

        Route::post('/store', [RegistrationController::class, 'store'])->name('store');
    });

    Route::prefix('forgot/')->name('forgot.')->group(function () {

        Route::get('/index', [ForgotPassController::class, 'index'])->name('index');

        Route::post('/store', [ForgotPassController::class, 'store'])->name('store');

        Route::get('/code/{user_id}', [VerifyCodeController::class, 'index']);

        Route::post('/code', [VerifyCodeController::class, 'store'])->name('code');

        Route::get('/recover/{user_id}', [RecoverPasswordController::class, 'index']);

        Route::post('/recover', [RecoverPasswordController::class, 'store'])->name('recover');
    });
});

Route::prefix('back/')->name('back.')->group(function () {

    Route::prefix('dashboard/')->name('dashboard.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [DashboardController::class, 'index'])->name('index');
        });
    });

    Route::prefix('/customers')->name('customers.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [CustomersController::class, 'index'])->name('index');

            Route::get('/{id}/create', [CustomersController::class, 'create'])->name('create');

            Route::post('/store', [CustomersController::class, 'store'])->name('store');

            Route::post('/show', [CustomersController::class, 'show'])->name('show');

            Route::post('/update', [CustomersController::class, 'update'])->name('update');

            Route::post('/delete', [CustomersController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('sales/')->name('sales.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [SalesController::class, 'index'])->name('index');

            Route::get('/{id}/create', [SalesController::class, 'create'])->name('create');

            Route::post('/store', [SalesController::class, 'store'])->name('store');

            Route::post('/show', [SalesController::class, 'show'])->name('show');

            Route::get('/{id}/view', [SalesController::class, 'view'])->name('view');

            Route::get('/{id}/print', [SalesController::class, 'print'])->name('print');

            Route::get('/{id}/pdf', [SalesController::class, 'pdf'])->name('pdf');

            Route::post('/send', [SalesController::class, 'send'])->name('send');

            Route::post('/update/total', [SalesController::class, 'update_total'])->name('update.total');

            Route::post('/update/discount', [SalesController::class, 'update_discount'])->name('update.discount');

            Route::post('/update/tax', [SalesController::class, 'update_tax'])->name('update.tax');

            Route::post('/update/shipping', [SalesController::class, 'update_shipping'])->name('update.shipping');

            Route::post('/update/payments', [SalesController::class, 'update_payments'])->name('update.payments');

            Route::get('edit/{id}', [SalesController::class, 'edit'])->name('edit');
        });
    });

    Route::prefix('pos/')->name('pos.')->group(function(){

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [POSController::class, 'index'])->name('index');

        });

    });

    Route::prefix('sale/products')->name('sale.products.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::post('/store', [SaleProductsController::class, 'store'])->name('store');

            Route::post('/delete', [SaleProductsController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('receipts/')->name('receipts.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [ReceiptsController::class, 'index'])->name('index');

            Route::post('/show', [ReceiptsController::class, 'show'])->name('show');

            Route::get('/{id}/view', [ReceiptsController::class, 'view'])->name('view');

            Route::get('/{id}/print', [ReceiptsController::class, 'print'])->name('print');

            Route::post('/store', [ReceiptsController::class, 'store'])->name('store');
        });
    });

    Route::prefix('invoices/')->name('invoices.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [InvoicesController::class, 'index'])->name('index');

            Route::get('/{id}/create', [InvoicesController::class, 'create'])->name('create');

            Route::post('/store', [InvoicesController::class, 'store'])->name('store');

            Route::post('/show', [InvoicesController::class, 'show'])->name('show');

            Route::get('/{id}/view', [InvoicesController::class, 'view'])->name('view');

            Route::get('/{id}/print', [InvoicesController::class, 'print'])->name('print');

            Route::post('/update/amount', [InvoicesController::class, 'update_amount'])->name('update.amount');
        });
    });

    Route::prefix('invoice/products/')->name('invoice.products.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::post('/store', [InvoiceProductsController::class, 'store'])->name('store');

            Route::post('/delete', [InvoiceProductsController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('profile/')->name('profile.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('{id}', [UsersController::class, 'index'])->name('index');

            Route::post('update', [UsersController::class, 'update'])->name('update');

            Route::post('update/password', [UsersController::class, 'update_password'])->name('update.password');

            Route::post('logout', [UsersController::class, 'logout'])->name('logout');
        });
    });

    Route::prefix('settings/')->name('settings.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [SettingsController::class, 'index'])->name('index');

            Route::post('/update', [SettingsController::class, 'update'])->name('update');

            Route::prefix("payment/methods")->name('payment.methods.')->group(function () {

                Route::get('/{id}', [PaymentMethodsController::class, 'index'])->name('index');

                Route::post('/store', [PaymentMethodsController::class, 'store'])->name('store');

                Route::post('/show', [PaymentMethodsController::class, 'show'])->name('show');

                Route::post('/delete', [PaymentMethodsController::class, 'destroy'])->name('delete');
            });
        });
    });

    Route::prefix('suppliers/')->name('suppliers.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [SuppliersController::class, 'index'])->name('index');

            Route::get('/{id}/create', [SuppliersController::class, 'create'])->name('create');

            Route::post('/store', [SuppliersController::class, 'store'])->name('store');

            Route::post('/show', [SuppliersController::class, 'show'])->name('show');

            Route::get('/{id}/edit', [SuppliersController::class, 'edit'])->name('edit');

            Route::post('/update', [SuppliersController::class, 'update'])->name('update');

            Route::post('/delete', [SuppliersController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('brands/')->name('brands.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [BrandsController::class, 'index'])->name('index');

            Route::get('/{id}/create', [BrandsController::class, 'create'])->name('create');

            Route::post('/store', [BrandsController::class, 'store'])->name('store');

            Route::post('/show', [BrandsController::class, 'show'])->name('show');

            Route::post('/delete', [BrandsController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('categories/')->name('categories.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [CategoriesController::class, 'index']);

            Route::get('/{id}/create', [CategoriesController::class, 'create'])->name('create');

            Route::post('/store', [CategoriesController::class, 'store'])->name('store');

            Route::post('/delete', [CategoriesController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('units/')->name('units.')->group(function(){

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [ProductUnitsController::class, 'index'])->name('index');

            Route::post('/store', [ProductUnitsController::class, 'store'])->name('store');

            Route::post('/show', [ProductUnitsController::class, 'show'])->name('show');

            Route::post('/base/unit', [ProductUnitsController::class, 'base_unit'])->name('base.unit');

            Route::post('/delete', [ProductUnitsController::class, 'destroy'])->name('delete');

        });

    });

    Route::prefix('products/')->name('products.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [ProductsController::class, 'index'])->name('index');

            Route::get('/{id}/create', [ProductsController::class, 'create'])->name('create');

            Route::post('/store', [ProductsController::class, 'store'])->name('store');

            Route::post('/show', [ProductsController::class, 'show'])->name('show');

            Route::get('/{id}/view', [ProductsController::class, 'view'])->name('view');

            Route::get('/{id}/print', [ProductsController::class, 'print'])->name('print');

            Route::get('/{id}/edit', [ProductsController::class, 'edit'])->name('edit');

            Route::post('/update', [ProductsController::class, 'update'])->name('update');

            Route::post('/update/stock', [ProductsController::class, 'update_stocks'])->name('update.stocks');

            Route::post('/update/deleted/stock', [ProductsController::class, 'update_deleted_stocks'])->name('update.deleted.stocks');

            Route::post('/delete', [ProductsController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('purchases/')->name('purchases.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [PurchasesController::class, 'index'])->name('index');

            Route::get('/{id}/create', [PurchasesController::class, 'create'])->name('create');

            Route::post('/store', [PurchasesController::class, 'store'])->name('store');

            Route::post('/show', [PurchasesController::class, 'show'])->name('show');

            Route::get('/{id}/view', [PurchasesController::class, 'view'])->name('view');

            Route::get('/{id}/print', [PurchasesController::class, 'print'])->name('print');

            Route::get('/{id}/pdf', [PurchasesController::class, 'pdf'])->name('pdf');

            Route::get('/{id}/edit', [PurchasesController::class, 'edit'])->name('edit');

            Route::post('/update/total', [PurchasesController::class, 'update_total'])->name('update.total');

            Route::post('/update/payments', [PurchasesController::class, 'update_payments'])->name('update.payments');

            Route::get('/{id}/send', [PurchasesController::class, 'send'])->name('send');

            Route::post('/delete', [PurchasesController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('purchase/products')->name('purchase.products.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [PurchaseProductsController::class, 'index'])->name('index');

            Route::post('store', [PurchaseProductsController::class, 'store'])->name('store');

            Route::post('/delete', [PurchaseProductsController::class, 'destroy'])->name('delete');
        });
    });

    Route::prefix('stocks/')->name('stocks.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/{id}', [StocksController::class, 'index'])->name('index');

            Route::post('/update', [StocksController::class, 'update'])->name('update');

            Route::post('/update/deleted', [StocksController::class, 'update_deleted_stock'])->name('update.deleted');

        });
    });

    Route::prefix('payments/')->name('payments.')->group(function(){

        Route::middleware(['auth'])->group(function(){

            Route::prefix('methods/')->name('methods.')->group(function(){

                Route::get('/{id}', [PaymentMethodsController::class, 'index'])->name('index');

                Route::post('/store', [PaymentMethodsController::class, 'store'])->name('store');

                Route::post('/delete', [PaymentMethodsController::class, 'destroy'])->name('delete');

            });

        });
    });
});

Route::prefix('organizations/')->name('organizations.')->group(function () {

    Route::prefix('dashboard')->name('dashboard.')->group(function () {

        Route::middleware(['auth'])->group(function () {

            Route::get('/index', [OrganizationsController::class, 'index'])->name('index');

            Route::get('/create', [OrganizationsController::class, 'create'])->name('create');

            Route::post('/create', [OrganizationsController::class, 'store']);

            Route::post('/show', [OrganizationsController::class, 'show'])->name('show');
        });
    });
});
