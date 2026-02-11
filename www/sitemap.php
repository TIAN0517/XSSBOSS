<?php
/**
 * sitemap.xml generator
 */
header('Content-Type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$urls = array(
    'https://xss.bossjy.ccwu.cc/xss.php',
    'https://xss.bossjy.ccwu.cc/xss.php?do=login',
    'https://xss.bossjy.ccwu.cc/xss.php?do=register',
);

foreach ($urls as $url) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($url) . "</loc>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "    <priority>0.8</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>';
