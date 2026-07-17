<?php
	if (!defined('__TYPECHO_ROOT_DIR__')) exit;

	require_once __DIR__ . '/lib/icons.php';

	// 主题设置
	function themeConfig($form) {
		$logoUrl = new Typecho_Widget_Helper_Form_Element_Text(
    	    'logoUrl', 
    	    NULL, 
    	    NULL, 
    	    _t('站点LOGO'),
    	    _t('在这里填入一个图片URL地址, 用来显示网站图片LOGO，为空则显示网站标题')
    	    );
        $form->addInput($logoUrl);
		
		$faviconUrl = new Typecho_Widget_Helper_Form_Element_Text(
		    'faviconUrl', 
		    NULL, 
		    NULL, 
		    _t('Favicon'), 
		    _t('请填入完整链接，作为网站标签图标，建议大小 32x32')
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
		
		$ICP = new Typecho_Widget_Helper_Form_Element_Text(
			'ICP', 
			NULL, 
			NULL, 
			_t('ICP备案号'), 
			_t('网站ICP备案号，留空关闭')
			);
		$form->addInput($ICP);
		
		$cardImg = new Typecho_Widget_Helper_Form_Element_Text(
			'cardImg', 
			NULL, 
			NULL, 
			_t('头像'), 
			_t('请填入完整链接（URL），在关于侧边栏中展示')
			);
		$form->addInput($cardImg);
		
		$cardName = new Typecho_Widget_Helper_Form_Element_Text(
			'cardName', 
			NULL, 
			NULL, 
			_t('昵称'), 
			_t('在关于侧边栏中展示')
			);
		$form->addInput($cardName);
		
		$cardDescription = new Typecho_Widget_Helper_Form_Element_Text(
			'cardDescription', 
			NULL, 
			NULL, 
			_t('介绍/一言'), 
			_t('在关于侧边栏中展示')
			);
		$form->addInput($cardDescription);
		
		$cardlinks = new Typecho_Widget_Helper_Form_Element_Textarea(
	        'cardlinks', 
	        NULL,
	        NULL,
	        _t('社交/分享链接'),
	        _t('一行一条，格式(请用半角逗号分隔)：名称,地址；目前支持：rss/github/facebook/twitter/telegram/email/weibo/wechat')
		);
		$form->addInput($cardlinks);
		$cardBg = new Typecho_Widget_Helper_Form_Element_Text(
			'cardBg', 
			NULL, 
			NULL, 
			_t('关于背景'), 
			_t('请填入完整链接（URL），设置关于侧边栏区块的背景（填充模式），建议大于360*170，留空自动关闭')
			);
		$form->addInput($cardBg);
		
		$load_html = new Typecho_Widget_Helper_Form_Element_Radio(
	        'load_html',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('站点加载动画'),
	        _t('是否启用等待站点加载完毕的动画')
	    );
		$form->addInput($load_html);

		$outdatedHint = new Typecho_Widget_Helper_Form_Element_Radio(
	        'outdatedHint',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('文章过时提示'),
	        _t('文章距上次修改超过一定天数时，是否在正文顶部显示过时提示')
	    );
		$form->addInput($outdatedHint);

		$outdatedDays = new Typecho_Widget_Helper_Form_Element_Text(
			'outdatedDays',
			NULL,
			'180',
			_t('过时提示天数'),
			_t('文章距上次修改超过多少天时显示过时提示，默认 180 天')
		);
		$form->addInput($outdatedDays);

		$showToc = new Typecho_Widget_Helper_Form_Element_Radio(
	        'showToc',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('文章目录侧边栏'),
	        _t('在文章页侧边栏显示自动生成的目录（标题数量 ≥ 3 时生效）')
	    );
		$form->addInput($showToc);

		$articles_list = new Typecho_Widget_Helper_Form_Element_Radio(
	        'articles_list',
	        array(
	            '1' => '文章模式',
	            '0' => '摘要模式'
	        ),
	        '1',
	        _t('首页文章展示样式'),
	        _t('设置首页文章展示样式')
	    );
		$form->addInput($articles_list);
		
		$find_first_image = new Typecho_Widget_Helper_Form_Element_Radio(
	        'find_first_image',
	        array(
	            '1' => '是',
	            '0' => '否'
	        ),
	        '0',
	        _t('自动寻找文章首图'),
	        _t('自动使用文章内第一张图片作为文章首图？')
	    );
		$form->addInput($find_first_image);
		
		$first_image = new Typecho_Widget_Helper_Form_Element_Textarea(
	        'first_image', 
	        NULL,
	        NULL,
	        _t('文章首图'),
	        _t('一行一条，随机使用，留空则关闭，权重：文章内定义首图 > 文章内第一张图片（如果启用） > 本处设置')
		);
		$form->addInput($first_image);
		
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
		
	    $picAlign = new Typecho_Widget_Helper_Form_Element_Radio(
	        'picAlign',
	        array(
	            'center' => '居中',
	            'left'   => '靠左'
	        ),
	        'center',
	        _t('图片位置'),
	        _t('设置文章内图片的对齐方式')
	    );
		$form->addInput($picAlign);
				
	    $fancyboxs = new Typecho_Widget_Helper_Form_Element_Radio(
	        'fancyboxs',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('图片灯箱效果'),
	        _t('是否启用图片灯箱效果（点击图片放大）')
	    );
		$form->addInput($fancyboxs);

		$JQlazyload = new Typecho_Widget_Helper_Form_Element_Radio(
	        'JQlazyload',
	        array(
	            '1' => '开启（JS模式，防爬虫）',
	            '0' => '关闭（原生 loading=lazy）'
	        ),
	        '1',
	        _t('图片懒加载'),
	        _t('开启时使用 JS IntersectionObserver 模式（data-src 防爬虫），关闭时使用浏览器原生懒加载')
	    );
		$form->addInput($JQlazyload);

		$lazyloadGif = new Typecho_Widget_Helper_Form_Element_Text(
	        'lazyloadGif',
	        NULL,
	        '',
	        _t('懒加载占位图地址'),
	        _t('JS懒加载模式下的占位图 URL；留空则使用默认骨架屏动画')
	    );
		$form->addInput($lazyloadGif);

		$navbarSearch = new Typecho_Widget_Helper_Form_Element_Radio(
	        'navbarSearch',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '1',
	        _t('菜单栏搜索框'),
	        _t('设置是否在菜单栏右侧显示一个自适应搜索框')
	    );
		$form->addInput($navbarSearch);
		
		$menuDropdown = new Typecho_Widget_Helper_Form_Element_Radio(
	        'menuDropdown',
	        array(
	            '6' => '不显示<br/>',
	            '5' => '全部收纳（每级独立列表）<br/>',
	            '4' => '全部收纳（仅一个下拉列表）<br/>',
	            '3' => '部分收纳（展开全部一级分类，收纳子分类，每级独立列表）<br/>',
	            '2' => '部分收纳（展开全部一级分类，收纳子分类，仅一个下拉列表）<br/>',
	            '1' => '全部展开（仅一级分类，不包含子分类）<br/>',
	            '0' => '全部展开（包括子分类）'
	        ),
	        '1',
	        _t('分类下拉菜单设置'),
	        _t('设置分类下拉菜单的样式')
	    );
		$form->addInput($menuDropdown);
		
		$pageDropdown = new Typecho_Widget_Helper_Form_Element_Radio(
	        'pageDropdown',
	        array(
	            '1' => '开启',
	            '0' => '关闭'
	        ),
	        '0',
	        _t('独立页面下拉菜单设置'),
	        _t('设置是否开启独立页面的下拉菜单')
	    );
		$form->addInput($pageDropdown);
		
		$menuLink = new Typecho_Widget_Helper_Form_Element_Textarea(
	        'menuLink', 
	        NULL,
	        '登录,/admin/login.php',
	        _t('自定义菜单'),
	        _t('一行一条，留空则关闭，格式(请用半角逗号分隔)：链接名称,链接地址（URL）')
		);
		$form->addInput($menuLink);
		
		
		$sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
		'sidebarBlock', 
		array(
		    'ShowAbouts' => _t('显示关于'),
		    'ShowCategory' => _t('显示分类'),
		    'ShowTags' => _t('显示标签云'),
		    'ShowRecentPosts' => _t('显示最新文章'),
		    'ShowRecentComments' => _t('显示最近回复'),
            'ShowArchive' => _t('显示归档'),
            'ShowText' => _t('显示自定义侧边栏'),
            'ShowLinks' => _t('显示友情链接'),
            'ShowOther' => _t('显示管理功能')
            ),
        array(
            'ShowAbouts',
		    'ShowTags',
		    'ShowRecentPosts',
		    'ShowRecentComments',
            'ShowLinks'
            ),
            _t('侧边栏显示')
        );
        $form->addInput($sidebarBlock->multiMode());

        $text_title = new Typecho_Widget_Helper_Form_Element_Text(
	        'text_title', 
	        NULL,
	        '微信关注',
	        _t('自定义侧边栏标题'),
	        _t('设置自定义侧边栏显示标题')
		);
		$form->addInput($text_title);
		
		$text_info = new Typecho_Widget_Helper_Form_Element_Textarea(
	        'text_info', 
	        NULL,
	        '<div class="waxy-diamond-loader"><span></span><span></span><span></span><span></span></div>',
	        _t('自定义侧边栏内容'),
	        _t('设置自定义侧边栏显示显示的内容，支持html')
		);
		$form->addInput($text_info);
		
	
	$links = new Typecho_Widget_Helper_Form_Element_Textarea(
	        'links', 
	        NULL,
	        'IDZD,https://www.idzd.top/,https://www.idzd.top/favicon.ico,IDZD - 乐于探索',
	        _t('友情链接'),
	        _t('一行一条，格式(请用半角逗号分隔)：网站名称,网站地址,网站图标(建议:32x32),网站说明')
		);
		$form->addInput($links);
		
	    $sticky = new Typecho_Widget_Helper_Form_Element_Text(
	        'sticky', 
	        NULL,
	        NULL,
	        _t('文章置顶'),
	        _t('置顶的文章cid，多个请用逗号或空格分隔，留空则关闭')
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
            _t('代码高亮主题'),
            _t('设置代码高亮插件使用的主题')
        );
        $form->addInput($codeHighlightTheme);
    
    	
    	$commentsReply = new Typecho_Widget_Helper_Form_Element_Radio(
            'commentsReply',
            array(
                '1' => '开启',
                '0' => '关闭'
            ),
            '1',
            _t('评论回复'),
            _t('是否允许读者回复评论（不影响 Typecho 后台的评论嵌套设置）')
        );
        $form->addInput($commentsReply);

    	$customCss = new Typecho_Widget_Helper_Form_Element_Textarea(
            'customCss', 
            NULL, 
            NULL, 
            _t('自定义CSS样式'), 
            _t('不需要 `style` 标签')
            );
        $form->addInput($customCss);
        
        
        $customJs = new Typecho_Widget_Helper_Form_Element_Textarea(
            'customJs',
            NULL,
            NULL,
            _t('自定义JS'),
            _t('需要 `script` 标签')
            );
        $form->addInput($customJs);

}

	// 加载短代码扩展支持
	function themeInit($self) {
		$options = $self->widget('Widget_Options');
	    if ($options->shortcode) {
	        require_once __DIR__ . '/shortcode.php';
	    }
	}
	
	// 提取标题 ID 并缓存供侧边栏目录使用
	function waxy_process_toc($content) {
	    $items   = [];
	    $counter = 0;
	    $content = preg_replace_callback(
	        '/<h([1-4])([^>]*)>(.*?)<\/h[1-4]>/is',
	        function ($m) use (&$items, &$counter) {
	            $counter++;
	            $level = (int) $m[1];
	            $attrs = $m[2];
	            $inner = $m[3];
	            $text  = trim(strip_tags($inner));
	            $id    = 'toc-' . $counter;
	            if (!preg_match('/\bid\s*=/i', $attrs)) {
	                $attrs .= ' id="' . $id . '"';
	            } else {
	                preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attrs, $im);
	                $id = $im[1] ?? $id;
	            }
	            $items[] = ['level' => $level, 'text' => $text, 'id' => $id];
	            return '<h' . $level . $attrs . '>' . $inner . '</h' . $level . '>';
	        },
	        $content
	    );
	    $GLOBALS['waxy_toc_items'] = $items;
	    return $content;
	}

	// 文章页内容
	function getContent($content) {
	    $options = Typecho_Widget::widget('Widget_Options');
	    // 短代码
	    if ($options->shortcode) {
	    	$content = do_shortcode($content);
	    }
	    $content = getPicHtml($content);
	    $content = waxy_process_toc($content);

	    return $content;

	}
	
	
	//首页内容（全文模式）：<!--more--> 与渲染高度谁先触发用谁——
	//有 <!--more--> 就在此处截断并始终露出"阅读全文"；没有标记则展示完整正文，
	//是否需要收起改由前端按渲染后的实际高度判断（见 post_list.php + waxy-main.js initContentClamp）
	function getIndexContent($content) {
	    $options = Typecho_Widget::widget('Widget_Options');
	    // 短代码
	    if ($options->shortcode) {
	    	$content = do_shortcode($content);
	    }

	    $content = getPicHtml($content);

	    $parts = explode('<!--more-->', $content);

	    return [
	        'content'  => $parts[0],
	        'has_more' => isset($parts[1]),
	    ];
	}
	
	
	// 懒加载属性辅助：JS模式用 data-src 占位（防爬虫），原生模式用 loading="lazy"
	define('WAXY_IMG_PLACEHOLDER', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
	function waxy_lazy_placeholder() {
		$options = Typecho_Widget::widget('Widget_Options');
		$gif = trim($options->lazyloadGif ?? '');
		return $gif !== '' ? htmlspecialchars($gif, ENT_QUOTES) : WAXY_IMG_PLACEHOLDER;
	}

	// 图片功能
	function getPicHtml($content) {
		$options     = Typecho_Widget::widget('Widget_Options');
		$lazy        = (bool)$options->JQlazyload;
		$placeholder = $lazy ? waxy_lazy_placeholder() : '';
		$center      = ($options->picAlign !== 'left');
		$lightbox    = (bool)$options->fancyboxs;

		return preg_replace_callback('/<img\s[^>]*>/i', function($matches) use ($lazy, $placeholder, $center, $lightbox) {
			$tag = $matches[0];
			preg_match('/\bsrc="([^"]*)"/i',   $tag, $s);
			preg_match('/\balt="([^"]*)"/i',   $tag, $a);
			preg_match('/\btitle="([^"]*)"/i', $tag, $t);
			$src   = $s[1] ?? '';
			$alt   = htmlspecialchars($a[1] ?? '', ENT_QUOTES);
			$title = htmlspecialchars($t[1] ?? '', ENT_QUOTES);

			if (!$src) return $tag;

			if ($lazy) {
				$img = '<img data-src="' . $src . '" src="' . $placeholder . '" alt="' . $alt . '"' . ($title ? ' title="' . $title . '"' : '') . '>';
			} else {
				$img = '<img loading="lazy" src="' . $src . '" alt="' . $alt . '"' . ($title ? ' title="' . $title . '"' : '') . '>';
			}

			$inner = $lightbox
				? '<a data-lightbox="gallery" href="' . $src . '">' . $img . '</a>'
				: $img;

			if ($title) $inner .= '<span class="imgtitle">' . $title . '</span>';

			return $center ? '<center>' . $inner . '</center>' : $inner;
		}, $content);
	}


	// 摘要格式清理：从原始正文提取纯文本摘要，依次剥离代码块/表格/图片/短代码等非文本内容
	function getExcerpt($excerpt,$num,$str) {
	    $array=explode('<!--more-->', $excerpt);
	    $excerpt=$array[0];

	    //围栏代码块（```...``` / ~~~...~~~），连内容一起整块去掉
	    $excerpt = preg_replace('/```[\s\S]*?```|~~~[\s\S]*?~~~/','',$excerpt);
	    //行内代码
	    $excerpt = preg_replace('/`[^`]*`/','',$excerpt);
	    //表格（每行首尾都是 | 的 GFM 表格行，含表头分隔行）
	    $excerpt = preg_replace('/^[ \t]*\|.*\|[ \t]*$/m','',$excerpt);
	    //图片（连 alt 文字一并去掉，图片描述放摘要里没有意义）
	    $excerpt = preg_replace('/!\[[^\]]*\]\([^\)]*\)/','',$excerpt);
	    //短代码
	    $excerpt = preg_replace('/\[[A-Za-z0-9 ="\/]+?\]/','',$excerpt);
	    //标题/引用/无序列表
	    $excerpt = preg_replace('/^[ \t]*[-|>|#]+[ ]/m','',$excerpt);
	    //粗斜删字体样式
	    $excerpt = preg_replace('/[~|*]+?([\S\s]+?)[~|*]+?/','$1',$excerpt);
	    //超链接
	    $excerpt = preg_replace('/\[([\S\s]+?)\]\([\S\s]+?\)/','$1',$excerpt);
	    //分隔符
	    $excerpt = preg_replace('/[-|*]+?/','',$excerpt);
	    //兜底：正文若混了原生 HTML（表格/图片/代码等），一并剥掉标签
	    $excerpt = strip_tags($excerpt);
	    //空白折叠成单行，避免删空后留下大段空行
	    $excerpt = trim(preg_replace('/\s+/',' ',$excerpt));

		//使用mb_substr防止中文截取成乱码，需要开启extension=php_mbstring.dll扩展，一般都开了
		if($str!==''||$num!==-1){
		   return mb_substr($excerpt,0,$num,"UTF-8").$str;
		}else {
		   return $excerpt;
		}
	}
	
	//获取文章中第一个图片地址
	function getFirstImg($content){
	    $options = Typecho_Widget::widget('Widget_Options');
	    $img_url = '';
	    if($options->find_first_image){
	        preg_match_all('/\<img.*?src\=\"(.*?)\".*?[^>]*>/i',$content,$match);
	        if(!empty($match[1][0])){
    	      $img_url = $match[1][0];
    	      return $img_url;
    	    }
	    }
	    // 兜底图池对同一次请求内的所有文章都是同一份配置，用 static 缓存解析结果，
	    // 避免 post_list.php 循环里每篇文章都重新 explode/trim/filter 一遍
	    static $images_list = null;
	    if ($images_list === null) {
	        $images = $options->first_image;
	        // 过滤空行，避免随机抽到空行渲染出 background-image:url('')
	        $images_list = $images ? array_values(array_filter(array_map('trim', explode(PHP_EOL, $images)), function($url) {
	            return $url !== '';
	        })) : [];
	    }
	    if (!empty($images_list)) {
	        $img_url = $images_list[array_rand($images_list)];
	    }
	    return $img_url;
	}

	/**
	 * 解析文章封面图：优先取自定义字段 img，否则回退到正文首图（getFirstImg）。
	 * post.php / post_list.php 的全文/摘要模式共用此逻辑。
	 *
	 * @param  string $fields_img 自定义字段 $this->fields->img 的值
	 * @param  string $content    文章正文（用于回退提取首图）
	 * @return string             转义后的图片 URL；无封面图时返回空字符串
	 */
	function waxy_resolve_cover_img($fields_img, $content) {
	    if ($fields_img) {
	        return htmlspecialchars(trim($fields_img));
	    }
	    $first_img = getFirstImg($content);
	    return $first_img ? htmlspecialchars(trim($first_img)) : '';
	}

	/**
	 * 渲染一组分类/标签徽章（图标 + 若干 <a class="excerpt__badge">）。
	 * $max 为 null 时不做数量上限；传入数字时超出部分折叠为 +N 徽章，
	 * 悬浮/聚焦展开 .excerpt__badge-tip 列出其余项。
	 * post.php、post_list.php 的全文/摘要模式共用此逻辑。
	 *
	 * @param  array       $items    分类或标签数组，每项含 permalink/name
	 * @param  string      $icon     waxy_icon() 的图标名
	 * @param  string      $modifier 附加到每个 .excerpt__badge 上的额外 class（含前导空格），如 ' excerpt__badge--cat'
	 * @param  int|null    $max      显示上限，null 表示不限制
	 * @return string                渲染好的 HTML；$items 为空时返回空字符串
	 */
	function waxy_render_badge_group($items, $icon, $modifier = '', $max = null) {
	    $items = (array)$items;
	    if (empty($items)) {
	        return '';
	    }
	    $shown = $max !== null ? array_slice($items, 0, $max) : $items;
	    $rest  = $max !== null ? array_slice($items, $max) : [];

	    $html = waxy_icon($icon);
	    foreach ($shown as $item) {
	        $html .= '<a class="excerpt__badge' . $modifier . '" href="' . htmlspecialchars($item['permalink']) . '">' . htmlspecialchars($item['name']) . '</a>';
	    }
	    if ($rest) {
	        $html .= '<span class="excerpt__badge excerpt__badge--more" tabindex="0">+' . count($rest);
	        $html .= '<span class="excerpt__badge-tip">';
	        foreach ($rest as $item) {
	            $html .= '<a class="excerpt__badge-tip__item' . $modifier . '" href="' . htmlspecialchars($item['permalink']) . '">' . htmlspecialchars($item['name']) . '</a>';
	        }
	        $html .= '</span></span>';
	    }
	    return $html;
	}

	/**额外的一些小工具**/
	
	function  art_count ($cid){
	$db=Typecho_Db::get ();
	$rs=$db->fetchRow ($db->select ('table.contents.text')->from ('table.contents')->where ('table.contents.cid=?',$cid)->order ('table.contents.cid',Typecho_Db::SORT_ASC)->limit (1));
	if ($rs) echo mb_strlen($rs['text'], 'UTF-8');
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
            // 格式填错时 strtotime 返回 false，此时保留默认值，不要用 false 参与后面的计算
            $parsed_start_time = strtotime($start_Time);
            if ($parsed_start_time !== false) {
                $site_create_time = $parsed_start_time;
            }
        }
		$time = time() - $site_create_time;
		if(is_numeric($time)){
			$value = array(
				"years" => 0, "days" => 0, "hours" => 0,
				"minutes" => 0, "seconds" => 0,
			);
			// 依次按 年/天/时/分 取模拆解剩余秒数
			$units = array("years" => 31556926, "days" => 86400, "hours" => 3600, "minutes" => 60);
			foreach ($units as $unit => $seconds) {
				if ($time >= $seconds) {
					$value[$unit] = floor($time / $seconds);
					$time %= $seconds;
				}
			}
			$value["seconds"] = floor($time);

			
			if($value['years']>0){
				return $value['years'].'年'.$value['days'].'天'.$value['hours'].'小时'.$value['minutes'].'分';
			}else{
				return $value['days'].'天'.$value['hours'].'小时'.$value['minutes'].'分';
			}
		}else{
			return '';
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
        $checkRow = $db->fetchRow($db->select()->from('table.contents'));
        if (!$checkRow || !array_key_exists('views', $checkRow)) {
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
        if (empty($sticky)) {
            return;
        }

        // 过滤空白/非数字的 cid，避免填错格式时拼出无效查询
        $sticky_cids = array_values(array_filter(
            array_map('trim', explode(',', strtr($sticky, ' ', ','))),
            function($cid) { return ctype_digit($cid); }
        ));
        if (empty($sticky_cids)) {
            return;
        }

        $db = Typecho_Db::get();//获取数据库连接

        // 调用官方接口查询，能正确取到动态生成的文章 URL
        $sticky_post = $db->fetchAll($db->select()->from('table.contents')
            ->where('status = ?','publish')
            ->where('type = ?', 'post')
            ->where('cid in ?',$sticky_cids)
            ->order('cid', Typecho_Db::SORT_ASC)
        );
        // cid 填的都是不存在/未发布的文章时，不渲染空壳容器
        if (empty($sticky_post)) {
            return;
        }

        $sticky_html = '<article id="top-article" class="post top-posts">
                            <div class="post__featured" title="置顶文章">
                                ' . waxy_icon('bookmark') . '
                            </div>
                            <div class="top-posts__body"><div class="top-posts__slide"><ul class="top-posts__list js-slide-list">';

        // push() 把当前行数据压入 widget 内部状态并返回一个普通数组（不含 permalink 等
        // 需要计算的虚拟属性）；必须在 widget 实例本身上做 -> 属性访问才能触发
        // ___permalink() 等 magic getter，不能读返回值
        $contentWidget = Typecho_Widget::widget('Widget_Abstract_Contents');
        foreach($sticky_post as $val){
            $contentWidget->push($val);
            $post_title = htmlspecialchars($contentWidget->title);
            $permalink = $contentWidget->permalink;
            $time = date('Y年m月d日', $contentWidget->created);
            $sticky_html = $sticky_html . '<li><span><a href="'.$permalink.'">《'.$post_title.'》</a></span><span>（'.$time.'）</span></li>';
        }

        $sticky_html = $sticky_html . '</ul></div></div></article>';

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
        $top_text_html = '<article id="top-text" class="post announce">';
        $top_text_html .= '<div class="post__featured" title="公告">' . waxy_icon('comment') . '</div>';
        $top_text_html .= '<div class="announce__body">'. $top_text .'</div>';
        $top_text_html .= '</article>';
        }
      
        echo $top_text_html;
    }

    /**
     * 收集 Typecho 评论列表的完整数据（含嵌套回复），绕过默认 HTML 输出。
     * Typecho 1.2.1 的 listComments() 无 callback 参数，但若全局函数
     * threadedComments() 存在，threadedCommentsCallback() 会对每条评论
     * （含递归子评论）调用它；这里借助该钩子把评论数据收集到数组中，
     * 用 ob_start/ob_end_clean 丢弃其默认 HTML 输出。
     *
     * @param  object $comments Typecho 评论 widget（$this->comments() 的结果）
     * @return array            以 coid 为键的评论数据数组
     */
    function waxy_collect_comments($comments) {
        global $waxy_coms_collect;
        $waxy_coms_collect = [];

        if (!function_exists('threadedComments')) {
            function threadedComments($comment, $options)
            {
                global $waxy_coms_collect;
                $mail = $comment->mail ?? '';
                $waxy_coms_collect[$comment->coid] = [
                    'coid'    => $comment->coid,
                    'parent'  => $comment->parent,
                    'author'  => $comment->author,
                    'url'     => $comment->url,
                    'created' => $comment->created,
                    'content' => $comment->content,
                    'status'  => $comment->status,
                    'avatar'  => 'https://cravatar.cn/avatar/' . md5(strtolower(trim($mail))) . '?s=40&d=mp&r=g',
                ];
                // 递归收集子评论（调用 widget 的 threadedComments() 方法，
                // 它会对每个子评论再次触发本函数）
                $comment->threadedComments();
            }
        }

        ob_start();
        $comments->listComments();
        ob_end_clean();

        $coms = $waxy_coms_collect;
        $waxy_coms_collect = [];
        return $coms;
    }

    /**
     * 自定义CSS样式
     */
    function add_custom_css($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $css_text = $options->customCss;
        $css_text_html = '';
        if(!empty($css_text)){
        $css_text_html = '<style type="text/css">';
        $css_text_html = $css_text_html . $css_text;
        $css_text_html = $css_text_html . '</style>';
        }
        echo $css_text_html;
    }
    
    /**
     * 自定义JS样式
     */
    function add_custom_js($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $js_text = $options->customJs;
        $js_text_html = '';
        if(!empty($js_text)){
        $js_text_html =  $js_text;
        }
        echo $js_text_html;
    }

    /**
     * ICP备案
     */
    function add_ICP($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $ICP_text = trim($options->ICP ?? '');
        if (!empty($ICP_text)) {
            echo ' | <a rel="nofollow noopener noreferrer" href="https://beian.miit.gov.cn/" target="_blank">'
                . htmlspecialchars($ICP_text, ENT_QUOTES, 'UTF-8')
                . '</a>';
        }
    }

    /**
     * 渲染一条"扁平"分类菜单（menuDropdown 模式 0/1/4 共用）：
     * 不做层级嵌套，逐个分类输出同一种 <li>，可选按一级分类过滤、可选包一层外壳。
     *
     * @param  object $category Widget_Metas_Category_List（尚未 next() 过的游标）
     * @param  object $archive  当前 Widget_Archive（用于高亮当前分类，即模板里的 $this）
     * @param  bool   $topOnly  true 时只保留一级分类（levels == 0），过滤掉子分类
     * @param  string $wrapOpen  外层包裹起始 HTML（模式4 的"分类"主菜单），不需要时传空字符串
     * @param  string $wrapClose 外层包裹结束 HTML
     * @return string
     */
    function waxy_render_category_flat($category, $archive, $topOnly, $wrapOpen, $wrapClose) {
        $html = $wrapOpen;
        while ($category->next()) {
            if ($topOnly && $category->levels != 0) continue;
            $active = $archive->is('category', $category->slug) ? ' nav__item--active' : '';
            $html .= '<li class="nav__item' . $active . '"><a class="nav__link" href="' . $category->permalink
                . '" title="' . $category->name . '">' . $category->name . '</a></li>';
        }
        return $html . $wrapClose;
    }

    /**
     * 渲染带层级折叠的分类菜单（menuDropdown 模式 2/3/5 共用）。
     *
     * 用"预读一步"的状态机实现：遍历时先缓存当前节点（lest*），等看到下一个节点的
     * 层级才能判断缓存节点是否为父节点（下一个更深）还是叶子节点，因此循环结束后
     * 必须补渲染最后缓存的节点，并闭合所有仍处于展开状态的 <ul></li>。
     *
     * @param  object $category      分类游标
     * @param  object $archive       当前 Widget_Archive（高亮判断）
     * @param  bool   $fullNesting   true：按实际层级逐级嵌套（模式3/5）；
     *                               false：只在一级分类处收纳子分类，且只收纳进一层列表（模式2）
     * @param  bool   $topLevelCaret true：一级分类的下拉开关用 caret 图标、更深层级用 more 样式（模式2/3）；
     *                               false：所有下拉开关统一用 more 样式、不显示 caret（模式5，
     *                               因为最外层已经有"分类"主菜单自己的 caret）
     * @param  string $wrapOpen      外层包裹起始 HTML（模式5 的"分类"主菜单），不需要时传空字符串
     * @param  string $wrapClose     外层包裹结束 HTML
     * @return string
     */
    function waxy_render_category_nested($category, $archive, $fullNesting, $topLevelCaret, $wrapOpen, $wrapClose) {
        $html = $wrapOpen;
        $lestLevel = -1;
        $lestSlug = $lestName = $lestLink = '';

        $renderItem = function ($isOpener) use (&$lestLevel, &$lestSlug, &$lestName, &$lestLink, $archive, $topLevelCaret) {
            $active = $archive->is('category', $lestSlug) ? ' nav__item--active' : '';
            if (!$isOpener) {
                return '<li class="nav__item' . $active . '"><a class="nav__link" href="' . $lestLink
                    . '" title="' . $lestName . '">' . $lestName . '</a></li>';
            }
            if ($topLevelCaret) {
                $moreClass = $lestLevel != 0 ? ' more' : '';
                $caret     = $lestLevel == 0 ? '<span class="nav__caret"></span>' : '';
            } else {
                $moreClass = ' more';
                $caret     = '';
            }
            return '<li class="nav__item' . $active . '"><a class="nav__link' . $moreClass . '" href="' . $lestLink
                . '" title="' . $lestName . '">' . $lestName . $caret . '</a><ul class="nav__sub">';
        };

        while ($category->next()) {
            if ($lestLevel == -1) {
                $lestLevel = $category->levels; $lestSlug = $category->slug;
                $lestName  = $category->name;   $lestLink = $category->permalink;
                continue;
            }

            $opens = $fullNesting
                ? ($lestLevel < $category->levels)
                : ($lestLevel < $category->levels && $lestLevel == 0);
            $html .= $renderItem($opens);

            if ($fullNesting) {
                if ($lestLevel > $category->levels) {
                    $x = $lestLevel - $category->levels;
                    while ($x > 0) { $html .= '</ul></li>'; $x--; }
                }
            } else {
                if ($lestLevel > $category->levels && $category->levels == 0) {
                    $html .= '</ul></li>';
                }
            }

            $lestLevel = $category->levels; $lestSlug = $category->slug;
            $lestName  = $category->name;   $lestLink = $category->permalink;
        }

        // 补渲染最后缓存的节点（循环里始终是"下一个节点出现才渲染上一个"，最后一个节点
        // 不会有下一个节点触发它，必然是叶子节点），并闭合仍展开着的 <ul></li>
        if ($lestLevel >= 0) {
            $html .= $renderItem(false);
            if ($fullNesting) {
                $x = $lestLevel;
                while ($x > 0) { $html .= '</ul></li>'; $x--; }
            } elseif ($lestLevel > 0) {
                $html .= '</ul></li>';
            }
        }

        return $html . $wrapClose;
    }

    /**
     * 渲染分类下拉菜单，对应主题选项 menuDropdown 的 6 种展示样式：
     *   0 — 全部展开（含子分类），扁平列表
     *   1 — 全部展开（仅一级分类），扁平列表，过滤掉子分类
     *   2 — 部分收纳：一级分类展开，子分类收纳进"仅一个"下拉列表
     *   3 — 部分收纳：一级分类展开，子分类按层级各自收纳（多级下拉）
     *   4 — 全部收纳：所有分类放入一个"分类"主菜单下的单层下拉列表
     *   5 — 全部收纳：所有分类放入一个"分类"主菜单下，按层级各自收纳（多级下拉）
     * 其余值（如 6 = 不显示）不输出任何内容。
     *
     * @param  object $category Widget_Metas_Category_List（尚未 next() 过的游标）
     * @param  int    $mode     menuDropdown 选项值
     * @param  object $archive  当前 Widget_Archive（即模板里的 $this）
     * @return string
     */
    function waxy_render_category_menu($category, $mode, $archive) {
        $wrapOpen  = '<li class="nav__item"><a class="nav__link">分类<span class="nav__caret"></span></a><ul class="nav__sub">';
        $wrapClose = '</ul></li>';
        switch ((int)$mode) {
            case 0:
                return waxy_render_category_flat($category, $archive, false, '', '');
            case 1:
                return waxy_render_category_flat($category, $archive, true, '', '');
            case 2:
                return waxy_render_category_nested($category, $archive, false, true, '', '');
            case 3:
                return waxy_render_category_nested($category, $archive, true, true, '', '');
            case 4:
                return waxy_render_category_flat($category, $archive, false, $wrapOpen, $wrapClose);
            case 5:
                return waxy_render_category_nested($category, $archive, true, false, $wrapOpen, $wrapClose);
            default:
                return '';
        }
    }

    /**
     * 渲染完整导航菜单项（首页 + 分类 + 独立页面 + 自定义链接）。
     * 桌面横向导航（#main-menu）和移动端抽屉导航各自单独调用一次本函数直接渲染，
     * 不做 JS 克隆——两处标签结构、交互都相同，只是外层容器的 CSS 表现不同
     * （抽屉里 .nav__sub 默认全展开，样式向侧边栏分类列表看齐）。
     *
     * @param  object $archive 当前 Widget_Archive（即模板里的 $this）
     * @return string
     */
    function waxy_render_nav_menu($archive) {
        $options = Typecho_Widget::widget('Widget_Options');
        $html = '<li class="nav__item' . ($archive->is('index') ? ' nav__item--active' : '') . '">'
            . '<a class="nav__link" href="' . $options->siteUrl . '">' . _t('首页') . '</a></li>';

        $archive->widget('Widget_Metas_Category_List')->to($category);
        $html .= waxy_render_category_menu($category, $options->menuDropdown, $archive);

        if ($options->pageDropdown) {
            $html .= '<li class="nav__item"><a class="nav__link">独立页面<span class="nav__caret"></span></a><ul class="nav__sub">';
        }
        $archive->widget('Widget_Contents_Page_List')->to($pages);
        while ($pages->next()) {
            $active = $archive->is('page', $pages->slug) ? ' nav__item--active' : '';
            $html .= '<li class="nav__item' . $active . '"><a class="nav__link" href="' . $pages->permalink
                . '" title="' . $pages->title . '">' . $pages->title . '</a></li>';
        }
        if ($options->pageDropdown) $html .= '</ul></li>';

        ob_start();
        add_menu_link($options);
        $html .= ob_get_clean();

        return $html;
    }

    /**
     * 解析"一行一条，逗号分隔"格式的 option 文本（友链/自定义菜单/社交链接三处共用）。
     * 按行拆分后再按逗号拆分，跳过空行；某行字段数少于 $minFields 时整行跳过。
     *
     * @param  string $raw       原始 option 文本
     * @param  int    $minFields 每行至少需要的字段数
     * @param  int    $limit     explode(',', ...) 的 limit 参数；0 表示不限制字段数
     * @return array             每行一个"已 trim 的字段数组"
     */
    function waxy_parse_option_lines($raw, $minFields = 1, $limit = 0) {
        $rows = [];
        if (empty($raw)) return $rows;
        foreach (explode(PHP_EOL, $raw) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $fields = $limit > 0 ? explode(',', $line, $limit) : explode(',', $line);
            if (count($fields) < $minFields) continue;
            $rows[] = array_map('trim', $fields);
        }
        return $rows;
    }

    /**
     * 显示友链
     */
    function add_links($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $links_html = '';
        foreach (waxy_parse_option_lines($options->links, 4) as $row) {
            $name = htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8');
            $url  = htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8');
            $icon = htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8');
            $desc = htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8');
            $links_html .= '<div class="recent-posts__item"><a rel="noopener" href="'
                . $url . '" title="' . $desc . '" target="_blank" class="recent-posts__title">'
                . '<img src="' . $icon . '" alt="' . $name . '" height="32">'
                . '<span style="margin-left: 10px;">' . $name . '</span></a></div>';
        }
        echo $links_html;
    }
    /**
     * 显示自定义链接
     */
    function add_menu_link($options){
        $menuLink_html = '';
        foreach (waxy_parse_option_lines($options->menuLink, 2) as $row) {
            $ml_name = htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8');
            $ml_url  = htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8');
            $menuLink_html .= '<li class="nav__item"><a class="nav__link" href="'
                . $ml_url . '" title="' . $ml_name . '">' . $ml_name . '</a></li>';
        }
        echo $menuLink_html;
    }
    /**
     * 显示社交/分享链接
     */
    function add_cardlinks($archive){
        $options = Typecho_Widget::widget('Widget_Options');
        $cardlinks = $options->cardlinks;
        $cardlinks_html = '';
        if(!empty($cardlinks)){
            $cardlinks_html = $cardlinks_html . '<div class="card__links">';
            foreach (waxy_parse_option_lines($cardlinks, 2, 2) as $row) {
                $icon = waxy_icon('social-' . strtolower($row[0]));
                if ($icon === '') continue;
                $cl_name = htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8');
                $cl_url  = htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8');
                $cardlinks_html .= '<a href="' . $cl_url . '" title="' . $cl_name . '">' . $icon . '</a>';
            }
            $cardlinks_html = $cardlinks_html . '</div>';

        }
        echo $cardlinks_html;
    }
