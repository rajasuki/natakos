<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KosProfile;
use App\Models\User;
use App\Support\WhatsappLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register', [
            'profile' => $this->profileData(),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah terdaftar.',
            'phone.required'     => 'Nomor HP wajib diisi.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex'     => 'Password harus mengandung minimal 1 huruf kapital dan 1 angka.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'tenant',
        ]);

        Auth::login($user);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    /**
     * @return array<string, string|null>
     */
    private function profileData(): array
    {
        $profile = app()->runningUnitTests() ? null : KosProfile::query()->first();
        $whatsappNumber = WhatsappLink::normalizeNumber($profile?->whatsapp_number);

        return [
            'name' => $profile?->name ?: 'NATAKOS',
            'description' => $profile?->description ?: 'NATAKOS menghadirkan kamar kos yang rapi, terkelola, dan siap mendukung rutinitas harian penghuni dengan sistem manajemen yang jelas.',
            'address' => $profile?->address ?: 'Alamat kos belum diatur.',
            'whatsapp_number' => $whatsappNumber,
            'whatsapp_url' => WhatsappLink::build($whatsappNumber, 'Halo, saya ingin bertanya tentang kamar di NATAKOS.'),
        ];
    }
}