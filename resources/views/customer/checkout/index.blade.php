@extends('layouts.customer')

@section('content')
<div class="container py-5">
    <!-- Header thanh toán -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <div class="checkout-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-credit-card fa-lg"></i>
                </div>
                <div>
                    <h1 class="h2 mb-0 fw-bold text-dark">Thanh Toán</h1>
                    <p class="text-muted mb-0">Hoàn tất thông tin để đặt hàng</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Form thông tin khách hàng -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Thông Tin Khách Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        <!-- Hidden input để gửi danh sách sản phẩm được chọn -->
                        <input type="hidden" name="selected_items" id="selected-items-input" value="">
                        
                        <!-- Chọn địa chỉ giao hàng -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">
                                Chọn địa chỉ giao hàng <span class="text-danger">*</span>
                            </label>
                            
                            @if($addresses->count() > 0)
                                <div class="address-selection">
                                    @foreach($addresses as $address)
                                        <div class="form-check address-option mb-3 p-3 border rounded {{ $address->is_default ? 'border-primary bg-light' : 'border-light' }}">
                                            <input class="form-check-input" type="radio" 
                                                   name="address_id" 
                                                   id="address_{{ $address->id }}" 
                                                   value="{{ $address->id }}"
                                                   {{ $address->is_default ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label w-100" for="address_{{ $address->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <h6 class="mb-0 fw-bold text-dark">{{ $address->full_name }}</h6>
                                                            @if($address->is_default)
                                                                <span class="badge bg-primary ms-2">
                                                                    <i class="fas fa-star me-1"></i>Mặc định
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted mb-1">
                                                            <i class="fas fa-phone me-2"></i>{{ $address->phone }}
                                                        </div>
                                                        <div class="text-muted">
                                                            <i class="fas fa-map-marker-alt me-2"></i>
                                                            @if($address->hasCompleteAddress())
                                                                {{ $address->street_address }}, {{ $address->short_address }}
                                                            @else
                                                                {{ $address->street_address }}
                                                            @endif
                                                        </div>
                                                        @if($address->note)
                                                            <div class="text-muted small mt-1">
                                                                <i class="fas fa-sticky-note me-2"></i>{{ $address->note }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                    
                                    <div class="text-center mt-3">
                                        <a href="{{ route('addresses.create') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
                                        </a>
                                        <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                                            <i class="fas fa-cog me-2"></i>Quản lý địa chỉ
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-3"></i>
                                        <div>
                                            <h6 class="mb-2">Bạn chưa có địa chỉ giao hàng</h6>
                                            <p class="mb-0">Vui lòng thêm địa chỉ giao hàng để tiếp tục đặt hàng.</p>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Phương thức thanh toán -->
                        <div class="mb-4">
                            <label for="payment_method" class="form-label fw-bold text-dark">
                                Phương thức thanh toán <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" 
                                    name="payment_method" 
                                    required>
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>
                                    Thanh toán khi nhận hàng (COD)
                                </option>
                                <option value="momo" {{ old('payment_method') == 'momo' ? 'selected' : '' }}>
                                    Thanh toán qua Momo
                                </option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Ghi chú -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-dark">
                                Ghi chú (không bắt buộc)
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2" 
                                      placeholder="Ghi chú thêm về đơn hàng hoặc yêu cầu đặc biệt">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Thông tin bổ sung -->
                        <div class="alert alert-info">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle fa-lg text-primary me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-2">Thông tin bổ sung:</h6>
                                    <ul class="mb-0 small">
                                        <li>Đơn hàng sẽ được xử lý trong vòng 24-48 giờ</li>
                                        <li>Thời gian giao hàng: 2-5 ngày làm việc</li>
                                        <li>Miễn phí vận chuyển cho đơn hàng trên 5 triệu VNĐ</li>
                                        <li>Hỗ trợ đổi trả trong vòng 7 ngày</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nút đặt hàng -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold" id="submitOrder" 
                                    {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-check-circle me-2"></i>
                                {{ $addresses->count() == 0 ? 'Vui lòng thêm địa chỉ giao hàng' : 'Đặt Hàng Ngay' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="top: 20px;">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Tóm Tắt Đơn Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Danh sách sản phẩm -->
                    <div class="order-items mb-3">
                        @foreach($cartItems as $item)
                        <div class="order-item d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="order-item-image me-3">
                                @if($item['product']->image)
                                    <img src="{{ asset('storage/' . $item['product']->image) }}" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="{{ $item['product']->name }}">
                                @else
                                    <img src="https://via.placeholder.com/50x50/cccccc/666666?text=Không+có+ảnh" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px;"
                                         alt="{{ $item['product']->name }}">
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="mb-1 fw-bold text-dark small">{{ $item['product']->name }}</h6>
                                <p class="mb-0 text-muted small">
                                    {{ number_format($item['product']->price) }} VNĐ × {{ $item['quantity'] }}
                                </p>
                            </div>
                            <div class="order-item-total">
                                <span class="fw-bold text-primary">{{ number_format($item['subtotal']) }} VNĐ</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Tổng tiền -->
                    <div class="order-summary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tổng tiền sản phẩm:</span>
                            <span class="fw-bold">{{ number_format($total) }} VNĐ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Phí vận chuyển:</span>
                            @if($shippingFee > 0)
                                <span class="fw-bold">{{ number_format($shippingFee) }} VNĐ</span>
                            @else
                                <span class="text-success fw-bold">Shop chịu phí</span>
                            @endif
                        </div>
                        
                        @if($shippingFee > 0)
                            <div class="alert alert-info small mb-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Mua thêm <strong>{{ number_format(5000000 - $total) }} VNĐ</strong> để được miễn phí vận chuyển!
                            </div>
                        @endif
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 fw-bold text-dark">Tổng cộng:</span>
                            <span class="h5 fw-bold text-primary">{{ number_format($finalAmount) }} VNĐ</span>
                        </div>
                    </div>
                    
                    <!-- Thông tin bảo mật -->
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-lock me-1"></i>
                            Thông tin của bạn được bảo mật
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quay lại giỏ hàng -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Quay Lại Giỏ Hàng
            </a>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy danh sách sản phẩm được chọn từ sessionStorage
    const selectedItems = sessionStorage.getItem('selectedCartItems');
    
    if (selectedItems) {
        // Parse JSON và set vào hidden input
        const selectedProductIds = JSON.parse(selectedItems);
        document.getElementById('selected-items-input').value = JSON.stringify(selectedProductIds);
        
        // Clear sessionStorage sau khi sử dụng
        sessionStorage.removeItem('selectedCartItems');
    }
    
    // Xử lý submit form
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        // Đảm bảo selected_items được gửi
        const selectedItemsInput = document.getElementById('selected-items-input');
        if (!selectedItemsInput.value) {
            // Nếu không có selected items, lấy tất cả sản phẩm hiện tại
            const currentProductIds = @json($selectedProductIds ?? []);
            selectedItemsInput.value = JSON.stringify(currentProductIds);
        }
    });
});
</script>

<!-- CSS tùy chỉnh -->
<style>
.checkout-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.address-option {
    transition: all 0.3s ease;
    cursor: pointer;
}

.address-option:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}

.address-option input[type="radio"]:checked + label {
    color: #007bff;
}

.order-item {
    transition: all 0.3s ease;
}

.order-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
    margin: -8px;
}

.sticky-top {
    z-index: 1020;
}

.form-control:focus,
.form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-success:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .sticky-top {
        position: static !important;
        margin-top: 2rem;
    }
}
</style>

<!-- JavaScript cho form validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitOrder');
    
    // Xử lý submit form
    form.addEventListener('submit', function(e) {
        // Kiểm tra xem có địa chỉ nào được chọn không
        const selectedAddress = document.querySelector('input[name="address_id"]:checked');
        if (!selectedAddress) {
            e.preventDefault();
            alert('Vui lòng chọn địa chỉ giao hàng!');
            return;
        }
        
        // Disable nút submit để tránh double submit
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
        
        // Form sẽ được submit bình thường
    });
    
    // Xử lý khi chọn địa chỉ
    const addressOptions = document.querySelectorAll('input[name="address_id"]');
    addressOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Bỏ highlight tất cả địa chỉ
            document.querySelectorAll('.address-option').forEach(addr => {
                addr.classList.remove('border-primary', 'bg-light');
                addr.classList.add('border-light');
            });
            
            // Highlight địa chỉ được chọn
            if (this.checked) {
                const addressOption = this.closest('.address-option');
                addressOption.classList.remove('border-light');
                addressOption.classList.add('border-primary', 'bg-light');
            }
        });
    });
});
</script>
@endsection
