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
            // 是否仍处于密码锁定状态：直接用 Typecho 核心的 hidden 判断（未输入/输错密码，
            // 且当前访问者不是作者或有编辑权限的登录用户），比自行拼字符串猜测密码表单可靠——
            // 之前用 strpos($this->content, 'name="password"') 判断，但 Typecho 密码表单的
            // 输入框实际 name 是 protectPassword，永远匹配不到，导致锁定态形同虚设，
            // 封面图/分类/字数等元信息会在未解锁时直接泄漏
            $post_locked = $this->hidden;
            $post_img = $post_locked ? '' : waxy_resolve_cover_img($this->fields->img, $this->content);
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
                    <span class="post-meta__author"><?php echo waxy_icon('user'); ?><a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a></span>
                    <time class="post-meta__date" datetime="<?php $this->date('c'); ?>"><?php echo waxy_icon('calendar'); ?><?php $this->date('Y年m月d日'); ?></time>
                    <?php $waxy_head_cats = (array)$this->categories; ?>
                    <?php if ($waxy_head_cats): ?>
                    <span class="post-meta__cat">
                        <?php echo waxy_render_badge_group($waxy_head_cats, 'folder-open', ' excerpt__badge--cat'); ?>
                    </span>
                    <?php endif; ?>
                    <span class="post-meta__count"><?php echo waxy_icon('toc'); ?><?php echo art_count($this->cid); ?>字</span>
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
                <?php $waxy_footer_tags = (array)$this->tags; ?>
                <?php if ($waxy_footer_tags): ?>
                <div class="post__tags">
                    <div class="excerpt__badges">
                        <?php echo waxy_render_badge_group($waxy_footer_tags, 'tag'); ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="post__permalink">
                    最后修改于：<?php echo date('Y年m月d日 H:i', $this->modified); ?>
                </div>
            </footer>

            <?php endif; ?>
            </article>

            <?php if (!$post_locked): ?>
            <div class="post-nav">
                <ul class="post-nav__list">
                    <?php
                    $waxy_nav_prev_format = '<li class="post-nav__item post-nav__item--prev"><span class="post-nav__label">' . waxy_icon('menu-left', 'post-nav__arrow') . '上一篇</span>%s</li>';
                    $waxy_nav_prev_empty  = '<li class="post-nav__item post-nav__item--prev post-nav__item--empty"><span class="post-nav__empty">没有更多了</span></li>';
                    $this->thePrev($waxy_nav_prev_format, $waxy_nav_prev_empty, ['tagClass' => 'post-nav__link']);

                    $waxy_nav_next_format = '<li class="post-nav__item post-nav__item--next"><span class="post-nav__label">下一篇' . waxy_icon('menu-right', 'post-nav__arrow') . '</span>%s</li>';
                    $waxy_nav_next_empty  = '<li class="post-nav__item post-nav__item--next post-nav__item--empty"><span class="post-nav__empty">没有更多了</span></li>';
                    $this->theNext($waxy_nav_next_format, $waxy_nav_next_empty, ['tagClass' => 'post-nav__link']);
                    ?>
                </ul>
            </div>

            <div class="about-author"><?php $this->need('comments.php'); ?></div>
            <?php endif; ?>
        </main>


        <?php $this->need('sidebar.php'); ?>

    </div>
</section>

<?php $this->need('footer.php'); ?>
