@extends('layouts.customer')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h2 mb-0 fw-bold text-dark">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Chi Tiết Đơn Hàng
                    </h1>
                    <p class="text-muted mb-0">Đơn hàng #{{ $order->order_number }}</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - Order Items -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-box me-2 text-primary"></i>
                        Chi Tiết Sản Phẩm
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Đánh giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="rounded" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <img src="https://via.placeholder.com/60x60/cccccc/666666?text=Không+có+ảnh" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="rounded" 
                                                         style="width: 60px; height: 60px;">
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $item->product_name }}</h6>
                                                @if($item->product && $item->product->category)
                                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                                @endif
                                                <div class="mt-1">
                                                    <span class="fw-bold text-primary">{{ number_format($item->product_price) }} VNĐ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($order->status === 'delivered')
                                            @php
                                                $hasReviewed = $item->product ? $item->product->reviews()->where('user_id', auth()->id())->exists() : false;
                                            @endphp
                                            
                                            @if($hasReviewed)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Đã đánh giá
                                                </span>
                                            @else
                                                <a href="{{ route('products.show', $item->product->id) }}#review-section" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-star me-1"></i>Đánh giá ngay
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>Chờ giao hàng
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-truck me-2 text-success"></i>
                        Thông Tin Giao Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="shipping-info">
                        <div class="mb-3">
                            <strong>Người nhận:</strong>
                            <p class="mb-0">{{ $order->shipping_name }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Số điện thoại:</strong>
                            <p class="mb-0">{{ $order->shipping_phone }}</p>
                        </div>
                        <div>
                            <strong>Địa chỉ:</strong>
                            <p class="mb-0 text-muted">{{ $order->full_shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Order Information -->
        <div class="col-lg-4">
            <!-- Order Status -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Trạng Thái Đơn Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                    <i class="fas fa-clock me-1"></i>Chờ xử lý
                                </span>
                                @break
                            @case('waiting_payment')
                                <span class="badge bg-orange text-white px-3 py-2 fs-6" style="background-color: #ff6b35 !important;">
                                    <i class="fas fa-credit-card me-1"></i>Chờ thanh toán
                                </span>
                                @break
                            @case('processing')
                                <span class="badge bg-info text-white px-3 py-2 fs-6">
                                    <i class="fas fa-cog me-1"></i>Đang xử lý
                                </span>
                                @break
                            @case('shipped')
                                <span class="badge bg-primary text-white px-3 py-2 fs-6">
                                    <i class="fas fa-shipping-fast me-1"></i>Đang giao hàng
                                </span>
                                @break
                            @case('delivered')
                                <span class="badge bg-success text-white px-3 py-2 fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Đã giao hàng
                                </span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger text-white px-3 py-2 fs-6">
                                    <i class="fas fa-times-circle me-1"></i>Đã hủy
                                </span>
                                @break
                        @endswitch
                    </div>
                    
                    <div class="order-meta">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Ngày đặt:</span>
                            <span class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phương thức:</span>
                            <span class="fw-bold">{{ $order->payment_method_text }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Thanh toán:</span>
                            <span class="fw-bold {{ $order->payment_status === 'paid' ? 'text-success' : 'text-warning' }}">
                                {{ $order->payment_status_text }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- Order Summary -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-calculator me-2 text-success"></i>
                        Tổng Quan Đơn Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="order-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền hàng:</span>
                            <span>{{ number_format($order->subtotal) }} VNĐ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            @if($order->shipping_fee > 0)
                                <span>{{ number_format($order->shipping_fee) }} VNĐ</span>
                            @else
                                <span class="text-success fw-bold">Shop chịu phí</span>
                            @endif
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }} VNĐ</span>
                        </div>
                        @endif
                        <hr class="my-3">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Tổng cộng:</span>
                            <span class="text-primary fs-5">{{ number_format($order->total_amount) }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-info"></i>
                        Lịch Trình Đơn Hàng
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="order-timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Đặt hàng thành công</h6>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($order->status != 'pending' && $order->status != 'waiting_payment')
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Đang xử lý</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($order->status, ['shipped', 'delivered']))
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Đang giao hàng</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'delivered')
                        <div class="timeline-item completed">
                            <div class="timeline-marker">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Giao hàng thành công</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'cancelled')
                        <div class="timeline-item cancelled">
                            <div class="timeline-marker">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Đơn hàng đã hủy</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS tùy chỉnh -->
<style>
.order-timeline {
    position: relative;
    padding-left: 30px;
}

.order-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    color: white;
}

.timeline-item.cancelled .timeline-marker {
    background: #dc3545;
    color: white;
}

.timeline-content {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.timeline-content h6 {
    margin: 0;
    color: #495057;
}

.shipping-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
}

.order-summary {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
}

.order-meta {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
}
</style>
@endsection
