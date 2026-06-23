<?php
function getCurrentUrl(): string {
    $scheme = 'http';
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') === '443') {
        $scheme = 'https';
    }
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    return htmlspecialchars($scheme . '://' . $host . $uri, ENT_QUOTES, 'UTF-8');
}

function getAbsoluteUrl(string $path): string {
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
    }
    $scheme = 'http';
    if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') === '443') {
        $scheme = 'https';
    }
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $path = '/' . ltrim($path, '/');
    return htmlspecialchars($scheme . '://' . $host . $path, ENT_QUOTES, 'UTF-8');
}

function outputOgMeta(string $title, string $description, string $image = null): void {
    $url = getCurrentUrl();
    $imageUrl = $image ? getAbsoluteUrl($image) : getAbsoluteUrl('images/6df3233a40e81208.png');
    $safeTitle = htmlspecialchars(trim($title), ENT_QUOTES, 'UTF-8');
    $safeDescription = htmlspecialchars(trim(preg_replace('/\s+/', ' ', strip_tags($description))), ENT_QUOTES, 'UTF-8');

    echo "    <meta property=\"og:url\" content=\"{$url}\">\n";
    echo "    <meta property=\"og:type\" content=\"website\">\n";
    echo "    <meta property=\"og:site_name\" content=\"Nnamdi Joseph Mosanya\">\n";
    echo "    <meta property=\"og:title\" content=\"{$safeTitle}\">\n";
    echo "    <meta property=\"og:description\" content=\"{$safeDescription}\">\n";
    echo "    <meta property=\"og:image\" content=\"{$imageUrl}\">\n";
    echo "    <meta name=\"twitter:card\" content=\"summary_large_image\">\n";
}
