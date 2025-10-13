<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GhnService
{
    private $apiUrl;
    private $token;
    private $shopId;
    private $fromDistrictId;
    private $fromWardCode;

    public function __construct()
    {
        $this->apiUrl = config('services.ghn.api_url');
        $this->token = config('services.ghn.token');
        $this->shopId = config('services.ghn.shop_id');
        $this->fromDistrictId = config('services.ghn.from_district_id');
        $this->fromWardCode = config('services.ghn.from_ward_code');
    }

    /**
     * Tạo đơn hàng vận chuyển trên GHN
     * 
     * @param array $orderData Dữ liệu đơn hàng
     * @return array|null Kết quả từ GHN API hoặc null nếu lỗi
     */
    public function createShippingOrder(array $orderData): ?array
    {
        try {
            // Chuẩn bị payload cho GHN API
            $payload = $this->prepareShippingOrderPayload($orderData);

            // Gọi API GHN
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])->withOptions([
                'verify' => false, // Tắt SSL verification cho development
            ])->timeout(30)->post($this->apiUrl . '/shiip/public-api/v2/shipping-order/create', $payload);

            if ($response->successful()) {
                $result = $response->json();
                
                // Log thành công
                Log::info('GHN API - Tạo đơn hàng thành công', [
                    'order_id' => $orderData['order_id'],
                    'order_code' => $result['data']['order_code'] ?? null,
                    'response' => $result
                ]);

                return $result;
            } else {
                // Log lỗi
                Log::error('GHN API - Lỗi tạo đơn hàng', [
                    'order_id' => $orderData['order_id'],
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return null;
            }

        } catch (Exception $e) {
            Log::error('GHN API - Exception khi tạo đơn hàng', [
                'order_id' => $orderData['order_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Chuẩn bị payload cho GHN API
     * 
     * @param array $orderData Dữ liệu đơn hàng
     * @return array Payload cho GHN API
     */
    private function prepareShippingOrderPayload(array $orderData): array
    {
        $isPrepaid = (bool)($orderData['is_prepaid'] ?? false);

        return [
            // payment_type_id: 1 = Người gửi trả phí (shop); 2 = Người nhận trả phí (khách)
            // Mặc định shop trả phí ship để khách hàng không mất phí vận chuyển
            'payment_type_id' => 1,
            'note' => $orderData['notes'] ?? '',
            'required_note' => 'KHONGCHOXEMHANG',
            'from_name' => 'Kitchen Hood Store', // Tên shop
            'from_phone' => '0352135115', // Số điện thoại shop
            'from_address' => '110 Nguyễn Huệ, Bến Nghé, Quận 1, Hồ Chí Minh', // Địa chỉ shop
            'from_district_id' => $this->fromDistrictId,
            'from_ward_code' => $this->fromWardCode,
            'to_name' => $orderData['shipping_name'],
            'to_phone' => $orderData['shipping_phone'],
            'to_address' => $orderData['shipping_address'],
            'to_ward_code' => $orderData['shipping_ward_code'],
            'to_district_id' => $orderData['shipping_district_id'],
            // Nếu đã thanh toán (MoMo/bank), tiền thu hộ = 0
            'cod_amount' => $isPrepaid ? 0 : (int)$orderData['total_amount'],
            'content' => $orderData['order_number'],
            'weight' => $this->calculateWeight($orderData['items']),
            'length' => 20,
            'width' => 20,
            'height' => 20,
            'service_type_id' => 2, // Standard
            'service_id' => 0, // Sẽ được tính toán tự động
            'items' => $this->prepareOrderItems($orderData['items'])
        ];
    }

    /**
     * Tính toán trọng lượng đơn hàng (gram)
     * 
     * @param array $items Danh sách sản phẩm
     * @return int Trọng lượng tính bằng gram
     */
    private function calculateWeight(array $items): int
    {
        $totalWeight = 0;
        
        foreach ($items as $item) {
            // Giả sử mỗi sản phẩm có trọng lượng 500g
            $weightPerItem = 500;
            $totalWeight += $weightPerItem * $item['quantity'];
        }

        // Tối thiểu 100g
        return max($totalWeight, 100);
    }

    /**
     * Chuẩn bị danh sách sản phẩm cho GHN API
     * 
     * @param array $items Danh sách sản phẩm
     * @return array Danh sách sản phẩm đã format
     */
    private function prepareOrderItems(array $items): array
    {
        $ghnItems = [];
        
        foreach ($items as $item) {
            $ghnItems[] = [
                'name' => $item['product_name'],
                'code' => 'SP' . $item['product_id'],
                'quantity' => (int)$item['quantity'],
                'price' => (int)$item['product_price'],
                'weight' => 500 // gram
            ];
        }

        return $ghnItems;
    }

    /**
     * Lấy thông tin phí vận chuyển
     * 
     * @param int $toDistrictId ID quận/huyện nhận hàng
     * @param int $toWardCode Mã phường/xã nhận hàng
     * @param int $weight Trọng lượng (gram)
     * @return array|null Thông tin phí vận chuyển
     */
    public function getShippingFee(int $toDistrictId, int $toWardCode, int $weight = 100): ?array
    {
        try {
            $payload = [
                'from_district_id' => $this->fromDistrictId,
                'from_ward_code' => $this->fromWardCode,
                'to_district_id' => $toDistrictId,
                'to_ward_code' => $toWardCode,
                'weight' => $weight,
                'service_type_id' => 2
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])->withOptions([
                'verify' => false, // Tắt SSL verification cho development
            ])->timeout(30)->post($this->apiUrl . '/shiip/public-api/v2/shipping-order/fee', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (Exception $e) {
            Log::error('GHN API - Lỗi lấy phí vận chuyển', [
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Lấy ward_code từ ward_id và district_id
     * 
     * @param int $districtId ID quận/huyện
     * @param int $wardId ID phường/xã
     * @return string|null Ward code hợp lệ cho GHN API
     */
    public function getWardCodeFromWardId(int $districtId, int $wardId): ?string
    {
        try {
            $wards = $this->getWardsByDistrict($districtId);
            
            if ($wards && isset($wards['data'])) {
                foreach ($wards['data'] as $ward) {
                    // GHN API không có WardID, chỉ có WardCode
                    // Sử dụng WardCode trực tiếp nếu wardId khớp với WardCode (so sánh string)
                    if (isset($ward['WardCode']) && $ward['WardCode'] == (string)$wardId) {
                        return $ward['WardCode'];
                    }
                }
            }
            
            return null;
            
        } catch (Exception $e) {
            Log::error('GHN API - Lỗi lấy ward code từ ward id', [
                'district_id' => $districtId,
                'ward_id' => $wardId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Lấy danh sách phường/xã theo district_id
     * 
     * @param int $districtId ID quận/huyện
     * @return array|null Danh sách phường/xã
     */
    public function getWardsByDistrict(int $districtId): ?array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])->withOptions([
                'verify' => false, // Tắt SSL verification cho development
            ])->timeout(30)->get($this->apiUrl . '/shiip/public-api/master-data/ward', [
                'district_id' => $districtId
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (Exception $e) {
            Log::error('GHN API - Lỗi lấy danh sách phường/xã', [
                'district_id' => $districtId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Lấy thông tin trạng thái đơn hàng
     * 
     * @param string $orderCode Mã đơn hàng GHN
     * @return array|null Thông tin trạng thái đơn hàng
     */
    public function getOrderStatus(string $orderCode): ?array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Token' => $this->token,
            ])->withOptions([
                'verify' => false, // Tắt SSL verification cho development
            ])->timeout(30)->get($this->apiUrl . '/shiip/public-api/v2/shipping-order/detail', [
                'order_code' => $orderCode
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (Exception $e) {
            Log::error('GHN API - Lỗi lấy trạng thái đơn hàng', [
                'order_code' => $orderCode,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
}