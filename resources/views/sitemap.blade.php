@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($urls as $url)
        <url>
            <loc>{{ $url }}</loc>
            <changefreq>weekly</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach

    @foreach ($productUrls as $product)
        <url>
            <loc>{{ $product['loc'] }}</loc>
            <lastmod>{{ $product['lastmod'] }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
