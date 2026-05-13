@extends('layouts.app')

@section('title', 'Kontak BeWood')

@section('content')
<div class="pt-32 pb-20 px-6 lg:px-14 bg-cream min-h-screen">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-serif text-4xl text-sage-900 font-light mb-8">Hubungi Kami</h1>
        
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-serif text-xl text-sage-800 mb-4">Informasi Kontak</h3>
                <div class="space-y-4">
                    <p class="flex items-center gap-3 text-sage-600">
                        <svg class="w-5 h-5 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        Jl. Sudirman No. 88, Jakarta Selatan
                    </p>
                    <p class="flex items-center gap-3 text-sage-600">
                        <svg class="w-5 h-5 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        +62 812-3456-7890
                    </p>
                    <p class="flex items-center gap-3 text-sage-600">
                        <svg class="w-5 h-5 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        halo@bewood.id
                    </p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-serif text-xl text-sage-800 mb-4">Kirim Pesan</h3>
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Anda" required class="w-full border border-sage-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-sage-300">
                    <input type="email" name="email" placeholder="Email" required class="w-full border border-sage-200 rounded-lg px-4 py-2">
                    <textarea name="message" placeholder="Pesan" rows="4" required class="w-full border border-sage-200 rounded-lg px-4 py-2"></textarea>
                    <button type="submit" class="btn-primary w-full py-3">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection