<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo một số đơn hàng mẫu
        $orders = [
            [
                'order_number' => 'ORD-20250120-001',
                'user_id' => null, // Đơn hàng guest
                'subtotal' => 2500000,
                'shipping_fee' => 50000,
                'discount_amount' => 0,
                'total_amount' => 2550000,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'shipping_name' => 'Nguyễn Văn An',
                'shipping_phone' => '0123456789',
                'shipping_address' => '123 Đường ABC',
                'shipping_province_id' => 79,
                'shipping_province_name' => 'TP.HCM',
                'shipping_district_id' => 769,
                'shipping_district_name' => 'Quận 1',
                'shipping_ward_id' => 26834,
                'shipping_ward_name' => 'Phường Bến Nghé',
                'notes' => 'Giao hàng vào buổi sáng',
                'status' => 'pending'
            ],
            [
                'order_number' => 'ORD-20250120-002',
                'user_id' => null, // Đơn hàng guest
                'subtotal' => 3500000,
                'shipping_fee' => 0,
                'discount_amount' => 0,
                'total_amount' => 3500000,
                'payment_method' => 'bank_transfer',
                'payment_status' => 'paid',
                'shipping_name' => 'Trần Thị Bình',
                'shipping_phone' => '0987654321',
                'shipping_address' => '456 Đường XYZ',
                'shipping_province_id' => 79,
                'shipping_province_name' => 'TP.HCM',
                'shipping_district_id' => 770,
                'shipping_district_name' => 'Quận 3',
                'shipping_ward_id' => 27022,
                'shipping_ward_name' => 'Phường Võ Thị Sáu',
                'notes' => 'Giao hàng vào buổi chiều',
                'status' => 'processing'
            ]
        ];

        foreach ($orders as $orderData) {
            $order = Order::create($orderData);
            
            // Tạo các mục đơn hàng
            $products = Product::inRandomOrder()->take(rand(1, 3))->get();
            
            foreach ($products as $product) {
                $quantity = rand(1, 3);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ]);
            }
        }
    }
}
