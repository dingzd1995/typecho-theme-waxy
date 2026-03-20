<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">

            <div class="breadcrumb">
                <span class="breadcrumb__item"><a href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title(); ?>">首页</a></span>
                <?php $this->archiveTitle(array(
                    'category'  => _t('<span class="breadcrumb__item">分类</span><span class="breadcrumb__item breadcrumb__item--active">%s</span>'),
                    'search'    => _t('<span class="breadcrumb__item">搜索</span><span class="breadcrumb__item breadcrumb__item--active">%s</span>'),
                    'tag'       => _t('<span class="breadcrumb__item">标签</span><span class="breadcrumb__item breadcrumb__item--active">%s</span>'),
                    'author'    => _t('<span class="breadcrumb__item">作者</span><span class="breadcrumb__item breadcrumb__item--active">%s</span>')
                ), '', ''); ?>
            </div>

            <?php while ($this->next()): ?>
            <!----全文模式开始----->
            <?php if ($this->options->articles_list == 1): ?>
            <article id="<?php $this->cid() ?>" class="post">

                <?php if ($this->fields->star): ?>
                <div class="featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
                <?php endif; ?>

                <div class="post__head">
                    <h1 class="post__title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
                    <div class="post-meta">
                        <span class="post-meta__author">作者：<a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></span>
                        <time class="post-meta__date" datetime="<?php $this->date('c'); ?>" title="<?php $this->date('Y年m月d日'); ?>">时间：<?php $this->date('Y年m月d日'); ?></time>
                        <span class="post-meta__cat">分类：<?php $this->category(','); ?></span>
                        <span class="post-meta__count">字数：<?php echo art_count($this->cid); ?></span>
                    </div>
                    <div class="post__border"></div>
                </div>

                <?php if ($this->fields->img): ?>
                <div class="featured-media">
                    <a href="<?php $this->permalink() ?>"><img src="<?php $this->fields->img(); ?>" alt="<?php $this->title() ?>"></a>
                </div>
                <?php endif; ?>

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
            <!----摘要模式开始----->
            <?php if ($this->options->articles_list == 0): ?>

            <article id="<?php $this->cid() ?>" class="post">

                <?php if ($this->fields->star): ?>
                <div class="featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
                <?php endif; ?>

                <div class="excerpt">
                <?php if ($this->fields->img): ?>
                    <div class="excerpt__img">
                        <img <?php echo waxy_lazy_img_attrs($this->fields->img); ?> alt="<?php $this->title() ?>" title="<?php $this->title() ?>">
                    </div>
                <?php else: ?>
                    <?php if (getFirstImg($this->content)): ?>
                    <div class="excerpt__img">
                        <img <?php echo waxy_lazy_img_attrs(getFirstImg($this->content)); ?> alt="<?php $this->title() ?>" title="<?php $this->title() ?>">
                    </div>
                    <?php endif; ?>
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
                        <?php if ($this->fields->info) { $this->fields->info(); } else { echo getExcerpt($this->text, 75, ''); } ?>
                        <a href="<?php $this->permalink() ?>" style="white-space:nowrap;"> - 阅读更多 - </a>
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

        </main>

        <?php $this->need('sidebar.php'); ?>

    </div>
</section>

<?php $this->need('footer.php'); ?>
