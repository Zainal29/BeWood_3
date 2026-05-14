@extends('layouts.app')

@section('title', 'Tentang BeWood - Kami & Perjalanan Kami')

@section('content')
<div class="bg-cream pt-32 pb-20 px-6 lg:px-14 min-h-screen">
    <div class="max-w-7xl mx-auto">

        {{-- Hero Section --}}
        <div class="text-center mb-16">
            <h1 class="font-serif text-5xl lg:text-6xl text-sage-900 font-light">Tentang <span class="text-sage-500">BeWood</span></h1>
            <p class="text-sage-500 mt-4 max-w-2xl mx-auto">Lebih dari sekadar furnitur — ini adalah warisan, inovasi, dan cinta terhadap kayu Indonesia</p>
            <div class="w-16 h-px bg-sage-300 mx-auto mt-6"></div>
        </div>

        {{-- Visi & Misi --}}
        <div class="grid md:grid-cols-2 gap-10 mb-20">
            <div class="bg-white rounded-2xl shadow-sm p-8 border border-sage-100">
                <div class="w-12 h-12 bg-sage-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                    </svg>
                </div>
                <h2 class="font-serif text-2xl text-sage-800 mb-3">Visi</h2>
                <p class="text-sage-600 leading-relaxed">Menjadi perusahaan furnitur premium yang mendunia dengan mengangkat kekayaan kayu Indonesia, tanpa meninggalkan warisan budaya dan keberlanjutan lingkungan.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-8 border border-sage-100">
                <div class="w-12 h-12 bg-sage-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                    </svg>
                </div>
                <h2 class="font-serif text-2xl text-sage-800 mb-3">Misi</h2>
                <ul class="space-y-2 text-sage-600 leading-relaxed list-disc list-inside">
                    <li>Menghadirkan furnitur berkualitas premium dengan sentuhan pengrajin lokal</li>
                    <li>Mengedepankan praktik bisnis yang berkelanjutan dan ramah lingkungan</li>
                    <li>Memberdayakan komunitas pengrajin kayu Indonesia</li>
                    <li>Mengintegrasikan teknologi modern tanpa meninggalkan nilai tradisional</li>
                </ul>
            </div>
        </div>

        {{-- Profil Pemilik (Founder) --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-px bg-gold"></div>
                <h2 class="font-serif text-3xl text-sage-800">Pendiri & Pemilik</h2>
                <div class="w-12 h-px bg-gold"></div>
            </div>
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-sage-100 rounded-2xl overflow-hidden aspect-square">
                        <img src="{{ asset('images/founder.jpg') }}" alt="Founder BeWood" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/600x600/5A7A5A/FFFFFF?text=Founder'">
                    </div>
                </div>
                <div class="lg:col-span-2">
                    <h3 class="font-serif text-2xl text-sage-800 mb-2">{{ $founderName ?? 'I Gede Putra Wijaya' }}</h3>
                    <p class="text-gold mb-4 font-body text-sm tracking-wide">Founder & Creative Director</p>
                    <div class="prose prose-sage text-sage-600 space-y-4">
                        <p>Lahir dari keluarga pengrajin kayu di Jepara, kecintaan terhadap kayu dan desain telah mengalir sejak kecil. Gelar Sarjana Desain Interior dari Institut Teknologi Bandung (ITB) dan pengalaman bekerja di studio desain internasional di Milan membentuk fondasi estetika yang kuat.</p>
                        <p>Pada tahun 2018, beliau memutuskan untuk pulang ke Indonesia dan mendirikan BeWood — sebuah brand yang menggabungkan keahlian turun-temurun pengrajin Nusantara dengan sentuhan desain kontemporer. Hingga kini, BeWood telah dipercaya oleh lebih dari 7.000 keluarga Indonesia.</p>
                        <p class="italic text-sage-500 border-l-3 border-gold pl-4">"Furniture bukan hanya benda mati, ia adalah cerita yang akan diwariskan dari generasi ke generasi."</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Sejarah Perusahaan --}}
        <div class="mb-20">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-px bg-gold"></div>
                <h2 class="font-serif text-3xl text-sage-800">Perjalanan BeWood</h2>
                <div class="w-12 h-px bg-gold"></div>
            </div>
            <div class="relative">
                <div class="absolute left-1/2 transform -translate-x-1/2 w-px h-full bg-sage-200 hidden md:block"></div>
                <div class="space-y-12">
                    @php
                        $timeline = [
                            ['year' => '2016', 'title' => 'Awal Mula', 'desc' => 'Gagasan awal lahir dari keprihatinan terhadap industri furnitur lokal yang mulai tergerus produk impor. Survei dan riset pasar dimulai.'],
                            ['year' => '2018', 'title' => 'Pendirian BeWood', 'desc' => 'BeWood resmi berdiri di Jepara dengan 5 pengrajin andal. Produk pertama adalah meja kayu jati minimalis.'],
                            ['year' => '2019', 'title' => 'Ekspansi Pasar', 'desc' => 'Mulai memasarkan produk secara online. Jangkauan meluas ke Jabodetabek, Bandung, Surabaya.'],
                            ['year' => '2020', 'title' => 'Digitalisasi', 'desc' => 'Mengadopsi teknologi CAD/CAM untuk presisi produksi. Meluncurkan fitur konsultasi desain online.'],
                            ['year' => '2022', 'title' => 'Go International', 'desc' => 'Mulai mengekspor ke Singapura dan Malaysia. Mendapat penghargaan Top Brand Indonesia.'],
                            ['year' => '2024', 'title' => 'Pengakuan Global', 'desc' => 'Membuka showroom di Jakarta dan Bali. Meraih penghargaan Sustainable Business Award.'],
                            ['year' => '2025', 'title' => 'Masa Depan', 'desc' => 'Meluncurkan koleksi smart furniture dengan teknologi terintegrasi. Siap menyambut era digital.']
                        ];
                    @endphp
                    @foreach($timeline as $index => $item)
                    <div class="relative flex flex-col md:flex-row {{ $index % 2 == 0 ? 'md:flex-row' : 'md:flex-row-reverse' }} items-center gap-6">
                        <div class="md:w-1/2 {{ $index % 2 == 0 ? 'md:text-right' : 'md:text-left' }} px-6">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-sage-100">
                                <span class="text-gold font-bold text-sm">{{ $item['year'] }}</span>
                                <h3 class="font-serif text-xl text-sage-800 mt-1">{{ $item['title'] }}</h3>
                                <p class="text-sage-500 text-sm mt-2">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gold text-white flex items-center justify-center z-10 shadow-md">
                            <span class="text-xs font-bold">{{ $index + 1 }}</span>
                        </div>
                        <div class="md:w-1/2 hidden md:block"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Teknologi yang Digunakan --}}
        <div class="mb-16">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-px bg-gold"></div>
                <h2 class="font-serif text-3xl text-sage-800">Teknologi Modern</h2>
                <div class="w-12 h-px bg-gold"></div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 text-center border border-sage-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-lg text-sage-800 mb-2">CAD/CAM</h3>
                    <p class="text-sage-500 text-sm">Desain dan pemotongan presisi dengan teknologi komputer untuk hasil yang konsisten dan akurat.</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-sage-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 1.5L18 3l1.5 1.5L21 3l-1.5-1.5zM6.75 6.75L5.25 8.25l1.5 1.5 1.5-1.5-1.5-1.5zM12 3.75L10.5 5.25l1.5 1.5 1.5-1.5-1.5-1.5zM16.5 9.75L15 11.25l1.5 1.5 1.5-1.5-1.5-1.5zM12 12.75l-1.5 1.5 1.5 1.5 1.5-1.5-1.5-1.5z" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-lg text-sage-800 mb-2">AI Quality Control</h3>
                    <p class="text-sage-500 text-sm">Sistem kecerdasan buatan untuk mendeteksi cacat kayu sebelum diproses, memastikan kualitas terbaik.</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-sage-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 4.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v7.5a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-lg text-sage-800 mb-2">AR Preview</h3>
                    <p class="text-sage-500 text-sm">Teknologi Augmented Reality untuk melihat furnitur di ruangan Anda sebelum membeli.</p>
                </div>
                <div class="bg-white rounded-xl p-6 text-center border border-sage-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-sage-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253" />
                        </svg>
                    </div>
                    <h3 class="font-serif text-lg text-sage-800 mb-2">SCM Digital</h3>
                    <p class="text-sage-500 text-sm">Manajemen rantai pasok digital yang transparan, dari hutan hingga ke rumah Anda.</p>
                </div>
            </div>
        </div>

        {{-- Call to Action --}}
        <div class="bg-sage-900 rounded-2xl p-10 text-center text-white">
            <h3 class="font-serif text-3xl mb-3">Bergabung dengan Perjalanan Kami</h3>
            <p class="text-sage-200 mb-6 max-w-2xl mx-auto">Jadilah bagian dari keluarga BeWood. Temukan furnitur impian Anda dan rasakan sendiri kualitas terbaik dari kami.</p>
            <a href="{{ route('products') }}" class="inline-block bg-gold hover:bg-gold-light text-sage-900 font-medium px-8 py-3 rounded-full transition">Jelajahi Koleksi</a>
        </div>

    </div>
</div>
@endsection
