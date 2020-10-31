<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<aside class="col-md-4 sidebar">

<!-- start Search widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowSearch', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="title">搜索</h4>
  <div class="content community sidebar-search">
    <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
        <label for="s" class="sr-only"><?php _e('搜索关键字'); ?></label>
        <input aria-label="search input" type="text" name="s" class="text asearch" placeholder="<?php _e('输入关键字搜索'); ?>" />
        <button type="submit"></button>
    </form>
  </div>
</div>
<?php endif; ?>
<!-- end Search widget --> 

<!-- start Category widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowCategory', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="title">分类</h4>
  <div class="category">
  <?php $this->widget('Widget_Metas_Category_List')->listCategories('wrapClass=widget-list'); ?>
  </div>
</div>
<?php endif; ?>
<!-- end Category widget --> 



<!-- start Tags widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowTags', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="title">标签云</h4>
  <div class="content tag-cloud">
    <?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 50))->to($tags); ?>  
<?php while($tags->next()): ?>  
<a rel="tag" href="<?php $tags->permalink(); ?>"><?php $tags->name(); ?>(<?php $tags->count(); ?>)</a>
<?php endwhile; ?>
  </div>
</div>
<?php endif; ?>
<!-- end Tags widget --> 

<!-- start RecentPosts widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)): ?>
<div class="widget">
  <h4 class="title">最新文章</h4>
  <div class="content recent-post">
        <?php $this->widget('Widget_Contents_Post_Recent', 'pageSize=3')->to($contents); $cont=1;?>
		<?php while($contents->next()): ?>
        <div class="recent-single-post">
            <a href="<?php $contents->permalink() ?>" class="post-title">
            <?php $contents->title() ?></a>
            <div class="date"><?php $contents->date('Y年m月d日') ?></div>
        </div>
        <?php $cont +=1; if($cont>3) break; ?>
		<?php endwhile; ?>
    </div>
</div>
<?php endif; ?>
<!-- end RecentPosts widget --> 

<!-- start RecentComments widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentComments', $this->options->sidebarBlock)): ?>
<div class="widget">
	<h4 class="title">最新评论</h4>
	<div class="content recent-post">
        <?php $this->widget('Widget_Comments_Recent','ignoreAuthor=true&pageSize=3')->to($comments); ?>
        <?php while ($comments->next()): ?>
        <div class="recent-single-post">
        	<a href="<?php $comments->permalink() ?>" class="post-title">
        	<?php $comments->excerpt('40'); ?></a>
        	<div class="date"><?php $comments->date('Y年m月d日') ?> # <?php $comments->author(false); ?></div>
    	</div> 
    <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>
<!-- end RecentComments widget -->  


<!-- start Archive widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowArchive', $this->options->sidebarBlock)): ?>
<div class="widget">
	<h4 class="title">归档列表</h4>
	<div class="category">
		<ul class="widget-list">
		<?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=Y年 m月')->to($parses); ?>
        <?php while ($parses->next()): ?>
		<li class="category-level-0 category-parent">
			<a href="<?php $parses->permalink() ?>"><?php $parses->date('Y年m月') ?> （共<?php $parses->count() ?>篇）</a>
		</li>
    	<?php endwhile; ?>
    	</ul> 
    </div>
</div>
<?php endif; ?>
<!-- end Archive widget --> 



<!-- start WX widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowWX', $this->options->sidebarBlock)): ?>
<div class="widget">
	<h4 class="title">微信关注</h4>
	<div class="content recent-post">
        <div class="recent-single-post" align="center">
            <img src="<?php $this->options->weixin_img(); ?>" title="欢迎关注公众号" alt="欢迎关注公众号" />
    	</div> 
    </div>
</div>
<?php endif; ?>
<!-- end WX widget --> 


<!-- start Links widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowLinks', $this->options->sidebarBlock)): ?>
<div class="widget">
	<h4 class="title">友情链接</h4>
	<div class="content recent-post">
		<!-- start Links list -->
		<?php add_links($this); ?>
		<!-- end Links list -->
    </div>
</div>
<?php endif; ?>
<!-- end Links widget --> 

<!-- start Others widget -->
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)): ?>
<div class="widget">
	<h4 class="title">其他</h4>
	<div class="category">
		<ul class="widget-list">
		<?php if($this->user->hasLogin()): ?>
			<li class="category-level-0 category-parent">
			    <a href="<?php $this->options->adminUrl(); ?>"><?php _e('进入后台'); ?> (<?php $this->user->screenName(); ?>)</a>
			    <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?>
			</li>
        <?php else: ?>
            <li class="category-level-0 category-parent"><a href="<?php $this->options->adminUrl('login.php'); ?>"><?php _e('登录'); ?></a></li>
        <?php endif; ?>
        <li class="category-level-0 category-parent"><a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 RSS'); ?></a></li>
        <li class="category-level-0 category-parent"><a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 RSS'); ?></a></li>
        <li class="category-level-0 category-parent">已在风雨中度过 <?php echo getBuildTime();?></li>
    	</ul> 
    </div>
</div>
<?php endif; ?>
<!-- end Others widget --> 
</aside>
