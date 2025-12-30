<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'alamat' => ['nullable', 'string'],
            'telp' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:100', 'unique:pelanggan,nik'],
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'alamat' => $validated['alamat'] ?? null,
            'telp' => $validated['telp'] ?? null,
        ]);

        if (! empty($validated['nik'])) {
            Pelanggan::create([
                'id_user' => $user->id,
                'nik' => $validated['nik'],
                'alamat' => $validated['alamat'] ?? '',
                'telp' => $validated['telp'] ?? '',
            ]);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('pelanggan');

        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Generate token
        $token = \Str::random(64);

        // Delete old tokens
        \DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->delete();

        // Create new token
        \DB::table('password_reset_tokens')->insert([
            'email' => $validated['email'],
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Send email (simplified - in production use Mail facade)
        // Mail::to($validated['email'])->send(new ResetPasswordMail($token));

        return response()->json([
            'message' => 'Link reset password telah dikirim ke email Anda',
            'token' => $token, // For testing only, remove in production
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Get token from database
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'message' => 'Token reset password tidak valid',
            ], 400);
        }

        // Check if token matches
        if (!Hash::check($validated['token'], $resetRecord->token)) {
            return response()->json([
                'message' => 'Token reset password tidak valid',
            ], 400);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($resetRecord->created_at) > 24) {
            return response()->json([
                'message' => 'Token reset password sudah kadaluarsa',
            ], 400);
        }

        // Update password
        $user = User::where('email', $validated['email'])->first();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Delete token
        \DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->delete();

        return response()->json([
            'message' => 'Password berhasil direset',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'telp' => ['nullable', 'string', 'max:50'],
            'nik' => ['nullable', 'string', 'max:50'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $userData = collect($validated)->only(['nama', 'alamat', 'telp'])->filter()->toArray();
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto);
            }
            $userData['foto'] = $request->file('foto')->store('profiles', 'public');
        }

        $user->update($userData);

        if (!empty($validated['nik']) || !empty($validated['alamat']) || !empty($validated['telp'])) {
            $pelanggan = Pelanggan::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'nik' => $validated['nik'] ?? ($user->pelanggan?->nik ?? ''),
                    'alamat' => $validated['alamat'] ?? ($user->pelanggan?->alamat ?? ''),
                    'telp' => $validated['telp'] ?? ($user->pelanggan?->telp ?? ''),
                ]
            );
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user->load('pelanggan'),
        ]);
    }
}


