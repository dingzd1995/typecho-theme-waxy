<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php $waxy_post_total = $this->getTotal(); ?>

<?php if ($waxy_post_total == 0): ?>
<div class="empty-state">
    <?php echo waxy_icon('folder-open', 'empty-state__icon'); ?>
    <p class="empty-state__text">这里还没有文章</p>
</div>
<?php else: ?>

<?php while ($this->next()): ?>

<?php
// 用 Typecho 核心的 hidden 判断是否仍锁定（未解锁/非作者/无编辑权限），
// 而不是只看 password 字段是否非空——否则作者本人登录后浏览列表也会被当成锁定
$is_protected = $this->hidden;
?>

<!----全文模式----->
<?php if ($this->options->articles_list == 1): ?>
<article id="<?php $this->cid() ?>" class="post">

    <?php if ($this->fields->star): ?>
    <div class="post__featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
    <?php endif; ?>

    <?php if ($is_protected): ?>

    <header class="post__head post__head--locked">
        <div class="post__lock"><?php echo waxy_icon('lock'); ?></div>
        <h1 class="post__title"><a href="<?php $this->permalink() ?>">加密文章</a></h1>
        <div class="post__border"></div>
    </header>
    <section class="post-content post__content">
        <a class="excerpt__text" href="<?php $this->permalink() ?>">此文章已加密保护，点击输入密码查看。</a>
    </section>

    <?php else: ?>

    <?php $list_img = waxy_resolve_cover_img($this->fields->img, $this->content); ?>
    <div class="post__head<?php echo $list_img ? ' post__head--has-bg' : ''; ?>"
         <?php if ($list_img): ?>style="--post-bg:url('<?php echo $list_img; ?>')"<?php endif; ?>>
        <h1 class="post__title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
        <div class="post-meta">
            <span class="post-meta__author"><?php echo waxy_icon('user'); ?><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></span>
            <time class="post-meta__date" datetime="<?php $this->date('c'); ?>" title="<?php $this->date('Y年m月d日'); ?>"><?php echo waxy_icon('calendar'); ?><?php $this->date('Y年m月d日'); ?></time>
            <?php $waxy_head_cats = (array)$this->categories; ?>
            <?php if ($waxy_head_cats): ?>
            <span class="post-meta__cat">
                <?php echo waxy_render_badge_group($waxy_head_cats, 'folder-open', ' excerpt__badge--cat'); ?>
            </span>
            <?php endif; ?>
            <span class="post-meta__count"><?php echo waxy_icon('toc'); ?><?php echo art_count($this->cid); ?>字</span>
        </div>
        <div class="post__border"></div>
    </div>

    <?php $waxy_full = getIndexContent($this->content); ?>
    <div class="post-content post__content" data-clamp-total="800">
        <?php echo $waxy_full['content']; ?>
    </div>
    <div class="readall"<?php echo $waxy_full['has_more'] ? '' : ' hidden'; ?>>
        <div class="readall__mask"></div>
        <a href="<?php $this->permalink() ?>" class="readall__link">阅读全文</a>
        <?php echo waxy_icon('chevron-down', 'readall__icon'); ?>
    </div>

    <footer class="post__footer">
        <?php $waxy_footer_tags = (array)$this->tags; ?>
        <?php if ($waxy_footer_tags): ?>
        <div class="post__tags">
            <div class="excerpt__badges">
                <?php echo waxy_render_badge_group($waxy_footer_tags, 'tag'); ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="post__permalink">
            <a href="<?php $this->permalink() ?>#comments" class="btn btn--default">前往评论</a>
        </div>
    </footer>

    <?php endif; ?>
</article>
<?php endif; ?>

<!----摘要模式----->
<?php if ($this->options->articles_list == 0): ?>
<?php
if ($is_protected) {
    $exc_img = htmlspecialchars(trim(getFirstImg('')));
} else {
    $exc_img = waxy_resolve_cover_img($this->fields->img, $this->content);
}
?>
<article id="<?php $this->cid() ?>" class="post">

    <?php if ($this->fields->star): ?>
    <div class="post__featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
    <?php endif; ?>

    <div class="excerpt">
        <?php if ($exc_img): ?>
        <div class="excerpt__img" style="background-image:url('<?php echo $exc_img; ?>')"></div>
        <?php endif; ?>
        <div class="excerpt__body">
            <?php if ($is_protected): ?>
            <div class="excerpt__title excerpt__title--locked">
                <?php echo waxy_icon('lock'); ?>
                <a href="<?php $this->permalink() ?>">加密文章</a>
            </div>
            <a class="excerpt__text" href="<?php $this->permalink() ?>">此文章已加密保护，点击输入密码查看。</a>
            <?php else: ?>
            <div class="excerpt__title">
                <a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
            </div>
            <a class="excerpt__text" href="<?php $this->permalink() ?>">
                <?php if ($this->fields->info): ?>
                <?php $info = strip_tags($this->fields->info); echo mb_substr($info, 0, 95, 'UTF-8') . (mb_strlen($info, 'UTF-8') > 95 ? '...' : ''); ?>
                <?php else: ?>
                <?php echo getExcerpt($this->text, 95, '...'); ?>
                <?php endif; ?>
            </a>
            <div class="excerpt__footer">
                <div class="excerpt__info">
                    <div class="excerpt__item"><?php echo waxy_icon('user'); ?><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></div>
                    <div class="excerpt__item"><?php echo waxy_icon('calendar'); ?><?php $this->date('Y-m-d'); ?></div>
                    <?php
                        $waxy_badge_groups = [
                            ['items' => (array)$this->categories, 'icon' => 'folder-open', 'max' => 2, 'modifier' => ' excerpt__badge--cat'],
                            ['items' => (array)$this->tags,       'icon' => 'tag',         'max' => 3, 'modifier' => ''],
                        ];
                    ?>
                    <?php foreach ($waxy_badge_groups as $waxy_group): ?>
                    <?php if (!empty($waxy_group['items'])): ?>
                    <div class="excerpt__badges">
                        <?php echo waxy_render_badge_group($waxy_group['items'], $waxy_group['icon'], $waxy_group['modifier'], $waxy_group['max']); ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <a class="btn btn--default excerpt__more" href="<?php $this->permalink() ?>">阅读全文<?php echo waxy_icon('menu-right'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</article>
<?php endif; ?>

<?php endwhile; ?>

<nav class="pagination" role="navigation">
    <?php $this->pageLink('<span aria-label="Previous" class="pagination__prev">' . waxy_icon('menu-left') . '</span>'); ?>
    <span class="pagination__info">第 <?php echo ($this->_currentPage > 1) ? $this->_currentPage : 1; ?> 页 / 共 <?php echo ceil($waxy_post_total / $this->parameter->pageSize); ?> 页</span>
    <?php $this->pageLink('<span aria-label="Next" class="pagination__next">' . waxy_icon('menu-right') . '</span>', 'next'); ?>
</nav>

<?php endif; ?>
