@props(['product','minimal'=>false])
<article class="product-card {{ $minimal?'shemagh-card':'' }}" data-category="{{ $product->category->slug }}">
 <div class="product-image">
  <a href="{{ route('product',$product->slug) }}"><img src="/images/{{ $product->image }}" alt="{{ $product->label }}" loading="lazy" width="600" height="760"></a>
  @unless($minimal)
  @if($product->discount)<span class="product-tag sale">−{{ $product->discount }}%</span>@elseif($product->tailored)<span class="product-tag">{{ __('على مقاسك') }}</span>@else<span class="product-tag">{{ __('اختيار رماش') }}</span>@endif
  @endunless
  <form method="post" action="/favorites/{{ $product->id }}" class="favorite-form">@csrf<button class="icon-button favorite {{ in_array($product->id,session('favorites',[]))?'selected':'' }}" aria-label="{{ __('المفضلة') }}: {{ $product->label }}" aria-pressed="{{ in_array($product->id,session('favorites',[]))?'true':'false' }}"><x-icon name="heart" size="19"/></button></form>
  <a class="quick-view" href="{{ route('product',$product->slug) }}">{{ __('اكتشف القطعة') }} <x-icon name="arrow" size="18"/></a>
 </div>
 @unless($minimal)<div class="product-meta"><span>{{ $product->category->label }}</span><span class="swatch" aria-label="{{ __('ألوان المجموعة') }}"></span></div>@endunless
 <h3><a href="{{ route('product',$product->slug) }}">{{ $product->label }}</a></h3>
 <div class="product-price">@if($product->price>0)<x-price :amount="$product->price"/>@else<span>{{ __('السعر قريبًا') }}</span>@endif @if($product->compare_price)<del><x-price :amount="$product->compare_price"/></del>@endif</div>
</article>
