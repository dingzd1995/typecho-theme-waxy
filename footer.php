<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer class="site-footer">
    <div class="site-footer__inner">
        <span>Copyright &copy; <?php echo date("Y"); ?> <a href="<?php $this->options->siteUrl(); ?>" target="_blank"><?php $this->options->title(); ?></a></span><br />
        <span>Powered by <a rel="noopener" href="https://typecho.org/" target="_blank">Typecho</a> | Theme by <a rel="noopener" href="https://github.com/dingzd1995/typecho-theme-waxy" target="_blank">Waxy</a><?php add_ICP($this); ?></span><br />
    </div>
</footer>

<a id="back-to-top"><?php echo waxy_icon('menu-up'); ?></a>

<script src="<?php $this->options->themeUrl('js/waxy-main.js'); ?>"></script>

<?php if ($this->options->codeHighlightControl): ?>
<script type="text/javascript">
(function(){
    var pres = document.querySelectorAll('pre');
    var lineNumberClassName = 'line-numbers';
    pres.forEach(function(item) {
        item.className = item.className === '' ? lineNumberClassName : item.className + ' ' + lineNumberClassName;
    });
})();
</script>
<script type="text/javascript" src="<?php $this->options->themeUrl('lib/prism/clipboard.min.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('lib/prism/prism.js'); ?>"></script>
<?php endif; ?>

<?php add_custom_js($this); ?>

<?php $this->footer(); ?>
</body>
</html>
