<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 归档页面（按年分类）
 *
 * @package custom
 */
?>

<?php $this->need('header.php'); ?>

<section class="content-wrap">
    <div class="layout">
    <main class="layout__main">
	<article id="arc" class="post">
    <header class="post__head">
       <div class="archive-tip">很好! 目前共计<strong><?php Typecho_Widget::widget('Widget_Stat')->to($stat)->publishedPostsNum(); ?></strong>篇文章，继续加油呀~</div>
    </header>
	<section class="post-content post__content">
	<div id="archives" class="archive-list">
    
<?php  $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);   
    $year=0; $output="";
    while($archives->next()):
        $year_tmp = date('Y',$archives->created);
        if ($year != $year_tmp) {   
          $year = $year_tmp;   
          $output .= '<h2 class="archive-year">'. $year .'</h2>'; //输出年份   
        } 
        $output .= '<div class="archive-item"><a class="archive-meta" href="'.$archives->permalink .'"><time>'.date('m/d',$archives->created).'</time>'. htmlspecialchars($archives->title) .'</a></div>';
    endwhile; 
    echo $output;
?>
	</div>
	</section>
	</article>
	</main>
	<?php $this->need('sidebar.php'); ?>
    </div>
</section>
<?php $this->need('footer.php'); ?>
