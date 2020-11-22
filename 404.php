<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<!--start 404 page-->
<style>
	.title404 {
		font-size:160px;
		color:#F3726D;
		text-align:center;
		padding-top:35px;
		font-weight:normal;
	}
	.text404 {
		text-align:center;
		font-size:40px;
		color:#F3726D;
		font-weight:normal;
	}
	.btn404 {
	    margin-top: 45px;
	}
	.bottom404 {
		margin: 35px;
	}
</style>
<section class="content-wrap">
    <div class="container">
		<div class="row" id="404">
			<h3 class="title404"><?php _e('404'); ?></h3>
			<p class="text404"><?php _e('Not Found'); ?></p>
			<p class="text404 btn404"><!--a href="javascript:history.go(-1)" class="btn btn-default"><?php _e('返回上一页'); ?></a--><a href="<?php $this->options->siteUrl(); ?>" class="btn btn-default" ><?php _e('返回首页'); ?>（<scan id="time"></scan>）</a></p>
		</div>
		<div class="bottom404"></div>
	</div>
</section>
<!--end 404 page-->

<?php $this->need('footer.php'); ?>


<script type="text/javascript">
var i=10;
$(function(){
	jump();
	after();
	//var window_height = window.screen.availHeight;
	//var div_height = window_height * 0.7;
	//$("#404").height(div_height);
});


//自动跳转首页
function jump(){
	setTimeout(function(){
		window.location.href='/';
	},i*1000);//10秒后返回首页
}

//自动刷新页面上的时间
function after(){
	 $("#time").empty().append(i);
	 i=i-1;
	 setTimeout(function(){
		 after();
	 },1000);
}
</script>