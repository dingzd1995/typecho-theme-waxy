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
                    <?php
                    $waxy_timeline_content = getContent($this->content);

                    // 顶部注脚（题记）节点文案，可按需修改
                    $waxy_timeline_epigraph_title = '从那一天起';
                    $waxy_timeline_epigraph_desc  = '风雨皆成往事，点滴记录在此';
                    $waxy_timeline_epigraph = '<li class="timeline-epigraph"><strong>' . $waxy_timeline_epigraph_title . '</strong><span class="timeline-epigraph__desc">' . $waxy_timeline_epigraph_desc . '</span></li>';

                    $waxy_timeline_ul_pos = stripos($waxy_timeline_content, '<ul>');
                    if ($waxy_timeline_ul_pos !== false) {
                        $waxy_timeline_content = substr_replace($waxy_timeline_content, '<ul>' . $waxy_timeline_epigraph, $waxy_timeline_ul_pos, 4);
                    }
                    echo $waxy_timeline_content;
                    ?>
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
