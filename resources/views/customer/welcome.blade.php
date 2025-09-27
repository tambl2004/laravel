@extends('layouts.customer')

@section('content')
<!-- Hero Section with Carousel -->
<section class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <!-- Carousel Items -->
        <div class="carousel-inner">
            <!-- Slide 1: Máy Hút Mùi Cao Cấp -->
            <div class="carousel-item active">
                <div class="hero-slide" style="background: url({{ asset('images/banners/banner1.png') }}); background-repeat: no-repeat; background-position: center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row align-items-center h-100 py-5">
                            <div class="col-lg-6">
                                <div class="badge bg-primary px-3 py-2 rounded-pill mb-3">
                                    <i class="fas fa-star me-1"></i>SẢN PHẨM MỚI
                                </div>
                                <h1 class="display-3 fw-bold mb-4 text-dark" style="line-height: 1.1;">
                                    Máy Hút Mùi<br>
                                    <span class="text-primary">Chất Lượng Cao</span>
                                </h1>
                                <p class="lead mb-4 text-muted">
                                    Khám phá bộ sưu tập máy hút mùi cao cấp với thiết kế hiện đại, công nghệ tiên tiến và hiệu suất vượt trội cho căn bếp của bạn.
                                </p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold">
                                        <i class="fas fa-shopping-cart me-2"></i>Xem Sản Phẩm
                                    </a>
                                    <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3">
                                        <i class="fas fa-info-circle me-2"></i>Tìm Hiểu Thêm
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Khuyến Mãi Đặc Biệt -->
            <div class="carousel-item">
                <div class="hero-slide" style="background: url({{ asset('images/banners/banner2.png') }}); background-repeat: no-repeat; background-position: center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row align-items-center h-100 py-5">
                            <div class="col-lg-6">
                                <div class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3">
                                    <i class="fas fa-fire me-1"></i>KHUYẾN MÃI ĐẶC BIỆT
                                </div>
                                <h1 class="display-3 fw-bold mb-4 text-dark" style="line-height: 1.1;">
                                    Giảm Giá<br>
                                    <span class="text-primary">Lên Đến 30%</span>
                                </h1>
                                <p class="lead mb-4 text-muted">
                                    Cơ hội duy nhất trong năm! Sở hữu máy hút mùi cao cấp với giá tốt nhất. Chương trình áp dụng cho tất cả sản phẩm trong bộ sưu tập.
                                </p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold">
                                        <i class="fas fa-tags me-2"></i>Mua Ngay
                                    </a>
                                    <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3">
                                        <i class="fas fa-clock me-2"></i>Xem Chi Tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Dịch Vụ Hậu Mãi -->
            <div class="carousel-item">
                <div class="hero-slide" style="background: url({{ asset('images/banners/banner3.png') }}); background-repeat: no-repeat; background-position: center center; background-size: cover;">
                    <div class="container h-100">
                        <div class="row align-items-center h-100 py-5">
                            <div class="col-lg-6">
                                <div class="badge bg-success px-3 py-2 rounded-pill mb-3">
                                    <i class="fas fa-shield-alt me-1"></i>DỊCH VỤ TỐT NHẤT
                                </div>
                                <h1 class="display-3 fw-bold mb-4 text-dark" style="line-height: 1.1;">
                                    Bảo Hành<br>
                                    <span class="text-primary">5 Năm</span>
                                </h1>
                                <p class="lead mb-4 text-muted">
                                    Đội ngũ kỹ thuật chuyên nghiệp 24/7, bảo hành chính hãng 5 năm, giao hàng miễn phí toàn quốc. Cam kết chất lượng và dịch vụ tốt nhất.
                                </p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold">
                                        <i class="fas fa-headset me-2"></i>Liên Hệ Ngay
                                    </a>
                                    <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3">
                                        <i class="fas fa-info-circle me-2"></i>Tìm Hiểu Thêm
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100 border-0">
                    <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-shipping-fast fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-primary">Giao Hàng Miễn Phí</h5>
                    <p class="text-muted mb-0">Miễn phí vận chuyển toàn quốc cho đơn hàng trên 5 triệu</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100 border-0">
                    <div class="feature-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-success">Bảo Hành Chính Hãng</h5>
                    <p class="text-muted mb-0">Bảo hành 5 năm với đội ngũ kỹ thuật chuyên nghiệp 24/7</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center p-4 bg-white rounded-3 shadow-sm h-100 border-0">
                    <div class="feature-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-medal fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-warning">Chất Lượng Đảm Bảo</h5>
                    <p class="text-muted mb-0">Sản phẩm chính hãng với các tiêu chuẩn chất lượng quốc tế</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Best Selling Products -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 fw-bold">
                <i class="fas fa-fire me-1"></i>BÁN CHẠY NHẤT
            </div>
            <h2 class="section-title display-5 fw-bold text-dark">Máy Hút Mùi Bán Chạy Nhất</h2>
            <p class="lead text-muted">Khám phá những mẫu máy hút mùi hot nhất được khách hàng lựa chọn nhiều nhất</p>
        </div>

        <div class="row g-4">
            @if(isset($latestProducts) && $latestProducts->count() > 0)
                @foreach($latestProducts->take(3) as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="card-img-top product-image">
                            @else
                                <img src="https://via.placeholder.com/300x280/cccccc/666666?text=Không+có+ảnh" 
                                     alt="{{ $product->name }}" 
                                     class="card-img-top product-image">
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-danger">BÁN CHẠY</span>
                            </div>
                            <!-- Favorite Button -->
                            @auth
                                <div class="position-absolute top-0 start-0 m-2">
                                    <button class="btn btn-favorite {{ auth()->user()->isFavorite($product->id) ? 'favorited' : '' }}" 
                                            data-product-id="{{ $product->id }}"
                                            data-store-url="{{ route('favorites.store', $product->id) }}"
                                            data-destroy-url="{{ route('favorites.destroy', $product->id) }}"
                                            title="{{ auth()->user()->isFavorite($product->id) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
                            @endauth
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'Không phân loại' }}</span>
                            </div>
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            <p class="card-text text-muted mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="rating-stars mb-3">
                                @if($product->has_reviews)
                                    <div class="d-flex align-items-center">
                                        <x-rating-stars :rating="$product->average_rating" :showCount="true" />
                                        <small class="text-muted ms-2">({{ $product->reviews_count }} đánh giá)</small>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <x-rating-stars :rating="0" :showHalfStar="false" />
                                        <span class="text-muted ms-2">(Chưa có đánh giá)</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="price-tag px-3 py-2 rounded-pill">{{ number_format($product->price) }} VNĐ</span>
                                    <span class="stock-badge">Còn: {{ $product->quantity ?? 0 }}</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>Xem chi tiết
                                    </a>
                                    @if($product->quantity > 0 && $product->is_active)
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <button type="submit" class="btn btn-add-cart text-white w-100">
                                                <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-ban me-2"></i>Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p class="text-muted">Chưa có sản phẩm nào.</p>
                </div>
            @endif
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg px-5">
                Xem Tất Cả Sản Phẩm
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Tin Tức Nổi Bật -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <div class="badge bg-info text-white px-3 py-2 rounded-pill mb-3 fw-bold">
                <i class="fas fa-newspaper me-1"></i>TIN TỨC NỔI BẬT
            </div>
            <h2 class="section-title display-5 fw-bold text-dark">Cập Nhật Mới Nhất</h2>
            <p class="lead text-muted">Những thông tin hữu ích về máy hút mùi và xu hướng nhà bếp hiện đại</p>
        </div>

        <div class="row g-4">
            @forelse($featuredNews as $article)
            <div class="col-lg-4 col-md-6">
                <div class="card news-card h-100">
                    <div class="position-relative">
                        <img src="{{ $article->image_url }}" 
                             class="card-img-top" 
                             alt="{{ $article->title }}"
                             style="height: 250px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-danger">NỔI BẬT</span>
                        </div>
                        <div class="position-absolute bottom-0 end-0 m-3">
                            <small class="text-white bg-dark px-2 py-1 rounded">
                                <i class="fas fa-calendar me-1"></i>{{ $article->formatted_date }}
                            </small>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-3">{{ $article->title }}</h5>
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($article->excerpt, 100) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('news.show', $article->slug) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-right me-2"></i>Đọc thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">Chưa có tin tức nổi bật nào.</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('news.index') }}" class="btn btn-outline-info btn-lg px-5">
                Xem Tất Cả Tin Tức
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>




<!-- Testimonials (Câu chuyện thành công từ khách hàng) -->
<section id="testimonials" class="py-5 position-relative">
    <!-- Nền ellipse trang trí: dùng file public/elipse.svg, chỉ mang tính decor -->
    <div class="position-absolute top-50 start-50 translate-middle w-100 overflow-hidden" aria-hidden="true" style="z-index:0; pointer-events:none;">
        <img src="{{ asset('elipse.svg') }}" alt="" class="d-block mx-auto" style="max-width: 900px; opacity:.65; filter: saturate(1.1);">
    </div>

    <div class="container position-relative" style="z-index:1;">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-dark mb-2">Câu chuyện thành công từ khách hàng</h2>
            <p class="text-muted mb-0">Trải nghiệm sau khi lắp đặt máy hút mùi tại gia đình và nhà hàng.</p>
        </div>

        @php
            // Dữ liệu mẫu tiếng Việt liên quan máy hút mùi (5 thẻ)
            $testimonials = [
                ['name' => 'Bùi Xuân Lộc','role' => 'Chủ quán ăn gia đình','avatar' => 'https://www.shutterstock.com/image-vector/man-character-face-avatar-glasses-260nw-562077406.jpg?q=80&w=200&auto=format&fit=crop','content' => 'Sau khi lắp máy hút mùi Inverter, mùi dầu mỡ gần như biến mất, nhà bếp thông thoáng hơn hẳn.'],
                ['name' => 'Đào Văn Tâm','role' => 'Chủ căn hộ Vinhomes','avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHUndSzxcF1UbSXX3bVILVaUbSIhoc_GEA8g&s?q=80&w=200&auto=format&fit=crop','content' => 'Công suất mạnh, lọc than hoạt tính hiệu quả. Đội ngũ lắp đặt nhanh và hướng dẫn tận tình.'],
                ['name' => 'Nguyễn Tiến Toán','role' => 'Bếp trưởng nhà hàng nhỏ','avatar' => 'https://png.pngtree.com/png-vector/20191101/ourmid/pngtree-cartoon-color-simple-male-avatar-png-image_1934459.jpg?q=80&w=200&auto=format&fit=crop','content' => 'Máy chạy êm ngay cả ở mức cao. Lưới lọc tháo lắp nhanh, việc vệ sinh mỗi tuần rất nhẹ nhàng.'],
                ['name' => 'Vũ Minh Quân','role' => 'Nội trợ','avatar' => 'https://png.pngtree.com/png-vector/20220817/ourmid/pngtree-man-avatar-with-circle-frame-vector-ilustration-png-image_6110328.png?q=80&w=200&auto=format&fit=crop','content' => 'Thiết kế kính cong đẹp, đèn LED sáng. Không còn mùi ám phòng khách sau khi chiên rán.'],
                ['name' => 'Đỗ Hải Nam','role' => 'Chủ homestay','avatar' => 'https://cdn1.iconfinder.com/data/icons/user-pictures/100/male3-512.png?q=80&w=200&auto=format&fit=crop','content' => 'Tiết kiệm điện, có hẹn giờ tắt rất tiện. Khách lưu trú khen khu bếp luôn sạch mùi.'],
            ];
        @endphp

        <div id="testimonialSlider" class="testimonial-slider">
            <div class="testimonial-track">
                @foreach($testimonials as $i => $t)
                <div class="testimonial-item" data-index="{{ $i }}">
                    <div class="bg-white shadow-sm testimonial-card h-100 p-4 position-relative border-0">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $t['avatar'] }}" class="rounded-circle me-3" alt="{{ $t['name'] }}" style="width:64px; height:64px; object-fit:cover;">
                            <div>
                                <div class="fw-bold text-dark">{{ $t['name'] }}</div>
                                <small class="text-muted">{{ $t['role'] }}</small>
                            </div>
                        </div>
                        <p class="text-muted mb-4">{{ $t['content'] }}</p>
                        <hr class="my-3"/>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-muted">Đánh giá</span>
                            <div class="text-warning"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Indicators -->
            <div class="testimonial-indicators">
                @foreach($testimonials as $i => $t)
                    <span class="indicator-dot @if($i===0) active @endif" data-slide="{{ $i }}"></span>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        /* Testimonial Slider với hiệu ứng đẩy */
        .testimonial-slider {
            position: relative;
            overflow: hidden;
            padding: 0 15px;
        }
        
        .testimonial-track {
            display: flex;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-66.666%);
        }
        
        .testimonial-item {
            flex: 0 0 33.333%;
            padding: 0 15px;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .testimonial-indicators {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
        }
        
        .indicator-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .indicator-dot.active {
            background: #0ea5e9;
            width: 28px;
        }
        
        .indicator-dot:hover {
            background: #0ea5e9;
        }
        
        /* Nâng cấp card nhìn giống mockup, tránh dùng màu tím */
        #testimonials .testimonial-card{ border-radius: 18px; box-shadow: 0 12px 30px rgba(2,6,23,.08); transition: transform .25s ease, box-shadow .25s ease; }
        #testimonials .testimonial-card:hover{ transform: translateY(-4px); box-shadow: 0 20px 40px rgba(2,6,23,.12); }
        @media (max-width: 991.98px){
            #testimonials .display-4{ font-size: 2rem; }
            .testimonial-item { flex: 0 0 50%; }
            .testimonial-track { transform: translateX(-25%); }
        }
        @media (max-width: 767.98px){
            .testimonial-item { flex: 0 0 100%; }
            .testimonial-track { transform: translateX(0%); }
        }
    </style>
    <script>
    // Testimonial Slider với hiệu ứng đẩy và vòng lặp vô hạn
    document.addEventListener('DOMContentLoaded', function(){
        const slider = document.getElementById('testimonialSlider');
        const track = slider.querySelector('.testimonial-track');
        const items = slider.querySelectorAll('.testimonial-item');
        const indicators = slider.querySelectorAll('.indicator-dot');
        
        let currentIndex = 0;
        const totalItems = items.length;
        
        // Tạo vòng lặp vô hạn bằng cách nhân bản items
        function createInfiniteLoop() {
            // Thêm items ở đầu và cuối để tạo vòng lặp
            const firstItem = items[0].cloneNode(true);
            const secondItem = items[1].cloneNode(true);
            const lastItem = items[totalItems - 1].cloneNode(true);
            const secondLastItem = items[totalItems - 2].cloneNode(true);
            
            // Thêm 2 items cuối vào đầu
            track.insertBefore(secondLastItem, track.firstChild);
            track.insertBefore(lastItem, track.firstChild);
            
            // Thêm 2 items đầu vào cuối
            track.appendChild(firstItem);
            track.appendChild(secondItem);
            
            // Cập nhật lại danh sách items
            const allItems = track.querySelectorAll('.testimonial-item');
            return allItems;
        }
        
        const allItems = createInfiniteLoop();
        
        function updateSlider() {
            // Tính toán vị trí với offset để bù cho 2 items đầu được thêm vào
            const translateX = -33.333 * (currentIndex + 2);
            track.style.transform = `translateX(${translateX}%)`;
            
            // Cập nhật indicators
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });
        }
        
        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalItems;
            updateSlider();
            
            // Reset về đầu khi đến cuối để tạo vòng lặp mượt
            if (currentIndex === 0) {
                setTimeout(() => {
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(-66.666%)';
                    setTimeout(() => {
                        track.style.transition = 'transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    }, 50);
                }, 600);
            }
        }
        
        // Tự động chuyển slide mỗi 7 giây
        setInterval(nextSlide, 7000);
        
        // Click vào indicator để chuyển slide
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', function() {
                currentIndex = index;
                updateSlider();
            });
        });
        
        // Khởi tạo slider
        updateSlider();
    });
    </script>
</section>

<!-- Logo thương hiệu -->
<section class="py-5 bg-white">
    <div class="container">
  
        <!-- Logo thương hiệu: chạy từ phải sang trái lặp vô hạn -->
        @php
            // Logo demo: dùng hình chữ với nền xám để hiển thị ngay, có thể thay bằng file thật trong public/images/brands
            $brandLogos = [
                ['name' => 'Bosch', 'logo' => 'https://assets.bosch.com/media/global/bosch_group/our_figures/brands/bosch-brand-bosch_res_1280x720.webp'],
                ['name' => 'Teka', 'logo' => 'https://teka-vietnam.com/images/news/3314.jpg'],
                ['name' => 'Siemens', 'logo' => 'https://vimf.vn/wp-content/uploads/2024/04/siemn.png'],
                ['name' => 'Electrolux', 'logo' => 'https://kinghome.vn/data/handbook/full_images/thuong-hieu-electrolux-1.jpg'],
                ['name' => 'Miele', 'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKKEQjYZlOrUda8ufN6B3NuEM_Jm_S2n5_mVl8MNzsp97_SzwaA8CIP1BmfuLCEKF13Ek&usqp=CAU'],
                ['name' => 'Hafele', 'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkGNoflnIJqKAPAHaO2c3_sd2iRlywscuvPA&s'],
                ['name' => 'Malloca', 'logo' => 'https://bizweb.dktcdn.net/thumb/grande/100/442/590/collections/thiet-bi-nha-bep-malloca-chinh-hang.jpg?v=1712720683203'],
                ['name' => 'Sunhouse', 'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRpgEv6auPDEVsqCqKbndit7bHrWWStZpiKWA&s'],
                ['name' => 'Canzy', 'logo' => 'https://bizweb.dktcdn.net/100/357/739/themes/723787/assets/logo.png?1756357471314'],
                ['name' => 'Chefs', 'logo' => 'https://noithatphuongdong.vn/images/logo-chefs.jpg'],
            ];
            // Nhân đôi mảng để tạo hiệu ứng chạy vô hạn mượt mà
            $brandTrack = array_merge($brandLogos, $brandLogos);
        @endphp

        <div class="brand-marquee">
            <div class="brand-track">
                @foreach($brandTrack as $b)
                <div class="brand-item">
                    <img src="{{ $b['logo'] }}" alt="{{ $b['name'] }}" class="brand-logo">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        /* Style cho testimonial: tập trung vào sự sạch sẽ, không dùng màu tím */
        .testimonial-card { border-radius: 18px; transition: transform .35s ease, opacity .35s ease, box-shadow .35s ease; }
        .tst-main{ transform: scale(1); opacity: 1; }
        .tst-side{ transform: scale(.92); opacity: .6; }
        .tst-side:hover{ opacity: .8; }
        .indicator-dot{
            width: 8px; height: 8px; border-radius: 999px; border: 0; background:#cbd5e1; transition: all .2s ease;
        }
        .indicator-dot.active, .indicator-dot:hover{ background:#0ea5e9; width: 32px; }
        /* Marquee logo thương hiệu */
        .brand-marquee{ position: relative; overflow: hidden; background: #ffffff;}
        .brand-track{ display: flex; align-items: center; width: max-content; gap: 40px; animation: brand-scroll 25s linear infinite; }
        .brand-item{ display: inline-flex; align-items: center; justify-content: center; padding: 8px 0; }
        .brand-logo{ height: 60px; width: auto;; opacity: .8; transition: filter .2s, opacity .2s, transform .2s; }
        .brand-item:hover .brand-logo{ filter: grayscale(0); opacity: 1; transform: scale(1.03); }
        @keyframes brand-scroll{
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
    </style>
</section>


<!-- Stats Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <!-- Phần thống kê bên trái -->
            <div class="col-lg-6">
                <div class="stats-section">
                    <!-- Tiêu đề chính -->
                    <h2 class="stats-title mb-3">
                        Máy Hút Mùi và hành trình 15 năm khai phá những con số ấn tượng
                    </h2>
                    
                    <!-- Đường trang trí -->
                    <div class="stats-decoration mb-4">
                        <svg width="60" height="8" viewBox="0 0 60 8" fill="none">
                            <path d="M2 4C2 4 8 1 15 4C22 7 28 1 35 4C42 7 48 1 55 4C58 5.5 58 2.5 58 4" stroke="#333" stroke-width="2" fill="none"/>
                        </svg>
                    </div>
                    
                    <!-- Các ô thống kê -->
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stats-box">
                                <div class="stats-number">
                                    <span class="number">15,000+</span>
                                    <div class="stats-line" style="background: #007bff;"></div>
                                </div>
                                <p class="stats-description">khách hàng hài lòng</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-box">
                                <div class="stats-number">
                                    <span class="number">500+</span>
                                    <div class="stats-line" style="background: #28a745;"></div>
                                </div>
                                <p class="stats-description">sản phẩm chất lượng cao</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-box">
                                <div class="stats-number">
                                    <span class="number">5</span>
                                    <div class="stats-line" style="background: #007bff;"></div>
                                </div>
                                <p class="stats-description">năm bảo hành chính hãng</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-box">
                                <div class="stats-number">
                                    <span class="number">24/7</span>
                                    <div class="stats-line" style="background: #28a745;"></div>
                                </div>
                                <p class="stats-description">hỗ trợ kỹ thuật</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bản đồ Việt Nam bên phải -->
            <div class="col-lg-6">
                <div class="vietnam-map-container">
                    <!-- Placeholder cho ảnh bản đồ Việt Nam -->
                    <div class="map-image-placeholder">
                        <img src="{{ asset('images/map.jpg') }}" 
                             alt="Bản đồ Việt Nam - Phân phối máy hút mùi" 
                             class="vietnam-map-image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Phần hình minh họa bên trái -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center">
                <!-- Thay thế minh họa bằng hình ảnh -->
                <img 
                    src="{{ asset('images/Choose_Us.png') }}"
                    style="max-width: 525px;"
                >
            </div>
            
            <!-- Phần nội dung bên phải -->
            <div class="col-lg-6">
                <div class="why-choose-content">
                    <!-- Icon và tiêu đề -->
                    <div class="content-header">
                       
                        <h2 class="content-title">Tại sao nên chọn chúng tôi?</h2>
                    </div>
                    
                    <!-- Mô tả chính -->
                    <p class="content-description">
                        Chúng tôi kết hợp sự sáng tạo, chiến lược và bảo mật để mang đến những giải pháp số toàn diện giúp thương hiệu phát triển nhanh chóng và thông minh hơn.
                    </p>
                    
                    <!-- Các điểm nổi bật -->
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title">Chất lượng:</h4>
                                <p class="feature-description">Chúng tôi mang đến những giải pháp có tác động cao với sự chú ý đến từng chi tiết và độ chính xác trong thiết kế.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title">Giao tiếp:</h4>
                                <p class="feature-description">Chúng tôi luôn cập nhật thông tin rõ ràng và hợp tác phản hồi nhanh chóng để bạn luôn được thông báo.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="feature-content">
                                <h4 class="feature-title">Độ tin cậy:</h4>
                                <p class="feature-description">Hãy tin tưởng vào chúng tôi để đáp ứng thời hạn và vượt qua mong đợi mỗi lần.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->

<!-- FAQ Section (đưa về layout giống mockup) -->
@if(isset($faqs) && count($faqs) > 0)
<section class="py-5 bg-light">
    <div class="container">
        <!-- Header nhỏ gọn theo mockup -->
        <div class="text-center mb-4">
            <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill fw-semibold" style="background:#e8f5e9;color:#2e7d32;">
                <i class="fas fa-slash"></i>
                FAQ
            </span>
            <h2 class="mt-3 mb-2 fw-bold text-dark">Câu hỏi <span class="text-primary">thường gặp</span></h2>
            <p class="text-muted mb-0">Duyệt nhanh các câu hỏi thường gặp để tìm câu trả lời bạn cần.</p>
        </div>

        <div class="row align-items-center g-4">
            <!-- Bên trái: hình minh hoạ -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="text-center pe-lg-4">
                    <img src="{{ asset('images/faq.png') }}" alt="FAQ Illustration" class="img-fluid" style="max-height:420px; object-fit:contain;">
                </div>
            </div>

            <!-- Bên phải: Accordion -->
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-sm p-2 p-sm-3 p-md-4">
                    <div class="accordion accordion-flush" id="faqAccordion">
                        @foreach($faqs as $index => $faq)
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button rounded-3 px-3 py-3 @if($index > 0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse @if($index == 0) show @endif" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Style nhấn mạnh giống mockup nhưng tránh màu tím */
        #faqAccordion .accordion-button {
            background-color: #ffffff;
            font-weight: 600;
            color: #0f172a;
        }
        #faqAccordion .accordion-button:not(.collapsed) {
            color: #1565c0; /* xanh dương đậm, không dùng tím */
            background-color: #e3f2fd; /* nền xanh nhạt */
            box-shadow: inset 0 -1px 0 rgba(0,0,0,.05);
        }
        #faqAccordion .accordion-item + .accordion-item {
            border-top: 1px solid #f1f5f9;
        }
    </style>
</section>
@endif
<!-- Thêm CSS cho giao diện sản phẩm -->
<style>
    .product-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .product-image {
        height: 280px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .price-tag {
        background: linear-gradient(45deg, #ffc107, #ffca28);
        color: #000;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .btn-add-cart {
        background: linear-gradient(45deg, #2c5aa0, #1e40af);
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(44, 90, 160, 0.3);
    }
    
    .rating-stars {
        color: #ffc107;
    }
    
    /* Style cho số lượng sản phẩm - làm nổi bật với màu nền xanh */
    .stock-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        transition: all 0.3s ease;
    }
    
    .stock-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    }
    
    /* Style cho tin tức */
    .news-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .news-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .news-card .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .news-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    /* Đảm bảo icon hiển thị đúng */
    .fas, .far, .fab {
        display: inline-block !important;
        font-style: normal !important;
        font-variant: normal !important;
        text-rendering: auto !important;
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
    }
    
    /* Stats Section Styles */
    .stats-section {
        padding: 2rem 0;
    }
    
    .stats-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #000;
        line-height: 1.2;
        margin-bottom: 1rem;
    }
    
    .stats-decoration {
        margin-bottom: 2rem;
    }
    
    .stats-box {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .stats-number {
        position: relative;
        margin-bottom: 0.5rem;
    }
    
    .stats-number .number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #000;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .stats-line {
        position: absolute;
        left: -1.5rem;
        top: 0;
        width: 4px;
        height: 100%;
        border-radius: 2px;
    }
    
    .stats-description {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
        font-weight: 500;
    }
    
    /* Vietnam Map Styles */
    .vietnam-map-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }
    
    .map-image-placeholder {
        width: 100%;
        max-width: 400px;
        height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 12px;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }
    
    .map-image-placeholder:hover {
        border-color: #007bff;
        background: #f0f8ff;
    }
    
    .vietnam-map-image {
        height: 162%;
    }
    
    .map-fallback {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
    }
    
    .map-placeholder-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .stats-title {
            font-size: 1.8rem;
        }
        
        .stats-number .number {
            font-size: 2rem;
        }
        
        .stats-box {
            padding: 1rem;
        }
        
        .vietnam-map-container {
            padding: 1rem;
        }
        
        .map-image-placeholder {
            height: 250px;
        }
        
        .map-fallback {
            padding: 1rem;
        }
    }


    /* css cho hỏi đáp */
    .qa-item {
        transition: all 0.3s ease;
    }
    
    .qa-item:hover {
        background-color: #f8f9fa;
    }
    
    .qa-avatar {
        font-size: 0.9rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* CSS cho phần câu trả lời trong welcome page */
    .answer-section {
        margin-top: 1rem;
        padding-left: 1.5rem;
        border-left: 3px solid #e9ecef;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
        padding: 1rem;
    }
    
    .answer-item {
        margin-bottom: 0.5rem;
    }
    
    .answer-item:last-child {
        margin-bottom: 0;
    }
    
    .answer-content {
        background: white;
        padding: 0.75rem;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .answer-meta {
        margin-top: 0.25rem;
        padding-top: 0.25rem;
        border-top: 1px solid #e9ecef;
    }
    
    .answer-divider {
        margin: 0.5rem 0;
        border-color: #dee2e6;
        opacity: 0.5;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    
    .form-control.is-valid, .form-select.is-valid {
        border-color: #198754;
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
    }
    
    .btn-outline-primary:hover {
        background-color: #3498db;
        border-color: #3498db;
        transform: translateY(-2px);
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    
    .card {
        border-radius: 15px;
        overflow: hidden;
        animation: slideInUp 0.6s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Why Choose Us Section Styles */
    .why-choose-illustration {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 0;
    }
    
    .tablet-container {
        position: relative;
        z-index: 2;
    }
    
    .tablet-device {
        position: relative;
        width: 280px;
        height: 200px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        transform: rotate(-5deg);
        transition: transform 0.3s ease;
    }
    
    .tablet-device:hover {
        transform: rotate(-3deg) scale(1.02);
    }
    
    .tablet-screen {
        width: 100%;
        height: 100%;
        background: #f8f9fa;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }
    
    .app-sidebar {
        position: absolute;
        left: 0;
        top: 0;
        width: 80px;
        height: 100%;
        background: #2c3e50;
        padding: 15px 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .sidebar-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 8px 4px;
        border-radius: 8px;
        color: #bdc3c7;
        font-size: 10px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .sidebar-item.active {
        background: #3498db;
        color: white;
    }
    
    .sidebar-item i {
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .sidebar-item span {
        font-size: 8px;
        line-height: 1.2;
    }
    
    .app-content {
        margin-left: 80px;
        padding: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .content-header h3 {
        font-size: 12px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        line-height: 1.3;
    }
    
    .chat-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .chat-bubble {
        background: #e3f2fd;
        padding: 8px 12px;
        border-radius: 12px;
        font-size: 9px;
        color: #2c3e50;
    }
    
    .chat-attachment {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
        font-size: 8px;
        color: #7f8c8d;
    }
    
    .status-section {
        display: flex;
        gap: 15px;
        font-size: 8px;
        color: #7f8c8d;
    }
    
    .status-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .status-label {
        font-weight: 600;
    }
    
    .status-value {
        color: #3498db;
        font-weight: 500;
    }
    
    .team-section {
        margin-top: 8px;
    }
    
    .team-member {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .member-avatar {
        width: 20px;
        height: 20px;
        background: #3498db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 8px;
    }
    
    .member-info {
        font-size: 8px;
    }
    
    .member-name {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .member-role {
        color: #7f8c8d;
        font-size: 7px;
    }
    
    .message-input {
        margin-top: auto;
    }
    
    .message-input input {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #ddd;
        border-radius: 15px;
        font-size: 8px;
        background: white;
    }
    
    .keyboard {
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 200px;
        height: 8px;
        background: #34495e;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .stylus {
        position: absolute;
        top: 20px;
        right: -20px;
        width: 40px;
        height: 3px;
        background: #95a5a6;
        border-radius: 2px;
        transform: rotate(45deg);
    }
    
    .bg-decoration {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        height: 300px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50px;
        opacity: 0.6;
        z-index: -1;
    }
    
    /* Content Styles */
    .why-choose-content {
        padding: 2rem 0;
    }
    
    .content-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 1.5rem;
    }
    
    .header-icon {
        width: 50px;
        height: 50px;
        background: #2c3e50;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    
    .content-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1.2;
    }
    
    .content-description {
        font-size: 1.1rem;
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .features-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .feature-icon {
        width: 24px;
        height: 24px;
        background: #27ae60;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .feature-content {
        flex: 1;
    }
    
    .feature-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 0.5rem 0;
    }
    
    .feature-description {
        font-size: 1rem;
        color: #7f8c8d;
        line-height: 1.5;
        margin: 0;
    }
    
    /* Responsive Design */
    @media (max-width: 991.98px) {
        .tablet-device {
            width: 240px;
            height: 170px;
        }
        
        .content-title {
            font-size: 2rem;
        }
        
        .content-description {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .why-choose-illustration {
            margin-bottom: 2rem;
        }
        
        .tablet-device {
            width: 200px;
            height: 140px;
            transform: rotate(0deg);
        }
        
        .content-header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .content-title {
            font-size: 1.8rem;
        }
        
        .features-list {
            gap: 1rem;
        }
        
        .feature-item {
            gap: 10px;
        }
        
        .feature-title {
            font-size: 1.1rem;
        }
        
        .feature-description {
            font-size: 0.95rem;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 1.8rem;
        }
        
        .answer-section {
            padding-left: 1rem;
            margin-left: 0.5rem;
        }
        
        .answer-content {
            padding: 0.5rem;
        }
    }
</style>

<!-- Thêm JavaScript để xử lý thêm vào giỏ hàng -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý thêm vào giỏ hàng với SweetAlert2
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productName = this.closest('.card').querySelector('.card-title').textContent;
            
            // Hiển thị loading
            Swal.fire({
                title: 'Đang thêm vào giỏ hàng...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Gửi request
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng giỏ hàng
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cartCount;
                    }
                    
                    // Hiển thị thông báo thành công
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: `Đã thêm "${productName}" vào giỏ hàng`,
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Không thể thêm sản phẩm vào giỏ hàng',
                    confirmButtonText: 'Thử lại'
                });
            });
        });
    });
});

// xử lý hỏi đáp

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý form đặt câu hỏi
    const questionForm = document.getElementById('questionForm');
    if (questionForm) {
        questionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form trước khi gửi
            if (!validateForm()) {
                return;
            }
            
            const formData = new FormData(this);
            
            // Hiển thị loading
            Swal.fire({
                title: 'Đang gửi câu hỏi...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Gửi request
            fetch('{{ route("question-answer.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Gửi thành công!',
                        text: data.message,
                        confirmButtonText: 'Đồng ý'
                    }).then(() => {
                        // Reset form
                        questionForm.reset();
                        $('.form-control, .form-select').removeClass('is-valid is-invalid');
                        // Chuyển đến trang hỏi đáp đầy đủ
                        window.location.href = '{{ route("question-answer.index") }}';
                    });
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Không thể gửi câu hỏi',
                    confirmButtonText: 'Thử lại'
                });
            });
        });
    }
    
    // Hàm validate form
    function validateForm() {
        let isValid = true;
        
        // Validate title
        const title = $('#questionTitle').val().trim();
        if (!title) {
            showFieldError('questionTitle', 'Vui lòng nhập tiêu đề câu hỏi.');
            isValid = false;
        } else if (title.length < 5) {
            showFieldError('questionTitle', 'Tiêu đề phải có ít nhất 5 ký tự.');
            isValid = false;
        } else if (title.length > 255) {
            showFieldError('questionTitle', 'Tiêu đề không được vượt quá 255 ký tự.');
            isValid = false;
        } else {
            showFieldSuccess('questionTitle');
        }
        
        // Validate category
        const category = $('#questionCategory').val();
        if (!category) {
            showFieldError('questionCategory', 'Vui lòng chọn danh mục câu hỏi.');
            isValid = false;
        } else {
            showFieldSuccess('questionCategory');
        }
        
        // Validate content
        const content = $('#questionContent').val().trim();
        if (!content) {
            showFieldError('questionContent', 'Vui lòng nhập nội dung câu hỏi.');
            isValid = false;
        } else if (content.length < 10) {
            showFieldError('questionContent', 'Nội dung phải có ít nhất 10 ký tự.');
            isValid = false;
        } else if (content.length > 2000) {
            showFieldError('questionContent', 'Nội dung không được vượt quá 2000 ký tự.');
            isValid = false;
        } else {
            showFieldSuccess('questionContent');
        }
        
        return isValid;
    }
    
    // Hàm hiển thị lỗi cho field
    function showFieldError(fieldName, message) {
        const input = $(`#${fieldName}`);
        const feedback = input.siblings('.invalid-feedback');
        
        input.removeClass('is-valid').addClass('is-invalid');
        feedback.text(message);
    }
    
    // Hàm hiển thị thành công cho field
    function showFieldSuccess(fieldName) {
        const input = $(`#${fieldName}`);
        const feedback = input.siblings('.invalid-feedback');
        
        input.removeClass('is-invalid').addClass('is-valid');
        feedback.text('');
    }
    
    // Real-time validation
    $('#questionTitle').on('input', function() {
        const value = $(this).val().trim();
        if (value.length >= 5 && value.length <= 255) {
            showFieldSuccess('questionTitle');
        } else if (value.length > 0) {
            if (value.length < 5) {
                showFieldError('questionTitle', 'Tiêu đề phải có ít nhất 5 ký tự.');
            } else if (value.length > 255) {
                showFieldError('questionTitle', 'Tiêu đề không được vượt quá 255 ký tự.');
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        }
    });
    
    $('#questionCategory').on('change', function() {
        const value = $(this).val();
        if (value) {
            showFieldSuccess('questionCategory');
        } else {
            $(this).removeClass('is-valid is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        }
    });
    
    $('#questionContent').on('input', function() {
        const value = $(this).val().trim();
        if (value.length >= 10 && value.length <= 2000) {
            showFieldSuccess('questionContent');
        } else if (value.length > 0) {
            if (value.length < 10) {
                showFieldError('questionContent', 'Nội dung phải có ít nhất 10 ký tự.');
            } else if (value.length > 2000) {
                showFieldError('questionContent', 'Nội dung không được vượt quá 2000 ký tự.');
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
            $(this).siblings('.invalid-feedback').text('');
        }
    });
});
</script>

<style>
/* Favorite Button Styles */
.btn-favorite {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.9);
    color: #ccc;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(10px);
}

.btn-favorite:hover {
    background: rgba(255, 255, 255, 1);
    color: #ef4444;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.btn-favorite.favorited {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    animation: heartbeat 1.5s ease-in-out infinite;
}

.btn-favorite.favorited:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
}

@keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Product Card Hover Effect */
.product-card:hover .btn-favorite {
    opacity: 1;
    visibility: visible;
}

.btn-favorite {
    opacity: 0.8;
    visibility: visible;
}

@media (max-width: 768px) {
    .btn-favorite {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
}
</style>
@endsection