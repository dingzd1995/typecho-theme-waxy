<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
/**
 * XML Sitemap（默认）/ 纯文本（?txt=1）
 * 独立页访问地址示例：https://example.com/sitemap.html
 *
 * @package custom
 */

$db      = Typecho_Db::get();
$options = Typecho_Widget::widget('Widget_Options');
$is_txt  = isset($_GET['txt']);
$limit   = 50000;

// 取全部已发布文章和独立页
$posts = $db->fetchAll(
    $db->select()->from('table.contents')
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.type = ? OR table.contents.type = ?', 'post', 'page')
        ->order('table.contents.modified', Typecho_Db::SORT_DESC)
);

$changefreq = 'weekly';
$xml_items  = '';
$txt_items  = '';

foreach ($posts as $p) {
    if ($limit-- <= 0) break;

    $type = $p['type'];
    if ($type === 'post') {
        $p['categories'] = $db->fetchAll(
            $db->select()->from('table.metas')
                ->join('table.relationships', 'table.relationships.mid = table.metas.mid')
                ->where('table.relationships.cid = ?', $p['cid'])
                ->where('table.metas.type = ?', 'category')
                ->order('table.metas.order', Typecho_Db::SORT_ASC)
        );
        $p['category'] = current(Typecho_Common::arrayFlatten($p['categories'], 'slug'));
    }

    $routeExists = (null !== Typecho_Router::get($type));
    if (!$routeExists) continue;

    $pathinfo  = Typecho_Router::url($type, $p);
    $permalink = Typecho_Common::url($pathinfo, $options->index);
    $lastmod   = date('Y-m-d\TH:i:sP', $p['modified'] ?: $p['created']);
    $priority  = $type === 'page' ? '0.6' : '0.8';

    if ($is_txt) {
        $txt_items .= $permalink . "\n";
    } else {
        $xml_items .= "\t<url>\n"
            . "\t\t<loc>" . htmlspecialchars($permalink) . "</loc>\n"
            . "\t\t<lastmod>" . $lastmod . "</lastmod>\n"
            . "\t\t<changefreq>" . $changefreq . "</changefreq>\n"
            . "\t\t<priority>" . $priority . "</priority>\n"
            . "\t</url>\n";
    }
}

if ($is_txt) {
    header('Content-Type: text/plain; charset=UTF-8');
    echo $txt_items;
} else {
    header('Content-Type: application/xml; charset=UTF-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    echo $xml_items;
    echo '</urlset>';
}
