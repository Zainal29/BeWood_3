@php
    $marqueeItems = App\Models\MarqueeItem::where('is_active', true)->orderBy('order')->get();
@endphp

@if($marqueeItems->count())
<div class="bg-sage-700 text-white py-3 overflow-hidden">
    <div class="marquee-track whitespace-nowrap">
        @foreach($marqueeItems as $index => $item)
            <span class="inline-flex items-center gap-2 mx-10">
                @if($item->icon)
                {!! $item->icon !!}
                @else
                @endif
                {{ $item->text }}
            </span>
            @if($index < $marqueeItems->count() - 1)
            <span class="text-sage-300 mx-6">✦</span>
            @endif
        @endforeach
    </div>
</div>
@endif