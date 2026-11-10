@props(['amount'])<span class="price-number">{{ number_format($amount/100, ($amount % 100) ? 2 : 0) }}</span> <span class="currency">{{ __('ر.س') }}</span>
