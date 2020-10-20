<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; 
/**
 * 时间线（无序列表，不支持二级）
 *
 * @package custom
 */
 ?>

<?php $this->need('header.php'); ?>
<style type="text/css">
    .post-content ul::before {
        content: ' ';
        height: 100%;
        width: 0.4em;
        background-color: #ebebeb;
        position: absolute;
        top: 0;
        left: 1.4em;
        /*z-index: -1;*/
    }
    .post-content li {
        display: inline-block;
        margin: 1em 0;
        vertical-align: top;
        background-color: #ebebeb;
        padding: 1em;
        width: 100%;
        border-radius: 10px;
    }
    .post-content li::before {
        content: ' ';
        width: 1.4em;
        height: 1.4em;
        position: absolute;
        border-radius: 50%;
        left: 0.9em;
        z-index: 1;
        box-sizing: border-box;
        background: #ff837e;
        border: 4px solid #ffffff
    }
    .post-content strong {
        display:block;
        margin-bottom: 0.2em;
        color: #F4645F;
    }
    .post-content strong::before {
        content: " ";
        left: 1.5em;
        width: 1.5em;
        border: solid transparent;
        position: absolute;
        pointer-events: none;
        border-right-color: #ebebeb;
        border-width: 10px;
        
    }
</style>

<section class="content-wrap">
    <div class="container">
        <div class="row">
            <main class="col-md-8 main-content">
                <article id="<?php $this->cid() ?>" class="post">
                    <section class="post-content">
                        <!--?php $this->content(); ?-->
                        <?php echo getContent($this->content); ?>
                    </section>
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
