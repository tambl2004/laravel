<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Tạo chữ ký HMAC SHA256 theo chuẩn MoMo
     */
    private function momoSign(string $rawHash, string $secretKey): string
    {
        return hash_hmac('sha256', $rawHash, $secretKey);
    }

    /**
     * Tạo requestId ngẫu nhiên cho MoMo
     */
    private function momoCreateRequestId(): string
    {
        return date('YmdHis') . rand(1000, 9999);
    }

    /**
     * Gọi API MoMo bằng Guzzle
     */
    private function momoPostJsonWithGuzzle(string $endpoint, array $data): array
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 10,
            ]);
            $res = $client->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($data)
            ]);
            $body = (string) $res->getBody();
            return json_decode($body, true) ?: [];
        } catch (\Throwable $e) {
            \Log::error('MoMo Guzzle error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Hiển thị trang thanh toán
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = Auth::user()->carts()->firstOrCreate(['user_id' => Auth::id()]);
        $items = CartItem::with('product.category')
            ->where('cart_id', $cart->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Lấy danh sách sản phẩm được chọn từ session
        $selectedItems = session('cart_selected_items', []);
        
        // Nếu không có sản phẩm nào được chọn, chuyển về giỏ hàng
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán!');
        }

        // Chỉ sử dụng sản phẩm được chọn để thanh toán
        $cartItems = [];
        $total = 0;
        $shippingFee = 0;

        foreach ($items as $item) {
            // Chỉ xử lý các sản phẩm được chọn
            if (in_array($item->product_id, $selectedItems) && $item->product) {
                $lineTotal = $item->product->price * $item->quantity;
                $cartItems[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineTotal,
                ];
                $total += $lineTotal;
            }
        }

        // Tính phí vận chuyển - Shop trả phí ship cho khách hàng
        $shippingFee = 0; // Shop chịu phí vận chuyển, khách hàng không mất phí ship

        // Tính tổng cuối cùng
        $finalAmount = $total + $shippingFee;

        // Lấy địa chỉ của user
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();

        return view('customer.checkout.index', compact('cartItems', 'total', 'shippingFee', 'finalAmount', 'addresses'));
    }

    /**
     * Xử lý đặt hàng
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
        ]);

        $cart = Auth::user()->carts()->firstOrCreate(['user_id' => Auth::id()]);
        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Lấy danh sách sản phẩm được chọn từ session
        $selectedItems = session('cart_selected_items', []);
        
        // Nếu không có sản phẩm nào được chọn, chuyển về giỏ hàng
        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm để thanh toán!');
        }

        // Chỉ sử dụng sản phẩm được chọn để đặt hàng
        $cartItems = [];
        $total = 0;

        foreach ($items as $item) {
            // Chỉ xử lý các sản phẩm được chọn
            if (in_array($item->product_id, $selectedItems) && $item->product) {
                $lineTotal = $item->product->price * $item->quantity;
                $cartItems[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineTotal,
                ];
                $total += $lineTotal;
            }
        }

        // Tính phí vận chuyển - Shop trả phí ship cho khách hàng
        $shippingFee = 0; // Shop chịu phí vận chuyển, khách hàng không mất phí ship
        $finalAmount = $total + $shippingFee;

        // Kiểm tra quyền sở hữu địa chỉ
        $address = Address::where('id', $request->address_id)
                         ->where('user_id', Auth::id())
                         ->first();
        
        if (!$address) {
            return redirect()->back()->with('error', 'Địa chỉ không hợp lệ!');
        }

        try {
            // Tạo đơn hàng
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

            // Xử lý thanh toán theo phương thức
            if ($request->payment_method === 'momo') {
                session(['momo_pending_order_id' => $order->id]);
                return redirect()->route('payment.momo', $order->id);
            } else {
                // Tích hợp với GHN API cho phương thức không phải MoMo
                // Gọi ở đây để chỉ đẩy sang GHN khi không cần chờ thanh toán ví MoMo
                // Xóa các sản phẩm đã đặt hàng khỏi giỏ hàng (chỉ khi không dùng MoMo)
            foreach ($cartItems as $item) {
                CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $item['product']->id)
                    ->delete();
            }

            // Xóa session lựa chọn sản phẩm
            session()->forget('cart_selected_items');

                // Đơn chưa thanh toán online -> Shop trả phí ship, GHN thu hộ tiền hàng
                $this->createGhnShippingOrder($order, $cartItems, false);
                // COD hoặc bank transfer - đặt hàng thành công ngay
                return redirect()->route('checkout.success', $order->id)
                               ->with('success', 'Đặt hàng thành công!');
            }

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!')
                           ->withInput();
        }
    }

    /**
     * Trang thành công sau khi đặt hàng
     */
    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('customer.checkout.success', compact('order'));
    }

    /**
     * Khởi tạo thanh toán MoMo (theo mẫu atm_momo.php)
     */
    public function momoStart(Request $request, Order $order)
    {
        // Kiểm tra quyền sở hữu đơn
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('checkout.index')->with('error', 'Không có quyền truy cập đơn hàng.');
        }

        // Lấy cấu hình từ file sample (momo_payment/php/basic.example/config.json)
        $configFile = base_path('momo_payment/php/basic.example/config.json');
        if (!file_exists($configFile)) {
            return redirect()->route('checkout.index')->with('error', 'Thiếu file cấu hình MoMo.');
        }
        $config = json_decode(file_get_contents($configFile), true);
        $partnerCode = $config['partnerCode'] ?? '';
        $accessKey = $config['accessKey'] ?? '';
        $secretKey = $config['secretKey'] ?? '';
        // Default endpoint sandbox nếu chưa cấu hình
        $endpoint = $config['endpoint'] ?? 'https://test-payment.momo.vn/v2/gateway/api/create';

        if (empty($partnerCode) || empty($accessKey) || empty($secretKey) || empty($endpoint)) {
            return redirect()->route('checkout.index')->with('error', 'Cấu hình MoMo không đầy đủ.');
        }

        // Tạo các tham số theo chuẩn MoMo v2
        $requestId = $this->momoCreateRequestId();
        // orderId khuyến nghị là chuỗi duy nhất theo đơn hàng, dùng order_number để dễ tra cứu
        $orderId = $order->order_number;
        $amount = (string) ((int) $order->total_amount);
        $orderInfo = "Thanh toán đơn hàng #{$order->order_number}";
        $returnUrl = route('payment.momo.return');
        $notifyUrl = route('payment.momo.notify');
        $extraData = "";

        // Tạo raw hash
        // Chuỗi ký chữ ký theo tài liệu MoMo v2 (ATM nội địa)
        $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$notifyUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$returnUrl}&requestId={$requestId}&requestType=payWithATM";
        
        // Tạo chữ ký
        $signature = $this->momoSign($rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl,
            'ipnUrl' => $notifyUrl,
            'extraData' => $extraData,
            // Sử dụng phương thức ATM nội địa theo yêu cầu
            'requestType' => 'payWithATM',
            'signature' => $signature,
            'lang' => 'vi'
        ];

        // Gọi API MoMo
        \Log::info('MoMo create payment - request', [
            'endpoint' => $endpoint,
            'payload' => $data,
            'order_id' => $order->id,
            'order_number' => $order->order_number
        ]);
        $result = $this->momoPostJsonWithGuzzle($endpoint, $data);

        if (isset($result['payUrl'])) {
            return redirect($result['payUrl']);
        } else {
            // Ghi log chi tiết để debug
            \Log::error('MoMo create payment - failed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'response' => $result
            ]);
            $message = $result['message'] ?? $result['localMessage'] ?? 'Không thể khởi tạo thanh toán MoMo.';
            if (isset($result['resultCode'])) {
                $message .= " (Mã: {$result['resultCode']})";
            }
            return redirect()->route('checkout.index')->with('error', $message);
        }
    }

    /**
     * Xử lý returnUrl từ MoMo
     */
    public function momoReturn(Request $request)
    {
        $orderIdParam = (string) $request->input('orderId');
        // MoMo trả về orderId đúng theo giá trị đã gửi (chúng ta dùng order_number)
        // Nếu là số, thử tìm theo id; nếu không, tìm theo order_number
        if ($orderIdParam !== '' && ctype_digit($orderIdParam)) {
            $order = Order::find((int) $orderIdParam);
        } else {
            $order = Order::where('order_number', $orderIdParam)->first();
        }

        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Đơn hàng không hợp lệ.');
        }

        $resultCode = $request->input('resultCode');
        
        if ($resultCode == 0) {
            // Thanh toán thành công: cập nhật trạng thái, xóa giỏ
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->save();

            // Xóa giỏ (nếu có đăng nhập)
            if (Auth::check()) {
                $cart = Auth::user()->carts()->firstOrCreate(['user_id' => Auth::id()]);
                CartItem::where('cart_id', $cart->id)->delete();
            }

            // Sau khi thanh toán MoMo thành công mới đẩy đơn sang GHN nếu chưa có mã
            if (empty($order->order_code)) {
                // Xây dựng lại danh sách items từ OrderItems để đẩy GHN
                $orderItems = $order->items()->with('product')->get();
                $cartItems = [];
                foreach ($orderItems as $it) {
                    $cartItems[] = [
                        'product' => $it->product ?? (object) ['id' => $it->product_id, 'name' => $it->product_name, 'price' => $it->product_price],
                        // Chèn object đơn giản nếu product đã bị xoá; vẫn đủ dữ liệu cho GHN
                        'quantity' => $it->quantity,
                        'subtotal' => $it->subtotal,
                    ];
                }
                // Đơn đã thanh toán MoMo -> GHN không thu hộ, người gửi trả phí
                $this->createGhnShippingOrder($order, $cartItems, true);
            }

            session()->forget('momo_pending_order_id');
            return redirect()->route('checkout.success', $order->id)->with('success', 'Thanh toán MoMo thành công!');
        }

        // Thất bại
        $order->payment_status = 'failed';
        $order->save();
        session()->forget('momo_pending_order_id');
        return redirect()->route('checkout.index')->with('error', 'Thanh toán MoMo thất bại.');
    }

    /**
     * Xử lý notifyUrl (server to server). Có thể chỉ log/cập nhật nếu cần.
     */
    public function momoNotify(Request $request)
    {
        // Ở sandbox, có thể nhận một số field, tùy vào cấu hình MoMo
        \Log::info('MoMo notify', $request->all());
        return response()->json(['status' => 'ok']);
    }

    /**
     * Tạo đơn hàng vận chuyển trên GHN API với địa chỉ đã convert
     * 
     * @param Order $order Đơn hàng đã tạo
     * @param array $cartItems Danh sách sản phẩm trong đơn hàng
     * @return void
     */
    private function createGhnShippingOrder(Order $order, array $cartItems, bool $isPrepaid = false): void
    {
        try {
            $ghnService = new GhnService();
            $mappingService = new AddressMappingService();
            
            // Convert địa chỉ hệ thống sang GHN format
            $addressMapping = $mappingService->convertAddressToGhn($order);
            
            if (!$addressMapping['success']) {
                \Log::warning('GHN Integration - Không thể convert địa chỉ', [
                    'order_id' => $order->id,
                    'error' => $addressMapping['error']
                ]);
                return; // Không tạo đơn hàng GHN nếu không convert được địa chỉ
            }
            
            // Chuẩn bị dữ liệu cho GHN API với địa chỉ đã convert
            $ghnItems = [];
            foreach ($cartItems as $item) {
                $ghnItems[] = [
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_price' => $item['product']->price,
                    'quantity' => $item['quantity']
                ];
            }
            
            $orderData = [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'shipping_name' => $order->shipping_name,
                'shipping_phone' => $order->shipping_phone,
                'shipping_address' => $order->shipping_address,
                'shipping_district_id' => $addressMapping['ghn_district_id'],
                'shipping_ward_code' => $addressMapping['ghn_ward_code'],
                'total_amount' => $order->total_amount,
                'notes' => $order->notes,
                'items' => $ghnItems
            ];

            // Gọi GHN API để tạo đơn hàng vận chuyển
            // Shop luôn trả phí ship (payment_type_id = 1), khách hàng không mất phí vận chuyển
            $orderData['is_prepaid'] = $isPrepaid;
            $ghnResult = $ghnService->createShippingOrder($orderData);

            if ($ghnResult && isset($ghnResult['data']['order_code'])) {
                // Cập nhật mã đơn hàng GHN vào database
                $order->order_code = $ghnResult['data']['order_code'];
                $order->save();

                \Log::info('GHN Integration - Đơn hàng được tạo thành công', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'ghn_order_code' => $ghnResult['data']['order_code'],
                    'address_mapping' => $addressMapping
                ]);
            } else {
                \Log::warning('GHN Integration - Không thể tạo đơn hàng vận chuyển', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'ghn_response' => $ghnResult,
                    'address_mapping' => $addressMapping
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('GHN Integration - Lỗi khi tạo đơn hàng vận chuyển', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}