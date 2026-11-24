<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $email = $request->query('email', '');
        return view('auth.login', compact('email'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended. Please contact Perlcon support.']);
            }

            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $role = $request->query('role', 'buyer');
        return view('auth.register', compact('role'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:buyer,supplier',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            // Company fields
            'company_name' => 'required|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Company::create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'],
            'slug' => Str::slug($validated['company_name']) . '-' . rand(100, 999),
            'business_type' => $validated['business_type'] ?? ($validated['role'] === 'supplier' ? 'manufacturer' : 'distributor'),
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'tax_id' => $validated['tax_id'] ?? null,
            'is_verified' => false,
        ]);

        Auth::login($user);

        return $this->redirectBasedOnRole($user)->with('success', 'Welcome to Perlcon International B2B Marketplace!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    public function redirectBasedOnRole(User $user)
    {
        switch ($user->role) {
            case 'super_admin':
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'supplier':
                return redirect()->route('supplier.dashboard');
            case 'logistics_partner':
                return redirect()->route('logistics.dashboard');
            case 'customs_partner':
                return redirect()->route('customs.dashboard');
            case 'buyer':
            default:
                return redirect()->route('buyer.dashboard');
        }
    }
}
