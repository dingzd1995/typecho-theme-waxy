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
                ), '<span class="breadcrumb__item breadcrumb__item--active"></span>', ''); ?>
            </div>

            <?php $this->need('post_list.php'); ?>

        </main>

        <?php $this->need('sidebar.php'); ?>

    </div>
</section>

<?php $this->need('footer.php'); ?>
