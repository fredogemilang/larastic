{!! '<' . '?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($pages as $page)
    <url>
        <loc>{{ rtrim(rtrim($siteUrl, '/') . $page->url, '/') . '/' }}</loc>
        <lastmod>{{ $page->updated_at->toISOString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>{{ $page->slug === 'home' ? '1.0' : '0.8' }}</priority>
    </url>
@endforeach
@foreach(['id', 'en'] as $loc)
    <url>
        <loc>{{ rtrim(rtrim($siteUrl, '/') . ($loc === 'en' ? '/en' : '') . '/' . $blogPrefix, '/') . '/' }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
@endforeach
@foreach($posts as $post)
    <url>
        <loc>{{ rtrim(rtrim($siteUrl, '/') . $post->url, '/') . '/' }}</loc>
        <lastmod>{{ $post->updated_at->toISOString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>
