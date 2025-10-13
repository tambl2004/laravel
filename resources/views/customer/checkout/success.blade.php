@extends('layouts.customer')

@section('content')
<div class="container py-5">
    <!-- Header thành công -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="success-icon mb-4">
                <div class="success-circle bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                    <i class="fas fa-check fa-3x"></i>
                </div>
            </div>
            <h1 class="display-4 fw-bold text-success mb-3">Đặt Hàng Thành Công!</h1>
            <p class="lead text-muted mb-4">
                Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.
            </p>
            <div class="alert alert-success d-inline-block">
                <i class="fas fa-info-circle me-2"></i>
                Mã đơn hàng: <strong class="text-dark">{{ $order->order_number }}</strong>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Chi tiết đơn hàng -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-receipt me-2"></i>
                        Chi Tiết Đơn Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Thông tin khách hàng -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Thông Tin Khách Hàng
                            </h6>
                            <div class="customer-info">
                                <p class="mb-2">
                                    <strong>Tên:</strong> {{ $order->customer_name }}
                                </p>
                                <p class="mb-2">
                                    <strong>Email:</strong> {{ $order->customer_email }}
                                </p>
                                <p class="mb-2">
                                    <strong>Số điện thoại:</strong> {{ $order->customer_phone }}
                                </p>
                                <p class="mb-0">
                                    <strong>Địa chỉ:</strong> {{ $order->customer_address }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-credit-card me-2 text-primary"></i>
                                Thông Tin Thanh Toán
                            </h6>
                            <div class="payment-info">
                                <p class="mb-2">
                                    <strong>Phương thức:</strong> {{ $order->payment_method_text }}
                                </p>
                                <p class="mb-2">
                                    <strong>Trạng thái:</strong> 
                                    <span class="badge bg-warning text-dark">{{ $order->status_text }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                                </p>
                                @if($order->notes)
                                <p class="mb-0">
                                    <strong>Ghi chú:</strong> {{ $order->notes }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="order-products">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>
                            Sản Phẩm Đã Đặt
                        </h6>
                        @foreach($order->items as $item)
                        <div class="order-product-item d-flex align-items-center p-3 border rounded mb-3">
                            <div class="product-image me-3">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;"
                                         alt="{{ $item->product_name }}">
                                @else
                                    <img src="https://via.placeholder.com/60x60/cccccc/666666?text=Không+có+ảnh" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px;"
                                         alt="{{ $item->product_name }}">
                                @endif
                            </div>
                            <div class="product-details flex-grow-1">
                                <h6 class="mb-1 fw-bold text-dark">{{ $item->product_name }}</h6>
                                <p class="mb-0 text-muted small">
                                    {{ number_format($item->product_price) }} VNĐ × {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="product-total">
                                <span class="fw-bold text-primary fs-5">{{ number_format($item->subtotal) }} VNĐ</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Tổng tiền -->
                    <div class="order-total mt-4">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="total-breakdown">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Tổng tiền sản phẩm:</span>
                                        <span class="fw-bold">{{ number_format($order->total_amount) }} VNĐ</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted">Phí vận chuyển:</span>
                                        @if($order->shipping_fee > 0)
                                            <span class="fw-bold">{{ number_format($order->shipping_fee) }} VNĐ</span>
                                        @else
                                            <span class="text-success fw-bold">Shop chịu phí</span>
                                        @endif
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 fw-bold text-dark">Tổng cộng:</span>
                                        <span class="h5 fw-bold text-primary">{{ number_format($order->final_amount) }} VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thông tin bổ sung -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông Tin Bổ Sung
                    </h5>
                </div>
                <div class="card-body">
                    <div class="additional-info">
                        <div class="info-item mb-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-clock me-2 text-info"></i>
                                Thời gian xử lý
                            </h6>
                            <p class="text-muted small mb-0">
                                Đơn hàng sẽ được xử lý trong vòng 24-48 giờ làm việc
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-shipping-fast me-2 text-info"></i>
                                Thời gian giao hàng
                            </h6>
                            <p class="text-muted small mb-0">
                                Dự kiến giao hàng trong 2-5 ngày làm việc
                            </p>
                        </div>
                        
                        <div class="info-item mb-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-phone me-2 text-info"></i>
                                Hỗ trợ khách hàng
                            </h6>
                            <p class="text-muted small mb-0">
                                Hotline: <strong>1900-xxxx</strong><br>
                                Email: <strong>support@example.com</strong>
                            </p>
                        </div>
                        
                        <div class="info-item">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-exchange-alt me-2 text-info"></i>
                                Chính sách đổi trả
                            </h6>
                            <p class="text-muted small mb-0">
                                Hỗ trợ đổi trả trong vòng 7 ngày kể từ ngày nhận hàng
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nút hành động -->
            <div class="d-grid gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Tiếp Tục Mua Sắm
                </a>
                
                <a href="{{ route('orders.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-list me-2"></i>
                    Xem Lịch Sử Đơn Hàng
                </a>
                
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-home me-2"></i>
                    Về Trang Chủ
                </a>
            </div>
        </div>
    </div>
    
    <!-- Thông báo email -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fas fa-envelope fa-2x me-3 text-info"></i>
                    <div>
                        <h6 class="mb-1 fw-bold">Xác nhận đơn hàng đã được gửi</h6>
                        <p class="mb-0">Chúng tôi đã gửi email xác nhận đến <strong>{{ $order->customer_email }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS tùy chỉnh -->
<style>
.success-circle {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.order-product-item {
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.order-product-item:hover {
    background-color: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.total-breakdown {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
}

.info-item {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background-color: #e9ecef;
    transform: translateX(5px);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .success-circle {
        width: 80px !important;
        height: 80px !important;
    }
    
    .success-circle i {
        font-size: 2rem !important;
    }
    
    .order-product-item {
        flex-direction: column;
        text-align: center;
    }
    
    .product-image {
        margin-bottom: 1rem;
    }
    
    .product-total {
        margin-top: 1rem;
    }
}
</style>
@endsection
