<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Generate dynamic XML sitemap for search crawlers.
     */
    public function sitemap(): Response
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->latest()->get();

        $content = view('seo.sitemap', compact('categories', 'products'))->render();

        return response($content, 200, [
            'Content-Type' => 'text/xml; charset=UTF-8',
        ]);
    }

    /**
     * Generate robots.txt file with SEO and crawling instructions.
     */
    public function robots(): Response
    {
        $sitemapUrl = route('sitemap');

        $content = "User-agent: *\n"
            ."Disallow: /admin/\n"
            ."Disallow: /checkout/\n"
            ."Disallow: /customer/\n"
            ."Disallow: /cart\n"
            ."Disallow: /wishlist\n"
            ."Disallow: /api/\n"
            ."Allow: /\n\n"
            ."Sitemap: {$sitemapUrl}\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
