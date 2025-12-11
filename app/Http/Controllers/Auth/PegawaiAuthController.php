<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ref_pegawais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PegawaiAuthController extends Controller
{
    public function showLoginForm()
    {
        $returnTo     = request('return_to');      // boleh kosong
        $requireEmail = request('require_email');  // '1' jika wajib email
        return view('Pegawai.login', compact('returnTo', 'requireEmail'));
    }

    public function login(Request $request)
    {
        // Normalisasi input
        $nip   = trim((string) $request->input('nip'));
        $email = trim(Str::lower((string) $request->input('email')));

        // Validasi dasar (tanpa exists untuk hindari enumeration)
        $rules = [
            'nip' => ['required','string','min:6','max:25'],
        ];
        if ($request->boolean('require_email')) {
            $rules['email'] = ['required','email:rfc'];
        }
        $validated = $request->validate($rules);

        // Rate limiter (10 percobaan per IP+NIP/menit)
        $key = 'pegawai-login:'.sha1($nip).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['login' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."])
                         ->withInput($request->only('nip','email'));
        }

        // Cari pegawai by NIP
        $pegawai = ref_pegawais::where('nip', $nip)->first();

        // Verifikasi (opsional: email)
        $ok = false;
        if ($pegawai) {
            if ($request->boolean('require_email')) {
                $ok = $pegawai->email && Str::lower($pegawai->email) === $email;
            } else {
                $ok = true;
            }
        }

        if (! $ok) {
            RateLimiter::hit($key, 60); // cooldown 60 detik per hit
            // Pesan generik (hindari beda pesan untuk nip/email agar tak bisa enumerate)
            return back()->withErrors(['login' => 'Kredensial tidak valid.'])
                         ->withInput($request->only('nip','email'));
        }

        // Sukses login
        Auth::guard('pegawais')->login($pegawai, true);
        $request->session()->regenerate();
        RateLimiter::clear($key);

        // (Opsional) simpan nama di session
        session(['nama_pegawai' => $pegawai->nama]);

        // Amankan return_to (hanya internal)
        $returnTo = $request->input('return_to');
        if ($returnTo && Str::startsWith($returnTo, url('/'))) {
            return redirect($returnTo);
        }
        if ($returnTo && Str::startsWith($returnTo, '/')) {
            return redirect($returnTo);
        }

        // Pakai intended apabila ada (mis. dari middleware auth)
        return redirect()->intended('/pegawai/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('pegawais')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('Pegawai.login');
    }
}
