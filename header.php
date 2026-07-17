<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html class="no-js" lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php $this->options->faviconUrl(); ?>" type="image/x-icon" />
    <?php
    $_site_title = $this->options->title;
    $_site_url   = rtrim($this->options->siteUrl, '/');
    $_site_desc  = strip_tags($this->options->description ?? '');

    $_seo_title   = $_site_title;
    $_seo_desc    = $_site_desc;
    $_seo_url     = $_site_url . '/';
    $_seo_type    = 'website';
    $_seo_img     = '';
    $_seo_noindex = false;
    $_seo_is_art  = false;
    $_seo_is_404  = !empty($GLOBALS['waxy_is_404']);
    $_seo_author  = '';
    $_seo_tags    = [];
    $_seo_cat     = '';
    $_pag_prev    = '';
    $_pag_next    = '';

    // 当前请求完整 URL（用于归档页）
    $_proto   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $_req_url = $_proto . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
    if (substr($_req_url, -1) !== '/') $_req_url .= '/';

    if ($_seo_is_404) {
        $_seo_noindex = true;
        $_seo_title   = '页面不存在 - ' . $_site_title;
        $_seo_url     = ''; // 404 页不输出 canonical
    } elseif ($this->is('single') || $this->is('page')) {
        $_seo_is_art    = $this->is('single');
        $_is_protected  = !empty($this->password);
        $_seo_type      = 'article';
        $_seo_title     = ($_is_protected ? '加密文章' : $this->title) . ' - ' . $_site_title;
        $_seo_url       = $this->permalink;
        $_seo_author    = $this->author->name ?? '';
        if (!$_is_protected) {
            if (!empty($this->fields->info)) {
                $_seo_desc = strip_tags($this->fields->info);
            } else {
                $_seo_desc = getExcerpt($this->text, 160, '');
            }
            if (!empty($this->fields->img)) {
                $_seo_img = htmlspecialchars(trim($this->fields->img));
            } elseif (($_ = getFirstImg($this->content))) {
                $_seo_img = htmlspecialchars(trim($_));
            }
        }
        if ($_seo_is_art) {
            ob_start(); $this->tags(',');   $_t = ob_get_clean();
            ob_start(); $this->category(','); $_seo_cat = strip_tags(ob_get_clean());
            if ($_t) $_seo_tags = array_values(array_filter(array_map('trim', explode(',', strip_tags($_t)))));
        }
    } elseif ($this->is('search')) {
        $_seo_noindex = true;
        $_seo_title   = '搜索结果 - ' . $_site_title;
        $_seo_url     = $_req_url;
    } elseif (!$this->is('index')) {
        // 归档页 canonical：去掉 /page/N/（首页分页）
        $_seo_url = preg_replace('#/page/\d+/$#', '/', $_req_url);
        // 标签/分类用 /slug/N/ 分页，第一页 /1/ 与 /slug/ 重复，去掉
        if ($this->is('category') || $this->is('tag') || $this->is('author')) {
            $_seo_url = preg_replace('#/1/$#', '/', $_seo_url);
        }
    }

    $_seo_desc = mb_substr(strip_tags($_seo_desc), 0, 160, 'UTF-8');

    // 分页 prev / next
    if (!$_seo_is_404 && !$this->is('single') && !$this->is('page') && !$this->is('search')) {
        $_page_size   = max(1, (int)$this->parameter->pageSize);
        $_total_pages = (int)ceil($this->getTotal() / $_page_size);
        $_cur_page    = max(1, (int)$this->_currentPage);
        // 作者/标签/分类归档第 2 页以上 noindex，避免深度分页被收录
        if ($_cur_page > 1 && ($this->is('author') || $this->is('tag') || $this->is('category'))) {
            $_seo_noindex = true;
        }
        if ($_total_pages > 1) {
            $_base = rtrim(preg_replace('#/page/\d+/$#', '/', $_req_url), '/') . '/';
            if ($_cur_page > 1)
                $_pag_prev = $_cur_page === 2 ? $_base : $_base . 'page/' . ($_cur_page - 1) . '/';
            if ($_cur_page < $_total_pages)
                $_pag_next = $_base . 'page/' . ($_cur_page + 1) . '/';
        }
    }

    // 面包屑条目
    $_bc = [['name' => '首页', 'url' => $_site_url . '/']];
    if ($this->is('single') || $this->is('page')) {
        $_bc[] = ['name' => $this->title, 'url' => $_seo_url];
    } elseif (!$_seo_is_404 && !$this->is('index') && !$this->is('search')) {
        if ($this->is('category'))      $_bc[] = ['name' => '分类', 'url' => ''];
        elseif ($this->is('tag'))       $_bc[] = ['name' => '标签', 'url' => ''];
        else                            $_bc[] = ['name' => '归档', 'url' => ''];
        ob_start();
        $this->archiveTitle(['category' => '%s', 'tag' => '%s', 'author' => '%s', 'date' => '%s'], '', '');
        $_arc_name = strip_tags(ob_get_clean());
        if ($_arc_name) $_bc[] = ['name' => $_arc_name, 'url' => $_seo_url];
    }
    ?>
    <title><?php echo htmlspecialchars($_seo_title); ?></title>
    <?php if ($_seo_noindex): ?>
    <meta name="robots" content="noindex, follow">
    <?php endif; ?>
    <meta name="description" content="<?php echo htmlspecialchars($_seo_desc); ?>">
    <?php if ($_seo_author): ?>
    <meta name="author" content="<?php echo htmlspecialchars($_seo_author); ?>">
    <?php endif; ?>
    <?php if ($_seo_url): ?><link rel="canonical" href="<?php echo htmlspecialchars($_seo_url); ?>"><?php endif; ?>
    <link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($_site_title); ?>" href="<?php $this->options->feedUrl(); ?>">
    <?php if ($_pag_prev): ?><link rel="prev" href="<?php echo htmlspecialchars($_pag_prev); ?>"><?php endif; ?>
    <?php if ($_pag_next): ?><link rel="next" href="<?php echo htmlspecialchars($_pag_next); ?>"><?php endif; ?>
    <!-- Open Graph -->
    <meta property="og:site_name" content="<?php echo htmlspecialchars($_site_title); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($_seo_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($_seo_desc); ?>">
    <?php if ($_seo_url): ?><meta property="og:url" content="<?php echo htmlspecialchars($_seo_url); ?>"><?php endif; ?>
    <meta property="og:type" content="<?php echo $_seo_type; ?>">
    <?php if ($_seo_img): ?>
    <meta property="og:image" content="<?php echo $_seo_img; ?>">
    <?php endif; ?>
    <?php if ($_seo_is_art): ?>
    <meta property="article:published_time" content="<?php $this->date('c'); ?>">
    <meta property="article:modified_time" content="<?php echo date('c', $this->modified); ?>">
    <?php if ($_seo_cat): ?>
    <meta property="article:section" content="<?php echo htmlspecialchars($_seo_cat); ?>">
    <?php endif; ?>
    <?php foreach ($_seo_tags as $_tag): ?>
    <meta property="article:tag" content="<?php echo htmlspecialchars($_tag); ?>">
    <?php endforeach; ?>
    <?php endif; ?>
    <!-- Twitter Card -->
    <meta name="twitter:card" content="<?php echo $_seo_img ? 'summary_large_image' : 'summary'; ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($_seo_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($_seo_desc); ?>">
    <?php if ($_seo_img): ?>
    <meta name="twitter:image" content="<?php echo $_seo_img; ?>">
    <?php endif; ?>
    <?php if ($this->is('single') || $this->is('page')): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "<?php echo $_seo_is_art ? 'Article' : 'WebPage'; ?>",
      "headline": <?php echo json_encode($this->title, JSON_UNESCAPED_UNICODE); ?>,
      "description": <?php echo json_encode($_seo_desc, JSON_UNESCAPED_UNICODE); ?>,
      "url": <?php echo json_encode($_seo_url); ?>,
      "datePublished": "<?php $this->date('c'); ?>",
      "dateModified": "<?php echo date('c', $this->modified); ?>",
      "author": {"@type": "Person", "name": <?php echo json_encode($_seo_author, JSON_UNESCAPED_UNICODE); ?>},
      "publisher": {"@type": "Organization", "name": <?php echo json_encode($_site_title, JSON_UNESCAPED_UNICODE); ?>}<?php if ($_seo_img): ?>,
      "image": <?php echo json_encode($_seo_img); ?>
      <?php endif; ?>
    }
    </script>
    <?php endif; ?>
    <?php if (!$_seo_is_404): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [<?php
        $i = 1;
        $parts = [];
        foreach ($_bc as $_item) {
            $entry = '{"@type":"ListItem","position":' . $i++ . ',"name":' . json_encode($_item['name'], JSON_UNESCAPED_UNICODE);
            if (!empty($_item['url'])) $entry .= ',"item":' . json_encode($_item['url']);
            $entry .= '}';
            $parts[] = $entry;
        }
        echo implode(',', $parts);
      ?>]
    }
    </script>
    <?php endif; ?>

    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/waxy-main.css'); ?>">

    <?php
    // 单篇文章/独立页：仅正文含代码块时才加载高亮资源；
    // 列表页全文模式：文章内容逐条渲染，头部阶段无法预判是否含代码块，按需直接加载
    $_has_code = false;
    if ($this->options->codeHighlightControl) {
        if (($this->is('single') || $this->is('page')) && empty($this->password)) {
            $_has_code = strpos($this->text, '```') !== false
                      || strpos($this->text, '~~~') !== false
                      || stripos($this->text, '<pre') !== false;
        } elseif ($this->options->articles_list == 1) {
            $_has_code = true;
        }
    }
    $GLOBALS['waxy_has_code'] = $_has_code;
    ?>
    <?php if ($_has_code): ?>
    <?php $_prism_css = htmlspecialchars(rtrim($this->options->themeUrl, '/') . '/lib/prism/css/' . $this->options->codeHighlightTheme); ?>
    <link rel="preload" href="<?php echo $_prism_css; ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo $_prism_css; ?>"></noscript>
    <?php endif; ?>

    <?php add_custom_css($this); ?>
    <?php $this->header(); ?>
    <script>
        (function() {
            var saved = localStorage.getItem('waxy-theme');
            if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="home-template">
<?php if ($this->options->load_html): ?>
<div id="loading" class="loading__overlay">
    <div class="loading">
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span>
    </div>
</div>
<?php endif; ?>
<!-- start navigation -->
<nav class="site-header">
    <div class="site-header__inner">

        <a class="site-header__brand" href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->description(); ?>">
            <?php if (!empty($this->options->logoUrl)): ?>
            <img src="<?php $this->options->logoUrl(); ?>" height="45" alt="<?php $this->options->title(); ?>">
            <?php else: ?><?php $this->options->title(); ?><?php endif; ?>
        </a>

        <?php if ($this->options->navbarSearch): ?>
        <div class="site-header__search-mobile">
            <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                <label for="s-mobile" class="sr-only"><?php _e('搜索关键字'); ?></label>
                <input aria-label="search input" type="text" id="s-mobile" name="s" class="search-form__input" placeholder="<?php _e('输入关键字搜索'); ?>" />
                <button type="submit" class="search-form__btn" aria-label="搜索"></button>
            </form>
        </div>
        <?php endif; ?>

        <?php /* 亮暗色切换按钮（移动端：显示在导航栏；桌面端：在 .nav 内的搜索框前）
        图标复用 lib/icons.php 的统一图标库，CSS 通过 .icon-moon/.icon-sun 控制亮/暗色下的显隐 */
        $waxy_theme_toggle_icons = waxy_icon('theme-moon', 'icon-moon') . waxy_icon('theme-sun', 'icon-sun');
        ?>
        <button class="waxy-theme-toggle waxy-theme-toggle--mobile" data-action="theme-toggle" aria-label="切换亮暗色模式">
            <?php echo $waxy_theme_toggle_icons; ?>
        </button>

        <button class="site-header__toggle" data-action="nav-toggle" aria-label="导航切换">
            <?php echo waxy_icon('menu-hamburger'); ?>
        </button>

        <?php
        // 分类/独立页面用的是 Typecho 的 widget 游标（Widget_Metas_Category_List / Widget_Contents_Page_List），
        // 同一个 widget 名在同一次请求里会被 Typecho 缓存复用，第二次调用拿到的会是已经 next() 耗尽的同一个游标，
        // 导致抽屉那份菜单里分类/页面全部渲染成空。这里只渲染一次，桌面导航和抽屉导航共用同一段 HTML。
        $waxy_nav_html = waxy_render_nav_menu($this);
        ?>
        <div class="nav" id="main-menu">
            <ul class="nav__list">
                <?php echo $waxy_nav_html; ?>
            </ul>

            <button class="waxy-theme-toggle waxy-theme-toggle--desktop" data-action="theme-toggle" aria-label="切换亮暗色模式">
                <?php echo $waxy_theme_toggle_icons; ?>
            </button>

            <?php if ($this->options->navbarSearch): ?>
            <form class="nav__search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                <label for="s-desktop" class="sr-only"><?php _e('搜索关键字'); ?></label>
                <input type="text" id="s-desktop" name="s" class="search-form__input" placeholder="<?php _e('输入关键字搜索'); ?>" />
                <button type="submit" class="search-form__btn" aria-label="搜索"></button>
            </form>
            <?php endif; ?>
        </div>

    </div>
</nav>
<!-- end navigation -->

<!-- Drawer overlay & panel -->
<div class="waxy-drawer-overlay" id="waxy-drawer-overlay"></div>
<div class="waxy-drawer" id="waxy-drawer">
    <div class="waxy-drawer__body" id="waxy-drawer-body">
        <div class="waxy-drawer__nav">
            <ul class="nav__list">
                <?php echo $waxy_nav_html; ?>
            </ul>
        </div>
    </div>
</div>

