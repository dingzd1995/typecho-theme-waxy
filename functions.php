<?php
	if (!defined('__TYPECHO_ROOT_DIR__')) exit;


	
	// 主题设置
	function themeConfig($form) {
		$logoUrl = new Typecho_Widget_Helper_Form_Element_Text(
    	    'logoUrl', 
    	    NULL, 
    	    NULL, 
    	    _t('站点LOGO地址'),
    	    _t('在这里填入一个图片URL地址, 用来显示网站图片LOGO，为空则显示网站标题')
    	    );
        $form->addInput($logoUrl);
		
		$faviconUrl = new Typecho_Widget_Helper_Form_Element_Text(
		    'faviconUrl', 
		    NULL, 
		    NULL, 
		    _t('Favicon'), 
		    _t('请填入完整链接，作为网站标签页图标，建议大小 114x114')
		);
		$form->addInput($faviconUrl);
		
		$startTime = new Typecho_Widget_Helper_Form_Element_Text(
			'startTime', 
			NULL, 
			NULL, 
			_t('建站日期'), 
			_t('格式：2019-11-23 13:55:00')
			);
		$form->addInput($startTime);
		
		$shortcode = new Typecho_Widget_Helper_Form_Element_Radio(
	        'shortcode',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('短代码支持'),
	        _t('是否启用短代码支持，移植的 WordPress 功能')
	    );
		$form->addInput($shortcode);
		
	    $picHtmlPrint = new Typecho_Widget_Helper_Form_Element_Radio(
	        'picHtmlPrint',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('图片高级功能'),
	        _t('居中、懒加载、灯箱总控制开关')
	    );
		$form->addInput($picHtmlPrint);
				
	    $fancyboxs = new Typecho_Widget_Helper_Form_Element_Radio(
	        'fancyboxs',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('图片灯箱效果'),
	        _t('是否启用fancybox的图片灯箱效果')
	    );
		$form->addInput($fancyboxs);
		
		$JQlazyload = new Typecho_Widget_Helper_Form_Element_Radio(
	        'JQlazyload',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('图片懒加载'),
	        _t('是否启用图片懒加载（lazyload）')
	    );
		$form->addInput($JQlazyload);
		
		$JQlazyload_gif = new Typecho_Widget_Helper_Form_Element_Text(
	        'JQlazyload_gif', 
	        NULL,
	        '/usr/themes/waxy/img/loading.gif',
	        _t('懒加载loading图片'),
	        _t('设置图片懒加载时的载入图片（gif格式）')
		);
		$form->addInput($JQlazyload_gif);
		
		$sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
		'sidebarBlock', 
		array(
		    'ShowSearch' => _t('显示搜索'),
		    'ShowCategory' => _t('显示分类'),
		    'ShowTags' => _t('显示标签云'),
		    'ShowRecentPosts' => _t('显示最新文章'),
		    'ShowRecentComments' => _t('显示最近回复'),
            'ShowArchive' => _t('显示归档'),
            'ShowWX' => _t('显示微信公众号'),
            'ShowLinks' => _t('显示友情链接'),
            'ShowOther' => _t('显示其它杂项')
            ),
        array(
            'ShowSearch',
		    'ShowTags',
		    'ShowRecentPosts',
		    'ShowRecentComments',
            'ShowLinks'
            ),
            _t('侧边栏显示')
        );
        $form->addInput($sidebarBlock->multiMode());
        
        $weixin_img = new Typecho_Widget_Helper_Form_Element_Text(
	        'weixin_img', 
	        NULL,
	        '/usr/themes/waxy/img/loading.gif',
	        _t('微信二维码'),
	        _t('设置侧边栏微信公众号二维码图片，建议200x200')
		);
		$form->addInput($weixin_img);
		
		
	    $sticky = new Typecho_Widget_Helper_Form_Element_Text(
	        'sticky', 
	        NULL,
	        NULL,
	        _t('文章置顶'),
	        _t('置顶的文章cid，多个请用逗号或空格分隔，为保证效果仅置顶第一个')
		);
		$form->addInput($sticky);
	   
		$toptext = new Typecho_Widget_Helper_Form_Element_Text(
	        'toptext', 
	        NULL,
	        NULL,
	        _t('置顶公告'),
	        _t('置顶公告，留空则关闭')
		);
		$form->addInput($toptext);
		
		$codeHighlightControl = new Typecho_Widget_Helper_Form_Element_Radio(
	        'codeHighlightControl',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '0',
	        _t('代码高亮'),
	        _t('是否启用代码高亮功能，如需使用其他同类型插件，请关闭此项防止冲突（默认关闭）')
	    );
		$form->addInput($codeHighlightControl);
		
		$codeHighlightTheme = new Typecho_Widget_Helper_Form_Element_Radio(
        'codeHighlightTheme',
        array(
            'Default.css' => _t('Default'),
            'Okaidia.css' => _t('Okaidia'),
            'Coy.css' => _t('Coy'),
            'SolarizedLight.css' => _t('Solarized Light'),
            'TomorrowNight.css' => _t('Tomorrow Night'),
			'Twilight.css' => _t('Twilight'),
			'Funky.css' => _t('Funky'),
			'Dark.css' => _t('Dark'),
            ),
            'Okaidia.css',
            _t('代码高亮'),
            _t('代码高亮')
        );
        $form->addInput($codeHighlightTheme);
    
    	
    	$CDN = new Typecho_Widget_Helper_Form_Element_Radio(
            'CDN',
            array(
                'local' => _t('本地'),
                'bootcss' => _t('Bootcss'),
                'jsdelivr' => _t('jsDelivr'),
            ),
           'bootcss',
            _t('CDN 设置'),
            _t('CDN 设置')
        );
        $form->addInput($CDN);
	
	}
	
	// 主题加载
	function themeInit($self) {
		$options = $self->widget('Widget_Options');
	    if ($options->shortcode) {
	        require_once __DIR__ . '/shortcode.php';
	    }
	}
	
	// 文章页内容
	function getContent($content) {
	    $options = Typecho_Widget::widget('Widget_Options');
	    // 短代码
	    if ($options->shortcode) {
	    	$content = do_shortcode($content);
	    }  
	    if ($options->picHtmlPrint) {
	    	$content = getPicHtml($content);
	    }
	    
	    return $content;
	    
	}
	
	
	//首页内容
	function getIndexContent($content,$permalink) {
	    $options = Typecho_Widget::widget('Widget_Options');
	    // 短代码
	    if ($options->shortcode) {
	    	$content = do_shortcode($content);
	    }
	    
	    if ($options->picHtmlPrint) {
	    	$content = getPicHtml($content);
	    }
	    
	   
	    
	    $array=explode('<!--more-->', $content);
	    
	    $content=$array[0];
	    
	    if($array[1]!==null){
	        $content = $content.'<div class="readall_box" >
	                                <div class="readall_mask" ></div>
	                                <a href="'.$permalink.'" alt="阅读剩余部分" class="readall_text">阅读剩余部分</a>
	                                <i class="glyphicon glyphicon-chevron-down readall_icon"></i>
	                            </div>';
	    }
	    
	    return $content;
	}
	
	
	// 图片功能
	function getPicHtml($content) {
		$options = Typecho_Widget::widget('Widget_Options');
		
	    $pattern = '/\<img.*?src\=\"(.*?)\".*?alt\=\"(.*?)\".*?title\=\"(.*?)\"[^>]*>/i';
	    $replacement = '<center><img src="$1" alt="$2" title="$3"><span class="imgtitle">$3<span></center>';
	    // 懒加载
	    if ($options->JQlazyload) {
	    	$replacement = '<center><img class="lazyload" src="'.$options->JQlazyload_gif.'" data-original="$1" alt="$2" title="$3"><span class="imgtitle">$3<span></center>';
	    }
	    // 灯箱效果
	    if ($options->fancyboxs) {
	    	$replacement = '<center><a data-fancybox="gallery" href="$1"><img  src="$1" alt="$2" title="$3"></a><span class="imgtitle">$3<span></center>';
	    }
	    
	    //all in
	    if($options->fancyboxs&&$options->JQlazyload){
	    	$replacement = '<center><a data-fancybox="gallery" href="$1"><img class="lazyload" src="'.$options->JQlazyload_gif.'" data-original="$1" alt="$2" title="$3"></a><span class="imgtitle">$3<span></center>';
	    }
	    $content = preg_replace($pattern, $replacement, $content);
	    
	    return $content;
	}
	
		// 短代码测试
	function getContentTest($content) {
	    $pattern = '/\[(info)\](.*?)\[\s*\/\1\s*\]/';
		$replacement = '<div class="hint hint-info"><span class="glyphicon glyphicon-info-sign hint-info-icon" aria-hidden="true"></span>$2</div>';	
		$content = preg_replace($pattern, $replacement, $content);
		
		$pattern = '/\[(warning)\](.*?)\[\s*\/\1\s*\]/';
		$replacement = '<div class="hint hint-warning"><span class="glyphicon glyphicon-question-sign hint-warning-icon" aria-hidden="true"></span>$2</div>';	
		$content = preg_replace($pattern, $replacement, $content);
		
		$pattern = '/\[(danger)\](.*?)\[\s*\/\1\s*\]/';
		$replacement = '<div class="hint hint-danger"><span class="glyphicon glyphicon-exclamation-sign hint-danger-icon" aria-hidden="true"></span>$2</div>';	
		$content = preg_replace($pattern, $replacement, $content);
	    
	    return $content;
	}
	
	
	// 摘要短代码测试
	function getExcerptTest($excerpt,$num,$str) {
	    $pattern = '/\[(info)\](.*?)\[\s*\/\1\s*\]/';
		$replacement = ' $2 ';	
		$excerpt = preg_replace($pattern, $replacement, $excerpt);
		
		$pattern = '/\[(warning)\](.*?)\[\s*\/\1\s*\]/';
		$replacement = ' $2 ';	
		$excerpt = preg_replace($pattern, $replacement, $excerpt);
		
		$pattern = '/\[(danger)\](.*?)\[\s*\/\1\s*\]/';
		$replacement = ' $2 ';	
		$excerpt = preg_replace($pattern, $replacement, $excerpt);
		
		//使用mb_substr防止中文截取成乱码，需要开启extension=php_mbstring.dll扩展，一般都开了
	    return mb_substr($excerpt,0,$num,"UTF-8").$str;
	}
	
	/**额外的一些小工具**/
	
	function  art_count ($cid){
	$db=Typecho_Db::get ();
	$rs=$db->fetchRow ($db->select ('table.contents.text')->from ('table.contents')->where ('table.contents.cid=?',$cid)->order ('table.contents.cid',Typecho_Db::SORT_ASC)->limit (1));
	echo mb_strlen($rs['text'], 'UTF-8');
	}
    /**
     * 加载时间
     * @return bool
     */
    function timer_start() {
        global $timestart;
        $mtime     = explode( ' ', microtime() );
        $timestart = $mtime[1] + $mtime[0];
        return true;
    }
    timer_start();
    function timer_stop( $display = 0, $precision = 3 ) {
        global $timestart, $timeend;
        $mtime     = explode( ' ', microtime() );
        $timeend   = $mtime[1] + $mtime[0];
        $timetotal = number_format( $timeend - $timestart, $precision );
        $r         = $timetotal < 1 ? $timetotal * 1000 . " ms" : $timetotal . " s";
        if ( $display ) {
            echo $r;
        }
        return $r;
    }
    
    // 设置时区
	date_default_timezone_set('Asia/Shanghai');
	/**
	 * 秒转时间，格式 年 月 日 时 分 秒
	 * 
	 * @author Roogle
	 * @return html
	 */
	function getBuildTime(){
		// 在下面按格式输入本站创建的时间
		$options = Typecho_Widget::widget('Widget_Options');
        $start_Time = $options->startTime;
        $site_create_time = strtotime('2019-11-23 13:55:00');
        if(!empty($start_Time)){
            $site_create_time = strtotime($start_Time);
        }
		$time = time() - $site_create_time;
		if(is_numeric($time)){
			$value = array(
				"years" => 0, "days" => 0, "hours" => 0,
				"minutes" => 0, "seconds" => 0,
			);
			if($time >= 31556926){
				$value["years"] = floor($time/31556926);
				$time = ($time%31556926);
			}
			if($time >= 86400){
				$value["days"] = floor($time/86400);
				$time = ($time%86400);
			}
			if($time >= 3600){
				$value["hours"] = floor($time/3600);
				$time = ($time%3600);
			}
			if($time >= 60){
				$value["minutes"] = floor($time/60);
				$time = ($time%60);
			}
			$value["seconds"] = floor($time);
			
			
			if($value['years']>0){
				echo ''.$value['years'].'年'.$value['days'].'天'.$value['hours'].'小时'.$value['minutes'].'分';	
			}else{
				echo ''.$value['days'].'天'.$value['hours'].'小时'.$value['minutes'].'分';	
			}
		}else{
			echo '';
		}
	}

    /**
    * 阅读统计
    * 调用
    */
    function get_post_view($archive){
        $cid = $archive->cid;
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
            echo 0;
            return;
        }
        $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
        if ($archive->is('single')) {
            $db->query($db->update('table.contents')->rows(array('views' => (int) $row['views'] + 1))->where('cid = ?', $cid));
        }
        echo $row['views'];
    }
    
    /**
    * 文章置顶
    */
    function on_up_post($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $sticky = $options->sticky; //置顶的文章cid，按照排序输入, 请以半角逗号或空格分隔
        if(!empty($sticky)){
        $sticky_html = '';
        $sticky_cids = explode(',', strtr($sticky, ' ', ',')); //分割cid
        $db = Typecho_Db::get();
        $sticky_post = $db->fetchRow($archive->select()->where('cid = ?', $sticky_cids[0]));
        $time = date('Y年m月d日',$sticky_post["created"]);
        $sticky_html = '<article id="' . $sticky_cids[0] . '" class="post" style="padding: 10px 10px 10px 20px;margin-bottom: 10px;">';
        $sticky_html = $sticky_html . '<div class="featured" title="置顶文章">
                                            <i class="glyphicon glyphicon-bookmark"></i>
                                       </div>';
                                       
        //foreach($sticky_post as $i => $cid) {}
                                       
        $sticky_html = $sticky_html . '<div style="margin-right: 30px;"><span style="font-weight: bold;">置顶：</span><span><a href="' 
									.$options->siteUrl. 'archives/'
									. $sticky_cids[0] . '/">《'
                                    . $sticky_post["title"] . 
                                    '》</a></span><span style="color: #959595;">（'.$time.'）</span><div>';
        $sticky_html = $sticky_html . '</article>'; 
        
        }
        echo $sticky_html;
    }
    
    /**
    * 公告
    */
    function on_top_text($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $top_text = $options->toptext;
        $top_text_html = '';
        if(!empty($top_text)){
        $top_text_html = '<article id="top_text" class="post" style="padding: 10px 10px 10px 20px;margin-bottom: 10px;">';
        $top_text_html = $top_text_html . '<div class="featured" title="公告">
                                            <i class="glyphicon glyphicon-comment"></i>
                                       </div>';
        $top_text_html = $top_text_html . '<div style="margin-right: 30px;"><span style="font-weight: bold;">公告：</span><span>'. $top_text .'</span></div>';
        $top_text_html = $top_text_html . '</article>';
        }
      
        echo $top_text_html;
    }
