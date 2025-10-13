<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AddressController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\ChatbotController;
use App\Models\Faq;


// Gửi link xác thực
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Link xác thực người dùng nhấp vào email
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    // Tìm user theo ID
    $user = \App\Models\User::find($id);
    
    if (!$user) {
        return redirect('/login')->with('error', 'User không tồn tại!');
    }
    
    // Kiểm tra hash email
    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        return redirect('/login')->with('error', 'Link xác thực không hợp lệ!');
    }
    
    // Kiểm tra xem email đã được xác thực chưa
    if ($user->hasVerifiedEmail()) {
        return redirect('/login')->with('info', 'Email đã được xác thực rồi!');
    }
    
    // Xác thực email
    $user->email_verified_at = now();
    $user->save();
    
    return redirect('/login')->with('success', 'Email đã được xác thực thành công! Bây giờ bạn có thể đăng nhập.');
})->middleware(['signed'])->name('verification.verify');

// Resend link
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link xác thực đã gửi lại!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
// Privacy Policy
Route::get('/privacy', function () {
    return response()->view('privacy');
});

// Terms of Service
Route::get('/terms', function () {
    return response()->view('terms');
});

// Data Deletion (Facebook yêu cầu)
Route::get('/delete-data', function () {
    return response()->json([
        'url' => 'mailto:admin@example.com',
        'instructions' => 'Liên hệ admin để yêu cầu xóa dữ liệu hoặc truy cập /privacy để biết thêm chi tiết.'
    ]);
});

// Google OAuth
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Facebook OAuth
Route::get('/auth/facebook/redirect', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook.redirect');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');
// Routes công khai - không cần đăng nhập
Route::get('/', function () {
    // Lấy sản phẩm mới nhất để hiển thị trên trang chủ
    $latestProducts = \App\Models\Product::with(['category', 'reviews'])->latest()->take(6)->get();
    $categories = \App\Models\Category::withCount('products')->take(4)->get();
    
    // Lấy tin tức nổi bật cho trang chủ
    $featuredNews = \App\Models\News::published()->featured()->take(3)->get();

    // Lấy FAQ hiển thị ngoài trang chủ, sắp xếp theo sort_order
    $faqs = Faq::where('is_visible', true)->orderBy('sort_order')->get();

    return view('customer.welcome', compact('latestProducts', 'categories', 'featuredNews', 'faqs'));
})->name('home');

// Routes cho tin tức
Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
// API Chatbot (khách hàng)
Route::post('/api/chatbot/message', [ChatbotController::class, 'message'])->name('api.chatbot.message');
Route::get('/news/{slug}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Routes cho đánh giá
Route::middleware('auth')->post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Trang Liên hệ
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Routes đăng nhập/đăng ký
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Quên mật khẩu
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Routes cho Social Login
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// Routes cho Payment MoMo
Route::get('/payment/momo/{orderId}', [App\Http\Controllers\PaymentController::class, 'momo'])->whereNumber('orderId')->name('payment.momo');
Route::post('/payment/momo/{orderId}', [App\Http\Controllers\PaymentController::class, 'redirectToMoMo'])->whereNumber('orderId')->name('payment.momo.process');
Route::get('/payment/momo/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.momo.callback');
Route::post('/payment/momo/ipn', [App\Http\Controllers\PaymentController::class, 'ipn'])->name('payment.momo.ipn');
Route::post('/payment/pay-again/{order}', [App\Http\Controllers\PaymentController::class, 'payAgain'])->name('payment.pay-again');

// Route debug để test Google OAuth
Route::get('/debug/google', function() {
    $clientId = config('services.google.client_id');
    $redirectUri = config('services.google.redirect');
    $scope = 'openid email profile';
    
    $authUrl = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'scope' => $scope,
        'response_type' => 'code',
        'access_type' => 'offline',
        'state' => 'google'
    ]);
    
    return response()->json([
        'auth_url' => $authUrl,
        'config' => config('services.google')
    ]);
});

// Route debug để test Facebook OAuth
Route::get('/debug/facebook', function() {
    $clientId = config('services.facebook.client_id');
    $redirectUri = config('services.facebook.redirect');
    $scope = 'email,public_profile';
    
    $authUrl = 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'scope' => $scope,
        'response_type' => 'code',
        'state' => 'facebook'
    ]);
    
    return response()->json([
        'auth_url' => $authUrl,
        'config' => config('services.facebook'),
        'test_links' => [
            'facebook_redirect' => route('auth.facebook.redirect'),
            'facebook_callback' => route('auth.facebook.callback')
        ]
    ]);
});

// Route debug để kiểm tra user
Route::get('/debug/user/{email}', function($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if($user) {
        return response()->json([
            'found' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'google_id' => $user->google_id,
            'facebook_id' => $user->facebook_id,
            'role' => $user->role,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);
    }
    return response()->json(['found' => false]);
});

// Route debug để kiểm tra user theo Facebook ID
Route::get('/debug/facebook-user/{facebookId}', function($facebookId) {
    $user = \App\Models\User::where('facebook_id', $facebookId)->first();
    if($user) {
        return response()->json([
            'found' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'facebook_id' => $user->facebook_id,
            'role' => $user->role,
            'created_at' => $user->created_at
        ]);
    }
    return response()->json(['found' => false]);
});

// Route để manually verify email cho user hiện tại
Route::get('/verify-email/{email}', function($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if($user) {
        if($user->email_verified_at) {
            return response()->json([
                'message' => 'Email đã được xác thực rồi!',
                'verified_at' => $user->email_verified_at
            ]);
        }
        
        // Set email_verified_at bằng ngày tạo tài khoản
        $user->email_verified_at = $user->created_at;
        $user->save();
        
        return response()->json([
            'message' => 'Email đã được xác thực thành công!',
            'verified_at' => $user->email_verified_at,
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }
    return response()->json(['error' => 'User không tồn tại']);
});


// Route tạm thời để kiểm tra user (sẽ xóa sau)
Route::get('/check-user/{email}', function($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if($user) {
        return response()->json([
            'found' => true,
            'name' => $user->name,
            'email_verified' => $user->email_verified_at ? true : false,
            'email_verified_at' => $user->email_verified_at,
            'role' => $user->role
        ]);
    }
    return response()->json(['found' => false]);
});


// Route tạm thời để gửi lại email xác thực (sẽ xóa sau)
Route::get('/resend-verification/{email}', function($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if($user) {
        if($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác thực rồi!']);
        }
        
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Email xác thực đã được gửi lại!']);
    }
    return response()->json(['error' => 'User không tồn tại']);
});

// Route tạm thời để xác thực email thủ công (sẽ xóa sau)
Route::get('/manual-verify/{email}', function($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if($user) {
        if($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email đã được xác thực rồi!']);
        }
        
        // Xác thực email thủ công
        $user->email_verified_at = now();
        $user->save();
        
        return response()->json([
            'message' => 'Email đã được xác thực thủ công thành công!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at
            ]
        ]);
    }
    return response()->json(['error' => 'User không tồn tại']);
});

// Routes cho customer (công khai - không cần đăng nhập)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'index'])->name('products.search');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Routes cho hỏi đáp customer (chỉ cần đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/hoi-dap', [App\Http\Controllers\QuestionAnswerController::class, 'index'])->name('question-answer.index');
    Route::post('/hoi-dap', [App\Http\Controllers\QuestionAnswerController::class, 'store'])->name('question-answer.store');
    Route::post('/hoi-dap/{question}/like', [App\Http\Controllers\QuestionAnswerController::class, 'toggleLike'])->name('question-answer.like');
});

// Route để lấy CSRF token
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf-token');

// API routes để lấy tỉnh thành, quận huyện, phường xã (công khai)
Route::get('/api/provinces', [AddressController::class, 'getProvinces'])->name('addresses.provinces');
Route::get('/api/districts/{provinceId}', [AddressController::class, 'getDistricts'])->name('addresses.districts');
Route::get('/api/wards/{districtId}', [AddressController::class, 'getWards'])->name('addresses.wards');

// Routes cho giỏ hàng (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/save-selected', [App\Http\Controllers\CartController::class, 'saveSelected'])->name('cart.save-selected');
    
    // Routes cho quản lý địa chỉ
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::post('/addresses/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.set-default');
    
    // API route để lấy danh sách địa chỉ (cho checkout)
    Route::get('/api/addresses', [AddressController::class, 'getAddresses'])->name('addresses.api');
    
    // Routes cho quản lý đơn hàng
    Route::get('/orders', [App\Http\Controllers\User\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\User\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/reorder', [App\Http\Controllers\User\OrderController::class, 'reorder'])->name('orders.reorder');
    Route::get('/orders/{order}/review', [App\Http\Controllers\User\OrderController::class, 'review'])->name('orders.review');
    Route::post('/orders/{order}/review', [App\Http\Controllers\User\OrderController::class, 'storeReview'])->name('orders.store-review');
    
    // Routes cho thông tin cá nhân
    Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');

        // Chat routes for users
        Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/data', [App\Http\Controllers\ChatController::class, 'getChatData'])->name('chat.data');
        Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
        Route::get('/customer/chatting/history', [App\Http\Controllers\ChatController::class, 'getChatHistory'])->name('chat.history');
        Route::get('/chat/unread-count', [App\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
});

// Routes cho thanh toán
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index')->middleware('check.cart');
Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store')->middleware('check.cart');
Route::get('/checkout/success/{order}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

// Payment routes
Route::get('/payment/momo/{order}', [App\Http\Controllers\PaymentController::class, 'momo'])->name('payment.momo');
Route::post('/payment/momo/{order}/process', [App\Http\Controllers\PaymentController::class, 'redirectToMoMo'])->name('payment.momo.process');
Route::get('/payment/momo/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.momo.callback');
Route::post('/payment/momo/ipn', [App\Http\Controllers\PaymentController::class, 'ipn'])->name('payment.momo.ipn');
Route::post('/payment/momo/{order}/pay-again', [App\Http\Controllers\PaymentController::class, 'payAgain'])->name('payment.momo.pay-again');
Route::post('/payment/momo/{order}/cancel-order', [App\Http\Controllers\PaymentController::class, 'cancelOrder'])->name('payment.momo.cancel-order');

// Routes cho trang thanh toán thành công và thất bại
Route::get('/payment/success/{order}', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failed/{order}', [App\Http\Controllers\PaymentController::class, 'failed'])->name('payment.failed');
Route::post('/payment/switch-to-cod/{order}', [App\Http\Controllers\PaymentController::class, 'switchToCod'])->name('payment.switch-to-cod');
Route::post('/payment/cancel-order/{order}', [App\Http\Controllers\PaymentController::class, 'cancelOrder'])->name('payment.cancel-order');

// Routes cho admin (cần đăng nhập và quyền admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý danh mục - sử dụng method adminIndex cho index
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Quản lý sản phẩm - sử dụng method adminIndex cho index
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Quản lý tin tức
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // Quản lý đơn hàng
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    // Quản lý Đánh giá
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/export', [AdminReviewController::class, 'export'])->name('reviews.export');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply');

    // Quản lý tồn kho
    Route::get('/inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/history', [App\Http\Controllers\Admin\InventoryController::class, 'history'])->name('inventory.history');
    Route::post('/inventory/export', [App\Http\Controllers\Admin\InventoryController::class, 'export'])->name('inventory.export');
    Route::get('/inventory/export-history', [App\Http\Controllers\Admin\InventoryController::class, 'exportHistory'])->name('inventory.export-history');
    Route::get('/inventory/{product}', [App\Http\Controllers\Admin\InventoryController::class, 'show'])->name('inventory.show');
    Route::post('/inventory/{product}/add-stock', [App\Http\Controllers\Admin\InventoryController::class, 'addStock'])->name('inventory.add-stock');

    // Quản lý FAQ
    Route::resource('/faqs', AdminFaqController::class)->names('faqs');

    // Quản lý người dùng
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Báo cáo & Thống kê
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/reports/revenue', [App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/products', [App\Http\Controllers\Admin\ReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/customers', [App\Http\Controllers\Admin\ReportController::class, 'customers'])->name('reports.customers');
    Route::post('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');

    // Chat routes for admin
    Route::get('/chatting', [App\Http\Controllers\Admin\ChatController::class, 'index'])->name('admin.chat.index');
    Route::get('/chatting/data', [App\Http\Controllers\Admin\ChatController::class, 'getChatData'])->name('admin.chat.data');
    Route::post('/chatting/send-message', [App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('admin.chat.send-message');
    Route::get('/chatting/history/{customerId}', [App\Http\Controllers\Admin\ChatController::class, 'getChatHistory'])->name('admin.chat.history');
    Route::post('/chatting/mark-read/{customerId}', [App\Http\Controllers\Admin\ChatController::class, 'markAsRead'])->name('admin.chat.mark-read');
    Route::get('/chatting/customer/{customerId}', [App\Http\Controllers\Admin\ChatController::class, 'getCustomerInfo'])->name('admin.chat.customer');
    Route::get('/chatting/unread-count', [App\Http\Controllers\Admin\ChatController::class, 'getUnreadCount'])->name('admin.chat.unread-count');

    // Quản lý hỏi đáp cho admin
    Route::get('/hoi-dap', [App\Http\Controllers\QuestionAnswerController::class, 'adminIndex'])->name('question-answer.index');
    
    // Cài đặt hệ thống (email/phone/address/map)
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
// Sửa câu hỏi (customer) - chỉ khi được ủy quyền bởi policy
Route::middleware('auth')->group(function () {
    Route::patch('/hoi-dap/{question}', [App\Http\Controllers\QuestionAnswerController::class, 'update'])
        ->name('question-answer.update');
});
    
    // API routes cho admin hỏi đáp
    Route::get('/api/unanswered-questions', [App\Http\Controllers\QuestionAnswerController::class, 'getUnansweredQuestions'])->name('question-answer.unanswered');
    Route::post('/api/answer/{question}', [App\Http\Controllers\QuestionAnswerController::class, 'answer'])->name('question-answer.answer');
    Route::get('/api/unanswered-count', [App\Http\Controllers\QuestionAnswerController::class, 'getUnansweredCount'])->name('question-answer.count');
    Route::get('/api/question-statistics', [App\Http\Controllers\QuestionAnswerController::class, 'getStatistics'])->name('question-answer.statistics');
    
});

// Routes cho favorites - sắp xếp từ cụ thể đến chung
Route::post('/favorites/{product}', [App\Http\Controllers\FavoriteController::class, 'store'])->name('favorites.store')->middleware('auth');
Route::delete('/favorites/{product}', [App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.destroy')->middleware('auth');
Route::post('/favorites/{product}/toggle', [App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle')->middleware('auth');
Route::get('/favorites/{product}/check', [App\Http\Controllers\FavoriteController::class, 'check'])->name('favorites.check')->middleware('auth');
Route::get('/favorites', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');

// Test route để kiểm tra
Route::get('/test-favorites', function() {
    return response()->json([
        'message' => 'Favorites routes are working',
        'routes' => [
            'GET /favorites' => 'favorites.index',
            'POST /favorites/{product}' => 'favorites.store',
            'DELETE /favorites/{product}' => 'favorites.destroy'
        ]
    ]);
});

// Routes xử lý yêu cầu xóa dữ liệu cho Facebook Login App và GDPR
Route::get('/delete', [App\Http\Controllers\DataDeletionController::class, 'delete']);
Route::post('/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'dataDeletion']);
Route::get('/privacy/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'privacyInfo']);

// Route hiển thị trang HTML về chính sách xóa dữ liệu
Route::get('/privacy/data-deletion-page', function () {
    return view('privacy.data-deletion');
});