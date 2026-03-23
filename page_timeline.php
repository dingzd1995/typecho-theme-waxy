<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 时间线（无序列表，不支持二级）
 *
 * @package custom
 */
?>

<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <article id="<?php $this->cid() ?>" class="post post--timeline">
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
