<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\MomoTransaction;
use App\Services\GhnService;
use App\Services\AddressMappingService;

class CheckoutController extends Controller
{

    /**
     * Hiển thị trang thanh toán - chỉ hiển thị sản phẩm đã chọn
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy danh sách sản phẩm được chọn từ request hoặc session
        $selectedProductIds = [];
        
        // Kiểm tra từ request parameter (nếu có)
        if ($request->has('selected_items')) {
            $selectedProductIds = json_decode($request->selected_items, true) ?: [];
        }
        
        // Nếu không có từ request, kiểm tra sessionStorage qua JavaScript
        if (empty($selectedProductIds)) {
            // Redirect về cart nếu không có sản phẩm được chọn
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm từ giỏ hàng trước khi thanh toán!');
        }

        $cart = Auth::user()->carts()->firstOrCreate(['user_id' => Auth::id()]);
        
        // Chỉ lấy những sản phẩm đã được chọn từ giỏ hàng
        $items = CartItem::with('product.category')
            ->where('cart_id', $cart->id)
            ->whereIn('product_id', $selectedProductIds)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm đã chọn!');
        }

        // Chỉ tính toán cho những sản phẩm đã được chọn
        $cartItems = [];
        $total = 0;
        $shippingFee = 0;

        foreach ($items as $item) {
            if ($item->product) {
                $lineTotal = $item->product->price * $item->quantity;
                $cartItems[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineTotal,
                ];
                $total += $lineTotal;
            }
        }

        // Tính phí vận chuyển (có thể tích hợp với GHN API)
        $shippingFee = 0; // Tạm thời miễn phí

        // Tính tổng cuối cùng
        $finalAmount = $total + $shippingFee;

        // Lấy địa chỉ của user
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();

        return view('customer.checkout.index', compact('cartItems', 'total', 'shippingFee', 'finalAmount', 'addresses', 'selectedProductIds'));
    }

    /**
     * Xử lý đặt hàng - chỉ lấy sản phẩm đã được chọn
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'notes' => 'nullable|string|max:500',
            'selected_items' => 'required|string', // Bắt buộc có danh sách sản phẩm được chọn
        ]);

        // Parse danh sách sản phẩm được chọn
        $selectedProductIds = json_decode($request->selected_items, true);
        
        if (empty($selectedProductIds) || !is_array($selectedProductIds)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán!');
        }

        $cart = Auth::user()->carts()->firstOrCreate(['user_id' => Auth::id()]);
        
        // Chỉ lấy những sản phẩm đã được chọn từ giỏ hàng
        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->whereIn('product_id', $selectedProductIds)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm đã chọn!');
        }

        // Chỉ sử dụng sản phẩm đã được chọn để đặt hàng
        $cartItems = [];
        $total = 0;

        foreach ($items as $item) {
            if ($item->product) {
                $lineTotal = $item->product->price * $item->quantity;
                $cartItems[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineTotal,
                ];
                $total += $lineTotal;
            }
        }

        // Tính phí vận chuyển
        $shippingFee = 0; // Tạm thời miễn phí
        $finalAmount = $total + $shippingFee;

        // Kiểm tra quyền sở hữu địa chỉ
        $address = Address::where('id', $request->address_id)
                         ->where('user_id', Auth::id())
                         ->first();
        
        if (!$address) {
            return redirect()->back()->with('error', 'Địa chỉ không hợp lệ!');
        }

        try {
            // Xử lý thanh toán theo phương thức
            if ($request->payment_method === 'momo') {
                Log::info('CheckoutController::store - Preparing MoMo payment with temp data', [
                    'user_id' => Auth::id(),
                    'total_amount' => $finalAmount,
                    'items_count' => count($cartItems)
                ]);

                // Lưu tạm thông tin đơn hàng vào session để tạo sau khi thanh toán thành công
                $tempOrderData = [
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'subtotal' => $total,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $finalAmount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'shipping_name' => $address->full_name,
                    'shipping_phone' => $address->phone,
                    'shipping_address' => $address->full_address,
                    'shipping_province_id' => $address->province_id,
                    'shipping_province_name' => $address->province_name,
                    'shipping_district_id' => $address->district_id,
                    'shipping_district_name' => $address->district_name,
                    'shipping_ward_id' => $address->ward_id,
                    'shipping_ward_name' => $address->ward_name,
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'cart_items' => $cartItems,
                    'selected_product_ids' => $selectedProductIds,
                    'cart_id' => $cart->id,
                    'created_at' => now(),
                ];

                // Lưu vào session
                session(['temp_order_data' => $tempOrderData]);

                Log::info('CheckoutController::store - Temp order data saved to session', [
                    'order_number' => $tempOrderData['order_number'],
                    'total_amount' => $tempOrderData['total_amount']
                ]);

                // Redirect đến PaymentController để xử lý MoMo
                return redirect()->route('payment.momo.temp');
            } else {
                // COD hoặc bank transfer - tạo đơn hàng ngay
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'order_code' => '', // Khởi tạo với giá trị rỗng, sẽ được cập nhật sau khi gọi GHN API
                    'subtotal' => $total,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $finalAmount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'shipping_name' => $address->full_name,
                    'shipping_phone' => $address->phone,
                    'shipping_address' => $address->full_address,
                    'shipping_province_id' => $address->province_id,
                    'shipping_province_name' => $address->province_name,
                    'shipping_district_id' => $address->district_id,
                    'shipping_district_name' => $address->district_name,
                    'shipping_ward_id' => $address->ward_id,
                    'shipping_ward_name' => $address->ward_name,
                    'notes' => $request->notes,
                    'status' => 'pending'
                ]);

                Log::info('CheckoutController::store - COD Order created', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount
                ]);

                // Tạo chi tiết đơn hàng và trừ tồn kho
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'product_price' => $item['product']->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Trừ tồn kho ngay khi tạo đơn hàng
                    $item['product']->reduceStock(
                        $item['quantity'],
                        "Xuất hàng cho đơn hàng #{$order->order_number}",
                        $order->id,
                        Auth::id()
                    );
                }

                // Xóa các sản phẩm đã đặt hàng khỏi giỏ hàng
                foreach ($cartItems as $item) {
                    CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $item['product']->id)
                        ->delete();
                }

                // Xóa session lựa chọn sản phẩm
                session()->forget('cart_selected_items');

                Log::info('CheckoutController::store - COD order completed', [
                    'order_id' => $order->id
                ]);

                // Đơn chưa thanh toán online -> GHN thu hộ, người nhận trả phí
                // Tạm thời bỏ qua GHN API, chỉ redirect về success
                return redirect()->route('checkout.success', $order->id)
                               ->with('success', 'Đặt hàng thành công!');
            }

        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!');
        }
    }

    /**
     * Hiển thị trang thanh toán thành công
     */
    public function success($orderId = null)
    {
        // Xử lý trường hợp order_id = 0 (thanh toán thất bại)
        if ($orderId == 0 || $orderId === null) {
            return view('customer.checkout.success', ['order' => null]);
        }

        $order = Order::findOrFail($orderId);
        
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('customer.checkout.success', compact('order'));
    }

}