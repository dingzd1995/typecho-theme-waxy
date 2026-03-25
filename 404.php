<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $GLOBALS['waxy_is_404'] = true; ?>
<?php $this->need('header.php'); ?>

<!--start 404 page-->
<section class="content-wrap">
    <div class="layout">
        <div class="page-404 col-full">
            <div class="page-404__card">
                <div class="page-404__code" aria-hidden="true">404</div>
                <h2 class="page-404__title"><?php _e('页面不存在'); ?></h2>
                <p class="page-404__msg"><?php _e('你访问的页面已丢失或从未存在过。'); ?></p>
                <div class="page-404__actions">
                    <a href="<?php $this->options->siteUrl(); ?>" class="btn btn--default page-404__btn">
                        <?php _e('返回首页'); ?>
                    </a>
                    <a href="javascript:history.back()" class="btn btn--outline page-404__btn">
                        <?php _e('返回上页'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end 404 page-->

<?php $this->need('footer.php'); ?>
