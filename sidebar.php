<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<aside class="layout__sidebar sidebar">


<!-- start Abouts widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowAbouts', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="widget__title">关于</h4>
  <?php if (!empty($this->options->cardBg)): ?>
  <div class="card__bg" style="background-image: url('<?php $this->options->cardBg(); ?>'); <?php if (in_array('ShowSearch', $this->options->sidebarBlock)) ?>"></div>
  <?php endif; ?>
  <div class="card">
  <div class="card__info">
    <img class="card__avatar" src="<?php $this->options->cardImg(); ?>">
    <div class="card__name"><?php $this->options->cardName(); ?></div>
    <div class="card__bio"><?php $this->options->cardDescription(); ?></div>
  </div>
  <?php add_cardlinks($this); ?>
  <div class="card__stats">
    <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
    <div class="card__stat">
        <div class="card__stat-label">文章</div>
        <div class="card__stat-value"><?php $stat->publishedPostsNum() ?></div>
    </div>
    <div class="card__stat">
        <div class="card__stat-label">分类</div>
        <div class="card__stat-value"><?php $stat->categoriesNum() ?></div>
    </div>
    <div class="card__stat">
        <div class="card__stat-label">评论</div>
        <div class="card__stat-value"><?php $stat->publishedCommentsNum() ?></div>
    </div>
    <div class="card__stat">
        <div class="card__stat-label">页面</div>
        <div class="card__stat-value"><?php echo $stat->publishedPagesNum + $stat->publishedPostsNum; ?></div>
    </div>
  </div>
  <div class="card__footer">
    <span>已在风雨中度过 <?php echo getBuildTime(); ?></span>
  </div>
</div>
</div>
<?php endif; ?>
<!-- end Abouts widget -->


<!-- start TOC widget -->
<?php if ($this->is('single') && $this->options->showToc != '0' && count($GLOBALS['waxy_toc_items'] ?? []) >= 3): ?>
<div class="widget widget--toc" id="waxy-toc-sidebar">
  <div class="waxy-toc-sidebar__header">
    <h4 class="widget__title">目录</h4>
  </div>
  <ul class="waxy-toc-sidebar__list widget__body">
        <?php foreach ($GLOBALS['waxy_toc_items'] as $item): ?>
        <li class="waxy-toc-sidebar__item waxy-toc-sidebar__item--h<?php echo $item['level']; ?>">
          <a class="waxy-toc-sidebar__link" href="#<?php echo htmlspecialchars($item['id']); ?>">
            <?php echo htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </li>
        <?php endforeach; ?>
  </ul>
  <div class="waxy-toc-sidebar__footer">
    <div class="waxy-toc-sidebar__bar"><div class="waxy-toc-sidebar__bar-fill" id="waxy-toc-bar"></div></div>
    <span class="waxy-toc-sidebar__progress" id="waxy-toc-progress">0%</span>
  </div>
</div>
<?php endif; ?>
<!-- end TOC widget -->

<!-- start Category widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowCategory', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="widget__title">分类</h4>
  <div class="widget__body">
      <?php $this->widget('Widget_Metas_Category_List')->to($category); $lestLevels = 0; ?>
      <ul class="menu-list">
        <?php while ($category->next()): ?>
            <?php if ($category->levels === 0){ if ($lestLevels > $category->levels){ echo '</ul>'; } ?>
            <li>
               <a href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>">
                   <span class="menu-list__title"><?php $category->name(); ?></span>
                   <span class="menu-list__count"><?php $category->count(); ?></span>
               </a>
            </li>
            <?php } else { if ($lestLevels < $category->levels && $lestLevels === 0){ echo '<ul>'; } ?>
            <li>
                <a href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>">
                    <span class="menu-list__title"><?php $category->name(); ?></span>
                    <span class="menu-list__count"><?php $category->count(); ?></span>
                </a>
            </li>
            <?php } $lestLevels = $category->levels; ?>
        <?php endwhile; ?>
      </ul>
  </div>
</div>
<?php endif; ?>
<!-- end Category widget -->


<!-- start Tags widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowTags', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="widget__title">标签云</h4>
  <div class="widget__body tag-cloud">
    <?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 50))->to($tags); ?>
<?php while ($tags->next()): ?>
<a rel="tag" href="<?php $tags->permalink(); ?>"><?php $tags->name(); ?>(<?php $tags->count(); ?>)</a>
<?php endwhile; ?>
  </div>
</div>
<?php endif; ?>
<!-- end Tags widget -->

<!-- start RecentPosts widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="widget__title">最新文章</h4>
  <div class="widget__body recent-posts">
        <?php $this->widget('Widget_Contents_Post_Recent', 'pageSize=3')->to($contents); $cont = 1; ?>
        <?php while ($contents->next()): ?>
        <div class="recent-posts__item">
            <a href="<?php $contents->permalink() ?>" class="recent-posts__title"><?php $contents->title() ?></a>
            <div class="recent-posts__date"><?php $contents->date('Y年m月d日') ?></div>
        </div>
        <?php $cont += 1; if ($cont > 3) break; ?>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>
<!-- end RecentPosts widget -->

<!-- start RecentComments widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentComments', $this->options->sidebarBlock)): ?>
<div class="widget">
    <h4 class="widget__title">最新评论</h4>
    <div class="widget__body recent-posts">
        <?php $this->widget('Widget_Comments_Recent', 'ignoreAuthor=true&pageSize=3')->to($comments); ?>
        <?php while ($comments->next()): ?>
        <div class="recent-posts__item">
            <a href="<?php $comments->permalink() ?>" class="recent-posts__title"><?php $comments->excerpt('40'); ?></a>
            <div class="recent-posts__date"><?php $comments->date('Y年m月d日') ?> # <?php $comments->author(false); ?></div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>
<!-- end RecentComments widget -->


<!-- start Archive widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowArchive', $this->options->sidebarBlock)): ?>
<div class="widget">
    <h4 class="widget__title">归档列表</h4>
    <div class="widget__body">
        <ul class="menu-list">
        <?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=Y年 m月')->to($parses); ?>
        <?php while ($parses->next()): ?>
        <li>
            <a href="<?php $parses->permalink() ?>">
                <span class="menu-list__title"><?php $parses->date('Y年m月') ?></span>
                <span class="menu-list__count"><?php $parses->count() ?>篇</span>
            </a>
        </li>
        <?php endwhile; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
<!-- end Archive widget -->


<!-- start Text widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowText', $this->options->sidebarBlock)): ?>
<div class="widget">
    <h4 class="widget__title"><?php $this->options->text_title(); ?></h4>
    <div class="widget__body widget__body--center">
        <?php $this->options->text_info(); ?>
    </div>
</div>
<?php endif; ?>
<!-- end Text widget -->


<!-- start Links widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowLinks', $this->options->sidebarBlock)): ?>
<div class="widget">
    <h4 class="widget__title">友情链接</h4>
    <div class="widget__body recent-posts">
        <?php add_links($this); ?>
    </div>
</div>
<?php endif; ?>
<!-- end Links widget -->

<!-- start Others widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)): ?>
<div class="widget">
    <h4 class="widget__title">管理功能</h4>
    <div class="widget__body">
        <ul class="menu-list">
        <?php if ($this->user->hasLogin()): ?>
            <li>
                <a href="<?php $this->options->adminUrl(); ?>"><?php _e('进入后台'); ?> (<?php $this->user->screenName(); ?>)</a>
                <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a>
            </li>
        <?php else: ?>
            <li><a href="<?php $this->options->adminUrl('login.php'); ?>"><?php _e('登录'); ?></a></li>
        <?php endif; ?>
        <li><a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 RSS'); ?></a></li>
        <li><a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 RSS'); ?></a></li>
        </ul>
    </div>
</div>
<?php endif; ?>
<!-- end Others widget -->
</aside>
