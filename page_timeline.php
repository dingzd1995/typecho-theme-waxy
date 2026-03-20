<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 时间线（无序列表，不支持二级）
 *
 * @package custom
 */
?>

<?php $this->need('header.php'); ?>
<style type="text/css">
    .post__content ul::before {
        content: ' ';
        height: 100%;
        width: 0.4em;
        background-color: #F4F4F4;
        position: absolute;
        top: 0;
        left: 1.4em;
    }
    .post__content li {
        display: inline-block;
        margin: 1em 0;
        vertical-align: top;
        background-color: #F4F4F4;
        padding: 1em;
        width: 100%;
        border-radius: 10px;
    }
    .post__content li::before {
        content: ' ';
        width: 1.4em;
        height: 1.4em;
        position: absolute;
        border-radius: 50%;
        left: 0.9em;
        z-index: 1;
        box-sizing: border-box;
        background: #ff837e;
        border: 4px solid #ffffff;
    }
    .post__content li:hover:before {
        background: #F4645F;
    }
    .post__content strong {
        display:block;
        margin-bottom: 0.2em;
        color: #F4645F;
    }
    .post__content strong::before {
        content: " ";
        left: 1.5em;
        width: 1.5em;
        border: solid transparent;
        position: absolute;
        pointer-events: none;
        border-right-color: #F4F4F4;
        border-width: 10px;
    }
</style>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <article id="<?php $this->cid() ?>" class="post">
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
