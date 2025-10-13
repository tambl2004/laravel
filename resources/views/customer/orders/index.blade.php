@extends('layouts.customer')

@section('title', 'Theo dõi đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <div class="badge bg-primary text-white px-3 py-2 rounded-pill mb-3 fw-bold">
                    <i class="fas fa-shopping-bag me-1"></i>THEO DÕI ĐƠN HÀNG
                </div>
                <h1 class="display-5 fw-bold text-dark mb-3">Lịch Sử Đơn Hàng</h1>
                <p class="lead text-muted">
                    Theo dõi trạng thái và tiến trình giao hàng của các đơn hàng của bạn
                </p>
            </div>

            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary active" data-filter="all">
                            <i class="fas fa-list me-1"></i>Tất cả
                        </button>
                        <button class="btn btn-outline-warning" data-filter="pending">
                            <i class="fas fa-clock me-1"></i>Chờ xử lý
                        </button>
                        <button class="btn btn-outline-orange" data-filter="waiting_payment" style="border-color: #ff6b35; color: #ff6b35;">
                            <i class="fas fa-credit-card me-1"></i>Chờ thanh toán
                        </button>
                        <button class="btn btn-outline-info" data-filter="processing">
                            <i class="fas fa-cog me-1"></i>Đang xử lý
                        </button>
                        <button class="btn btn-outline-primary" data-filter="shipped">
                            <i class="fas fa-shipping-fast me-1"></i>Đang giao
                        </button>
                        <button class="btn btn-outline-success" data-filter="delivered">
                            <i class="fas fa-check-circle me-1"></i>Đã giao
                        </button>
                        <button class="btn btn-outline-danger" data-filter="cancelled">
                            <i class="fas fa-times-circle me-1"></i>Đã hủy
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchOrder" placeholder="Tìm kiếm đơn hàng...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="orders-container">
                @forelse($orders as $order)
                <div class="card order-card mb-4 border-0 shadow-sm" data-status="{{ $order->status }}">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="order-status-badge me-3">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i>Chờ xử lý
                                                </span>
                                                @break
                                            @case('waiting_payment')
                                                <span class="badge bg-orange text-white px-3 py-2" style="background-color: #ff6b35 !important;">
                                                    <i class="fas fa-credit-card me-1"></i>Chờ thanh toán
                                                </span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info text-white px-3 py-2">
                                                    <i class="fas fa-cog me-1"></i>Đang xử lý
                                                </span>
                                                @break
                                            @case('shipped')
                                                <span class="badge bg-primary text-white px-3 py-2">
                                                    <i class="fas fa-shipping-fast me-1"></i>Đang giao
                                                </span>
                                                @break
                                            @case('delivered')
                                                <span class="badge bg-success text-white px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i>Đã giao
                                                </span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger text-white px-3 py-2">
                                                    <i class="fas fa-times-circle me-1"></i>Đã hủy
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary text-white px-3 py-2">
                                                    <i class="fas fa-question me-1"></i>Không xác định
                                                </span>
                                        @endswitch
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Đơn hàng #{{ $order->order_number }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-md-center">
                                <div class="order-total">
                                    <span class="fw-bold text-primary fs-5">{{ number_format($order->total_amount) }} VNĐ</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                <button class="btn btn-outline-secondary btn-sm" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}">
                                    <i class="fas fa-list me-1"></i>Tóm tắt
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details Collapse -->
                    <div class="collapse" id="orderDetails{{ $order->id }}">
                        <div class="card-body border-top bg-light">
                            <!-- Order Items -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-box me-2"></i>Chi tiết sản phẩm
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th class="text-center">Số lượng</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderItems as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/50x50/cccccc/666666?text=Không+có+ảnh' }}" 
                                                                 alt="{{ $item->product->name }}" 
                                                                 class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                   
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>Thông tin giao hàng
                                    </h6>
                                    <div class="bg-white p-3 rounded">
                                        <p class="mb-1 fw-bold">{{ $order->shipping_name }}</p>
                                        <p class="mb-1 text-muted">{{ $order->shipping_phone }}</p>
                                        <p class="mb-0 text-muted">{{ $order->shipping_address }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-receipt me-2"></i>Tổng quan đơn hàng
                                    </h6>
                                    <div class="bg-white p-3 rounded">
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
                                        <hr class="my-2">
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Tổng cộng:</span>
                                            <span class="text-primary">{{ number_format($order->total_amount) }} VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Review Status -->
                            @if($order->status === 'delivered')
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-star me-2"></i>Trạng thái đánh giá
                                    </h6>
                                    <div class="review-status">
                                        @php
                                            $totalProducts = $order->items->count();
                                            $reviewedProducts = 0;
                                            foreach($order->items as $item) {
                                                if($item->product && $item->product->reviews()->where('user_id', auth()->id())->exists()) {
                                                    $reviewedProducts++;
                                                }
                                            }
                                        @endphp
                                        
                                        @if($reviewedProducts === $totalProducts)
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <strong>Đã đánh giá tất cả sản phẩm</strong>
                                                <p class="mb-0 mt-1">Bạn đã hoàn thành đánh giá cho {{ $reviewedProducts }}/{{ $totalProducts }} sản phẩm trong đơn hàng này.</p>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Chưa đánh giá sản phẩm</strong>
                                                <p class="mb-0 mt-1">Bạn đã đánh giá {{ $reviewedProducts }}/{{ $totalProducts }} sản phẩm. 
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none">Đánh giá ngay</a></p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Order Timeline -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-history me-2"></i>Lịch trình đơn hàng
                                    </h6>
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
                                        
                                        @if($order->status != 'pending')
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

                            <!-- Order Actions -->
                            @if(in_array($order->status, ['pending', 'waiting_payment']))
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    @if($order->status == 'waiting_payment' && $order->payment_method == 'momo')
                                        <!-- Nút thanh toán lại cho MoMo -->
                                        <form action="{{ route('payment.momo.pay-again', $order->id) }}" method="POST" class="d-inline me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-mobile-alt me-1"></i>Thanh toán lại MoMo
                                            </button>
                                        </form>
                                        
                                        <!-- Nút hủy đơn hàng -->
                                        <form action="{{ route('payment.momo.cancel-order', $order->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này? Giỏ hàng sẽ được khôi phục.')" 
                                              class="d-inline me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-times me-1"></i>Hủy đơn hàng
                                            </button>
                                        </form>
                                    @elseif($order->status == 'pending')
                                        <!-- Nút hủy đơn hàng cho các phương thức khác -->
                                        <button class="btn btn-danger me-2" onclick="cancelOrder({{ $order->id }})">
                                            <i class="fas fa-times me-1"></i>Hủy đơn hàng
                                        </button>
                                        <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i>Chỉnh sửa đơn hàng
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Bạn chưa có đơn hàng nào</h4>
                        <p class="text-muted mb-4">Hãy mua sắm để có đơn hàng đầu tiên!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Styles cho giao diện đơn hàng -->
<style>
.order-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.order-status-badge .badge {
    border-radius: 20px;
    font-size: 0.9rem;
}

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

.empty-state {
    padding: 40px 20px;
}

.empty-state i {
    opacity: 0.5;
}

/* Filter buttons */
.btn[data-filter] {
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn[data-filter].active {
    transform: scale(1.05);
}

/* Pagination Styles - Giao diện đẹp */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 0;
}

.pagination .page-item {
    list-style: none;
}

.pagination .page-link {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    color: #495057;
    padding: 10px 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    background: white;
    min-width: 45px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.pagination .page-link:hover {
    background: #007bff;
    border-color: #007bff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.25);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border-color: #007bff;
    color: white;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    transform: scale(1.1);
}

.pagination .page-item.disabled .page-link {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination .page-item.disabled .page-link:hover {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    transform: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Previous/Next buttons */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 10px;
    font-weight: 700;
    padding: 10px 20px;
}

.pagination .page-item:first-child .page-link {
    background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
}

.pagination .page-item:last-child .page-link {
    background: linear-gradient(90deg, #ffffff 0%, #f8f9fa 100%);
}

.pagination .page-item:first-child .page-link:hover,
.pagination .page-item:last-child .page-link:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

/* Dots separator */
.pagination .page-item .page-link[aria-label*="..."] {
    border: none;
    background: transparent;
    box-shadow: none;
    cursor: default;
    padding: 10px 8px;
}

.pagination .page-item .page-link[aria-label*="..."]:hover {
    background: transparent;
    transform: none;
    box-shadow: none;
}

/* Pagination wrapper */
.d-flex.justify-content-center {
    padding: 20px 0;
}

/* Responsive */
@media (max-width: 768px) {
    .order-card .card-header .row > div {
        margin-bottom: 10px;
    }
    
    .order-timeline {
        padding-left: 20px;
    }
    
    .timeline-marker {
        left: -17px;
        width: 25px;
        height: 25px;
    }
    
    .pagination .page-link {
        padding: 8px 12px;
        min-width: 38px;
        font-size: 14px;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 8px 14px;
    }
    
    .pagination {
        gap: 4px;
    }
}

@media (max-width: 576px) {
    .pagination .page-link {
        padding: 6px 10px;
        min-width: 35px;
        font-size: 13px;
        border-radius: 8px;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 6px 12px;
    }
    
    /* Ẩn một số số trang trên mobile để gọn hơn */
    .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
        display: none;
    }
}
</style>

<!-- JavaScript cho giao diện đơn hàng -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('[data-filter]');
    const orderCards = document.querySelectorAll('.order-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter orders
            orderCards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (filter === 'all' || status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchOrder');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        orderCards.forEach(card => {
            const orderNumber = card.querySelector('h6').textContent.toLowerCase();
            if (orderNumber.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Function to cancel order
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        // Gửi request hủy đơn hàng
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Không thể hủy đơn hàng: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi hủy đơn hàng');
        });
    }
}
</script>
@endsection
