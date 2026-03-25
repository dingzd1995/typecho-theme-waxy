<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $outdatedDays = (int)($this->options->outdatedDays ?: 180); ?>
<?php $lostTime = time() - $this->modified; ?>
<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="layout">
        <main class="layout__main">
            <article id="<?php $this->cid() ?>" class="post post--single">

            <?php if ($this->fields->star): ?>
            <div class="post__featured" title="推荐文章"><?php echo waxy_icon('star'); ?></div>
            <?php endif; ?>

            <?php
            // 检测是否仍处于密码锁定状态（密码未输入或输入错误）
            $post_locked = !empty($this->password) && strpos($this->content, 'name="password"') !== false;
            $post_img = '';
            if (!$post_locked) {
                if ($this->fields->img) {
                    $post_img = htmlspecialchars(trim($this->fields->img));
                } elseif (getFirstImg($this->content)) {
                    $post_img = htmlspecialchars(trim(getFirstImg($this->content)));
                }
            }
            ?>

            <?php if ($post_locked): ?>

            <header class="post__head post__head--locked">
                <div class="post__lock"><?php echo waxy_icon('lock'); ?></div>
                <h1 class="post__title">加密文章</h1>
                <div class="post__border"></div>
            </header>
            <section class="post-content post__content">
                <?php echo getContent($this->content); ?>
            </section>

            <?php else: ?>

            <header class="post__head<?php echo $post_img ? ' post__head--has-bg' : ''; ?>"
                    <?php if ($post_img): ?>style="--post-bg:url('<?php echo $post_img; ?>')"<?php endif; ?>>
                <h1 class="post__title"><?php $this->title() ?></h1>
                <section class="post-meta">
                    <span class="post-meta__author">作者：<a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a></span>
                    <time class="post-meta__date" datetime="<?php $this->date('c'); ?>">时间：<?php $this->date('Y年m月d日'); ?></time>
                    <span class="post-meta__cat">分类：<?php $this->category(','); ?></span>
                    <span class="post-meta__count">字数：<?php echo art_count($this->cid); ?></span>
                </section>
                <div class="post__border"></div>
            </header>

            <section class="post-content post__content">
                <?php if ($this->options->outdatedHint != '0' && $lostTime > $outdatedDays * 86400): ?>
                <div class="hint hint--warning">
                    <?php echo waxy_icon('question-sign', 'hint--warning-icon'); ?><span class="sr-only">warning:</span>
                    这篇文章距离上次修改已过<?php echo floor($lostTime / 86400); ?>天，其中的内容可能已经有所变动。
                </div>
                <?php endif; ?>
                <?php echo getContent($this->content); ?>
            </section>

            <footer class="post__footer">
                <div class="post__tags">
                    <?php echo waxy_icon('folder-open'); ?>
                    <?php $this->tags(' , ', true, 'none'); ?>
                </div>
                <div class="post__permalink">
                    最后修改于：<?php echo date('Y年m月d日 H:i', $this->modified); ?>
                </div>
            </footer>

            <?php endif; ?>
            </article>

            <?php if (!$post_locked): ?>
            <div class="post-nav">
                <ul>
                    <?php $this->thePrev('<li class="post-nav__prev">%s</li>', '', ['title' => '上一篇', 'tagClass' => 'btn btn--default']); ?>
                    <?php $this->theNext('<li class="post-nav__next">%s</li>', '', ['title' => '下一篇', 'tagClass' => 'btn btn--default']); ?>
                </ul>
            </div>

            <div class="about-author"><?php $this->need('comments.php'); ?></div>
            <?php endif; ?>
        </main>


        <?php $this->need('sidebar.php'); ?>

    </div>
</section>

<?php $this->need('footer.php'); ?>
