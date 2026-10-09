<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService
    ) {}

    /**
     * Primary XML Sitemap (Sitemaps Protocol 0.9).
     * Exposes eligible, canonical, indexable URLs across all public taxonomies.
     */
    public function sitemap(): Response
    {
        $xml = $this->sitemapService->generateXml('main');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Google News XML Sitemap.
     * Indexable stories published within the trailing 48 hours.
     */
    public function newsSitemap(): Response
    {
        $xml = $this->sitemapService->generateXml('news');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Articles sub-sitemap.
     */
    public function articlesSitemap(): Response
    {
        $xml = $this->sitemapService->generateXml('articles');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Categories sub-sitemap.
     */
    public function categoriesSitemap(): Response
    {
        $xml = $this->sitemapService->generateXml('categories');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Rankings sub-sitemap.
     */
    public function rankingsSitemap(): Response
    {
        $xml = $this->sitemapService->generateXml('rankings');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Authors sub-sitemap.
     */
    public function authorsSitemap(): Response
    {
        $xml = $this->sitemapService->generateXml('authors');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Scalable Sitemap Index XML.
     */
    public function sitemapIndex(): Response
    {
        $xml = $this->sitemapService->generateXml('index');

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Dynamic robots.txt output adhering to RFC 9309.
     * Directs search crawlers to XML discovery feeds while protecting private admin panels.
     */
    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /account/\n";
        $content .= "Disallow: /contributor/\n";
        $content .= "Disallow: /livewire/\n";
        $content .= "Disallow: /horizon/\n";
        $content .= "Disallow: /telescope/\n";
        $content .= "Allow: /\n\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
        $content .= "Sitemap: " . url('/sitemap-news.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
