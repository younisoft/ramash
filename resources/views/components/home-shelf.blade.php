@props(['products', 'id', 'title', 'href', 'image' => null, 'rail' => true, 'pinned' => false])

<section class="home-shelf {{ $pinned && $image ? 'home-shelf-pinned' : '' }}" id="{{ $id }}" aria-labelledby="{{ $id }}-heading">
    @if($image)
        <a class="collection-banner" href="{{ $href }}" aria-label="{{ $title }}" @unless($pinned) data-reveal @endunless>
            <img src="{{ \App\Support\StoreImage::url($image) }}" alt="" loading="lazy">
            <span>{{ $title }}</span>
        </a>
    @endif
    <div class="home-shelf-surface">
      <div class="home-wrap">
        <h2 class="home-title" id="{{ $id }}-heading" data-reveal><span>{{ $title }}</span></h2>
        <div class="home-products {{ $rail ? 'home-rail' : '' }}" @if($rail) data-rail tabindex="0" role="region" aria-labelledby="{{ $id }}-heading" @endif>
            @foreach($products as $product)
                <x-product-card :product="$product" :minimal="true"/>
            @endforeach
        </div>
        <div class="home-shelf-actions">
            <a class="shemagh-explore" href="{{ $href }}">{{ __('عرض الكل') }} <x-icon name="arrow" size="18"/></a>
            @if($rail)
                <div class="rail-controls" data-rail-controls hidden>
                    <button type="button" data-rail-step="-1" aria-label="{{ __('السابق') }}">‹</button>
                    <button type="button" data-rail-step="1" aria-label="{{ __('التالي') }}">›</button>
                </div>
            @endif
        </div>
      </div>
    </div>
</section>
