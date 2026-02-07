<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani data yang dikirim balik oleh Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user di database berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Jika user sudah ada, update google_id-nya
                $user->update(['google_id' => $googleUser->id]);
            } else {
                // Jika belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null, // Login via Google tidak butuh password manual
                    'role' => 'customer', // Default role
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}