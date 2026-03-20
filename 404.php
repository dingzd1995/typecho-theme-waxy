<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<!--start 404 page-->
<style>
    .title404 { font-size:160px; color:#F3726D; text-align:center; padding-top:35px; font-weight:normal; }
    .text404 { text-align:center; font-size:40px; color:#F3726D; font-weight:normal; }
    .btn404 { margin-top: 45px; }
    .bottom404 { margin: 35px; }
</style>
<section class="content-wrap">
    <div class="layout">
        <div id="404" style="grid-column: 1 / -1;">
            <h3 class="title404"><?php _e('404'); ?></h3>
            <p class="text404"><?php _e('Not Found'); ?></p>
            <p class="text404 btn404">
                <a href="<?php $this->options->siteUrl(); ?>" class="btn btn--default"><?php _e('返回首页'); ?>（<span id="time"></span>）</a>
            </p>
        </div>
        <div class="bottom404" style="grid-column: 1 / -1;"></div>
    </div>
</section>
<!--end 404 page-->

<?php $this->need('footer.php'); ?>

<script type="text/javascript">
var i = 10;
document.addEventListener('DOMContentLoaded', function() {
    jump();
    after();
});

function jump() {
    setTimeout(function() {
        window.location.href = '/';
    }, i * 1000);
}

function after() {
    var el = document.getElementById('time');
    if (el) el.textContent = i;
    i = i - 1;
    setTimeout(function() {
        after();
    }, 1000);
}
</script>
