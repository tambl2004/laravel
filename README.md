<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Tr%E1%BA%A1ng%20th%C3%A1i-Production%20Ready-brightgreen" alt="Status">
  <img src="https://img.shields.io/badge/Phi%C3%AAn%20b%E1%BA%A3n-1.0.0-blue" alt="Version">
  <img src="https://img.shields.io/badge/C%E1%BA%ADp%20nh%E1%BA%ADt-09/2025-orange" alt="Last Update">
  <br/>
  <strong>Website bán hàng KitchenHood Pro</strong>
  <br/>
  Đơn giản – Hiện đại – Dễ triển khai
  
</p>

## Nội dung

- [Tổng quan](#tổng-quan)
- [Tính năng nổi bật](#tính-năng-nổi-bật)
- [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
- [Cài đặt nhanh](#cài-đặt-nhanh)
- [Tài khoản mặc định](#tài-khoản-mặc-định)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)
- [Các lệnh hữu ích](#các-lệnh-hữu-ích)
- [Khắc phục sự cố](#khắc-phục-sự-cố)

## Tổng quan

KitchenHood Pro là dự án thương mại điện tử xây dựng với Laravel 10, giao diện hiện đại (Bootstrap 5), tối ưu cho triển khai nhanh trong môi trường phát triển nội bộ.

## Tính năng nổi bật

- ⭐ **Rating động**: Tính điểm tự động từ đánh giá thực tế, kèm progress bar và % khuyến nghị (4–5 sao)
- 🛒 **Giỏ hàng thông minh**: Thêm/sửa/xóa, tính tổng tiền tự động, lưu theo session
- 📦 **Đơn hàng**: Theo dõi trạng thái, địa chỉ giao hàng, lịch sử chi tiết
- 🎨 **Giao diện**: Responsive với Bootstrap 5, icon Font Awesome, hiệu ứng mượt

## Yêu cầu hệ thống

- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **Node.js**: >= 16.0 và **NPM**: >= 8.0
- **MySQL**: >= 8.0 hoặc MariaDB >= 10.5
- **Web Server**: Apache hoặc Nginx

## Cài đặt nhanh

> Lưu ý: Các lệnh dưới đây dành cho môi trường phát triển local. Trên Windows, nên chạy Terminal ở chế độ Administrator khi cần.

### 1) Clone dự án

```bash
git clone https://github.com/tambl2004/laravel.git
cd laravel
```

### 2) Cài đặt dependencies

```bash
# PHP dependencies
composer update

# Node dependencies
npm install

# Cài Tailwind plugin cho Vite (nếu sử dụng)
npm install -D @tailwindcss/vite
```

Nếu gặp lỗi khi chạy composer trên Windows (thiếu extension), thử:

```bash
# Mở file php.ini và bật các extension cần thiết
# Ví dụ với XAMPP (tuỳ máy): C:\xampp\php\php.ini
# Bỏ dấu ; trước các dòng:
#   extension=gd
#   extension=zip
#   extension=mbstring

composer clear-cache
composer update
```

### 3) Cấu hình môi trường

```bash
cp .env.example .env
php artisan config:clear
php artisan key:generate
# Tuỳ chọn: cache lại cấu hình sau khi đã chỉnh .env
php artisan config:cache
```

### 4) Cấu hình database (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce2024
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=file
```

### 5) Khởi tạo cơ sở dữ liệu

- Tạo database mới tên `ecommerce2024`
- Import file SQL mẫu (nếu dự án cung cấp) vào database này

Hoặc sử dụng migration/seed (nếu hỗ trợ):

```bash
php artisan migrate:fresh --seed
```

### 6) Liên kết storage

```bash
php artisan storage:link
```

### 7) Build assets

```bash
# Production build
npm run build

# Hoặc chạy chế độ phát triển
npm run dev
```

### 8) Chạy server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## Tài khoản mặc định

- **Admin**: Email `admin@admin.com` — Mật khẩu `12345678`
- **User**: Mật khẩu mặc định `12345678`

## Cấu trúc thư mục

```text
kitchenhood-pro/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controller cho quản trị
│   │   └── User/            # Controller cho người dùng
│   ├── Models/              # Eloquent models
│   └── Helpers/             # Helper classes
├── database/
│   ├── migrations/          # Migration
│   └── seeders/             # Seeders
├── resources/
│   ├── views/
│   │   ├── admin/           # View Admin Panel
│   │   ├── customer/        # View Customer
│   │   └── components/      # Blade components
│   ├── css/                 # CSS
│   └── js/                  # JavaScript
├── routes/
│   └── web.php              # Web routes
└── public/                  # Tài nguyên public
```

## Các lệnh hữu ích

```bash
# Xoá cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Tạo dữ liệu mẫu (nếu có seeder)
php artisan db:seed

# Kiểm thử hệ thống rating (nếu có command)
php artisan test:rating

# Tạo lại storage link
php artisan storage:link

# Reset DB và seed lại
php artisan migrate:fresh --seed
```

## Khắc phục sự cố

- **"Class not found"**: chạy `composer dump-autoload`
- **Permission storage** (Linux/Mac):
  ```bash
  chmod -R 775 storage/
  chmod -R 775 bootstrap/cache/
  ```
- **Không kết nối được DB**: kiểm tra `.env`, đảm bảo MySQL/MariaDB đang chạy
- **Lỗi npm install**:
  ```bash
  npm cache clean --force
  npm install
  ```



chạy để test với facebook: ngrok http 8000
chạy cập nhật đơn hàng tự động: php artisan schedule:work