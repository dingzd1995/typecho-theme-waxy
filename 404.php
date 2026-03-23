<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
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
                <div class="page-404__progress-wrap">
                    <div class="page-404__progress-bar" id="page-404-bar"></div>
                </div>
                <p class="page-404__countdown"><span id="time">10</span> 秒后自动跳转首页</p>
            </div>
        </div>
    </div>
</section>
<!--end 404 page-->

<?php $this->need('footer.php'); ?>

<script>
(function() {
    var total = 10, remaining = total;
    var timeEl = document.getElementById('time');
    var barEl  = document.getElementById('page-404-bar');

    function tick() {
        if (timeEl) timeEl.textContent = remaining;
        if (barEl)  barEl.style.width = ((total - remaining) / total * 100) + '%';
        if (remaining <= 0) { window.location.href = '/'; return; }
        remaining--;
        setTimeout(tick, 1000);
    }

    document.addEventListener('DOMContentLoaded', tick);
})();
</script>
