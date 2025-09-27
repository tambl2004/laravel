<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Address;

class PaymentController extends Controller
{
    // ==========================
    // Đặt cấu hình thông số MoMo test
    // ==========================
    private $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey = 'klm05TvNBzhg7h7j';
    private $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    private $redirectUrl;
    private $ipnUrl;

    public function __construct()
    {
        $this->redirectUrl = route('checkout.success', 0); // Sẽ được cập nhật động trong từng method
        $this->ipnUrl = route('payment.momo.ipn');
    }

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
            return json_decode($res->getBody(), true);
        } catch (\Exception $e) {
            Log::error('MoMo API call failed', [
                'endpoint' => $endpoint,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Hiển thị trang thanh toán MoMo từ temp data (chưa tạo đơn hàng)
     */
    public function momoTemp(Request $request)
    {
        Log::info('PaymentController::momoTemp - Displaying MoMo temp payment page');
        
        // Lấy thông tin đơn hàng tạm từ session
        $tempOrderData = session('temp_order_data');
        
        if (!$tempOrderData) {
            Log::warning('PaymentController::momoTemp - No temp order data found in session');
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin đơn hàng. Vui lòng thử lại!');
        }

        Log::info('PaymentController::momoTemp - Temp order data found', [
            'order_number' => $tempOrderData['order_number'],
            'total_amount' => $tempOrderData['total_amount'],
            'items_count' => count($tempOrderData['cart_items'])
        ]);

        return view('customer.payment.momo-temp', compact('tempOrderData'));
    }

    /**
     * Xử lý redirect đến MoMo từ temp data
     */
    public function redirectToMoMoTemp(Request $request)
    {
        Log::info('PaymentController::redirectToMoMoTemp - Processing MoMo payment from temp data');
        
        // Lấy thông tin đơn hàng tạm từ session
        $tempOrderData = session('temp_order_data');
        
        if (!$tempOrderData) {
            Log::error('PaymentController::redirectToMoMoTemp - No temp order data found');
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin đơn hàng. Vui lòng thử lại!');
        }

        try {
            // Tạo requestId và orderId cho MoMo
            $requestId = $this->momoCreateRequestId();
            $orderId = $requestId . '_TEMP'; // Thêm _TEMP để phân biệt với đơn hàng thật
            
            Log::info('PaymentController::redirectToMoMoTemp - Creating MoMo ATM request', [
                'request_id' => $requestId,
                'order_id' => $orderId,
                'amount' => $tempOrderData['total_amount'],
                'request_type' => 'payWithATM'
            ]);

            // Tạo dữ liệu request cho MoMo ATM
            $redirectUrl = route('payment.momo.callback'); // Sử dụng callback để xử lý message từ MoMo
            $rawHash = "accessKey={$this->accessKey}&amount={$tempOrderData['total_amount']}&extraData=&ipnUrl={$this->ipnUrl}&orderId={$orderId}&orderInfo={$tempOrderData['order_number']}&partnerCode={$this->partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType=payWithATM";
            $signature = $this->momoSign($rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $tempOrderData['total_amount'],
                'orderId' => $orderId,
                'orderInfo' => $tempOrderData['order_number'],
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $this->ipnUrl,
                'lang' => 'vi',
                'extraData' => '',
                'requestType' => 'payWithATM',
                'signature' => $signature
            ];

            Log::info('PaymentController::redirectToMoMoTemp - Sending request to MoMo ATM API', [
                'endpoint' => $this->endpoint,
                'request_type' => 'payWithATM',
                'data' => $data
            ]);

            $result = $this->momoPostJsonWithGuzzle($this->endpoint, $data);
            
            Log::info('PaymentController::redirectToMoMoTemp - MoMo API response', [
                'result' => $result
            ]);

            if ($result['resultCode'] == 0) {
                // Lưu thông tin transaction vào session để xử lý sau
                session(['temp_momo_transaction' => [
                    'request_id' => $requestId,
                    'order_id' => $orderId,
                    'amount' => $tempOrderData['total_amount'],
                    'order_number' => $tempOrderData['order_number'],
                    'created_at' => now()
                ]]);
                
                Log::info('PaymentController::redirectToMoMoTemp - Redirecting to MoMo ATM', [
                    'pay_url' => $result['payUrl']
                ]);
                
                return redirect($result['payUrl']);
            } else {
                Log::error('PaymentController::redirectToMoMoTemp - MoMo API error', [
                    'result_code' => $result['resultCode'],
                    'message' => $result['message'] ?? 'Unknown error'
                ]);
                
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo giao dịch MoMo: ' . ($result['message'] ?? 'Lỗi không xác định'));
            }

        } catch (\Exception $e) {
            Log::error('PaymentController::redirectToMoMoTemp - Exception occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại!');
        }
    }

    /**
     * Hiển thị trang thanh toán MoMo
     */
    public function momo($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        return view('customer.payment.momo', compact('order'));
    }

    /**
     * Tạo giao dịch MoMo và chuyển hướng người dùng
     */
    public function redirectToMoMo(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();

        $redirectUrl = route('payment.momo.callback');
        $ipnUrl = route('payment.momo.ipn');
        $orderId = time() . '_' . $order->id;
        $requestId = uniqid();

        $orderInfo = "Thanh toán đơn hàng #{$order->id}";
        $amount = (string) max(1000, (int) $order->total_amount); // test nên >= 1000
        $extraData = ''; // có thể base64_encode(json_encode(...))
        $requestType = 'payWithATM'; // có thể chuyển thành creditCard nếu lỗi
        
        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}"
            . "&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}"
            . "&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        
        Log::info('MoMo rawHash for signature: ' . $rawHash);
        
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
        
        $payload = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => "KitchenHood Pro",
            'storeId' => "Store_01",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        Log::info('MoMo ATM request payload: ', $payload);

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
                ->withoutVerifying()
                ->post($this->endpoint, $payload);

            if (!$response->successful()) {
                Log::error('MoMo create payment failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return redirect()
                    ->route('orders.index')
                    ->with('error', 'Không thể kết nối MoMo (' . $response->status() . '). Vui lòng thử lại.');
            }

            $json = $response->json();
            Log::info('MoMo ATM response:', $json);

            if (!empty($json['payUrl'])) {
                $order->update([
                    'momo_request_id' => $requestId,
                    'momo_order_id' => $orderId,
                ]);
                return redirect()->away($json['payUrl']);
            }

            // Không có payUrl → báo lỗi rõ
            $msg = $json['message'] ?? 'MoMo không trả về payUrl.';
            Log::error('MoMo payUrl missing', ['response' => $json]);
            return redirect()
                ->route('orders.index')
                ->with('error', 'Không tạo được link thanh toán MoMo: ' . $msg);

        } catch (\Exception $e) {
            Log::error('MoMo request exception', ['error' => $e->getMessage()]);
            return redirect()
                ->route('orders.index')
                ->with('error', 'Lỗi khi tạo thanh toán MoMo: ' . $e->getMessage());
        }
    }

    /**
     * Callback từ MoMo - xử lý kết quả thanh toán và redirect đến success
     */
    public function callback(Request $request)
    {
        Log::info('PaymentController::callback - MoMo callback received', [
            'request_data' => $request->all(),
            'url' => $request->fullUrl()
        ]);

        $resultCode = $request->input('resultCode'); // 0 = success
        $message = $request->input('message', '');
        $orderId = $request->input('orderId', '');
        $amount = $request->input('amount', 0);
        
        // Decode message từ URL encoding
        $message = urldecode($message);
        
        Log::info('PaymentController::callback - Processing callback', [
            'result_code' => $resultCode,
            'message' => $message,
            'order_id' => $orderId,
            'amount' => $amount
        ]);

        // Kiểm tra xem có phải temp order không
        $isTempOrder = false;
        $order = null;
        $tempOrderData = null;
        
        if ($orderId) {
            // Kiểm tra xem có phải temp order không (có _TEMP)
            if (strpos($orderId, '_TEMP') !== false) {
                $isTempOrder = true;
                $tempOrderData = session('temp_order_data');
                
                Log::info('PaymentController::callback - Temp order detected', [
                    'momo_order_id' => $orderId,
                    'has_temp_data' => $tempOrderData ? true : false
                ]);
            } else {
                // Đơn hàng thật - lấy id thực từ "time_orderId"
                $parts = explode('_', $orderId);
                $realOrderId = end($parts);
                $order = Order::find($realOrderId);

                Log::info('PaymentController::callback - Real order lookup', [
                    'momo_order_id' => $orderId,
                    'parsed_order_id' => $realOrderId,
                    'order_found' => $order ? true : false
                ]);
            }
        }

        // Xử lý theo kết quả thanh toán
        if ($resultCode === '0' || $resultCode === 0) {
            // ✅ THÀNH CÔNG
            Log::info('PaymentController::callback - Payment successful', [
                'result_code' => $resultCode,
                'message' => $message,
                'is_temp_order' => $isTempOrder
            ]);

            if ($isTempOrder && $tempOrderData) {
                // Tạo đơn hàng thật từ temp data
                try {
                    $order = Order::create([
                        'user_id' => $tempOrderData['user_id'],
                        'order_number' => $tempOrderData['order_number'],
                        'order_code' => '', // Sẽ được cập nhật sau khi gọi GHN API
                        'subtotal' => $tempOrderData['subtotal'],
                        'shipping_fee' => $tempOrderData['shipping_fee'],
                        'total_amount' => $tempOrderData['total_amount'],
                        'payment_method' => $tempOrderData['payment_method'],
                        'payment_status' => 'paid',
                        'shipping_name' => $tempOrderData['shipping_name'],
                        'shipping_phone' => $tempOrderData['shipping_phone'],
                        'shipping_address' => $tempOrderData['shipping_address'],
                        'shipping_province_id' => $tempOrderData['shipping_province_id'],
                        'shipping_province_name' => $tempOrderData['shipping_province_name'],
                        'shipping_district_id' => $tempOrderData['shipping_district_id'],
                        'shipping_district_name' => $tempOrderData['shipping_district_name'],
                        'shipping_ward_id' => $tempOrderData['shipping_ward_id'],
                        'shipping_ward_name' => $tempOrderData['shipping_ward_name'],
                        'notes' => $tempOrderData['notes'],
                        'status' => 'processing'
                    ]);

                    // Tạo chi tiết đơn hàng và trừ tồn kho
                    foreach ($tempOrderData['cart_items'] as $item) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product']->id,
                            'product_name' => $item['product']->name,
                            'product_price' => $item['product']->price,
                            'quantity' => $item['quantity'],
                            'subtotal' => $item['subtotal'],
                        ]);

                        // Trừ tồn kho
                        $item['product']->reduceStock(
                            $item['quantity'],
                            "Xuất hàng cho đơn hàng #{$order->order_number}",
                            $order->id,
                            $tempOrderData['user_id']
                        );
                    }

                    // Xóa các sản phẩm đã đặt hàng khỏi giỏ hàng
                    foreach ($tempOrderData['cart_items'] as $item) {
                        CartItem::where('cart_id', $tempOrderData['cart_id'])
                            ->where('product_id', $item['product']->id)
                            ->delete();
                    }

                    // Xóa session temp data
                    session()->forget(['temp_order_data', 'temp_momo_transaction', 'cart_selected_items']);

                    Log::info('PaymentController::callback - Order created successfully', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number
                    ]);

                } catch (\Exception $e) {
                    Log::error('PaymentController::callback - Error creating order', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    // Xóa temp data nếu có lỗi
                    session()->forget(['temp_order_data', 'temp_momo_transaction']);
                    
                    return redirect()->route('checkout.success', 0)
                        ->with('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng liên hệ hỗ trợ.')
                        ->with('momo_message', $message);
                }

            } else if ($order) {
                // Cập nhật đơn hàng thật
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);

                // Xóa giỏ hàng
                $cart = Cart::where('user_id', $order->user_id)->first();
                if ($cart) {
                    $orderProductIds = $order->items->pluck('product_id')->toArray();
                    CartItem::where('cart_id', $cart->id)
                           ->whereIn('product_id', $orderProductIds)
                           ->delete();
                }
            }
            
            // Redirect đến success với thông báo thành công
            Log::info('PaymentController::callback - Redirecting to success with order', [
                'order_id' => $order->id ?? 0,
                'message' => $message
            ]);
            
            return redirect()->route('checkout.success', $order->id ?? 0)
                ->with('success', 'Thanh toán MoMo ATM thành công! Đơn hàng của bạn đã được tạo.')
                ->with('momo_message', $message);

        } else {
            // ❌ THẤT BẠI
            Log::warning('PaymentController::callback - Payment failed', [
                'result_code' => $resultCode,
                'message' => $message,
                'is_temp_order' => $isTempOrder
            ]);

            // Xóa temp data nếu có
            if ($isTempOrder) {
                session()->forget(['temp_order_data', 'temp_momo_transaction']);
                Log::info('PaymentController::callback - Temp data cleared for failed payment');
            }

            // Redirect đến success với thông báo thất bại
            Log::info('PaymentController::callback - Redirecting to success with error', [
                'message' => $message,
                'result_code' => $resultCode
            ]);
            
            return redirect()->route('checkout.success', 0)
                ->with('error', 'Thanh toán MoMo ATM thất bại.')
                ->with('momo_message', $message)
                ->with('momo_result_code', $resultCode);
        }
    }

    /**
     * IPN: MoMo gọi ngầm (server-to-server) báo trạng thái
     */
    public function ipn(Request $request)
    {
        Log::info('MoMo IPN payload:', $request->all());
        
        // TODO: bạn nên xác thực chữ ký ở đây
        // Ví dụ cập nhật trạng thái dựa vào orderId/resultCode:
        if ($request->filled('orderId')) {
            $parts = explode('_', $request->orderId);
            $orderId = end($parts);
            if ($order = Order::find($orderId)) {
                if ((string)($request->resultCode) === '0') {
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);
                } else {
        $order->update([
                        'status' => 'pending',
                        'payment_status' => 'failed'
                    ]);
                }
            }
        }
        
        return response()->json(['resultCode' => 0, 'message' => 'Received']);
    }

    /**
     * Cho phép user kéo lại đơn chưa thanh toán đi MoMo lần nữa
     */
    public function payAgain(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thanh toán lại đơn này.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.index')->with('info', 'Đơn này đã thanh toán.');
        }

        // Đưa về "chờ thanh toán" trước khi tạo giao dịch mới
        $order->update(['status' => 'waiting_payment']);
        
        // PHẢI return
        return $this->redirectToMoMo(new Request(), $order->id);
    }

    /**
     * Hiển thị trang thanh toán thành công
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('customer.payment.success', compact('order'));
    }

    /**
     * Hiển thị trang thanh toán thất bại
     */
    public function failed(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('customer.payment.failed', compact('order'));
    }

    /**
     * Chuyển đổi phương thức thanh toán sang COD
     */
    public function switchToCod(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thay đổi phương thức thanh toán đơn này.');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.index')->with('error', 'Đơn này đã thanh toán.');
        }

        $order->update([
            'payment_method' => 'cod',
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Đã chuyển sang thanh toán COD thành công!');
    }

    /**
     * Hủy đơn hàng và khôi phục giỏ hàng (method chung)
     */
    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền hủy đơn này.');
        }

        // Chỉ cho phép hủy đơn hàng chưa thanh toán
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.index')->with('error', 'Không thể hủy đơn hàng đã thanh toán.');
        }

        try {
            // Khôi phục tồn kho cho các sản phẩm
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->addStock(
                        $item->quantity,
                        "Hủy đơn hàng #{$order->order_number}",
                        Auth::id()
                    );
                }
            }

            // Khôi phục giỏ hàng
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            foreach ($order->items as $item) {
                $existingCartItem = CartItem::where('cart_id', $cart->id)
                                          ->where('product_id', $item->product_id)
                                          ->first();
                
                if ($existingCartItem) {
                    // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
                    $existingCartItem->update([
                        'quantity' => $existingCartItem->quantity + $item->quantity
                    ]);
                } else {
                    // Thêm mới vào giỏ hàng
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
            }

            // Xóa đơn hàng
            $order->delete();

            return redirect()->route('cart.index')
                           ->with('success', 'Đã hủy đơn hàng và khôi phục giỏ hàng thành công!');

        } catch (\Exception $e) {
            Log::error('Cancel order error: ' . $e->getMessage());
            return redirect()->route('orders.index')
                           ->with('error', 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại!');
        }
    }

}