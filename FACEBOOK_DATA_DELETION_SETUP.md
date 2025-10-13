# Thiết lập Route Xóa Dữ Liệu cho Facebook Login App

## Tổng Quan
Đã thêm các route xử lý yêu cầu xóa dữ liệu cá nhân để đáp ứng yêu cầu của Facebook Login App và GDPR.

## Các Route Đã Thêm

### 1. **Route chính cho Facebook** - `GET /delete`
- **Mục đích:** Facebook sẽ gửi request đến route này để kiểm tra
- **Response:** JSON với status 200 OK
- **Controller:** `DataDeletionController@delete`
- **Log:** Ghi lại tất cả request để theo dõi

### 2. **Route chi tiết cho GDPR** - `POST /data-deletion`
- **Mục đích:** Xử lý yêu cầu xóa dữ liệu chi tiết
- **Input:** user_id, email, reason (optional)
- **Response:** JSON với thông tin chi tiết về quy trình
- **Controller:** `DataDeletionController@dataDeletion`

### 3. **Route thông tin chính sách** - `GET /privacy/data-deletion`
- **Mục đích:** Cung cấp thông tin về chính sách xóa dữ liệu
- **Response:** JSON với thông tin đầy đủ
- **Controller:** `DataDeletionController@privacyInfo`

### 4. **Route trang HTML** - `GET /privacy/data-deletion-page`
- **Mục đích:** Hiển thị trang web đẹp về chính sách xóa dữ liệu
- **Response:** HTML page với giao diện thân thiện
- **View:** `privacy.data-deletion`

## Files Đã Tạo/Cập Nhật

### 1. **Controller:** `app/Http/Controllers/DataDeletionController.php`
- Method `delete()`: Xử lý request từ Facebook
- Method `dataDeletion()`: Xử lý yêu cầu chi tiết
- Method `privacyInfo()`: Cung cấp thông tin chính sách
- Logging: Ghi lại tất cả request để audit

### 2. **View:** `resources/views/privacy/data-deletion.blade.php`
- Giao diện HTML đẹp mắt
- Thông tin đầy đủ về quy trình
- Liên kết email trực tiếp
- Responsive design

### 3. **Routes:** `routes/web.php`
- Thêm 4 routes mới
- Tất cả đều trả về status 200 OK
- Không cần authentication

## Response Examples

### GET /delete (cho Facebook)
```json
{
    "message": "Để xóa dữ liệu cá nhân, vui lòng liên hệ admin qua email: admin@kitchenhood.com",
    "contact_email": "admin@kitchenhood.com",
    "instructions": [
        "Gửi email với tiêu đề: \"Yêu cầu xóa dữ liệu cá nhân\"",
        "Cung cấp email đăng ký và thông tin liên hệ",
        "Chúng tôi sẽ xử lý trong vòng 30 ngày làm việc"
    ],
    "status": "success",
    "timestamp": "2025-01-20T12:00:00.000000Z"
}
```

### POST /data-deletion (cho GDPR)
```json
{
    "message": "Yêu cầu xóa dữ liệu đã được ghi nhận",
    "instructions": [
        "1. Gửi email đến admin@kitchenhood.com với tiêu đề \"Yêu cầu xóa dữ liệu cá nhân\"",
        "2. Cung cấp thông tin: Email đăng ký, Số điện thoại (nếu có)",
        "3. Chúng tôi sẽ xử lý yêu cầu trong vòng 30 ngày làm việc"
    ],
    "contact_email": "admin@kitchenhood.com",
    "response_time": "30 ngày làm việc",
    "request_id": "DEL_1737360000_a1b2c3d4",
    "status": "success",
    "timestamp": "2025-01-20T12:00:00.000000Z"
}
```

## Kiểm Tra

### 1. **Test Facebook Route:**
```bash
curl -X GET http://localhost:8000/delete
```
**Expected:** Status 200, JSON response

### 2. **Test GDPR Route:**
```bash
curl -X POST http://localhost:8000/data-deletion \
  -H "Content-Type: application/json" \
  -d '{"user_id":"123","email":"user@example.com"}'
```
**Expected:** Status 200, JSON response

### 3. **Test Privacy Info:**
```bash
curl -X GET http://localhost:8000/privacy/data-deletion
```
**Expected:** Status 200, JSON response

### 4. **Test HTML Page:**
```bash
curl -X GET http://localhost:8000/privacy/data-deletion-page
```
**Expected:** Status 200, HTML response

## Cấu Hình Facebook App

Trong Facebook Developer Console:
1. Vào **App Settings** → **Advanced**
2. Trong phần **Data Deletion Request URL**
3. Nhập: `https://yourdomain.com/delete`
4. Save changes

Facebook sẽ tự động test route này và báo lỗi nếu không nhận được response 200 OK.

## Logging

Tất cả request đều được log vào `storage/logs/laravel.log` với thông tin:
- IP address
- User Agent
- Timestamp
- Request data (nếu có)

Để xem logs:
```bash
tail -f storage/logs/laravel.log | grep "Data deletion"
```
