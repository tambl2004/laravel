@extends('layouts.customer')

@section('content')
<style>
/* ===== CART STYLES ===== */
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
}

/* Container */
.cart-container {
    background: var(--bg-light);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Header */
.cart-header {
    background: white;
    border-radius: 20px;
    padding: 3rem 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.cart-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
}

.cart-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.cart-title i {
    color: var(--primary-color);
    font-size: 2rem;
}

.cart-subtitle {
    color: var(--text-light);
    font-size: 1.1rem;
    margin: 0;
}

/* Cart Items */
.cart-items-section {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title i {
    color: var(--primary-color);
}

/* Product Selection */
.product-select {
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-check {
    margin: 0;
}

.form-check-input {
    width: 20px;
    height: 20px;
    border: 2px solid var(--primary-color);
    border-radius: 4px;
    background-color: white;
    cursor: pointer;
    position: relative;
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-input:checked::after {
    content: '';
    position: absolute;
    left: 6px;
    top: 2px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.form-check-label {
    display: none;
}

/* Select All Button */
.select-all-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 1rem;
    background: var(--bg-light);
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.select-all-btn {
    background: var(--info-color);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.select-all-btn:hover {
    background: #0284c7;
    transform: translateY(-1px);
}

.selected-count {
    font-weight: 600;
    color: var(--text-dark);
}

.cart-item {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1.25rem; /* giảm padding để gọn hơn */
    margin-bottom: 1.25rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    display: grid;
    /* Thu hẹp các cột để tránh tràn chữ và bố cục gọn hơn */
    grid-template-columns: 40px 110px 1fr auto auto auto;
    gap: 1rem;
    align-items: center;
    position: relative;
}


.cart-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
    border-radius: 16px 16px 0 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cart-item:hover::before {
    opacity: 1;
}

/* Product Image */
.product-image-container {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    background: var(--bg-light);
}

.product-image {
    width: 110px; /* ảnh nhỏ lại để tránh đè chữ */
    height: 110px;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease;
}

.cart-item:hover .product-image {
    transform: scale(1.05);
}

/* Product Info */
.product-info {
    flex: 1;
    min-width: 0;
}

.product-title {
    font-size: 1rem; /* giảm cỡ để gọn hơn */
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    line-height: 1.4;
    /* Hạn chế số dòng để tránh trồng chéo chữ với các cột bên phải */
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2; /* hiển thị tối đa 2 dòng */
    word-break: break-word;
}

.product-meta {
    color: var(--text-light);
    font-size: 0.85rem; /* nhỏ hơn một chút */
    margin-bottom: 0.75rem;
}

.product-price {
    font-size: 0.95rem; /* giá nhỏ lại để cân đối */
    font-weight: 600;
    color: var(--primary-color);
}

/* Stock warnings */
.stock-warning, .stock-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    padding: 0.5rem;
    border-radius: 8px;
    font-weight: 500;
}

.stock-warning.out-of-stock {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.stock-warning.low-stock {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
}

.stock-info {
    background: #dbeafe;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

/* Quantity Controls */
.quantity-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--bg-light);
    border-radius: 12px;
    padding: 0.25rem;
    border: 1px solid var(--border-color);
}

.quantity-btn {
    width: 32px; /* nút nhỏ lại */
    height: 32px;
    border: none;
    background: white;
    color: var(--primary-color);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.quantity-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.05);
}

.quantity-btn:active {
    transform: scale(0.95);
}

.quantity-input {
    width: 48px; /* input nhỏ lại */
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.95rem; /* chữ nhỏ hơn để không chiếm chỗ */
}

.quantity-input:focus {
    outline: none;
}
.check-out-btn {
    text-align: center;
}
.update-btn {
    background: var(--primary-color);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.update-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

/* Subtotal */
.subtotal-section {
    text-align: center;
    min-width: 120px;
}

.subtotal-display {
    font-size: 1.05rem; /* nhỏ lại để cân đối bố cục */
    font-weight: 700;
    color: var(--success-color);
    margin-bottom: 0.5rem;
}

/* Remove Button */
.remove-section {
    display: flex;
    justify-content: center;
}

.remove-btn {
    width: 40px; /* nhỏ hơn chút để gọn */
    height: 40px;
    background: var(--danger-color);
    border: none;
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.remove-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.remove-btn:active {
    transform: scale(0.95);
}

/* Order Summary */
.order-summary {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 2rem;
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.summary-title i {
    color: var(--success-color);
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    font-weight: 600;
    color: var(--text-dark);
}

.summary-value {
    font-weight: 700;
    color: var(--text-dark);
}

.total-section {
    background: var(--bg-light);
    border-radius: 12px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.total-display {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--success-color);
}

.checkout-btn {
    background: linear-gradient(135deg, var(--success-color), #059669);
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    width: 100%;
    margin-top: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    text-decoration: none; /* Bỏ gạch chân */
}


.checkout-btn:hover {
    background: linear-gradient(135deg, #059669, var(--success-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.security-note {
    text-align: center;
    margin-top: 1rem;
    font-size: 0.875rem;
    color: var(--text-light);
}

.security-note i {
    color: var(--success-color);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.clear-cart-btn {
    background: var(--text-muted);
    border: none;
    color: white;
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    flex: 1;
}

.clear-cart-btn:hover {
    background: var(--text-light);
    transform: translateY(-1px);
}

.continue-shopping-btn {
    background: var(--info-color);
    border: none;
    color: white;
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    flex: 1;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.continue-shopping-btn:hover {
    background: #0284c7;
    transform: translateY(-1px);
    text-decoration: none;
    color: white;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.empty-cart-icon {
    font-size: 5rem;
    color: var(--text-muted);
    margin-bottom: 2rem;
    opacity: 0.6;
}

.empty-cart-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.empty-cart-text {
    color: var(--text-light);
    margin-bottom: 2rem;
    font-size: 1rem;
}

.shop-now-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border: none;
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
}

.shop-now-btn:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
    color: white;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .cart-item {
        grid-template-columns: 100px 1fr auto auto auto; /* thu hẹp trên màn hình md */
        gap: 0.75rem;
    }
    
    .product-image {
        width: 100px;
        height: 100px;
    }
}

@media (max-width: 992px) {
    .cart-item {
        grid-template-columns: 40px 100px 1fr;
        grid-template-rows: auto auto auto;
        gap: 0.75rem; /* khoảng cách nhỏ hơn để tránh chồng chữ */
    }
    
    .cart-item .quantity-section {
        grid-row: 2;
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }
    
    .cart-item .subtotal-section {
        grid-row: 2;
        grid-column: 3;
    }
    
    .cart-item .remove-section {
        grid-row: 3;
        grid-column: 3;
        justify-self: end;
    }
    
    .product-image {
        width: 90px;
        height: 90px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .cart-container {
        padding: 1rem 0;
    }
    
    .cart-header {
        padding: 2rem 1rem;
    }
    
    .cart-title {
        font-size: 2rem;
    }
    
    .cart-items-section {
        padding: 1.5rem;
    }
    
    .order-summary {
        padding: 1.5rem;
    }
    
    .cart-item {
        padding: 1rem;
    }
}

@media (max-width: 576px) {
    .cart-header {
        padding: 1.5rem 1rem;
    }
    
    .cart-title {
        font-size: 1.5rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .cart-items-section {
        padding: 1rem;
    }
    
    .order-summary {
        padding: 1rem;
    }
    
    .cart-item {
        padding: 0.75rem;
    }
    
    .product-image {
        width: 72px;
        height: 72px;
    }
    
    .product-title {
        font-size: 0.95rem;
    }
    
    .quantity-controls {
        gap: 0.25rem;
    }
    
    .quantity-btn {
        width: 30px;
        height: 30px;
    }
    
    .quantity-input {
        width: 44px;
    }
}
</style>

<div class="cart-container">
    <div class="container">
        <!-- Header -->
        <div class="cart-header">
            <h1 class="cart-title">
                <i class="fas fa-shopping-cart"></i>
                Giỏ Hàng
            </h1>
        </div>

        @if(count($cartItems) > 0)
        <div class="row">
            <!-- Danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="cart-items-section">
                    <h4 class="section-title">
                        <i class="fas fa-list"></i>
                        Sản phẩm trong giỏ hàng ({{ count($cartItems) }})
                    </h4>

                    <!-- Chọn tất cả -->
                    <div class="select-all-section">
                        <button type="button" class="select-all-btn" id="select-all-btn">
                            <i class="fas fa-check-square me-1"></i>Chọn tất cả
                        </button>
                        <span class="selected-count">
                            Đã chọn: <span id="selected-count">0</span>/{{ count($cartItems) }} sản phẩm
                        </span>
                    </div>

                    @foreach($cartItems as $item)
                    <div class="cart-item" data-product-id="{{ $item['product']->id }}" data-price="{{ $item['product']->price }}">
                        <!-- Checkbox chọn sản phẩm -->
                        <div class="product-select">
                            <div class="form-check">
                                <input class="form-check-input product-checkbox" type="checkbox" 
                                       value="{{ $item['product']->id }}" 
                                       id="product-{{ $item['product']->id }}">
                                <label class="form-check-label" for="product-{{ $item['product']->id }}">
                                    <i class="fas fa-check"></i>
                                </label>
                            </div>
                        </div>
                        
                        <div class="product-image-container">
                            @if($item['product']->image)
                                <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="product-image">
                            @else
                                <img src="https://via.placeholder.com/140x140/cccccc/666666?text=Không+có+ảnh" alt="{{ $item['product']->name }}" class="product-image">
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-title">{{ $item['product']->name }}</div>
                            <div class="product-meta">{{ $item['product']->category->name ?? 'Không phân loại' }}</div>
                            <span class="product-price">{{ number_format($item['product']->price) }} VNĐ</span>
                            
                            @if($item['out_of_stock'] ?? false)
                                <div class="stock-warning out-of-stock">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Sản phẩm đã hết hàng!</span>
                                </div>
                            @elseif($item['stock_warning'] ?? false)
                                <div class="stock-warning low-stock">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Chỉ còn {{ $item['product']->quantity }} sản phẩm trong kho!</span>
                                </div>
                            @elseif($item['product']->quantity <= 10)
                                <div class="stock-info">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Còn {{ $item['product']->quantity }} sản phẩm</span>
                                </div>
                            @endif
                        </div>
                        <div class="quantity-section">
                            <div class="quantity-controls">
                                <button class="quantity-btn quantity-decrease" type="button"><i class="fas fa-minus"></i></button>
                                <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="99">
                                <button class="quantity-btn quantity-increase" type="button"><i class="fas fa-plus"></i></button>
                            </div>
                            <button class="update-btn update-quantity" type="button"><i class="fas fa-sync-alt me-1"></i>Cập nhật</button>
                        </div>
                        <div class="subtotal-section">
                            <div class="subtotal-display" data-subtotal="{{ $item['subtotal'] }}">{{ number_format($item['subtotal']) }} VNĐ</div>
                        </div>
                        <div class="remove-section">
                            <button class="remove-btn remove-item" type="button" data-product-id="{{ $item['product']->id }}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    @endforeach

                    <!-- Các nút hành động -->
                    <div class="action-buttons">
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="clear-cart-btn" onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                                <i class="fas fa-trash me-2"></i>Xóa toàn bộ giỏ hàng
                            </button>
                        </form>
                        <a href="{{ route('products.index') }}" class="continue-shopping-btn">
                            <i class="fas fa-arrow-left"></i>Tiếp Tục Mua Sắm
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h4 class="summary-title">
                        <i class="fas fa-calculator"></i>
                        Tóm Tắt Đơn Hàng
                    </h4>
                    
                    <div class="summary-item">
                        <span class="summary-label">Tổng tiền sản phẩm:</span>
                        <span class="summary-value" id="subtotal-display">0 VNĐ</span>
                    </div>
                    
                    <div class="summary-item">
                        <span class="summary-label">Phí vận chuyển:</span>
                        <span class="summary-value text-success">Shop chịu phí</span>
                    </div>
                    
                    <div class="total-section">
                        <div class="summary-item">
                            <span class="summary-label">Tổng cộng:</span>
                            <span class="total-display" id="total-display">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="check-out-btn">
                        <button type="button" class="checkout-btn" id="checkout-btn">
                            <i class="fas fa-credit-card me-2"></i>Tiến Hành Thanh Toán
                        </button>
                    </div>
                    <div class="security-note">
                        <i class="fas fa-shield-alt me-1"></i>Thanh toán an toàn
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Giỏ hàng trống -->
        <div class="empty-cart">
            <i class="fas fa-shopping-cart empty-cart-icon"></i>
            <h3 class="empty-cart-title">Giỏ hàng của bạn đang trống</h3>
            <p class="empty-cart-text">Hãy thêm một số sản phẩm vào giỏ hàng để bắt đầu mua sắm!</p>
            <a href="{{ route('products.index') }}" class="shop-now-btn">
                <i class="fas fa-shopping-bag"></i>Mua Sắm Ngay
            </a>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các biến
    let cartItems = document.querySelectorAll('.cart-item');
    let subtotalDisplay = document.getElementById('subtotal-display');
    let totalDisplay = document.getElementById('total-display');
    
    // Hàm tính toán tổng tiền chỉ cho sản phẩm được chọn
    function calculateTotal() {
        let subtotal = 0;
        let selectedCount = 0;
        
        cartItems.forEach(item => {
            const checkbox = item.querySelector('.product-checkbox');
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            const price = parseFloat(item.dataset.price);
            const itemSubtotal = quantity * price;
            
            // Cập nhật subtotal của item
            const subtotalElement = item.querySelector('.subtotal-display');
            subtotalElement.textContent = numberFormat(itemSubtotal) + ' VNĐ';
            subtotalElement.dataset.subtotal = itemSubtotal;
            
            // Chỉ tính tổng cho sản phẩm được chọn
            if (checkbox.checked) {
                subtotal += itemSubtotal;
                selectedCount++;
            }
        });
        
        // Cập nhật hiển thị
        subtotalDisplay.textContent = numberFormat(subtotal) + ' VNĐ';
        totalDisplay.textContent = numberFormat(subtotal) + ' VNĐ';
        
        // Cập nhật số lượng đã chọn
        document.getElementById('selected-count').textContent = selectedCount;
        
        return subtotal;
    }
    
    // Hàm format số tiền
    function numberFormat(number) {
        return new Intl.NumberFormat('vi-VN').format(number);
    }
    
    // Xử lý checkbox chọn sản phẩm
    document.querySelectorAll('.product-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            calculateTotal();
        });
    });
    
    // Xử lý nút chọn tất cả
    document.getElementById('select-all-btn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        
        this.innerHTML = allChecked ? 
            '<i class="fas fa-check-square me-1"></i>Chọn tất cả' : 
            '<i class="fas fa-square me-1"></i>Bỏ chọn tất cả';
            
        calculateTotal();
    });
    
    // Xử lý tăng/giảm số lượng
    cartItems.forEach(item => {
        const decreaseBtn = item.querySelector('.quantity-decrease');
        const increaseBtn = item.querySelector('.quantity-increase');
        const quantityInput = item.querySelector('.quantity-input');
        const subtotalElement = item.querySelector('.subtotal-display');
        
        // Giảm số lượng
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateItemSubtotal();
            }
        });
        
        // Tăng số lượng
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < 99) {
                quantityInput.value = currentValue + 1;
                updateItemSubtotal();
            }
        });
        
        // Thay đổi số lượng bằng input
        quantityInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1) this.value = 1;
            if (value > 99) this.value = 99;
            updateItemSubtotal();
        });
        
        // Cập nhật subtotal của item
        function updateItemSubtotal() {
            const quantity = parseInt(quantityInput.value);
            const price = parseFloat(item.dataset.price);
            const subtotal = quantity * price;
            
            subtotalElement.textContent = numberFormat(subtotal) + ' VNĐ';
            subtotalElement.dataset.subtotal = subtotal;
        }
    });
    
    // Xử lý cập nhật số lượng
    document.querySelectorAll('.update-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
            const item = this.closest('.cart-item');
            const productId = item.dataset.productId;
            const quantity = item.querySelector('.quantity-input').value;
            
            // Hiển thị loading
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang cập nhật...';
            this.disabled = true;
            
            // Gửi request cập nhật
            fetch(`{{ url('/cart/update') }}/${productId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(async response => {
                const text = await response.text();
                try { return JSON.parse(text); } catch (_) { return { success: response.ok, message: text }; }
            })
            .then(data => {
                if (data.success) {
                    // Cập nhật tổng tiền
                    calculateTotal();
                    
                    // Hiển thị thông báo thành công
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Đã cập nhật số lượng sản phẩm',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: error.message || 'Không thể cập nhật số lượng',
                    confirmButtonText: 'Thử lại'
                });
            })
            .finally(() => {
                // Khôi phục nút
                this.innerHTML = '<i class="fas fa-sync-alt me-1"></i>Cập nhật';
                this.disabled = false;
            });
        });
    });
    
    // Xử lý xóa sản phẩm
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const item = this.closest('.cart-item');
            
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiển thị loading
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.disabled = true;
                    
                    // Gửi request xóa
                    fetch(`{{ url('/cart/remove') }}/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(async response => {
                        const text = await response.text();
                        try { return JSON.parse(text); } catch (_) { return { success: response.ok, message: text }; }
                    })
                    .then(data => {
                        if (data.success) {
                            // Xóa item khỏi DOM
                            item.remove();
                            
                            // Cập nhật tổng tiền
                            calculateTotal();
                            
                            // Cập nhật số lượng giỏ hàng
                            const cartCount = document.getElementById('cart-count');
                            if (cartCount) {
                                cartCount.textContent = data.cartCount;
                            }
                            
                            // Kiểm tra nếu giỏ hàng trống
                            if (document.querySelectorAll('.cart-item').length === 0) {
                                location.reload();
                            }
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Đã xóa sản phẩm khỏi giỏ hàng',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            throw new Error(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: error.message || 'Không thể xóa sản phẩm',
                            confirmButtonText: 'Thử lại'
                        });
                        
                        // Khôi phục nút
                        this.innerHTML = '<i class="fas fa-trash"></i>';
                        this.disabled = false;
                    });
                }
            });
        });
    });
    
    // Hàm tiến hành thanh toán
    function proceedToCheckout() {
        const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked'))
            .map(checkbox => checkbox.value);
            
        if (selectedProducts.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn sản phẩm!',
                text: 'Vui lòng chọn ít nhất một sản phẩm để thanh toán',
                confirmButtonText: 'Đồng ý'
            });
            return;
        }
        
        // Kiểm tra tồn kho trước khi checkout
        const stockErrors = [];
        document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
            const cartItem = checkbox.closest('.cart-item');
            const quantityInput = cartItem.querySelector('.quantity-input');
            const quantity = parseInt(quantityInput.value);
            const productId = checkbox.value;
            
            // Kiểm tra nếu có cảnh báo tồn kho
            const outOfStockWarning = cartItem.querySelector('.stock-warning.out-of-stock');
            const lowStockWarning = cartItem.querySelector('.stock-warning.low-stock');
            
            if (outOfStockWarning) {
                const productName = cartItem.querySelector('.product-title').textContent;
                stockErrors.push(`Sản phẩm "${productName}" đã hết hàng!`);
            } else if (lowStockWarning) {
                const productName = cartItem.querySelector('.product-title').textContent;
                const availableStock = lowStockWarning.querySelector('span').textContent.match(/(\d+)/)[1];
                if (quantity > parseInt(availableStock)) {
                    stockErrors.push(`Sản phẩm "${productName}" chỉ còn ${availableStock} sản phẩm trong kho!`);
                }
            }
        });
        
        if (stockErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Không thể thanh toán!',
                html: stockErrors.join('<br>'),
                confirmButtonText: 'Đồng ý'
            });
            return;
        }
        
        // Lưu sản phẩm được chọn vào session thông qua AJAX
        fetch('{{ route("cart.save-selected") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ selected_items: selectedProducts })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Chuyển đến trang checkout
                window.location.href = '{{ route("checkout.index") }}';
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: error.message || 'Không thể lưu sản phẩm được chọn',
                confirmButtonText: 'Thử lại'
            });
        });
    }
    
    // Thêm event listener cho nút checkout
    document.getElementById('checkout-btn').addEventListener('click', proceedToCheckout);
    
    // Khởi tạo tổng tiền
    calculateTotal();
});
</script>
@endsection
