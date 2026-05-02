<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Supplier\DashboardController as SupplierDashboardController;
use App\Http\Controllers\Supplier\InventoryController;
use App\Http\Controllers\Supplier\OrderController as SupplierOrderController;
use App\Http\Controllers\Farmer\DashboardController as FarmerDashboardController;
use App\Http\Controllers\Farmer\AddressController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\AgentSettingsController;
use App\Http\Controllers\Veterinary\DashboardController as VeterinaryDashboardController;
use App\Http\Controllers\Veterinary\ConsultationController;
use App\Http\Controllers\Veterinary\FarmVisitController;
use App\Http\Controllers\Veterinary\VeterinarySettingsController;
use App\Http\Controllers\Veterinary\SubscriptionController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\LogisticsController;
use App\Http\Controllers\Admin\MortalityController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SubscriptionPlanController as AdminSubscriptionPlanController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use Illuminate\Http\Request;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard - redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect('/login');
    }

    // If no role selected, redirect to role selection
    if (!$user->role) {
        return redirect('/select-role')->with('info', 'Please select your role first.');
    }

    // Redirect based on role
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'supplier' => redirect()->route('supplier.dashboard'),
        'farmer' => redirect()->route('farmer.dashboard'),
        'agent' => redirect()->route('agent.dashboard'),
        'veterinary' => redirect()->route('veterinary.dashboard'),
        default => redirect('/select-role')->with('error', 'Invalid role selected. Please choose again.'),
    };
})->middleware(['auth'])->name('dashboard');

// =================== ADMIN ROUTES ===================
// Admin routes - no verification needed
// =================== ADMIN ROUTES ===================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard - NOW USING CONTROLLER
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // User management routes
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');

    // Single user verification routes
    Route::post('/users/{user}/verify', [AdminUserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/unverify', [AdminUserController::class, 'unverify'])->name('users.unverify');
    Route::post('/users/{user}/verification', [AdminUserController::class, 'updateVerification'])->name('users.verification');
    Route::post('/users/{user}/send-email', [AdminUserController::class, 'sendEmail'])->name('users.send-email');

    // Analytics routes
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/suppliers', [AnalyticsController::class, 'supplierOverview'])->name('suppliers');
        Route::get('/farmers', [AnalyticsController::class, 'farmerOverview'])->name('farmers');
        Route::get('/agents', [AnalyticsController::class, 'agentOverview'])->name('agents');
    });

    // Logistics routes
    Route::prefix('logistics')->name('logistics.')->group(function () {
        Route::get('/deliveries', [LogisticsController::class, 'deliveriesOverview'])->name('deliveries');
        Route::get('/delays', [LogisticsController::class, 'delayedOrders'])->name('delays');
        Route::get('/completed', [LogisticsController::class, 'completedOrders'])->name('completed');
    });

    // Mortality Track Routes
    Route::prefix('mortality')->name('mortality.')->group(function () {
        Route::get('/transport', [MortalityController::class, 'transportMortality'])->name('transport');
        Route::get('/expectation', [MortalityController::class, 'expectationFlagged'])->name('expectation');
        Route::get('/reports', [MortalityController::class, 'reportsComplaints'])->name('reports');
    });

    // Admin verification management routes
    Route::prefix('verification')->name('verification.')->group(function () {
        Route::get('/', [VerificationController::class, 'index'])->name('index');
        Route::get('/statistics', [VerificationController::class, 'statistics'])->name('statistics');
        Route::get('/{verificationRequest}', [VerificationController::class, 'show'])->name('show');
        Route::post('/{verificationRequest}/approve', [VerificationController::class, 'approve'])->name('approve');
        Route::post('/{verificationRequest}/reject', [VerificationController::class, 'reject'])->name('reject');
    });

    // Product management routes
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Order management routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Subscription Plan Management
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [AdminSubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/create', [AdminSubscriptionPlanController::class, 'create'])->name('create');
        Route::post('/', [AdminSubscriptionPlanController::class, 'store'])->name('store');
        Route::get('/statistics', [AdminSubscriptionPlanController::class, 'statistics'])->name('statistics');

        // These two routes are critical - notice the order!
        Route::get('/{subscriptionPlan}/edit', [AdminSubscriptionPlanController::class, 'edit'])->name('edit');
        Route::get('/{subscriptionPlan}', [AdminSubscriptionPlanController::class, 'show'])->name('show');

        Route::put('/{subscriptionPlan}', [AdminSubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{subscriptionPlan}', [AdminSubscriptionPlanController::class, 'destroy'])->name('destroy');
        Route::put('/{subscriptionPlan}/toggle-status', [AdminSubscriptionPlanController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/verify-payment/{subscription}', [AdminSubscriptionPlanController::class, 'verifyPayment'])->name('verify-payment');
    });
});
// =================== SUPPLIER ROUTES ===================
// Supplier routes - require verification
Route::middleware(['auth', 'role:supplier', 'verified'])->prefix('supplier')->name('supplier.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');

    // Inventory management routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/inventory/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{product}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::get('/inventory/categories', [InventoryController::class, 'categories'])->name('inventory.categories');
    Route::get('/inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/inventory/expired', [InventoryController::class, 'expired'])->name('inventory.expired');

    // Order management routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [SupplierOrderController::class, 'index'])->name('index');
        Route::get('/pending', [SupplierOrderController::class, 'pending'])->name('pending');
        Route::get('/processing', [SupplierOrderController::class, 'processing'])->name('processing');
        Route::get('/shipped', [SupplierOrderController::class, 'shipped'])->name('shipped');
        Route::get('/delivered', [SupplierOrderController::class, 'delivered'])->name('delivered');
        Route::get('/cancelled', [SupplierOrderController::class, 'cancelled'])->name('cancelled');
        Route::get('/bulk', [SupplierOrderController::class, 'bulk'])->name('bulk');
        Route::get('/{order}', [SupplierOrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [SupplierOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/bulk/status', [SupplierOrderController::class, 'bulkUpdateStatus'])->name('bulkUpdateStatus');
    });

    // Marketplace routes
    Route::get('/marketplace', [MarketplaceController::class, 'supplierProducts'])->name('marketplace.index');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::get('/marketplace/{id}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
    Route::put('/marketplace/{id}', [MarketplaceController::class, 'update'])->name('marketplace.update');
    Route::delete('/marketplace/{id}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');

    // Analytics
    Route::get('/analytics', [SupplierDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/reports', [SupplierDashboardController::class, 'reports'])->name('reports');
});

// =================== FARMER ROUTES ===================
// Farmer routes - require verification
Route::middleware(['auth', 'role:farmer', 'verified'])->prefix('farmer')->name('farmer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('dashboard');

    // Marketplace routes
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');

    // Order routes - UPDATED WITH M-PESA SUPPORT
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('process-checkout');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('cancel');
        Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('reorder');
        Route::get('/{order}/track', [OrderController::class, 'trackOrder'])->name('track');
        Route::post('/retry-payment', [OrderController::class, 'retryPayment'])->name('retry-payment');
    });

    // ADD THE SIMULATE PAYMENT ROUTE HERE (inside farmer group)
    Route::get('/simulate-payment/{orderId}', function ($orderId) {
        $order = \App\Models\Order::find($orderId);
        if (!$order) {
            return "Order not found.";
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'payment_reference' => 'SIMULATED' . rand(1000, 9999)
        ]);

        return "Order #{$order->order_number} has been manually marked as paid!";
    })->name('simulate.payment');

    // Cart routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [MarketplaceController::class, 'viewCart'])->name('index');
        Route::post('/add/{id}', [MarketplaceController::class, 'addToCart'])->name('add');
        Route::post('/update/{id}', [MarketplaceController::class, 'updateCart'])->name('update');
        Route::delete('/remove/{id}', [MarketplaceController::class, 'removeFromCart'])->name('remove');
        Route::post('/clear', [MarketplaceController::class, 'clearCart'])->name('clear');
    });

    // Address routes
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::get('/create', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/default', [AddressController::class, 'setDefault'])->name('set-default');
    });

    // Wishlist routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [MarketplaceController::class, 'viewWishlist'])->name('index');
        Route::post('/add/{id}', [MarketplaceController::class, 'addToWishlist'])->name('add');
        Route::delete('/remove/{id}', [MarketplaceController::class, 'removeFromWishlist'])->name('remove');
    });
});

// =================== AGENT ROUTES ===================
// Agent routes - require verification
Route::middleware(['auth', 'role:agent', 'verified'])->prefix('agent')->name('agent.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

    // Marketplace Routes Group
    Route::prefix('marketplace')->name('marketplace.')->group(function () {
        Route::get('/', [MarketplaceController::class, 'agentMarketplace'])->name('index');
        Route::post('/', [MarketplaceController::class, 'store'])->name('store');

        // Product management
        Route::get('/product/{id}', [MarketplaceController::class, 'show'])->name('show');
        Route::get('/product/{id}/edit', [MarketplaceController::class, 'edit'])->name('edit');
        Route::put('/product/{id}', [MarketplaceController::class, 'update'])->name('update');
        Route::delete('/product/{id}', [MarketplaceController::class, 'destroy'])->name('delete');

        // Stock management
        Route::post('/update-stock', [MarketplaceController::class, 'updateStock'])->name('update-stock');
        Route::get('/product/{id}/stock', [MarketplaceController::class, 'getProductStock'])->name('get-stock');

        // Farmer management
        Route::get('/farmers', [MarketplaceController::class, 'getFarmers'])->name('farmers.list');
        Route::get('/farmers/{id}', [MarketplaceController::class, 'showFarmer'])->name('farmers.show');
        Route::get('/farmers/{id}/marketplace', [MarketplaceController::class, 'farmerMarketplace'])->name('farmers.marketplace');

        // Order creation
        Route::post('/order/create', [MarketplaceController::class, 'createOrderForFarmer'])->name('order.create');
        Route::get('/product/{id}/details', [MarketplaceController::class, 'getProductDetails'])->name('product.details');
        Route::get('/farmer/{id}/products', [MarketplaceController::class, 'getFarmerProducts'])->name('farmer.products');
    });

    // Order and commission tracking
    Route::get('/orders', [AgentDashboardController::class, 'agentOrders'])->name('orders.index');
    Route::get('/commissions', [AgentDashboardController::class, 'commissions'])->name('commissions');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AgentSettingsController::class, 'index'])->name('index');
        Route::post('/business', [AgentSettingsController::class, 'updateBusinessInfo'])->name('business.update');
        Route::post('/notifications', [AgentSettingsController::class, 'updateNotifications'])->name('notifications.update');
        Route::post('/working-hours', [AgentSettingsController::class, 'updateWorkingHours'])->name('working-hours.update');
        Route::post('/targets', [AgentSettingsController::class, 'updatePerformanceTarget'])->name('targets.update');
        Route::delete('/targets/{id}', [AgentSettingsController::class, 'deletePerformanceTarget'])->name('targets.delete');

        // Notification management
        Route::prefix('notifications')->name('notifications.')->group(function() {
            Route::post('/{id}/mark-read', [AgentSettingsController::class, 'markNotificationAsRead'])->name('mark-read');
            Route::post('/mark-all-read', [AgentSettingsController::class, 'markAllNotificationsAsRead'])->name('mark-all-read');
            Route::delete('/{id}', [AgentSettingsController::class, 'deleteNotification'])->name('delete');
            Route::delete('/clear-all', [AgentSettingsController::class, 'clearAllNotifications'])->name('clear-all');
            Route::post('/bulk-action', [AgentSettingsController::class, 'bulkNotificationAction'])->name('bulk-action');
            Route::get('/counts', [AgentSettingsController::class, 'getNotificationCounts'])->name('counts');
            Route::get('/list', [AgentSettingsController::class, 'getNotifications'])->name('list');
        });
    });
});

// =================== VETERINARY ROUTES ===================
// Veterinary routes - subscription routes are exempt, others require verification
Route::middleware(['auth', 'role:veterinary'])->prefix('veterinary')->name('veterinary.')->group(function () {

    // Subscription routes (NO verification required - accessible before payment and verification)
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans');
        Route::get('/{slug}', [SubscriptionController::class, 'show'])->name('show');
        Route::post('/process', [SubscriptionController::class, 'processPayment'])->name('process');
    });

    // Protected veterinary routes (require BOTH role AND verification)
    Route::middleware(['verified'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [VeterinaryDashboardController::class, 'index'])->name('dashboard');

        // Consultations
        Route::prefix('consultations')->name('consultations.')->group(function () {
            Route::get('/', [ConsultationController::class, 'index'])->name('index');
            Route::get('/create', [ConsultationController::class, 'create'])->name('create');
            Route::post('/', [ConsultationController::class, 'store'])->name('store');

            // SPECIFIC ROUTES MUST COME BEFORE DYNAMIC ROUTES
            Route::get('/pending', [ConsultationController::class, 'pending'])->name('pending');
            Route::get('/completed', [ConsultationController::class, 'completed'])->name('completed');
            Route::get('/telemedicine', [ConsultationController::class, 'telemedicine'])->name('telemedicine');
            Route::get('/follow-ups', [ConsultationController::class, 'followUps'])->name('follow-ups');
            Route::get('/emergency', [ConsultationController::class, 'emergency'])->name('emergency');
            Route::get('/disease-outbreak', [ConsultationController::class, 'diseaseOutbreak'])->name('disease-outbreak');

            // ACTION ROUTES
            Route::post('/{consultation}/complete', [ConsultationController::class, 'markComplete'])->name('complete');
            Route::post('/{consultation}/prescription', [ConsultationController::class, 'addPrescription'])->name('prescription');

            // DYNAMIC ROUTES MUST COME LAST
            Route::get('/{consultation}', [ConsultationController::class, 'show'])->name('show');
            Route::get('/{consultation}/edit', [ConsultationController::class, 'edit'])->name('edit');
            Route::put('/{consultation}', [ConsultationController::class, 'update'])->name('update');
            Route::delete('/{consultation}', [ConsultationController::class, 'destroy'])->name('destroy');
        });

        // Farm Visits
        Route::prefix('farm-visits')->name('farm-visits.')->group(function () {
            // Main CRUD
            Route::get('/', [FarmVisitController::class, 'index'])->name('index');
            Route::get('/create', [FarmVisitController::class, 'create'])->name('create');
            Route::post('/', [FarmVisitController::class, 'store'])->name('store');

            // Filter routes
            Route::get('/upcoming', [FarmVisitController::class, 'upcoming'])->name('upcoming');
            Route::get('/history', [FarmVisitController::class, 'history'])->name('history');
            Route::get('/emergency', [FarmVisitController::class, 'emergency'])->name('emergency');
            Route::get('/reports', [FarmVisitController::class, 'reports'])->name('reports');

            // Action routes
            Route::post('/{farmVisit}/complete', [FarmVisitController::class, 'markComplete'])->name('complete');
            Route::post('/{farmVisit}/emergency', [FarmVisitController::class, 'markEmergency'])->name('emergency');
            Route::get('/{farmVisit}/report', [FarmVisitController::class, 'generateReport'])->name('generate-report');

            // Dynamic routes
            Route::get('/{farmVisit}', [FarmVisitController::class, 'show'])->name('show');
            Route::get('/{farmVisit}/edit', [FarmVisitController::class, 'edit'])->name('edit');
            Route::put('/{farmVisit}', [FarmVisitController::class, 'update'])->name('update');
            Route::delete('/{farmVisit}', [FarmVisitController::class, 'destroy'])->name('destroy');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [VeterinarySettingsController::class, 'index'])->name('index');
            Route::put('/profile', [VeterinarySettingsController::class, 'updateProfile'])->name('update-profile');
            Route::put('/professional-info', [VeterinarySettingsController::class, 'updateProfessionalInfo'])->name('update-professional-info');
            Route::put('/availability', [VeterinarySettingsController::class, 'updateAvailability'])->name('update-availability');
            Route::put('/service-areas', [VeterinarySettingsController::class, 'updateServiceAreas'])->name('update-service-areas');
            Route::post('/licenses', [VeterinarySettingsController::class, 'uploadLicense'])->name('upload-license');
            Route::delete('/licenses/{license}', [VeterinarySettingsController::class, 'deleteLicense'])->name('delete-license');
            Route::put('/notifications', [VeterinarySettingsController::class, 'updateNotifications'])->name('update-notifications');
        });

        // Emergency
        Route::get('/emergency-hotline', [VeterinaryDashboardController::class, 'emergencyHotline'])->name('emergency-hotline');
        Route::get('/help', [VeterinaryDashboardController::class, 'help'])->name('help');

        // Services
        Route::get('/services', [VeterinaryDashboardController::class, 'services'])->name('services.index');
        Route::get('/appointments', [VeterinaryDashboardController::class, 'appointments'])->name('appointments.index');
        Route::get('/farmers', [VeterinaryDashboardController::class, 'farmerDirectory'])->name('farmers.index');
        Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    });
});

// =================== M-PESA PAYMENT ROUTES ===================

// Public M-Pesa Callback Routes (no auth - Safaricom needs to access these)
Route::prefix('api')->name('api.')->group(function () {
    Route::post('/mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');
    Route::get('/mpesa/status/{checkoutRequestId}', [MpesaController::class, 'checkStatus'])->name('mpesa.status');

    // Veterinary subscription status check
    Route::get('/veterinary/subscription/status/{checkoutRequestId}',
        [SubscriptionController::class, 'checkStatus'])->name('veterinary.subscription.status');
});

// Protected M-Pesa Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/mpesa/initiate', [MpesaController::class, 'initiatePayment'])->name('mpesa.initiate');

    // Farmer payment retry route
    Route::middleware(['role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
        Route::post('/orders/retry-payment', [OrderController::class, 'retryPayment'])->name('orders.retry-payment');
    });
});

// =================== COMMON MARKETPLACE ROUTES ===================
// Marketplace routes - require verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/product/{id}', [MarketplaceController::class, 'show'])->name('marketplace.show');
    Route::get('/marketplace/search', [MarketplaceController::class, 'search'])->name('marketplace.search');
    Route::get('/marketplace/category/{category}', [MarketplaceController::class, 'byCategory'])->name('marketplace.category');

    // Cart routes
    Route::post('/marketplace/cart/add/{id}', [MarketplaceController::class, 'addToCart'])->name('marketplace.cart.add');
    Route::delete('/marketplace/cart/remove/{id}', [MarketplaceController::class, 'removeFromCart'])->name('marketplace.cart.remove');
    Route::get('/cart', [MarketplaceController::class, 'viewCart'])->name('cart.index');
    Route::post('/cart/update/{id}', [MarketplaceController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/clear', [MarketplaceController::class, 'clearCart'])->name('cart.clear');

    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/checkout/success', [OrderController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/checkout/cancel', [OrderController::class, 'checkoutCancel'])->name('checkout.cancel');

    // Order tracking
    Route::get('/orders/{order}/track', [OrderController::class, 'trackOrder'])->name('orders.track');

    // Wishlist
    Route::post('/marketplace/wishlist/add/{id}', [MarketplaceController::class, 'addToWishlist'])->name('marketplace.wishlist.add');
    Route::delete('/marketplace/wishlist/remove/{id}', [MarketplaceController::class, 'removeFromWishlist'])->name('marketplace.wishlist.remove');
    Route::get('/wishlist', [MarketplaceController::class, 'viewWishlist'])->name('wishlist.index');

    // Reviews
    Route::post('/marketplace/product/{id}/review', [MarketplaceController::class, 'addReview'])->name('marketplace.review.add');
});

// =================== NOTIFICATION ROUTES ===================
// Notification routes - require auth but not verification
// =================== NOTIFICATION ROUTES ===================
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/read/all', [NotificationController::class, 'destroyAllRead'])->name('notifications.destroyAllRead');
    Route::delete('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');

    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::get('/notifications/stats', [NotificationController::class, 'getStats'])->name('notifications.stats');
});

// =================== COMMON ROUTES ===================

// Role selection - no verification required
Route::middleware(['auth'])->group(function () {
    Route::get('/select-role', [RoleController::class, 'select'])->name('select.role');
    Route::post('/assign-role', [RoleController::class, 'assign'])->name('role.assign');
    Route::get('/change-role', [RoleController::class, 'select'])->name('change.role')->middleware('can:change-role');
});

// Profile routes - require auth but not verification
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
});

// User verification routes (accessible without verification)
Route::middleware(['auth'])->prefix('verification')->name('verification.')->group(function () {
    Route::get('/apply', [VerificationController::class, 'create'])->name('create');
    Route::post('/apply', [VerificationController::class, 'store'])->name('store');
    Route::delete('/{id}', [VerificationController::class, 'destroy'])->name('cancel');
    Route::get('/pending', function () {
        return view('verification.pending');
    })->name('pending');
    Route::get('/rejected', function () {
        return view('verification.rejected');
    })->name('rejected');
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

// Unauthorized page
Route::get('/unauthorized', function () {
    return view('errors.unauthorized')->with('message', 'You are not authorized to access this area.');
})->name('unauthorized');

require __DIR__.'/auth.php';
