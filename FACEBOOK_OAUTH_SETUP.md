# Hướng Dẫn Cấu Hình Facebook OAuth

## Tổng Quan
Đã hoàn thiện chức năng đăng nhập bằng Facebook cho ứng dụng Kitchen Hood.

## Cấu Hình Đã Hoàn Thành

### 1. **Routes Facebook OAuth**
- ✅ `GET /auth/facebook/redirect` - Chuyển hướng đến Facebook
- ✅ `GET /auth/facebook/callback` - Xử lý callback từ Facebook
- ✅ `GET /auth/facebook` - Route chính cho đăng nhập Facebook

### 2. **Controller: SocialAuthController**
- ✅ `redirectToFacebook()` - Yêu cầu quyền email và public_profile
- ✅ `handleFacebookCallback()` - Xử lý callback với error handling và logging
- ✅ Tìm user theo Facebook ID trước, sau đó mới tìm theo email
- ✅ Tự động tạo user mới nếu chưa tồn tại
- ✅ Cập nhật thông tin user hiện có

### 3. **Database Schema**
- ✅ Migration `2025_09_18_034921_add_social_ids_to_users_table.php` đã tạo cột `facebook_id`
- ✅ User model đã có `facebook_id` trong fillable

### 4. **Frontend UI**
- ✅ Nút đăng nhập Facebook đã có sẵn trong `login.blade.php`
- ✅ Giao diện đẹp với icon Facebook và hover effects
- ✅ Responsive design

### 5. **Error Handling & Logging**
- ✅ Log tất cả lỗi OAuth để debug
- ✅ Fallback với stateless mode
- ✅ SSL verification tắt cho môi trường local
- ✅ Log thông tin user khi đăng nhập thành công

## Cấu Hình Facebook App

### 1. **Facebook Developer Console**
1. Vào [Facebook Developers](https://developers.facebook.com/)
2. Tạo App mới hoặc chọn app hiện có
3. Thêm sản phẩm "Facebook Login"

### 2. **App Settings**
```env
# .env file
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://127.0.0.1:8000/auth/facebook/callback
```

### 3. **Valid OAuth Redirect URIs**
Trong Facebook App Settings → Facebook Login → Settings, thêm:
- `http://127.0.0.1:8000/auth/facebook/callback` (local)
- `https://yourdomain.com/auth/facebook/callback` (production)

### 4. **App Permissions**
Trong Facebook Login → App Review, yêu cầu:
- ✅ `email` - Để lấy địa chỉ email
- ✅ `public_profile` - Để lấy tên và ảnh đại diện

### 5. **Data Deletion URL**
Đã cấu hình route `/delete` để đáp ứng yêu cầu của Facebook.

## Routes Debug

### 1. **Test Facebook OAuth Config**
```bash
curl http://localhost:8000/debug/facebook
```
**Response:**
```json
{
    "auth_url": "https://www.facebook.com/v18.0/dialog/oauth?...",
    "config": {
        "client_id": "your_app_id",
        "client_secret": "your_app_secret",
        "redirect": "http://127.0.0.1:8000/auth/facebook/callback"
    },
    "test_links": {
        "facebook_redirect": "http://127.0.0.1:8000/auth/facebook/redirect",
        "facebook_callback": "http://127.0.0.1:8000/auth/facebook/callback"
    }
}
```

### 2. **Check User by Email**
```bash
curl http://localhost:8000/debug/user/user@example.com
```

### 3. **Check User by Facebook ID**
```bash
curl http://localhost:8000/debug/facebook-user/123456789
```

## Flow Đăng Nhập Facebook

### 1. **User Click "Đăng nhập với Facebook"**
- Redirect đến `/auth/facebook/redirect`
- Chuyển hướng đến Facebook OAuth với scopes: `email`, `public_profile`

### 2. **Facebook OAuth Flow**
- User đăng nhập và cấp quyền trên Facebook
- Facebook redirect về `/auth/facebook/callback` với authorization code

### 3. **Handle Callback**
- Exchange code lấy access token
- Lấy thông tin user từ Facebook API
- Tìm user theo `facebook_id` hoặc `email`
- Tạo user mới hoặc cập nhật user hiện có
- Đăng nhập user và redirect

## Xử Lý Trường Hợp Đặc Biệt

### 1. **User Không Cung Cấp Email**
- Tạo email giả: `{facebook_id}@facebook.local`
- Không verify email cho user này

### 2. **User Đã Có Tài Khoản Với Email Khác**
- Liên kết Facebook ID với tài khoản hiện có
- Cập nhật thông tin từ Facebook

### 3. **Lỗi OAuth**
- Log chi tiết lỗi
- Thử lại với stateless mode
- Redirect về login với thông báo lỗi

## Testing

### 1. **Test Local**
```bash
# Start server
php artisan serve --host=0.0.0.0 --port=8000

# Test Facebook OAuth
curl http://localhost:8000/debug/facebook
```

### 2. **Test Production**
- Đảm bảo HTTPS
- Cập nhật redirect URI trong Facebook App
- Test với tài khoản Facebook thật

## Logs

Tất cả hoạt động Facebook OAuth được log vào `storage/logs/laravel.log`:

```bash
# Xem logs Facebook OAuth
tail -f storage/logs/laravel.log | grep -i facebook

# Xem logs lỗi
tail -f storage/logs/laravel.log | grep -i "facebook.*error"
```

## Troubleshooting

### 1. **Lỗi "Invalid OAuth Access Token"**
- Kiểm tra `FACEBOOK_CLIENT_SECRET` trong `.env`
- Đảm bảo redirect URI chính xác

### 2. **Lỗi "App Not Setup"**
- Kiểm tra Facebook App ID
- Đảm bảo Facebook Login được bật

### 3. **Lỗi "Invalid Scope"**
- Kiểm tra permissions trong Facebook App
- Đảm bảo app đã được review

### 4. **Lỗi SSL cURL 60 (Local)**
- Đã tự động tắt SSL verification cho môi trường local
- Không ảnh hưởng đến production

## Bảo Mật

### 1. **Environment Variables**
- Không commit `.env` file
- Sử dụng secrets manager trong production

### 2. **CSRF Protection**
- Laravel tự động bảo vệ với CSRF tokens
- Facebook OAuth không bị ảnh hưởng

### 3. **Password Security**
- User đăng nhập Facebook không có password thật
- Tạo password ngẫu nhiên và băm

## Kết Luận

✅ **Chức năng đăng nhập Facebook đã hoàn thiện!**

- Routes và controller đã được cấu hình
- Error handling và logging đầy đủ
- UI/UX đẹp mắt và responsive
- Database schema sẵn sàng
- Debug routes để test

Chỉ cần cấu hình Facebook App credentials trong `.env` file là có thể sử dụng ngay!
