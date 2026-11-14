@props(['categories' => collect()])
@php
  $social = array_filter([
    'instagram' => ['Instagram', config('store.social.instagram')],
    'x' => ['X', config('store.social.x')],
    'snapchat' => ['Snapchat', config('store.social.snapchat')],
    'tiktok' => ['TikTok', config('store.social.tiktok')],
    'whatsapp' => ['WhatsApp', config('store.social.whatsapp')],
  ]);
  $phone = config('store.contact.phone');
  $email = config('store.contact.email');
@endphp
<footer class="site-footer">
  <div class="container footer-main">
    <div class="footer-about">
      <a class="footer-logo" href="/"><x-brand-logo/></a>
      <h3>{{ __('من نحن') }}</h3>
      <p>{{ __('رماش علامة سعودية للأزياء الرجالية، تجمع الثوب والشماغ والإكسسوارات والعطور في تصاميم هادئة تهتم بالتفاصيل، لتكون حاضرة معك كل يوم.') }}</p>
      <h3>{{ __('تواصل معنا') }}</h3>
      <ul class="footer-contact">
        @if($phone)<li><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" dir="ltr">{{ $phone }}</a></li>@endif
        @if($email)<li><a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
        <li><a href="/pages/contact">{{ __('أرسل لنا رسالة') }} ←</a></li>
      </ul>
      <h3>{{ __('وسائل التواصل') }}</h3>
      <ul class="footer-social">
        @foreach($social as $key => [$label, $url])
          <li>@if($url)<a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}"><x-brand-icon :name="$key"/></a>@else<span aria-label="{{ $label }} — {{ __('قريبًا') }}" title="{{ $label }} — {{ __('قريبًا') }}"><x-brand-icon :name="$key"/></span>@endif</li>
        @endforeach
      </ul>
    </div>

    <details class="footer-col" open>
      <summary>{{ __('روابط سريعة') }}</summary>
      <ul>
        <li><a href="/">{{ __('الصفحة الرئيسية') }}</a></li>
        <li><a href="/shop">{{ __('جميع المنتجات') }}</a></li>
        @foreach($categories->take(4) as $category)<li><a href="/category/{{ $category->slug }}">{{ $category->label }}</a></li>@endforeach
        <li><a href="/shop?sale=1">{{ __('الأسعار الخاصة') }}</a></li>
        <li><a href="/pages/privacy">{{ __('سياسة الاستخدام والخصوصية') }}</a></li>
      </ul>
    </details>

    <details class="footer-col" open>
      <summary>{{ __('خدمات العملاء') }}</summary>
      <ul>
        <li><a href="/pages/about">{{ __('من نحن') }}</a></li>
        <li><a href="/pages/sizes">{{ __('دليل المقاسات') }}</a></li>
        <li><a href="/pages/shipping">{{ __('الشحن والتوصيل') }}</a></li>
        <li><a href="/pages/returns">{{ __('الاستبدال والاسترجاع') }}</a></li>
        <li><a href="/pages/branches">{{ __('الفروع وأخذ المقاس') }}</a></li>
        <li><a href="/pages/contact">{{ __('الدعم الفني') }}</a></li>
        <li><a href="/account">{{ __('حسابي وطلباتي') }}</a></li>
      </ul>
    </details>
  </div>

  <div class="container footer-payments">
    <span class="footer-payments-label">{{ config('store.demo') ? __('طرق الدفع المتاحة عند الإطلاق') : __('طرق الدفع') }}</span>
    <ul aria-label="{{ __('طرق الدفع') }}">
      <li class="pay-badge pay-applepay" title="Apple Pay"><x-brand-icon name="applepay" size="34"/><span class="visually-hidden">Apple Pay</span></li>
      <li class="pay-badge pay-mada" title="mada"><span class="mada-mark" aria-hidden="true"><i></i><i></i></span><span>mada</span></li>
      <li class="pay-badge pay-visa" title="Visa"><x-brand-icon name="visa" size="38"/><span class="visually-hidden">Visa</span></li>
      <li class="pay-badge pay-mastercard" title="Mastercard"><span class="mc-mark" aria-hidden="true"><i></i><i></i></span><span class="visually-hidden">Mastercard</span></li>
      <li class="pay-badge pay-tamara" title="Tamara"><span>tamara</span></li>
    </ul>
  </div>

  <div class="container footer-bottom">
    <span>© {{ date('Y') }} RAMASH. {{ __('جميع الحقوق محفوظة') }}</span>
    <form method="post" action="/locale">@csrf<input type="hidden" name="locale" value="{{ app()->getLocale()==='ar'?'en':'ar' }}"><button class="language">{{ app()->getLocale()==='ar'?'English':'العربية' }}</button></form>
    @if(config('store.demo'))<span class="demo-tag">{{ __('متجر تجريبي — لا يتم تحصيل مبالغ') }}</span>@endif
  </div>
</footer>
