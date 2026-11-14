@props(['product'])

@php($images = array_values(array_unique(array_filter([$product->image, ...($product->gallery ?? [])]))))
<div class="product-gallery" data-product-gallery>
    @if(count($images) > 1)
        <div class="gallery-thumbs" hidden>
            @foreach($images as $image)
                <button type="button" data-gallery-image="{{ $loop->index }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" aria-label="{{ __('عرض الصورة :number', ['number' => $loop->iteration]) }}">
                    <img src="{{ \App\Support\StoreImage::url($image) }}" alt="" loading="lazy" width="72" height="88">
                </button>
            @endforeach
        </div>
    @endif
    <div class="gallery-stage">
        <button class="gallery-main" data-open="zoom" aria-label="{{ __('تكبير صورة المنتج') }}">
            <img src="{{ \App\Support\StoreImage::url($product->image) }}" alt="{{ $product->label }}" fetchpriority="high" data-gallery-main>
            <span>＋ {{ __('تكبير') }}</span>
        </button>
        @if(count($images) > 1)
            <div class="gallery-controls" hidden>
                <button type="button" data-gallery-step="-1" aria-label="{{ __('السابق') }}">‹</button>
                <button type="button" data-gallery-step="1" aria-label="{{ __('التالي') }}">›</button>
            </div>
        @endif
    </div>
</div>
