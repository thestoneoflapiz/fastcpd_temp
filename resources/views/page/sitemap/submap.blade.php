<?='<?xml version="1.0" encoding="UTF-8"?>';?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach($display_urls as $url)
<url>
    <loc><?=$url["link"]?></loc>
    <lastmod><?=$url["timestamp"]?></lastmod>
    <changefreq>daily</changefreq>
    <priority><?=$url["priority"]?></priority>
</url>
@endforeach
</urlset> 