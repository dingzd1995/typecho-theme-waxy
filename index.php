<?php
/**
 * Waxy 简约自适应博客主题，轻量高效，悦于书写！支持主题自定义、短代码、文章置顶/标星、公告、CDN切换等功能。<br/>详细说明请移步：<a href="https://github.com/dingzd1995/typecho-theme-waxy">https://github.com/dingzd1995/typecho-theme-waxy</a>
 *
 * @package Waxy
 * @author Dingzd
 * @version 2020.10.18
 * @link https://www.idzd.top/
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <?php if ($this->is('index')) { on_top_text($this); on_up_post($this); } ?>
            <?php $this->need('post_list.php'); ?>
        </main>
        <?php $this->need('sidebar.php'); ?>
    </div>
</section>

<?php $this->need('footer.php'); ?>
