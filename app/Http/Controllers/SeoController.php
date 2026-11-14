<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController
{
    public const PAGES = ['about', 'sizes', 'shipping', 'returns', 'privacy', 'contact', 'branches'];

    public function robots(): Response
    {
        $lines = config('store.indexable')
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /cart', 'Disallow: /checkout', 'Disallow: /account', 'Disallow: /favorites', 'Disallow: /orders/', 'Disallow: /login', 'Disallow: /register', 'Disallow: /*?*q=', '', 'Sitemap: '.url('/sitemap.xml')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap(): Response
    {
        $urls = [['loc' => url('/'), 'priority' => '1.0'], ['loc' => url('/shop'), 'priority' => '0.9']];

        foreach (Category::visible()->orderBy('sort')->get() as $category) {
            $urls[] = ['loc' => url('/category/'.$category->slug), 'lastmod' => $category->updated_at, 'priority' => '0.8'];
        }
        foreach (Product::visible()->latest('id')->get() as $product) {
            $urls[] = ['loc' => route('product', $product->slug), 'lastmod' => $product->updated_at, 'priority' => '0.7', 'image' => url('/images/'.$product->image), 'title' => $product->name];
        }
        foreach (self::PAGES as $page) {
            $urls[] = ['loc' => url('/pages/'.$page), 'priority' => '0.4'];
        }

        return response()->view('seo.sitemap', compact('urls'))->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
