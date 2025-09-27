@extends('layouts.customer')

@section('content')
<div class="container py-5">
    <!-- Header thanh toán MoMo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <div class="payment-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-credit-card fa-lg"></i>
                </div>
                <div>
                    <h1 class="h2 mb-0 fw-bold text-dark">Thanh Toán MoMo ATM</h1>
                    <p class="text-muted mb-0">Đơn hàng #{{ $order->order_number }}</p>
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
        <!-- Thông tin đơn hàng -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Thông Tin Đơn Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Danh sách sản phẩm -->
                    <div class="order-items mb-4">
                        @foreach($order->items as $item)
                        <div class="order-item d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="order-item-image me-3">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="{{ $item->product_name }}">
                                @else
                                    <img src="https://via.placeholder.com/50x50/cccccc/666666?text=Không+có+ảnh" 
                                         class="rounded" 
                                         style="width: 50px; height: 50px;"
                                         alt="{{ $item->product_name }}">
                                @endif
                            </div>
                            <div class="order-item-details flex-grow-1">
                                <h6 class="mb-1 fw-bold text-dark small">{{ $item->product_name }}</h6>
                                <p class="mb-0 text-muted small">
                                    {{ number_format($item->product_price) }} VNĐ × {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="order-item-total">
                                <span class="fw-bold text-primary">{{ number_format($item->subtotal) }} VNĐ</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Thông tin giao hàng -->
                    <div class="shipping-info">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-truck me-2 text-success"></i>
                            Thông Tin Giao Hàng
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Người nhận:</strong> {{ $order->shipping_name }}</p>
                                <p class="mb-2"><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Địa chỉ:</strong></p>
                                <p class="text-muted">{{ $order->full_shipping_address }}</p>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                    
        <!-- Tóm tắt thanh toán -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-calculator me-2 text-success"></i>
                        Tóm Tắt Thanh Toán
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Tổng tiền -->
                    <div class="order-summary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tổng tiền sản phẩm:</span>
                            <span class="fw-bold">{{ number_format($order->subtotal) }} VNĐ</span>
                    </div>
                    
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Phí vận chuyển:</span>
                            @if($order->shipping_fee > 0)
                                <span class="fw-bold">{{ number_format($order->shipping_fee) }} VNĐ</span>
                            @else
                                <span class="text-success fw-bold">Miễn phí</span>
                            @endif
                </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 fw-bold text-dark">Tổng cộng:</span>
                            <span class="h5 fw-bold text-primary">{{ number_format($order->total_amount) }} VNĐ</span>
                    </div>
                    </div>
                    
                    <!-- Nút thanh toán MoMo -->
                    <form action="{{ route('payment.momo.process', $order->id) }}" method="POST" id="momoForm" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 py-3 mb-3" id="momoBtn">
                            <i class="fas fa-credit-card me-2"></i>
                            Thanh Toán Bằng MoMo ATM
                        </button>
                    </form>
                    
                    <!-- Nút hủy đơn hàng -->
                    <form action="{{ route('payment.momo.cancel-order', $order->id) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này? Giỏ hàng sẽ được khôi phục.')" 
                          class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 py-2">
                            <i class="fas fa-times me-2"></i>
                            Hủy Đơn Hàng
                        </button>
                    </form>
                    
                    <!-- Thông tin bảo mật -->
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Thanh toán an toàn với MoMo
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quay lại -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Quay Lại Đơn Hàng
            </a>
        </div>
    </div>
</div>

<!-- CSS tùy chỉnh -->
<style>
.payment-icon {
    background: linear-gradient(135deg, #d82d8b 0%, #e91e63 100%);
}

.order-item {
    transition: all 0.3s ease;
}

.order-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin: 0 -1rem 1rem -1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #d82d8b 0%, #e91e63 100%);
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #c2185b 0%, #d81b60 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(216, 45, 139, 0.3);
}

.btn-primary:disabled {
    background: #6c757d;
    transform: none;
    box-shadow: none;
}

.shipping-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.order-summary {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
}
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const momoForm = document.getElementById('momoForm');
    const momoBtn = document.getElementById('momoBtn');
    
    momoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hiển thị loading
        momoBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang chuyển đến MoMo ATM...';
        momoBtn.disabled = true;
        
        // Submit form
        setTimeout(() => {
            momoForm.submit();
        }, 1000);
    });
});
</script>
@endsection