<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Property;
use Illuminate\Support\Facades\Response;

class SeoController extends Controller
{
    /**
     * Sitemap XML — daftar semua halaman publik yang boleh di-index Google:
     * homepage, semua properti aktif, dan semua artikel published.
     * Otomatis update setiap kali diakses (tidak perlu generate manual).
     */
    public function sitemap()
    {
        $urls = collect();

        // Halaman statis
        $urls->push([
            'loc' => route('home'),
            'priority' => '1.0',
            'changefreq' => 'daily',
        ]);
        $urls->push([
            'loc' => route('articles.index'),
            'priority' => '0.8',
            'changefreq' => 'daily',
        ]);

        // Semua properti aktif
        Property::where('status', 'active')->get()->each(function ($property) use ($urls) {
            $urls->push([
                'loc' => route('properties.show', $property),
                'lastmod' => $property->updated_at->toAtomString(),
                'priority' => '0.9',
                'changefreq' => 'weekly',
            ]);
        });

        // Semua artikel published
        Article::published()->get()->each(function ($article) use ($urls) {
            $urls->push([
                'loc' => route('articles.show', $article->slug),
                'lastmod' => $article->updated_at->toAtomString(),
                'priority' => '0.6',
                'changefreq' => 'monthly',
            ]);
        });

        $xml = view('seo.sitemap', ['urls' => $urls])->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }

    /**
     * robots.txt — izinkan index semua halaman publik, larang halaman
     * dashboard/login/register/area privat lainnya.
     */
    public function robots()
    {
        $content = view('seo.robots')->render();

        return Response::make($content, 200, ['Content-Type' => 'text/plain']);
    }
}