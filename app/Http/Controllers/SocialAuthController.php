<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Throwable;

class SocialAuthController extends Controller
{
    // GOOGLE
    public function redirectToGoogle()
    {
        return app(\Laravel\Socialite\Contracts\Factory::class)
            ->driver('google')
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        // Chuẩn bị HTTP client cho Socialite, fix lỗi SSL cURL 60 ở môi trường local (Windows/MAMP)
        $httpOptions = [];
        if (app()->environment('local')) {
            // Cảnh báo: chỉ vô hiệu hóa verify trong môi trường local để debug
            $httpOptions['verify'] = false;
        }

        $provider = app(\Laravel\Socialite\Contracts\Factory::class)->driver('google');
        if (!empty($httpOptions)) {
            $provider->setHttpClient(new Client($httpOptions));
        }

        try {
            $googleUser = $provider->user();
        } catch (Throwable $e) {
            // Một số môi trường (proxy, thiếu session) gây ra lỗi InvalidState

            try {
                $providerStateless = app(\Laravel\Socialite\Contracts\Factory::class)->driver('google')->stateless();
                if (!empty($httpOptions)) {
                    $providerStateless->setHttpClient(new Client($httpOptions));
                }
                $googleUser = $providerStateless->user();
            } catch (Throwable $e2) {
                return redirect()->route('login')->with('warning', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
            }
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: ($googleUser->user['given_name'] ?? 'Người dùng'),
                'email' => $googleUser->getEmail(),
                // Băm mật khẩu ngẫu nhiên để an toàn hơn dù không dùng tới
                'password' => bcrypt(Str::random(32)),
                'role' => 'customer',
            ]);
        } else {
            if ($googleUser->getName()) {
                $user->name = $googleUser->getName();
            }
        }

        // Google: mặc định xác minh luôn
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user, true);

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->intended(route('home'));
    }

    // FACEBOOK
    public function redirectToFacebook()
    {
        return app(\Laravel\Socialite\Contracts\Factory::class)
            ->driver('facebook')
            ->scopes(['public_profile']) // Chỉ yêu cầu thông tin công khai, không yêu cầu email
            ->redirect();
    }

    public function handleFacebookCallback()
    {
        // Chuẩn bị HTTP client cho Socialite, fix lỗi SSL cURL 60 ở môi trường local
        $httpOptions = [];
        if (app()->environment('local')) {
            $httpOptions['verify'] = false;
        }

        $provider = app(\Laravel\Socialite\Contracts\Factory::class)->driver('facebook');
        if (!empty($httpOptions)) {
            $provider->setHttpClient(new Client($httpOptions));
        }

        try {
            $fbUser = $provider->user();
        } catch (Throwable $e) {
            // Log lỗi để debug
            \Log::error('Facebook OAuth error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            try {
                // Thử với stateless mode
                $providerStateless = app(\Laravel\Socialite\Contracts\Factory::class)->driver('facebook')->stateless();
                if (!empty($httpOptions)) {
                    $providerStateless->setHttpClient(new Client($httpOptions));
                }
                $fbUser = $providerStateless->user();
            } catch (Throwable $e2) {
                \Log::error('Facebook OAuth stateless error: ' . $e2->getMessage());
                return redirect()->route('login')->with('warning', 'Đăng nhập Facebook thất bại. Vui lòng thử lại.');
            }
        }

        $facebookId = $fbUser->getId();
        
        // Facebook có thể không trả về email nếu scope không được cấu hình đúng
        $email = null;
        try {
            $email = $fbUser->getEmail();
        } catch (Exception $e) {
            \Log::info('Facebook email not available', ['error' => $e->getMessage()]);
        }

        // Log thông tin Facebook user để debug
        \Log::info('Facebook login attempt', [
            'facebook_id' => $facebookId,
            'email' => $email,
            'name' => $fbUser->getName(),
            'avatar' => method_exists($fbUser, 'getAvatar') ? $fbUser->getAvatar() : null
        ]);

        // Tìm user theo Facebook ID trước, sau đó mới tìm theo email
        $user = User::where('facebook_id', $facebookId)->first();
        
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            // Tạo user mới với email giả định duy nhất
            $generatedEmail = sprintf('%s@facebook.local', $facebookId);
            $user = User::create([
                'name' => $fbUser->getName() ?: 'Người dùng Facebook',
                'email' => $generatedEmail,
                'password' => bcrypt(Str::random(32)), // Băm password để an toàn
                'role' => 'customer',
                'facebook_id' => $facebookId,
                'email_verified_at' => null, // Không verify email giả định
            ]);

            \Log::info('Created new Facebook user', [
                'user_id' => $user->id,
                'facebook_id' => $facebookId,
                'email' => $user->email,
                'has_real_email' => $email ? true : false
            ]);
        } else {
            // Cập nhật thông tin user hiện có
            if ($fbUser->getName()) {
                $user->name = $fbUser->getName();
            }
            
            // Nếu user chưa có Facebook ID, cập nhật nó
            if (!$user->facebook_id) {
                $user->facebook_id = $facebookId;
            }

            // Nếu có email thật từ Facebook và user chưa verify email, thì verify
            if ($email && $email !== $user->email && !$user->email_verified_at) {
                // Cập nhật email thật nếu user đang dùng email giả định
                if (str_ends_with($user->email, '@facebook.local')) {
                    $user->email = $email;
                    $user->email_verified_at = now();
                }
            }

            $user->save();

            \Log::info('Updated existing user with Facebook login', [
                'user_id' => $user->id,
                'facebook_id' => $facebookId,
                'email_updated' => $email ? true : false
            ]);
        }

        Auth::login($user, true);

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->intended(route('home'));
    }
}   