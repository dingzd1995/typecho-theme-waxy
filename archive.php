<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>


<section class="content-wrap">
    <div class="container">
        <div class="row">
            <main class="col-md-8 main-content">
                
            <div class="cover tag-cover">
                <ol class="breadcrumb tag-name">
                  <li><a href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title(); ?>">首页</a></li>
                  <?php $this->archiveTitle(array(
                        'category'  =>  _t('<li>分类</li><li class="active">%s</li>'),
                        'search'    =>  _t('<li>搜索</li><li class="active">%s</li>'),
                        'tag'       =>  _t('<li>标签</li><li class="active">%s</li>'),
                        'author'    =>  _t('<li>作者</li><li class="active">%s</li>')
                    ), '', ''); ?>
                  
                </ol>
            </div>

            <?php while($this->next()): ?>
			<!----全文模式开始----->
			<?php if ($this->options->articles_list==1):?>
			<article id="<?php $this->cid() ?>" class="post">

				<?php if ($this->fields->star): ?><div class="featured" title="推荐文章">
					<?php echo waxy_icon('star'); ?>
				</div><?php endif; ?>

				<div class="post-head">
					<h1 class="post-title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h1>
					<div class="post-meta">
						<span class="author">作者：<a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></span>
						<time class="post-date" datetime="<?php $this->date('c'); ?>" title="<?php $this->date('Y年m月d日'); ?>">时间：<?php $this->date('Y年m月d日'); ?></time>
						<span class="author">分类：<?php $this->category(','); ?></span>
						<span class="author">字数：<?php echo art_count($this->cid); ?></span>
						<!--span class="author">阅读：<?php //get_post_view($this) ?></span-->
					</div>
					<div class="post-border"></div>
				</div>
						
				<?php if ($this->fields->img): ?>
                <div class="featured-media">
                    <a href="<?php $this->permalink() ?>"><img src="<?php $this->fields->img(); ?>" alt="<?php $this->title() ?>"></a>
                </div>
                <?php endif; ?>
				
				<div class="post-content">
					<?php echo getIndexContent($this->content,$this->permalink); ?>
				</div>
				

				<footer class="post-footer clearfix">
					
					<div class="pull-left tag-list" >
						<?php echo waxy_icon('folder-open'); ?>
						<?php $this->tags(' , ', true, 'none'); ?>
					</div>
					
					<div class="pull-right post-permalink" >
						<a href="<?php $this->permalink() ?>#comments" class="btn btn-default">前往评论</a>
					</div>
				</footer>
			</article>
			<?php endif; ?>
			<!----摘要模式开始----->
			<?php if ($this->options->articles_list==0):?>

			<article id="<?php $this->cid() ?>" class="post" style="padding:25px 10px;">
				
				<?php if ($this->fields->star): ?>
					<div class="featured" title="推荐文章"> <?php echo waxy_icon('star'); ?></div>
				<?php endif; ?>
				<div class="excerpt">
				<?php if ($this->fields->img): ?>
					<div class="excerpt-img">
						<img <?php echo waxy_lazy_img_attrs($this->fields->img); ?> alt="<?php $this->title() ?>" title="<?php $this->title() ?>">
					</div>
				<?php else: ?>
					<?php if (getFirstImg($this->content)): ?>
					<div class="excerpt-img">
						<img <?php echo waxy_lazy_img_attrs(getFirstImg($this->content)); ?> alt="<?php $this->title() ?>" title="<?php $this->title() ?>">
					</div>
					<?php endif; ?>
				<?php endif; ?>
				<div class="post-excerpt">
					
					<div class="excerpt-title">
						<a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
					</div>
					<div class="excerpt-info">
						<div class="excerpt-item"><?php echo waxy_icon('user'); ?><a href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a></div> 
						<div class="excerpt-item"><?php echo waxy_icon('calendar'); ?><?php $this->date('Y-m-d'); ?></div> 
						<div class="excerpt-item"><?php echo waxy_icon('tag'); ?><?php $this->category(','); ?></div>
						<!--div class="excerpt-item"><span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span><?php $this->tags(' , ', true, 'none'); ?></div-->
					</div>
					<div class="excerpt-content">
						<?php if ($this->fields->info){ $this->fields->info();} else { echo getExcerpt($this->text,75,'');} ?>
						<a href="<?php $this->permalink() ?>" style="white-space:nowrap;" > - 阅读更多 - </a>
					</div>
				</div>
				</div>
			</article>

			<?php endif; ?>

			<?php endwhile; ?>

            <nav class="pagination" role="navigation">
                    
                    <?php $this->pageLink('<x aria-label="Previous" class="newer-posts"><?php echo waxy_icon('menu-left'); ?></x>'); ?>
                <span class="page-number">第 <?php if($this->_currentPage>1) echo $this->_currentPage;  else echo 1;?> 页 / 共 <?php echo ceil($this->getTotal() / $this->parameter->pageSize); ?> 页</span>
                    <?php $this->pageLink('<x aria-label="Next" class="older-posts"><?php echo waxy_icon('menu-right'); ?></x>','next'); ?>
            </nav>

            </main>

            <?php $this->need('sidebar.php'); ?>

        </div>
    </div>
</section>

<?php $this->need('footer.php'); ?>
