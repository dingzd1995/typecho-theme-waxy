<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 归档页面（按月分类）
 *
 * @package custom
 */
?>

<?php $this->need('header.php'); ?>


<section class="content-wrap">
    <div class="container">
    <div class="row">
    <main class="col-md-8 main-content">
	<article class="post">
	<header class="post-head">
	<h1 class="post-title">归档页面</h1>
	<section class="post-meta"></section>
	<div class="post-border"></div></header>
	<section class="post-content"><div id="archives">
	 
	<div class="hint hint-info">
	    <span class="glyphicon glyphicon-info-sign hint-info-icon" aria-hidden="true"></span>
	    <span class="sr-only">info:</span>
	    <span>统计数据：</span><br/>
    	<div class="wrap_lo">
    	    <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
            <p>
                
                <span>本次页面加载耗时：<?php echo timer_stop();?>;</span><br/>
                <span>网站已稳定运行：<?php echo getBuildTime();?> ;</span><br/>
                <span>文章：<?php $stat->publishedPostsNum() ?>篇;　</span>
                <span>分类：<?php $stat->categoriesNum() ?>个;　</span>
                <span>评论：<?php $stat->publishedCommentsNum() ?>条;　</span>
                <span>页面：<?php echo $stat->publishedPagesNum + $stat->publishedPostsNum; ?>个;　</span>
            </p>
    	</div>
     </div>
    
<?php  $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);   
    $year=0; $mon=0; $i=0; $j=0;  
    while($archives->next()):   
        $year_tmp = date('Y',$archives->created);   
        $mon_tmp = date('m',$archives->created);   
        $y=$year; $m=$mon;   
        if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';   
        if ($year != $year_tmp && $year > 0) $output .= '</ul>';   
        if ($year != $year_tmp) {   
           $year = $year_tmp;   
        //   $output .= '<h1 class="al_year">'. $year .' 年</h1>'; //输出年份   
        }   
        if ($mon != $mon_tmp) {   
            $mon = $mon_tmp;   
            $output .= '<h2 ><a style="color:#505050;" href="/'. $year .'/'. $mon .'/">'. $year .' 年 '. $mon .' 月</a></h2><ul class="al_post_list">'; //输出月份   
        }   
        //$output .= '<li>'.date('d日: ',$archives->created).'<a href="'.$archives->permalink .'">'. $archives->title .'</a>';
        $output .= '<li><a href="'.$archives->permalink .'">'. $archives->title .'</a>';
        $output .= ' <span style="color: #959595;">（'.date('Y/m/d',$archives->created).'）</span>';
        //$output .= '<em>（'. $archives->commentsNum.'条评论）</em>';
        $output .= '</li>'; //输出文章日期和标题   
    endwhile;   
    $output .= '</ul>';
    echo $output;
?>
	</div>
	</section>
	<footer class="post-footer clearfix"></footer>
	</article>
	</main>
	<?php $this->need('sidebar.php'); ?>
	</div>
	</div>
</section>
<?php $this->need('footer.php'); ?>