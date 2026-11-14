@extends('layouts.app')
@section('title',__('رماش | ثياب وأشمغة وأزياء رجالية سعودية'))
@section('canonical', url('/'))
@push('structured-data')@include('seo.site-schema')@endpush

@section('content')
<section class="hero home-hero" data-home-hero>
    <img class="hero-image" src="/images/ramash-hero.webp" alt="{{ __('إطلالة من مجموعة رماش') }}" fetchpriority="high">
    <div class="hero-shade"></div>
    <div class="hero-content home-wrap">
        <h1>{{ __('حضورك،') }}<br>{{ __('في التفاصيل.') }}</h1>
    </div>
    <a class="home-hero-shop" href="/shop">{{ __('اكتشف المجموعة') }}</a>
    <a class="home-scroll" href="#store-start" aria-label="{{ __('انزل لتكتشف') }}"><span aria-hidden="true">⌄</span></a>
</section>

<div id="store-start">
    @if($featured->isNotEmpty())
        <x-home-shelf :products="$featured" id="featured" :title="__('مختارات رماش')" href="/shop" :rail="false"/>
    @endif

    @if($categories->isNotEmpty())
        <section id="collections" class="home-wrap home-section" aria-labelledby="collections-heading">
            <h2 id="collections-heading" class="home-title" data-reveal><span>{{ __('أقسام رماش') }}</span></h2>
            <div class="home-categories">
                @foreach($categories as $category)
                    <a class="home-category" href="/category/{{ $category->slug }}" data-reveal>
                        <img src="/images/{{ $category->image }}" alt="" loading="lazy" width="480" height="600">
                        <span>{{ $category->label }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if($categories->contains('slug', 'thobes'))
        <a class="collection-banner tailoring-banner" href="/category/thobes" data-reveal>
            <img src="/images/tailoring-craft.jpg" alt="" loading="lazy">
            <span>{{ __('اكتشف ثياب التفصيل') }}</span>
        </a>
    @endif

    @php($apparel = $categories->whereIn('slug', ['thobes', 'outerwear', 'casual'])->filter(fn ($category) => $collections[$category->slug]->isNotEmpty()))
    @if($apparel->isNotEmpty())
        <div class="home-collections" data-collection-tabs>
            <nav class="collection-tabs home-wrap" aria-label="{{ __('تشكيلات الملابس') }}">
                @foreach($apparel as $category)
                    <a href="#collection-{{ $category->slug }}">{{ $category->label }}</a>
                @endforeach
            </nav>
            @foreach($apparel as $category)
                <x-home-shelf :products="$collections[$category->slug]" :id="'collection-'.$category->slug" :title="$category->label" :href="route('category', $category->slug)"/>
            @endforeach
        </div>
    @endif

    @foreach(['shemagh', 'perfumes', 'shoes', 'leather'] as $slug)
        @php($category = $categories->firstWhere('slug', $slug))
        @if($category && $collections[$slug]->isNotEmpty())
            <x-home-shelf
                :products="$collections[$slug]"
                :id="$slug === 'shemagh' ? 'shemaghs' : 'collection-'.$slug"
                :title="$category->label"
                :href="route('category', $slug)"
                :image="match($slug) {'shemagh' => 'ramash-shemagh-original-logo.webp', 'perfumes' => null, default => $category->image}"
                :pinned="in_array($slug, ['shemagh', 'leather'])"
            />
        @endif
    @endforeach

    @if($reviews->isNotEmpty())
        <section class="home-wrap home-section" aria-labelledby="reviews-heading">
            <h2 id="reviews-heading" class="home-title" data-reveal><span>{{ __('آراء العملاء') }}</span></h2>
            <div class="home-reviews">
                @foreach($reviews as $review)
                    <article>
                        <strong>{{ $review->user->name }}</strong>
                        <span class="review-stars" aria-label="{{ $review->rating }}/5">{{ str_repeat('★', $review->rating) }}</span>
                        <p>{{ $review->body }}</p>
                        <a class="text-link" href="{{ route('product', $review->product->slug) }}">{{ $review->product->label }}</a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
