<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 友情链接（128x128,转换带图片链接的无序列表）
 *
 * @package custom
 */
?>

<?php
function getFriendsHtml($content) {
    $options = Typecho_Widget::widget('Widget_Options');

    if ($options->shortcode) {
        $content = do_shortcode($content);
    }

    $useLazy = (bool)$options->JQlazyload;
    $placeholder = WAXY_IMG_PLACEHOLDER;

    $content = preg_replace_callback('/\<img([^>]+)>/i', function($m) use ($useLazy, $placeholder) {
        $attrs = $m[1];
        preg_match('/\bsrc\s*=\s*"([^"]*)"/i',   $attrs, $src);
        preg_match('/\balt\s*=\s*"([^"]*)"/i',   $attrs, $alt);
        preg_match('/\btitle\s*=\s*"([^"]*)"/i', $attrs, $title);
        $srcVal   = $src[1]   ?? '';
        $altVal   = $alt[1]   ?? '';
        $titleVal = $title[1] ?? $altVal;
        $imgSrc   = $useLazy
            ? 'data-src="' . htmlspecialchars($srcVal) . '" src="' . $placeholder . '"'
            : 'loading="lazy" src="' . htmlspecialchars($srcVal) . '"';
        return '<div class="friends-img-box"><img ' . $imgSrc . ' alt="' . htmlspecialchars($altVal) . '" title="' . htmlspecialchars($titleVal) . '"></div><span>' . htmlspecialchars($titleVal) . '</span>';
    }, $content);

    return $content;
}
?>
<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <article id="<?php $this->cid() ?>" class="post post--friends">
                <header class="post__head">
                    <h2>友情链接</h2>
                    <div class="page-subtitle">有朋自远方来，不亦乐乎？</div>
                </header>
                <section class="post-content post__content">
                    <?php echo getFriendsHtml($this->content); ?>
                </section>
            </article>
            <div class="about-author">
                <?php $this->need('comments.php'); ?>
            </div>
        </main>
        <?php $this->need('sidebar.php'); ?>
    </div>
</section>

<?php $this->need('footer.php'); ?>
