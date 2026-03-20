<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <article id="<?php $this->cid() ?>" class="post">
                <header class="post__head">
                    <h1 class="post__title"><?php $this->title() ?></h1>
                    <div class="post__border"></div>
                </header>
                <section class="post-content post__content">
                    <?php echo getContent($this->content); ?>
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
