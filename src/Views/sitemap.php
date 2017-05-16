<?php
/**
 * Default template of sitemap, you can change the class and action to yours
 */

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php
    function siteURL()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }

    $i = 0;
    while ($i < count($data['sitemap'])) {
        $name_short = mb_strtolower($data['sitemap'][$i]['name_short']);
        $path = $data['sitemap'][$i]['path'];

        if ($name_short == 'error' || $name_short == 'sitemap') {
            $i++;
            continue;
        }
        if ($name_short == 'index') $name_short = null; else $name_short = '/' . $name_short;

        $j = 0;
        while ($j < count($data['sitemap'][$i]['methods'])) {

            $method = $data['sitemap'][$i]['methods'][$j];
            if ($method == '__construct') {
                $j++;
                continue;
            }

            $method = str_replace('action_', '', $method);
            if ($method == 'index') $method = null; else $method = '/' . $method;

            echo "<url>\n";
            echo "<loc>" . siteURL() . $name_short . $method . "</loc>\n";
            echo "<lastmod>" . date('Y-m-d\TH:i:s+00:00', filemtime($path)) . "</lastmod>\n";
            echo "<changefreq>monthly</changefreq>\n";
            echo "</url>\n";

            $j++;
        }
        $i++;
    }
    ?>
</urlset>
