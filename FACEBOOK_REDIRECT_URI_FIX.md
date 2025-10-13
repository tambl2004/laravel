# 🚨 Sửa Lỗi "URL Bị Chặn" - Facebook OAuth

## 📋 Lỗi Hiện Tại
**"URL bị chặn"** - Redirect URI không được đưa vào danh sách hợp lệ trong Cài đặt OAuth của ứng dụng.

## 🔧 Cách Sửa - Cập Nhật Facebook App Settings

### 1. 🌐 **Truy cập Facebook Developer Console**
```
https://developers.facebook.com/apps/791954796709807/
```

### 2. 🔧 **Cập Nhật Valid OAuth Redirect URIs**

**Bước 1:** Vào **Facebook Login** → **Settings**

**Bước 2:** Trong phần **Valid OAuth Redirect URIs**, thêm chính xác URL này:
```
https://unsimmering-presley-speakably.ngrok-free.dev/auth/facebook/callback
```

**Bước 3:** Click **Save Changes**

### 3. 🔒 **Cập Nhật App Domains**

**Bước 1:** Vào **Settings** → **Basic**

**Bước 2:** Trong phần **App Domains**, thêm:
```
unsimmering-presley-speakably.ngrok-free.dev
```

**Bước 3:** Click **Save Changes**

### 4. 📱 **Thêm Website Platform**

**Bước 1:** Trong **Settings** → **Basic**

**Bước 2:** Scroll xuống phần **Platform**

**Bước 3:** Click **Add Platform** → Chọn **Website**

**Bước 4:** Thêm **Site URL:**
```
https://unsimmering-presley-speakably.ngrok-free.dev
```

**Bước 5:** Click **Save Changes**

### 5. 🔐 **Cập Nhật Privacy Policy & Data Deletion URLs**

**Bước 1:** Trong **Settings** → **Basic**

**Bước 2:** Thêm các URL bắt buộc:

**Privacy Policy URL:**
```
https://unsimmering-presley-speakably.ngrok-free.dev/privacy
```

**Data Deletion Request URL:**
```
https://unsimmering-presley-speakably.ngrok-free.dev/delete
```

**Bước 3:** Click **Save Changes**

## ✅ Checklist Cần Làm

- [ ] **Valid OAuth Redirect URIs:** `https://unsimmering-presley-speakably.ngrok-free.dev/auth/facebook/callback`
- [ ] **App Domains:** `unsimmering-presley-speakably.ngrok-free.dev`
- [ ] **Website Platform:** `https://unsimmering-presley-speakably.ngrok-free.dev`
- [ ] **Privacy Policy URL:** `https://unsimmering-presley-speakably.ngrok-free.dev/privacy`
- [ ] **Data Deletion URL:** `https://unsimmering-presley-speakably.ngrok-free.dev/delete`

## 🧪 Test Sau Khi Cập Nhật

### 1. **Kiểm tra config:**
```
https://unsimmering-presley-speakably.ngrok-free.dev/debug/facebook
```

### 2. **Test đăng nhập Facebook:**
```
https://unsimmering-presley-speakably.ngrok-free.dev/login
```

### 3. **Kiểm tra Privacy Policy:**
```
https://unsimmering-presley-speakably.ngrok-free.dev/privacy
```

### 4. **Kiểm tra Data Deletion:**
```
https://unsimmering-presley-speakably.ngrok-free.dev/delete
```

## ⚠️ Lưu Ý Quan Trọng

1. **URL phải chính xác 100%** - không được có dấu cách hay ký tự thừa
2. **Phải sử dụng HTTPS** - không được HTTP
3. **Ngrok URL có thể thay đổi** - nếu ngrok restart, cần cập nhật lại
4. **Save Changes** sau mỗi thay đổi

## 🔄 Nếu Vẫn Lỗi

### 1. **Kiểm tra App Mode:**
- Đảm bảo app đang ở chế độ **Development**
- Chưa cần submit review nếu chỉ test

### 2. **Kiểm tra Facebook Login:**
- Đảm bảo **Facebook Login** được **bật**
- **Web OAuth Login** phải được **enabled**

### 3. **Kiểm tra ngrok:**
```bash
# Kiểm tra ngrok có còn hoạt động không
curl https://unsimmering-presley-speakably.ngrok-free.dev
```

### 4. **Clear cache:**
- Clear browser cache
- Thử incognito/private mode

## 📊 Debug Commands

```bash
# Kiểm tra config Laravel
php artisan tinker --execute="print_r(config('services.facebook'));"

# Kiểm tra routes
php artisan route:list | grep facebook
```

## 🎯 Kết Quả Mong Đợi

Sau khi cập nhật đúng tất cả settings, đăng nhập Facebook sẽ hoạt động bình thường mà không còn lỗi "URL bị chặn".

**Quan trọng nhất:** Đảm bảo **Valid OAuth Redirect URIs** có chính xác URL callback!
