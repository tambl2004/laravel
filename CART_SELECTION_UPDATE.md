# Cập Nhật Giỏ Hàng - Chọn Sản Phẩm Để Đặt Hàng

## Tổng Quan
Đã cập nhật hệ thống giỏ hàng để **chỉ đặt hàng với những sản phẩm được chọn**, thay vì mặc định đặt tất cả sản phẩm trong giỏ hàng.

## Các Thay Đổi Đã Thực Hiện

### 1. **Cart View - Mặc định không chọn sản phẩm**
- **File:** `resources/views/customer/cart/index.blade.php`
- **Thay đổi:**
  - Bỏ thuộc tính `checked` khỏi checkbox sản phẩm
  - Hiển thị "Đã chọn: 0" thay vì tất cả sản phẩm
  - Tổng tiền hiển thị "0 VNĐ" ban đầu
  - Cập nhật JavaScript để tính tổng chỉ cho sản phẩm được chọn

### 2. **CartController - Thêm method lưu sản phẩm được chọn**
- **File:** `app/Http/Controllers/CartController.php`
- **Thêm method:** `saveSelected()`
  - Lưu danh sách sản phẩm được chọn vào session
  - Validation: kiểm tra sản phẩm tồn tại
  - Trả về JSON response cho AJAX

### 3. **CheckoutController - Chỉ xử lý sản phẩm được chọn**
- **File:** `app/Http/Controllers/CheckoutController.php`
- **Thay đổi:**
  - Method `index()`: Kiểm tra session `cart_selected_items`
  - Method `store()`: Chỉ xử lý sản phẩm trong danh sách được chọn
  - Redirect về cart nếu không có sản phẩm nào được chọn

### 4. **Routes - Thêm route lưu sản phẩm được chọn**
- **File:** `routes/web.php`
- **Thêm route:** `POST /cart/save-selected`
  - Lưu danh sách sản phẩm được chọn vào session
  - Middleware: auth

### 5. **JavaScript - Cập nhật logic frontend**
- **File:** `resources/views/customer/cart/index.blade.php`
- **Thay đổi:**
  - Tính tổng tiền chỉ cho sản phẩm được chọn
  - Lưu sản phẩm được chọn qua AJAX thay vì sessionStorage
  - Kiểm tra tồn kho trước khi checkout
  - Hiển thị cảnh báo nếu không chọn sản phẩm nào

## Kết Quả

### ✅ **Đối với Khách hàng:**
- Mặc định không chọn sản phẩm nào trong giỏ hàng
- Phải tích chọn sản phẩm muốn mua
- Tổng tiền hiển thị chính xác cho sản phẩm được chọn
- Không thể checkout nếu không chọn sản phẩm nào

### ✅ **Đối với Hệ thống:**
- Chỉ tạo đơn hàng với sản phẩm được chọn
- Chỉ trừ tồn kho cho sản phẩm được chọn
- Chỉ xóa khỏi giỏ hàng những sản phẩm đã đặt
- Session lưu trữ danh sách sản phẩm được chọn

### ✅ **Tính năng bổ sung:**
- Nút "Chọn tất cả" / "Bỏ chọn tất cả"
- Hiển thị số lượng sản phẩm đã chọn
- Kiểm tra tồn kho trước khi checkout
- Cảnh báo nếu sản phẩm hết hàng hoặc không đủ

## Luồng Hoạt Động

1. **Khách hàng vào giỏ hàng** → Không sản phẩm nào được chọn
2. **Chọn sản phẩm muốn mua** → Tích checkbox
3. **Tổng tiền cập nhật** → Chỉ tính sản phẩm được chọn
4. **Nhấn "Tiến hành thanh toán"** → Lưu danh sách vào session
5. **Chuyển đến checkout** → Chỉ hiển thị sản phẩm được chọn
6. **Đặt hàng thành công** → Chỉ xóa sản phẩm đã đặt khỏi giỏ

## Kiểm Tra
Để kiểm tra hoạt động:
1. Thêm nhiều sản phẩm vào giỏ hàng
2. Chỉ chọn một số sản phẩm
3. Kiểm tra tổng tiền chỉ tính sản phẩm được chọn
4. Thực hiện checkout và xác nhận chỉ đặt hàng sản phẩm được chọn
