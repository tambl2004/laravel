<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        $cartItems = [];
        $total = 0;

        $cartQuery = Cart::query();
        if (Auth::check()) {
            $cart = $cartQuery->firstOrCreate(['user_id' => Auth::id()]);
            $items = CartItem::with('product.category')
                ->where('cart_id', $cart->id)
                ->get();

        foreach ($items as $item) {
            if ($item->product) {
                $lineTotal = $item->product->price * $item->quantity;
                $cartItems[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'subtotal' => $lineTotal,
                    'stock_warning' => $item->quantity > $item->product->quantity, // Cảnh báo tồn kho
                    'out_of_stock' => $item->product->quantity <= 0, // Hết hàng
                ];
                $total += $lineTotal;
            }
        }
        }

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request, $productId)
    {
        $this->authorizeUser();
        $product = Product::findOrFail($productId);

        // Kiểm tra tồn kho
        if ($product->quantity <= 0) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã hết hàng!',
                ], 400);
            }
            return redirect()->back()->with('error', 'Sản phẩm đã hết hàng!');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
        
        $newQuantity = ($item->exists ? $item->quantity : 0) + 1;
        
        // Kiểm tra tổng số lượng trong giỏ không vượt quá tồn kho
        if ($newQuantity > $product->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Chỉ còn {$product->quantity} sản phẩm trong kho!",
                ], 400);
            }
            return redirect()->back()->with('error', "Chỉ còn {$product->quantity} sản phẩm trong kho!");
        }
        
        $item->quantity = $newQuantity;
        $item->save();

        $count = CartItem::where('cart_id', $cart->id)->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
                'cartCount' => $count,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm ' . $product->name . ' vào giỏ hàng!');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $productId)
    {
        $this->authorizeUser();
        $quantity = max(1, (int)$request->input('quantity', 1));

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->with('product')
            ->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        // Kiểm tra tồn kho
        if ($quantity > $item->product->quantity) {
            return response()->json([
                'success' => false, 
                'message' => "Chỉ còn {$item->product->quantity} sản phẩm trong kho!"
            ], 400);
        }

        $item->quantity = $quantity;
        $item->save();

        $count = CartItem::where('cart_id', $cart->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng!',
            'cartCount' => $count,
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove($productId)
    {
        $this->authorizeUser();
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->delete();

        $count = CartItem::where('cart_id', $cart->id)->sum('quantity');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
                'cartCount' => $count,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    /**
     * Lưu danh sách sản phẩm được chọn vào session
     */
    public function saveSelected(Request $request)
    {
        $this->authorizeUser();
        
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'integer|exists:products,id'
        ]);
        
        // Lưu danh sách sản phẩm được chọn vào session
        session(['cart_selected_items' => $request->selected_items]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã lưu sản phẩm được chọn'
        ]);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $this->authorizeUser();
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        CartItem::where('cart_id', $cart->id)->delete();
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ hàng
     */
    public function getCartCount()
    {
        if (!Auth::check()) return 0;
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        return CartItem::where('cart_id', $cart->id)->sum('quantity');
    }

    private function authorizeUser(): void
    {
        if (!Auth::check()) {
            abort(403, 'Bạn cần đăng nhập để sử dụng giỏ hàng');
        }
    }
}
