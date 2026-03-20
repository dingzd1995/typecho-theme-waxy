<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 友情链接（32x32小图，转换带图片链接的无序列表）
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

    $pattern = '/\<img.*?src\=\"(.*?)\".*?alt\=\"(.*?)\".*?title\=\"(.*?)\"[^>]*>/i';
    $lazy = $options->JQlazyload
        ? 'data-src="$1" src="' . WAXY_IMG_PLACEHOLDER . '"'
        : 'loading="lazy" src="$1"';
    $replacement = '<img ' . $lazy . ' alt="$2" title="$3"><span>$3</span>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
?>

<?php $this->need('header.php'); ?>
<style type="text/css">
    .post__head, .post-head { border-bottom: 1px solid #ebebeb; }
    .post__head h2, .post-head h2 { margin: 0 0 5px 0; font-size: 1.6em; text-align: left; }
    .post-content ul, .post__content ul { list-style: none; margin: 0 auto; padding: 0; text-align: center; }
    .post-content ul li, .post__content ul li {
        transition: all .2s ease 0s; display: inline-block; text-align: center;
        border-radius: 10px; border: 1px solid #DEDEDC; margin: 10px;
    }
    .post-content ul li img, .post__content ul li img { margin: 3px 5px; width: 32px; height: 32px; }
    .post-content ul li span, .post__content ul li span { color: #AAA; font-size: 12px; margin: 10px; }
    .post__content ul li:hover { box-shadow: rgba(0,0,0,.2) 0 1px 3px,rgba(157,182,200,.1) 0 1px 20px; }
    .post__content ul li a:hover { text-decoration: none; }
</style>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <article id="<?php $this->cid() ?>" class="post">
                <header class="post__head">
                    <h2>友情链接</h2>
                    <div style="text-align: left;color: #BDBDBD;">有朋自远方来，不亦乐乎？</div>
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
