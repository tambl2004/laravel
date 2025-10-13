# Cập Nhật Phí Vận Chuyển - Shop Chịu Phí Ship

## Tổng Quan
Đã cập nhật hệ thống để **Shop chịu phí vận chuyển** cho tất cả đơn hàng, khách hàng không phải trả phí ship.

## Các Thay Đổi Đã Thực Hiện

### 1. **GhnService.php** - Cấu hình GHN API
- **File:** `app/Services/GhnService.php`
- **Thay đổi:** 
  - `payment_type_id` luôn được set = 1 (Shop trả phí)
  - Cập nhật comment để làm rõ rằng shop chịu phí ship

### 2. **CheckoutController.php** - Logic xử lý checkout
- **File:** `app/Http/Controllers/CheckoutController.php`
- **Thay đổi:**
  - `$shippingFee = 0` (Shop chịu phí)
  - Cập nhật comment trong các method liên quan
  - Đảm bảo `finalAmount = total + 0`

### 3. **Views - Giao diện người dùng**
Cập nhật tất cả view để hiển thị "**Shop chịu phí**" thay vì "Miễn phí":

#### Customer Views:
- `resources/views/customer/checkout/index.blade.php`
- `resources/views/customer/checkout/success.blade.php`
- `resources/views/customer/orders/index.blade.php`
- `resources/views/customer/orders/show.blade.php`
- `resources/views/customer/payment/success.blade.php`

#### Admin Views:
- `resources/views/admin/orders/show.blade.php`

## Kết Quả

### ✅ **Đối với Khách hàng:**
- Không phải trả phí vận chuyển
- Hiển thị "Shop chịu phí" trong tất cả giao diện
- Tổng tiền chỉ bao gồm giá sản phẩm

### ✅ **Đối với Shop:**
- Shop sẽ trả phí vận chuyển cho GHN
- `payment_type_id = 1` trong GHN API
- Phí ship được tính vào chi phí vận hành

### ✅ **Đối với GHN API:**
- Đơn hàng được tạo với `payment_type_id = 1`
- Shop sẽ được GHN tính phí vận chuyển
- Khách hàng chỉ trả tiền hàng (COD) hoặc đã thanh toán online

## Lưu Ý
- Tất cả đơn hàng mới sẽ áp dụng chính sách này
- Đơn hàng cũ không bị ảnh hưởng
- Shop cần đảm bảo có đủ tài chính để trả phí ship cho GHN

## Kiểm Tra
Để kiểm tra hoạt động:
1. Tạo đơn hàng mới
2. Kiểm tra GHN API có `payment_type_id = 1`
3. Xác nhận khách hàng không bị tính phí ship
4. Kiểm tra giao diện hiển thị "Shop chịu phí"
