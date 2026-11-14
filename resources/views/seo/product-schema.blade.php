@php
  $productUrl = route('product', $product->slug);
  $inStock = $product->variants->sum('stock') > 0;
  $schema = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->label,
    'description' => \Illuminate\Support\Str::limit(strip_tags($product->copy), 500),
    'sku' => $product->slug,
    'url' => $productUrl,
    'image' => collect($product->gallery ?: [$product->image])->map(fn ($image) => url('/images/'.$image))->values()->all(),
    'brand' => ['@type' => 'Brand', 'name' => 'RAMASH'],
    'category' => $product->category->label,
  ];
  if ($product->price > 0) {
    $schema['offers'] = [
      '@type' => 'Offer',
      'url' => $productUrl,
      'priceCurrency' => 'SAR',
      'price' => number_format($product->price / 100, 2, '.', ''),
      'availability' => $inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
      'itemCondition' => 'https://schema.org/NewCondition',
      'seller' => ['@type' => 'Organization', 'name' => 'RAMASH'],
    ];
  }
  if ($product->reviews->count()) {
    $schema['aggregateRating'] = ['@type' => 'AggregateRating', 'ratingValue' => round($product->reviews->avg('rating'), 1), 'reviewCount' => $product->reviews->count()];
  }
  $breadcrumbs = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => __('الرئيسية'), 'item' => url('/')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => $product->category->label, 'item' => url('/category/'.$product->category->slug)],
      ['@type' => 'ListItem', 'position' => 3, 'name' => $product->label, 'item' => $productUrl],
    ],
  ];
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
