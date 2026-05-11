@extends('layouts.app')

@section('content')
    <div id="landing-view">
        @include('sections.hero', ['heroSettings' => $heroSettings ?? []])
        @include('sections.marquee')
        @include('sections.categories', ['categories' => $featuredCategories ?? []])
        @include('sections.products', ['products' => $products ?? []])
        @include('sections.why-us')
        @include('sections.testimonial', ['testimonials' => $testimonials ?? []])
        @include('sections.instagram', ['posts' => $instagramPosts ?? []])
        @include('sections.cta')
        @include('sections.faq')
    </div>
@endsection