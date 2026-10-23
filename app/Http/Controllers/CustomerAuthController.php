<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isStaff() 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('account');
        }
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isStaff()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('account'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('account');
        }
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|min:7|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $cleanPhone,
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('account')->with('success', 'Account created successfully! Welcome to The Cheddarain.');
    }

    public function account()
    {
        $user = Auth::user();
        
        // Retrieve orders matching customer's email or phone
        $orders = Order::with('items')
            ->where(function ($q) use ($user) {
                $q->where('customer_email', $user->email);
                if ($user->phone) {
                    $q->orWhere('customer_phone', preg_replace('/[^0-9]/', '', $user->phone));
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.auth.account', compact('user', 'orders'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out successfully.');
    }
}
