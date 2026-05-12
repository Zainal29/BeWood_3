@extends('layouts.app')

@section('title', 'Login - BeWood')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-cream">
        <div class="max-w-md w-full bg-white rounded-lg shadow-premium p-8">
            <div class="text-center mb-8">
                <h2 class="font-serif text-3xl font-light text-sage-900">Masuk</h2>
                <p class="text-sage-500 text-sm mt-2">Selamat datang kembali</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sage-700 text-sm font-sans mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2 border border-sage-200 rounded focus:outline-none focus:border-sage-400 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sage-700 text-sm font-sans mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2 border border-sage-200 rounded focus:outline-none focus:border-sage-400">
                </div>
                <button type="submit" class="btn-primary w-full py-3 text-sm font-sans font-semibold">MASUK</button>
            </form>
            <p class="text-center text-sage-500 text-sm mt-6">
                Belum punya akun? <a href="{{ route('register') }}" class="text-sage-700 hover:underline">Daftar</a>
            </p>
        </div>
    </div>
@endsection
