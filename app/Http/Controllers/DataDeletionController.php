<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataDeletionController extends Controller
{
    /**
     * Xử lý yêu cầu xóa dữ liệu cho Facebook Login App
     * Route: GET /delete
     */
    public function delete(Request $request)
    {
        // Log yêu cầu xóa dữ liệu để theo dõi
        Log::info('Data deletion request received', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return response()->json([
            'message' => 'Để xóa dữ liệu cá nhân, vui lòng liên hệ admin qua email: admin@kitchenhood.com',
            'contact_email' => 'admin@kitchenhood.com',
            'instructions' => [
                'Gửi email với tiêu đề: "Yêu cầu xóa dữ liệu cá nhân"',
                'Cung cấp email đăng ký và thông tin liên hệ',
                'Chúng tôi sẽ xử lý trong vòng 30 ngày làm việc'
            ],
            'status' => 'success',
            'timestamp' => now()->toISOString()
        ], 200);
    }

    /**
     * Xử lý yêu cầu xóa dữ liệu chi tiết (GDPR)
     * Route: POST /data-deletion
     */
    public function dataDeletion(Request $request)
    {
        // Validate request data
        $request->validate([
            'user_id' => 'nullable|string',
            'email' => 'nullable|email',
            'reason' => 'nullable|string|max:500'
        ]);

        // Log yêu cầu chi tiết
        Log::info('Detailed data deletion request', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $request->user_id,
            'email' => $request->email,
            'reason' => $request->reason,
            'timestamp' => now()
        ]);

        return response()->json([
            'message' => 'Yêu cầu xóa dữ liệu đã được ghi nhận',
            'instructions' => [
                '1. Gửi email đến admin@kitchenhood.com với tiêu đề "Yêu cầu xóa dữ liệu cá nhân"',
                '2. Cung cấp thông tin: Email đăng ký, Số điện thoại (nếu có)',
                '3. Chúng tôi sẽ xử lý yêu cầu trong vòng 30 ngày làm việc'
            ],
            'contact_email' => 'admin@kitchenhood.com',
            'response_time' => '30 ngày làm việc',
            'request_id' => 'DEL_' . time() . '_' . substr(md5($request->ip()), 0, 8),
            'status' => 'success',
            'timestamp' => now()->toISOString()
        ], 200);
    }

    /**
     * Trang thông tin về chính sách xóa dữ liệu
     * Route: GET /privacy/data-deletion
     */
    public function privacyInfo()
    {
        return response()->json([
            'title' => 'Chính sách xóa dữ liệu cá nhân',
            'description' => 'Thông tin về cách yêu cầu xóa dữ liệu cá nhân khỏi hệ thống',
            'contact' => [
                'email' => 'admin@kitchenhood.com',
                'phone' => '0352135115',
                'address' => '110 Nguyễn Huệ, Bến Nghé, Quận 1, TP.HCM'
            ],
            'process' => [
                '1. Gửi yêu cầu qua email',
                '2. Xác minh danh tính',
                '3. Xử lý yêu cầu trong 30 ngày',
                '4. Xác nhận hoàn thành'
            ],
            'data_types' => [
                'Thông tin tài khoản',
                'Địa chỉ giao hàng',
                'Lịch sử đơn hàng',
                'Dữ liệu thanh toán (đã mã hóa)'
            ],
            'status' => 'success'
        ], 200);
    }
}