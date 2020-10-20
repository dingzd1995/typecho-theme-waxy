<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $halfyear = 3600 * 24 * 30 * 6; ?>
<?php $lostTime = time() - $this->modified; ?>
<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="container">
    <div class="row">
        <main class="col-md-8 main-content">
            <article id="<?php $this->cid() ?>" class="post">

            <header class="post-head">
                <h1 class="post-title"><?php $this->title() ?></h1>
                <section class="post-meta">
                    <span class="author">作者：<a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a></span>
                    <time class="post-date" datetime="<?php $this->date('c'); ?>">时间：<?php $this->date('Y年m月d日'); ?></time>
                    <span class="author">分类：<?php $this->category(','); ?></span>
                    <span class="author">字数：<?php echo art_count($this->cid); ?></span>
                    <!--span class="author">阅读：<?php //get_post_view($this) ?></span-->
                </section>
                <div class="post-border"></div>
            </header>

           <section class="post-content">
           		<?php if ($lostTime > $halfyear): ?>
    		       <div class="hint hint-warning">
    		       		<span class="glyphicon glyphicon-question-sign hint-warning-icon" aria-hidden="true"></span><span class="sr-only">warning:</span>
    		            这篇文章距离上次修改已过<?php echo floor($lostTime/86400); ?>天，其中的内容可能已经有所变动。
    		        </div>
        		<?php endif; ?>
                <!--?php $this->content(); ?-->
                <?php echo getContent($this->content); ?>
           </section>
       
        
            <footer class="post-footer clearfix">
                <div class="pull-left tag-list">
                    <i class="glyphicon glyphicon-folder-open"></i>
                    <?php $this->tags(' , ', true, 'none'); ?>
                </div>
                <div class="pull-right tag-list post-permalink" >
		    最后修改于：<?php echo date('Y年m月d日 H:i' , $this->modified); ?>
		</div>
            </footer>
            </article>
            
            <div >
		<ul style="height: 30px; position: relative; left: -22px; margin: 0px 5px 30px; list-style-type: none;">
		    <?php $this->thePrev('<li style="float: left;">%s</li>','',['title'=>'上一篇','tagClass'=>'btn btn-default']); ?>
		    <?php $this->theNext('<li style="float: right;">%s</li>','',['title'=>'下一篇','tagClass'=>'btn btn-default']); ?>
		</ul>
    	    </div>
	
	        <div class="about-author clearfix"><?php $this->need('comments.php'); ?></div>
        </main>

        <?php $this->need('sidebar.php'); ?>

    </div class="row">
    </div class="container">
</section>

<?php $this->need('footer.php'); ?>
