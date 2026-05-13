@extends('layouts.app')

@section('title', 'FAQ - BeWood')

@section('content')
<div class="pt-32 pb-20 px-6 lg:px-14 bg-cream min-h-screen">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-serif text-4xl text-sage-900 font-light mb-8">Pertanyaan Umum</h1>
        
        <div class="space-y-4">
            @forelse($faqs as $faq)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-serif text-xl text-sage-800 font-medium mb-3">{{ $faq->question }}</h3>
                <p class="text-sage-600 leading-relaxed">{{ $faq->answer }}</p>
            </div>
            @empty
                <p class="text-sage-500">Belum ada FAQ.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection