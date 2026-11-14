@php
  $sameAs = array_values(array_filter(config('store.social', [])));
  $organization = array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'RAMASH',
    'alternateName' => 'رماش',
    'url' => url('/'),
    'logo' => url('/apple-touch-icon.png'),
    'sameAs' => $sameAs ?: null,
    'email' => config('store.contact.email') ?: null,
    'telephone' => config('store.contact.phone') ?: null,
  ]);
  $website = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'RAMASH',
    'alternateName' => 'رماش',
    'url' => url('/'),
    'inLanguage' => app()->getLocale() === 'ar' ? 'ar-SA' : 'en',
    'potentialAction' => ['@type' => 'SearchAction', 'target' => ['@type' => 'EntryPoint', 'urlTemplate' => url('/shop').'?q={search_term_string}'], 'query-input' => 'required name=search_term_string'],
  ];
@endphp
<script type="application/ld+json">{!! json_encode($organization, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
<script type="application/ld+json">{!! json_encode($website, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
