@extends('layouts.customer')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="h2 fw-bold mb-0">{{ $product->name }}</h1>
                        @auth
                            <button class="btn btn-favorite btn-lg {{ auth()->user()->isFavorite($product->id) ? 'favorited' : '' }}" 
                                    data-product-id="{{ $product->id }}"
                                    data-store-url="{{ route('favorites.store', $product->id) }}"
                                    data-destroy-url="{{ route('favorites.destroy', $product->id) }}"
                                    title="{{ auth()->user()->isFavorite($product->id) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
                                <i class="fas fa-heart"></i>
                            </button>
                        @endauth
                    </div>
                    
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Quay Lại
                        </a>
                        @if($product->category)
                        <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-tag me-1"></i>Xem Sản Phẩm Cùng Danh Mục
                        </a>
                        @endif
                    </div>

                    <!-- Gallery hình ảnh sản phẩm -->
                    <div class="product-gallery mb-4">
                        <!-- Ảnh chính -->
                        <div class="main-image-container text-center mb-3">
                            <img id="mainImage" 
                                 src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x400/cccccc/666666?text=Không+có+ảnh' }}" 
                                 class="img-fluid rounded shadow-lg main-image" 
                                 alt="{{ $product->name }}"
                                 style="max-height: 500px; max-width: 100%; cursor: pointer; transition: all 0.3s ease;">
                        </div>
                        
                        <!-- Thumbnail gallery -->
                        <div class="thumbnail-gallery">
                            <div class="row g-2">
                                @if($product->image)
                                <div class="col-auto">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         class="thumbnail-img active" 
                                         alt="{{ $product->name }}"
                                         data-main-src="{{ asset('storage/' . $product->image) }}"
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 3px solid #007bff; transition: all 0.3s ease;">
                                </div>
                                @endif
                                @if($product->image2)
                                <div class="col-auto">
                                    <img src="{{ asset('storage/' . $product->image2) }}" 
                                         class="thumbnail-img" 
                                         alt="{{ $product->name }} - Hình ảnh 2"
                                         data-main-src="{{ asset('storage/' . $product->image2) }}"
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 3px solid transparent; transition: all 0.3s ease;">
                                </div>
                                @endif
                                @if($product->image3)
                                <div class="col-auto">
                                    <img src="{{ asset('storage/' . $product->image3) }}" 
                                         class="thumbnail-img" 
                                         alt="{{ $product->name }} - Hình ảnh 3"
                                         data-main-src="{{ asset('storage/' . $product->image3) }}"
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 3px solid transparent; transition: all 0.3s ease;">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Mô Tả Sản Phẩm</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    <!-- Đặc điểm sản phẩm -->
                    @if($product->features && count($product->features) > 0)
                    <div class="mb-4">
                        <h5 class="fw-bold">Đặc Điểm Sản Phẩm</h5>
                        <div class="row g-3">
                            @foreach($product->features as $feature)
                            <div class="col-md-6">
                                <div class="bg-light rounded p-3">
                                    <strong class="text-primary">{{ $feature['key'] }}:</strong>
                                    <span class="ms-2">{{ $feature['value'] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Thông tin bổ sung -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Thông Tin Bổ Sung</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="bg-light rounded p-3">
                                    <strong class="text-muted">Ngày tạo:</strong>
                                    <span class="ms-2">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-3">
                                    <strong class="text-muted">Cập nhật lần cuối:</strong>
                                    <span class="ms-2">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                       <!-- Rating tổng quan -->
                    <div class="mb-4">
                        <x-product-rating :product="$product" />
                    </div>

                    <!-- Đánh giá sản phẩm -->
                    <hr class="my-5">

                    <div class="row">
                        <div class="col-12">
                            <h3 class="mb-4">Đánh giá sản phẩm ({{ $product->reviews->count() }})</h3>

                            @forelse ($product->reviews as $review)
                                <div class="media mb-4 d-flex gap-3">
                                    <div class="review-avatar">
                                        @if($review->user?->avatar)
                                            <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="review-avatar-img">
                                        @else
                                            <span class="review-avatar-initial">{{ mb_strtoupper(mb_substr($review->user->name, 0, 1, 'UTF-8'), 'UTF-8') }}</span>
                                        @endif
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mt-0">{{ $review->user->name }}</h5>
                                        <div class="text-warning mb-2">
                                            <x-rating-stars :rating="$review->rating" :showHalfStar="false" />
                                            <span class="badge bg-primary ms-2">{{ $review->rating }}/5</span>
                                        </div>
                                        <p>{{ $review->comment }}</p>
                                        
                                        <!-- Hiển thị hình ảnh đánh giá -->
                                        @if($review->images && count($review->images) > 0)
                                            <div class="review-images mt-3">
                                                <div class="row g-2">
                                                    @foreach($review->images as $image)
                                                        <div class="col-auto">
                                                            <img src="{{ asset('storage/' . $image) }}" 
                                                                 alt="Hình ảnh đánh giá" 
                                                                 class="img-thumbnail" 
                                                                 style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                                 data-bs-toggle="modal" 
                                                                 data-bs-target="#imageModal"
                                                                 onclick="showImageModal('{{ asset('storage/' . $image) }}')">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <small class="text-muted">Đăng vào {{ $review->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            @empty
                                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Gửi form đánh Giá -->
                    <hr class="my-5" id="review-section">

                    <div class="row">
                        <div class="col-12">
                            @auth
                                @php
                                    // Kiểm tra xem người dùng đã mua sản phẩm chưa
                                    $hasPurchased = auth()->user()->orders()
                                        ->where('status', 'delivered')
                                        ->whereHas('items', function($query) use ($product) {
                                            $query->where('product_id', $product->id);
                                        })
                                        ->exists();

                                    // Kiểm tra xem người dùng đã đánh giá chưa
                                    $hasReviewed = $product->reviews()->where('user_id', auth()->id())->exists();
                                @endphp

                                @if ($hasPurchased && !$hasReviewed)
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h4 class="mb-0">
                                                <i class="fas fa-star me-2"></i>Viết đánh giá của bạn
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label fw-bold">Đánh giá (số sao):</label>
                                                            <x-rating-stars-interactive name="rating" value="0" size="large" />
                                                            <small class="text-muted d-block mt-2">Nhấp vào ngôi sao để chọn điểm đánh giá</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="images" class="form-label fw-bold">Hình ảnh đánh giá:</label>
                                                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                                                            <small class="text-muted d-block mt-1">Có thể chọn nhiều hình ảnh (tối đa 2MB mỗi ảnh)</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="comment" class="form-label fw-bold">Bình luận:</label>
                                                    <textarea name="comment" id="comment" rows="4" class="form-control" 
                                                              placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Đánh giá của bạn sẽ giúp khách hàng khác đưa ra quyết định mua hàng
                                                    </small>
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($hasPurchased && $hasReviewed)
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>Cảm ơn bạn!</strong> Bạn đã đánh giá sản phẩm này.
                                    </div>
                                @elseif(!$hasPurchased)
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Thông báo:</strong> Bạn cần mua sản phẩm này để có thể đánh giá.
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm ms-2">
                                            <i class="fas fa-shopping-cart me-1"></i>Mua ngay
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Vui lòng đăng nhập</strong> để viết đánh giá.
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm ms-2">
                                        <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar thông tin -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="top: 2rem;">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Thông Tin Sản Phẩm</h5>
                    
                    <!-- Giá sản phẩm -->
                    <div class="mb-4">
                        <h3 class="text-primary fw-bold mb-2">
                            {{ number_format($product->price) }} VNĐ
                        </h3>
                        <small class="text-muted">Giá đã bao gồm thuế</small>
                    </div>

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Danh Mục</h6>
                        <span class="badge bg-info fs-6">
                            <i class="fas fa-tag me-1"></i>
                            {{ $product->category->name ?? 'N/A' }}
                        </span>
                    </div>

                    <!-- Số lượng -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Tình Trạng Kho</h6>
                        @if($product->quantity > 10)
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check me-1"></i>
                                Còn {{ $product->quantity }} sản phẩm
                            </span>
                        @elseif($product->quantity > 0)
                            <span class="badge bg-warning fs-6">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Chỉ còn {{ $product->quantity }} sản phẩm
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times me-1"></i>
                                Hết hàng
                            </span>
                        @endif
                    </div>

                    <!-- Trạng thái -->
                    <div class="mb-4">
                        <h6 class="fw-bold">Trạng Thái</h6>
                        @if($product->is_active)
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>
                                Hoạt động
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6">
                                <i class="fas fa-pause-circle me-1"></i>
                                Không hoạt động
                            </span>
                        @endif
                    </div>

                    <!-- Nút hành động -->
                    <div class="d-grid gap-2">
                        @if($product->quantity > 0 && $product->is_active)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-grid add-to-cart-form">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-shopping-cart me-2"></i>Thêm Vào Giỏ Hàng
                                </button>
                            </form>
                        @elseif($product->quantity <= 0)
                            <button class="btn btn-danger btn-lg" disabled>
                                <i class="fas fa-ban me-2"></i>Hết Hàng
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="fas fa-pause me-2"></i>Không Khả Dụng
                            </button>
                        @endif
                        
                        <!-- Nút yêu thích -->
                        @auth
                            <button type="button" class="btn btn-outline-danger btn-lg btn-favorite {{ auth()->user()->isFavorite($product->id) ? 'favorited' : '' }}" 
                                    data-product-id="{{ $product->id }}"
                                    data-store-url="{{ route('favorites.store', $product->id) }}"
                                    data-destroy-url="{{ route('favorites.destroy', $product->id) }}"
                                    title="{{ auth()->user()->isFavorite($product->id) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
                                <i class="fas fa-heart me-2"></i>
                                <span class="favorite-text">{{ Auth::user()->isFavorite($product->id) ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg">
                                <i class="fas fa-heart me-2"></i>Thêm vào yêu thích
                            </a>
                        @endauth
                        
                        @if($product->category)
                        <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-tags me-2"></i>Xem Sản Phẩm Cùng Danh Mục
                        </a>
                        @endif
                        
                        <!-- Nút xem giỏ hàng -->
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-shopping-bag me-2"></i>Xem Giỏ Hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingSelect = document.getElementById('rating');
    const ratingPreview = document.getElementById('rating-preview');
    
    if (ratingSelect && ratingPreview) {
        ratingSelect.addEventListener('change', function() {
            const rating = parseInt(this.value);
            if (rating >= 1 && rating <= 5) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        stars += '⭐';
                    } else {
                        stars += '☆';
                    }
                }
                ratingPreview.innerHTML = stars + ' (' + rating + ' sao)';
            } else {
                ratingPreview.innerHTML = '<span class="text-muted">Chọn số sao để xem trước</span>';
            }
        });
    }
});
</script>

<!-- Modal xem hình ảnh đánh giá -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Hình ảnh đánh giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Hình ảnh đánh giá" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<script>
// Function hiển thị modal hình ảnh đánh giá
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
}
</script>
@endsection

<style>
/* Product Gallery Styles */
.product-gallery {
    position: relative;
}

.main-image {
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.main-image:hover {
    box-shadow: 0 12px 35px rgba(0,0,0,0.2);
}

.thumbnail-img {
    transition: all 0.3s ease;
    opacity: 0.8;
}

.thumbnail-img:hover {
    opacity: 1;
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.thumbnail-img.active {
    opacity: 1;
    border-color: #007bff !important;
    box-shadow: 0 4px 15px rgba(0,123,255,0.3);
}

.thumbnail-gallery {
    padding: 10px 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .main-image {
        max-height: 350px !important;
    }
    
    .thumbnail-img {
        width: 60px !important;
        height: 60px !important;
    }
}

/* Avatar trong danh sách đánh giá */
.review-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #9e9e9e;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    overflow: hidden;
    flex-shrink: 0;
}
.review-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.review-avatar-initial { font-size: 1rem; }

@media (max-width: 576px) {
    .main-image {
        max-height: 300px !important;
    }
    
    .thumbnail-img {
        width: 50px !important;
        height: 50px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Product Gallery Functionality
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    
    // Click thumbnail để thay đổi ảnh chính
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Cập nhật ảnh chính
            const newSrc = this.getAttribute('data-main-src');
            const newAlt = this.getAttribute('alt');
            
            // Thêm hiệu ứng fade
            mainImage.style.opacity = '0.7';
            
            setTimeout(() => {
                mainImage.src = newSrc;
                mainImage.alt = newAlt;
                mainImage.style.opacity = '1';
            }, 150);
            
            // Cập nhật trạng thái active của thumbnail
            thumbnails.forEach(thumb => {
                thumb.classList.remove('active');
                thumb.style.borderColor = 'transparent';
            });
            
            this.classList.add('active');
            this.style.borderColor = '#007bff';
        });
    });
    
    // Click ảnh chính để phóng to
    mainImage.addEventListener('click', function() {
        showImageModal(this.src, this.alt);
    });
    
    // Function hiển thị modal phóng to ảnh
    function showImageModal(imageSrc, imageAlt) {
        // Tạo modal HTML
        const modalHtml = `
            <div class="image-modal" id="imageModal" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.9);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            ">
                <div style="position: relative; max-width: 90%; max-height: 90%;">
                    <img src="${imageSrc}" alt="${imageAlt}" style="
                        max-width: 100%;
                        max-height: 100%;
                        border-radius: 8px;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
                    ">
                    <button id="closeModal" style="
                        position: absolute;
                        top: -40px;
                        right: 0;
                        background: rgba(255,255,255,0.2);
                        border: none;
                        color: white;
                        font-size: 24px;
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">×</button>
                </div>
            </div>
        `;
        
        // Thêm modal vào body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Xử lý đóng modal
        const modal = document.getElementById('imageModal');
        const closeBtn = document.getElementById('closeModal');
        
        function closeModal() {
            modal.remove();
        }
        
        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        // Đóng modal bằng phím ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    }
    
    // Xử lý thêm vào giỏ hàng với SweetAlert2
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productName = '{{ $product->name }}';
            
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
</script> 