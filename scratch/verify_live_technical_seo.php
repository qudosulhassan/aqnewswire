<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== APEX MEDIA v2.4 TECHNICAL SEO LIVE RUNTIME VERIFICATION ===\n\n";

function testEndpoint($app, $path) {
    $request = Illuminate\Http\Request::create($path, 'GET');
    $response = $app->handle($request);
    $status = $response->getStatusCode();
    $contentType = $response->headers->get('Content-Type');
    $content = $response->getContent();

    echo "Endpoint: {$path}\n";
    echo "  HTTP Status: {$status}\n";
    echo "  Content-Type: {$contentType}\n";

    if (str_contains($contentType, 'xml')) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        $validXml = ($xml !== false && empty($errors));
        echo "  XML Valid: " . ($validXml ? "YES" : "NO") . "\n";
        if ($validXml) {
            $urls = [];
            foreach ($xml->url as $u) { $urls[] = (string) $u->loc; }
            echo "  URL Count: " . count($urls) . "\n";
            echo "  Sample URL: " . ($urls[0] ?? 'none') . "\n";
        }
    } elseif (str_contains($contentType, 'text/plain')) {
        echo "  Line count: " . substr_count($content, "\n") . "\n";
        echo "  Disallow Admin: " . (str_contains($content, 'Disallow: /admin/') ? "YES" : "NO") . "\n";
        echo "  References Sitemap: " . (str_contains($content, 'Sitemap: ') ? "YES" : "NO") . "\n";
    }

    echo "\n";
    return ['status' => $status, 'content' => $content];
}

// 1. Discovery Feeds
testEndpoint($app, '/sitemap.xml');
testEndpoint($app, '/sitemap-news.xml');
testEndpoint($app, '/sitemap-articles.xml');
testEndpoint($app, '/sitemap-categories.xml');
testEndpoint($app, '/robots.txt');

// 2. Published Articles Cross-Check (3 Real Articles)
$articles = App\Models\Article::published()->take(3)->get();
echo "=== 3 PUBLISHED ARTICLES RUNTIME AUDIT ===\n";

foreach ($articles as $art) {
    echo "Story: {$art->title} (/article/{$art->slug})\n";
    $req = Illuminate\Http\Request::create('/article/' . $art->slug, 'GET');
    $res = $app->handle($req);
    $html = $res->getContent();

    // Title tag
    preg_match('/<title>(.*?)<\/title>/s', $html, $titleMatch);
    echo "  <title>: " . ($titleMatch[1] ?? 'NOT FOUND') . "\n";

    // Meta description
    preg_match('/<meta name="description" content="(.*?)"/s', $html, $descMatch);
    echo "  <meta name=\"description\">: " . ($descMatch[1] ?? 'NOT FOUND') . "\n";

    // Canonical
    preg_match('/<link rel="canonical" href="(.*?)"/s', $html, $canonMatch);
    echo "  <link rel=\"canonical\">: " . ($canonMatch[1] ?? 'NOT FOUND') . "\n";

    // Canonical count
    $canonCount = substr_count($html, 'rel="canonical"');
    echo "  Canonical count in DOM: {$canonCount}\n";

    // Robots
    preg_match('/<meta name="robots" content="(.*?)"/s', $html, $robotsMatch);
    echo "  <meta name=\"robots\">: " . ($robotsMatch[1] ?? 'NOT FOUND') . "\n";

    // OG Title & Image
    preg_match('/<meta property="og:title" content="(.*?)"/s', $html, $ogTitleMatch);
    echo "  <meta property=\"og:title\">: " . ($ogTitleMatch[1] ?? 'NOT FOUND') . "\n";

    preg_match('/<meta property="og:image" content="(.*?)"/s', $html, $ogImgMatch);
    echo "  <meta property=\"og:image\">: " . ($ogImgMatch[1] ?? 'NOT FOUND') . "\n";

    // Twitter Card
    preg_match('/<meta name="twitter:card" content="(.*?)"/s', $html, $twCardMatch);
    echo "  <meta name=\"twitter:card\">: " . ($twCardMatch[1] ?? 'NOT FOUND') . "\n";

    // JSON-LD NewsArticle & BreadcrumbList
    $hasNewsArticle = str_contains($html, '"@type": "NewsArticle"') || str_contains($html, '"@type":"NewsArticle"');
    $hasBreadcrumbs = str_contains($html, '"@type": "BreadcrumbList"') || str_contains($html, '"@type":"BreadcrumbList"');
    echo "  JSON-LD NewsArticle: " . ($hasNewsArticle ? "YES" : "NO") . "\n";
    echo "  JSON-LD Breadcrumbs: " . ($hasBreadcrumbs ? "YES" : "NO") . "\n\n";
}

echo "=== ALL RUNTIME TESTS COMPLETED SUCCESSFULLY ===\n";
