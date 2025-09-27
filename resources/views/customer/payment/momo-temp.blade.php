@extends('layouts.customer')

@section('title', 'Thanh toán MoMo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Thanh toán MoMo ATM
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Thông tin đơn hàng -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Mã đơn hàng</h6>
                            <p class="fw-bold">{{ $tempOrderData['order_number'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Tổng tiền</h6>
                            <p class="fw-bold text-danger fs-5">{{ number_format($tempOrderData['total_amount']) }} VNĐ</p>
                        </div>
                    </div>

                    <!-- Thông tin giao hàng -->
                    <div class="mb-4">
                        <h6 class="text-muted">Thông tin giao hàng</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-1"><strong>{{ $tempOrderData['shipping_name'] }}</strong></p>
                            <p class="mb-1">{{ $tempOrderData['shipping_phone'] }}</p>
                            <p class="mb-0">{{ $tempOrderData['shipping_address'] }}</p>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="mb-4">
                        <h6 class="text-muted">Sản phẩm đặt hàng</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tempOrderData['cart_items'] as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/products/' . $item['product']->image) }}" 
                                                     alt="{{ $item['product']->name }}" 
                                                     class="me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <span>{{ $item['product']->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>{{ number_format($item['product']->price) }} VNĐ</td>
                                        <td>{{ number_format($item['subtotal']) }} VNĐ</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tổng kết -->
                    <div class="row mb-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($tempOrderData['subtotal']) }} VNĐ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span>{{ number_format($tempOrderData['shipping_fee']) }} VNĐ</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng cộng:</strong>
                                    <strong class="text-danger">{{ number_format($tempOrderData['total_amount']) }} VNĐ</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lưu ý -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Đơn hàng sẽ chỉ được tạo sau khi thanh toán MoMo ATM thành công</li>
                            <li>Bạn sẽ được chuyển đến trang thanh toán ATM của MoMo</li>
                            <li>Nếu thanh toán thất bại, đơn hàng sẽ không được tạo và bạn có thể thử lại</li>
                        </ul>
                    </div>

                    <!-- Nút thanh toán -->
                    <div class="text-center">
                        <form action="{{ route('payment.momo.temp.process') }}" method="POST" id="momo-payment-form">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="momo-pay-btn">
                                <i class="fas fa-credit-card me-2"></i>
                                Thanh toán MoMo ATM
                            </button>
                        </form>
                        
                        <div class="mt-3">
                            <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div id="loading-overlay" class="loading-overlay" style="display: none;">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Đang xử lý thanh toán...</p>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}

.loading-content {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('MoMo ATM temp payment page loaded');
    console.log('Temp order data:', @json($tempOrderData));
    
    const form = document.getElementById('momo-payment-form');
    const payBtn = document.getElementById('momo-pay-btn');
    const loadingOverlay = document.getElementById('loading-overlay');
    
    form.addEventListener('submit', function(e) {
        console.log('MoMo ATM payment form submitted');
        
        // Hiển thị loading
        loadingOverlay.style.display = 'flex';
        payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang chuyển đến MoMo ATM...';
        
        // Không prevent default để form submit bình thường
    });
});
</script>
@endsection
