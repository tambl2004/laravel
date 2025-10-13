@extends('layouts.admin')

@section('content')
<!-- Header Section -->
<div class="order-header-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="header-info">
            <h1 class="order-title">
                <i class="fas fa-shopping-cart me-3"></i>
                Chi tiết Đơn hàng #{{ $order->id }}
            </h1>
            <div class="order-meta">
                <span class="order-number">{{ $order->order_number }}</span>
                <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<!-- Main Content Grid -->
<div class="order-content-grid">
    <!-- Left Column - Order Items -->
    <div class="order-items-section">
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-box me-2"></i>
                    Sản phẩm trong đơn hàng
                </h3>
            </div>
            <div class="section-content">
                <div class="order-items-list">
                    @forelse($order->items as $item)
                    <div class="order-item">
                        <div class="item-image">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product_name }}" 
                                     class="product-img">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>
                        <div class="item-details">
                            <h4 class="item-name">{{ $item->product_name }}</h4>
                            @if($item->product)
                                <p class="item-category">{{ $item->product->category->name ?? 'N/A' }}</p>
                            @endif
                        </div>
                        <div class="item-price">
                            <span class="price">{{ number_format($item->product_price) }}₫</span>
                        </div>
                        <div class="item-quantity">
                            <span class="quantity-badge">{{ $item->quantity }}</span>
                        </div>
                        <div class="item-total">
                            <span class="total">{{ number_format($item->subtotal) }}₫</span>
                        </div>
                    </div>
                    @empty
                    <div class="no-items">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Không có sản phẩm nào trong đơn hàng</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span class="label">Tổng tiền sản phẩm:</span>
                        <span class="value">{{ number_format($order->subtotal) }}₫</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Phí vận chuyển:</span>
                        <span class="value {{ $order->shipping_fee > 0 ? '' : 'free' }}">
                            @if($order->shipping_fee > 0)
                                {{ number_format($order->shipping_fee) }}₫
                            @else
                                Shop chịu phí
                            @endif
                        </span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total-row">
                        <span class="label">Tổng cộng:</span>
                        <span class="value total-amount">{{ number_format($order->total_amount) }}₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Order Information -->
    <div class="order-info-section">
        <!-- Customer Information -->
        <div class="info-card">
            <div class="info-header">
                <h3 class="info-title">
                    <i class="fas fa-user me-2"></i>
                    Thông tin khách hàng
                </h3>
            </div>
            <div class="info-content">
                <div class="info-item">
                    <label>Tên khách hàng</label>
                    <span>{{ $order->shipping_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <span>{{ $order->user->email ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Điện thoại</label>
                    <span>{{ $order->shipping_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Địa chỉ</label>
                    <span>{{ $order->shipping_address ?? 'N/A' }}</span>
                    @if($order->shipping_ward_name || $order->shipping_district_name || $order->shipping_province_name)
                        <span class="address-detail">
                            {{ $order->shipping_ward_name ?? '' }}, 
                            {{ $order->shipping_district_name ?? '' }}, 
                            {{ $order->shipping_province_name ?? '' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="info-card">
            <div class="info-header">
                <h3 class="info-title">
                    <i class="fas fa-credit-card me-2"></i>
                    Thông tin thanh toán
                </h3>
            </div>
            <div class="info-content">
                <div class="info-item">
                    <label>Phương thức thanh toán</label>
                    <span class="payment-method">{{ $order->payment_method_text }}</span>
                </div>
                <div class="info-item">
                    <label>Trạng thái thanh toán</label>
                    <span class="payment-status status-{{ $order->payment_status }}">
                        @if($order->payment_status == 'paid')
                            Đã thanh toán
                        @elseif($order->payment_status == 'pending')
                            @if($order->status == 'delivered')
                                Đã hoàn thành (COD)
                            @else
                                Chờ thanh toán
                            @endif
                        @elseif($order->payment_status == 'failed')
                            Thanh toán thất bại
                        @else
                            {{ $order->payment_status }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        @if($order->notes)
        <div class="info-card">
            <div class="info-header">
                <h3 class="info-title">
                    <i class="fas fa-sticky-note me-2"></i>
                    Ghi chú
                </h3>
            </div>
            <div class="info-content">
                <div class="notes-content">
                    {{ $order->notes }}
                </div>
            </div>
        </div>
        @endif

        <!-- Status Update -->
        <div class="info-card status-update-card">
            <div class="info-header">
                <h3 class="info-title">
                    <i class="fas fa-cog me-2"></i>
                    Cập nhật trạng thái
                </h3>
            </div>
            <div class="info-content">
                <div class="current-status">
                    <label>Trạng thái hiện tại:</label>
                    <span class="status-badge status-{{ $order->status }}">
                        @if($order->status == 'pending')
                            Chờ xử lý
                        @elseif($order->status == 'processing')
                            Đang xử lý
                        @elseif($order->status == 'shipped')
                            Đang giao hàng
                        @elseif($order->status == 'delivered')
                            Đã giao hàng
                        @elseif($order->status == 'cancelled')
                            Đã hủy
                        @endif
                    </span>
                </div>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="status-form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="status" class="form-label">Cập nhật trạng thái:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-update">
                        <i class="fas fa-save me-2"></i>Cập nhật trạng thái
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== ORDER DETAIL PAGE STYLES ===== */
:root {
    --primary-color: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1d4ed8;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #0ea5e9;
    --text-dark: #1e293b;
    --text-light: #64748b;
    --text-muted: #94a3b8;
    --border-color: #e2e8f0;
    --bg-light: #f8fafc;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

/* Header Section */
.order-header-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.order-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.order-title i {
    color: var(--primary-color);
    font-size: 1.5rem;
}

.order-meta {
    display: flex;
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.order-number {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}

.order-date {
    color: var(--text-light);
    font-weight: 500;
    font-size: 0.875rem;
}

.btn-back {
    background: var(--bg-light);
    color: var(--text-dark);
    border: 1px solid var(--border-color);
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    text-decoration: none;
}

/* Main Content Grid */
.order-content-grid {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    align-items: flex-start;
}

.order-items-section {
    flex: 2;
    min-width: 0;
}

.order-info-section {
    flex: 1;
    min-width: 300px;
}

/* Order Items Section */
.order-items-section {
    min-height: 600px;
}

.section-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.section-header {
    background: var(--bg-light);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.section-title i {
    color: var(--primary-color);
}

.section-content {
    padding: 2rem;
}

/* Order Items List */
.order-items-list {
    margin-bottom: 2rem;
}

.order-item {
    display: grid;
    grid-template-columns: 80px 1fr auto auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item:hover {
    background: var(--bg-light);
    margin: 0 -2rem;
    padding: 1.5rem 2rem;
    border-radius: 12px;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    background: var(--bg-light);
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    color: var(--text-muted);
    font-size: 1.5rem;
}

.item-details {
    min-width: 0;
}

.item-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.item-category {
    font-size: 0.875rem;
    color: var(--text-light);
    margin: 0;
}

.item-price, .item-total {
    text-align: right;
}

.price, .total {
    font-size: 1rem;
    font-weight: 700;
    color: var(--success-color);
}

.item-quantity {
    text-align: center;
}

.quantity-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}

.no-items {
    text-align: center;
    padding: 3rem 0;
    color: var(--text-muted);
}

.no-items i {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
}

/* Order Summary */
.order-summary {
    background: var(--bg-light);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.summary-row:last-child {
    margin-bottom: 0;
}

.summary-row .label {
    font-weight: 500;
    color: var(--text-light);
}

.summary-row .value {
    font-weight: 600;
    color: var(--text-dark);
}

.summary-row .value.free {
    color: var(--success-color);
}

.summary-divider {
    height: 1px;
    background: var(--border-color);
    margin: 1rem 0;
}

.total-row {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid var(--border-color);
}

.total-row .label {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
}

.total-amount {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--primary-color);
}

/* Order Info Section */
.order-info-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.info-header {
    background: var(--bg-light);
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.info-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
}

.info-title i {
    color: var(--primary-color);
}

.info-content {
    padding: 1.5rem;
}

.info-item {
    margin-bottom: 1rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-item span {
    display: block;
    font-size: 1rem;
    font-weight: 500;
    color: var(--text-dark);
    line-height: 1.5;
}

.address-detail {
    font-size: 0.875rem;
    color: var(--text-light);
    margin-top: 0.25rem;
}

/* Payment Status Styles */
.payment-method {
    background: var(--info-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-block;
}

.payment-status {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-block;
}

.status-paid {
    background: var(--success-color);
    color: white;
}

.status-pending {
    background: var(--success-color);
    color: white;
}

.status-failed {
    background: var(--danger-color);
    color: white;
}

/* Notes Content */
.notes-content {
    background: var(--bg-light);
    padding: 1rem;
    border-radius: 8px;
    font-style: italic;
    color: var(--text-light);
    line-height: 1.6;
}

/* Status Update Card */
.status-update-card {
    border: 2px solid var(--primary-color);
}

.current-status {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-block;
    margin-top: 0.5rem;
}

.status-pending {
    background: var(--success-color);
    color: white;
}

.status-processing {
    background: var(--info-color);
    color: white;
}

.status-shipped {
    background: var(--primary-color);
    color: white;
}

.status-delivered {
    background: var(--success-color);
    color: white;
}

.status-cancelled {
    background: var(--danger-color);
    color: white;
}

.status-form {
    margin-top: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: block;
}

.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    outline: none;
}

.btn-update {
    width: 100%;
    background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
    color: white;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-update:hover {
    background: linear-gradient(135deg, #059669 0%, var(--success-color) 100%);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .order-content-grid {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .order-items-section {
        flex: none;
    }
    
    .order-info-section {
        flex: none;
        min-width: auto;
    }
}

@media (max-width: 1024px) {
    .order-item {
        grid-template-columns: 60px 1fr auto auto auto;
        gap: 0.75rem;
    }
    
    .item-image {
        width: 60px;
        height: 60px;
    }
}

@media (max-width: 768px) {
    .order-header-section {
        padding: 1.5rem;
    }
    
    .order-title {
        font-size: 1.5rem;
    }
    
    .order-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .section-content {
        padding: 1.5rem;
    }
    
    .order-item {
        grid-template-columns: 1fr;
        gap: 1rem;
        text-align: center;
    }
    
    .item-image {
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .item-price, .item-quantity, .item-total {
        text-align: center;
    }
    
    .summary-row {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .info-content {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .order-header-section {
        padding: 1rem;
    }
    
    .section-content {
        padding: 1rem;
    }
    
    .order-summary {
        padding: 1rem;
    }
    
    .info-content {
        padding: 0.75rem;
    }
}
</style>
@endsection