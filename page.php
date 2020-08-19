<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="container">
        <div class="row">
            <main class="col-md-8 main-content">
                <article id="<?php $this->cid() ?>" class="post">
                    <header class="post-head">
                        <h1 class="post-title"><?php $this->title() ?></h1>
                        <section class="post-meta"></section>
                    <div class="post-border"></div>
                    </header>

                    <section class="post-content">
                        <!--?php $this->content(); ?-->
                        <?php echo getContent($this->content); ?>
                    </section>
                    <footer class="post-footer clearfix"></footer>
                </article>
                <div class="about-author clearfix">
                    <?php $this->need('comments.php'); ?>
                </div>
            </main>
            <?php $this->need('sidebar.php'); ?>
        </div class="row">
    </div class="container">
</section>

<?php $this->need('footer.php'); ?>
