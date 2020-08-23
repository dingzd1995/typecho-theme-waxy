<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html class="no-js" lang=zh-cmn-Hans>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
        
    <!-- Loading -->
    <style type="text/css">
            .loadingBG{
                position: fixed;
                width: 100%;
                height: 100%;
                background-color: white;
                z-index: 99999;
            }
	        .loading{
	            position: relative;
				width: 85px;
				height: 40px;
				margin: -20px auto auto -40px;
				top: 50%;
				left: 50%;
			}
			.loading span{
				display: inline-block;
				width: 8px;
				height: 100%;
				border-radius: 4px;
				background: #F09A97;
				-webkit-animation: load 1s ease infinite;
			}
			@-webkit-keyframes load{
				0%,100%{
					height: 40px;
					background: #F09A97;
				}
				50%{
					height: 70px;
					margin: -15px 0;
					background: #FF4d40;
				}
			}
			.loading span:nth-child(2){
				-webkit-animation-delay:0.2s;
			}
			.loading span:nth-child(3){
				-webkit-animation-delay:0.4s;
			}
			.loading span:nth-child(4){
				-webkit-animation-delay:0.6s;
			}
			.loading span:nth-child(5){
				-webkit-animation-delay:0.8s;
			}
	</style>
    <div id="loading" class="loadingBG" >
    	<div class="loading">
    	    <span></span>
    	    <span></span>
    	    <span></span>
    	    <span></span>
    	    <span></span>
    	    <span></span>
    	    <span></span>
        </div>
    </div>
    <!-- Loading END -->
    
    <!--bootcss-->
    <?php if (strcmp($this->options->CDN,"bootcss")==0): ?>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.bootcss.com/fancybox/3.5.7/jquery.fancybox.min.css">
    <?php endif; ?>
    <!--bootcss END-->
    
    <!--jsdelivr-->
    <?php if (strcmp($this->options->CDN,"jsdelivr")==0): ?>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
    <?php endif; ?>
    <!--jsdelivr END-->
    
    <!--local-->
    <?php if (strcmp($this->options->CDN,"local")==0): ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/jquery.fancybox.min.css'); ?>">
    <?php endif; ?>
    <!--local END-->
    
    
    <!-- 使用url函数转换相关路径 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/waxy-main.css'); ?>">
    
    <!--代码高亮-->
	<?php if ($this->options->codeHighlightControl): ?>
	<link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('lib/prism/css/'); ?><?php $this->options->codeHighlightTheme(); ?>" />
    <?php endif; ?>
    <!--END-->
	
	<!--自定义CSS-->
    <?php add_custom_css($this); ?>
    <!--END-->
	
	<link rel="shortcut icon" href="<?php $this->options->faviconUrl(); ?>">
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
</head>
<body class="home-template">
    <!-- start navigation -->
    <nav class="main-navigation">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
        			<div class="container">
        				<div class="menu menu-logo">
							<a class="navbar-brand menu-title" href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->description() ?>" >
							    <?php if (!empty($this->options->logoUrl)){ ?>
							    <img src="/logo.png" height="45" style="margin: -15px -15px 35px 0px;" alt="<?php $this->options->title(); ?>">
							    <?php }else{$this->options->title();} ?>
							</a>
					    </div>
					    
						<div class="navbar-header">
						    <span class="nav-toggle-button collapsed" data-toggle="collapse" data-target="#main-menu">
						    <span class="sr-only">导航切换</span>
						    <i class="glyphicon glyphicon-menu-hamburger" ></i>
						    </span>
						</div>

						<div class="collapse navbar-collapse" id="main-menu">
	                        <ul class="menu" >
								<li<?php if($this->is('index')): ?> class="nav-current"<?php endif; ?>><a href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a></li>
								
								<?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
					            <?php while ($category->next()): ?>
					              
					              <li <?php if($this->is('category', $category->slug)): ?> class="nav-current" <?php endif; ?>><a href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>"><?php $category->name(); ?></a></li>
					                
					            <?php endwhile; ?>
					            
								<?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
								<?php while($pages->next()): ?>
								<li<?php if($this->is('page', $pages->slug)): ?> class="nav-current"<?php endif; ?>><a href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a></li>
			                    <?php endwhile; ?>
							</ul>
	                    </div>
	                    <!--div class="menu menu-search">
	                        <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                                <label for="s" class="sr-only"><?php _e('搜索关键字'); ?></label>
                                <input type="text" name="s" class="text asearch" placeholder="<?php _e('输入关键字搜索'); ?>" />
                        		<i  title="搜索" class="fa fa-search" id="tubiao"></i>
                            </form>
					    </div-->
	                </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- end navigation -->
    
