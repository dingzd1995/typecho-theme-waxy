<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php while ($this->next()): ?>

<!----全文模式----->
<?php if ($this->options->articles_list == 1): ?>
<article id="<?php $this->cid() ?>" class="post">

    <?php if ($this->fields->star): ?>
    <div class="featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
    <?php endif; ?>

    <?php
    $list_img = '';
    if ($this->fields->img) {
        $list_img = htmlspecialchars(trim($this->fields->img));
    } elseif (getFirstImg($this->content)) {
        $list_img = htmlspecialchars(trim(getFirstImg($this->content)));
    }
    ?>
    <div class="post__head<?php echo $list_img ? ' post__head--has-bg' : ''; ?>"
         <?php if ($list_img): ?>style="--post-bg:url('<?php echo $list_img; ?>')"<?php endif; ?>>
        <h1 class="post__title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
        <div class="post-meta">
            <span class="post-meta__author">作者：<a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></span>
            <time class="post-meta__date" datetime="<?php $this->date('c'); ?>" title="<?php $this->date('Y年m月d日'); ?>">时间：<?php $this->date('Y年m月d日'); ?></time>
            <span class="post-meta__cat">分类：<?php $this->category(','); ?></span>
            <span class="post-meta__count">字数：<?php echo art_count($this->cid); ?></span>
        </div>
        <div class="post__border"></div>
    </div>

    <div class="post__content">
        <?php echo getIndexContent($this->content, $this->permalink); ?>
    </div>

    <footer class="post__footer">
        <div class="post__tags">
            <?php echo waxy_icon('folder-open'); ?>
            <?php $this->tags(' , ', true, 'none'); ?>
        </div>
        <div class="post__permalink">
            <a href="<?php $this->permalink() ?>#comments" class="btn btn--default">前往评论</a>
        </div>
    </footer>
</article>
<?php endif; ?>

<!----摘要模式----->
<?php if ($this->options->articles_list == 0): ?>
<?php
$exc_img = '';
if ($this->fields->img) {
    $exc_img = htmlspecialchars(trim($this->fields->img));
} elseif (getFirstImg($this->content)) {
    $exc_img = htmlspecialchars(trim(getFirstImg($this->content)));
}
?>
<article id="<?php $this->cid() ?>" class="post">
    <a href="<?php $this->permalink() ?>" class="post__card-link" aria-label="<?php $this->title() ?>"></a>

    <?php if ($this->fields->star): ?>
    <div class="featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
    <?php endif; ?>

    <div class="excerpt<?php echo $exc_img ? ' excerpt--has-img' : ''; ?>">
        <?php if ($exc_img): ?>
        <div class="excerpt__img" style="background-image:url('<?php echo $exc_img; ?>')"></div>
        <?php endif; ?>
        <div class="excerpt__body">
            <div class="excerpt__title">
                <a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
            </div>
            <div class="excerpt__info">
                <div class="excerpt__item"><?php echo waxy_icon('user'); ?><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></div>
                <div class="excerpt__item"><?php echo waxy_icon('calendar'); ?><?php $this->date('Y-m-d'); ?></div>
                <div class="excerpt__item"><?php echo waxy_icon('tag'); ?><?php $this->category(','); ?></div>
            </div>
            <div class="excerpt__text">
                <?php
                if ($this->fields->info) {
                    $info = strip_tags($this->fields->info);
                    echo mb_substr($info, 0, 95, 'UTF-8') . (mb_strlen($info, 'UTF-8') > 95 ? '...' : '');
                } else {
                    echo getExcerpt($this->text, 95, '...');
                }
                ?>
            </div>
        </div>
    </div>
</article>
<?php endif; ?>

<?php endwhile; ?>

<nav class="pagination" role="navigation">
    <?php $this->pageLink('<span aria-label="Previous" class="pagination__prev">' . waxy_icon('menu-left') . '</span>'); ?>
    <span class="pagination__info">第 <?php echo ($this->_currentPage > 1) ? $this->_currentPage : 1; ?> 页 / 共 <?php echo ceil($this->getTotal() / $this->parameter->pageSize); ?> 页</span>
    <?php $this->pageLink('<span aria-label="Next" class="pagination__next">' . waxy_icon('menu-right') . '</span>', 'next'); ?>
</nav>
