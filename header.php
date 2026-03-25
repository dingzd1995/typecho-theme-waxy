<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html class="no-js" lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
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
    // 仅在单篇文章/独立页且正文含代码块时才加载高亮资源
    $_has_code = false;
    if ($this->options->codeHighlightControl && ($this->is('single') || $this->is('page')) && empty($this->password)) {
        $_has_code = strpos($this->text, '```') !== false
                  || strpos($this->text, '~~~') !== false
                  || stripos($this->text, '<pre') !== false;
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

        <?php /* 亮暗色切换按钮（移动端：显示在导航栏；桌面端：在 .nav 内的搜索框前） */
        $waxy_theme_toggle_icons = '<svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/></svg><svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707z"/></svg>';
        ?>
        <button class="waxy-theme-toggle waxy-theme-toggle--mobile" data-action="theme-toggle" aria-label="切换亮暗色模式">
            <?php echo $waxy_theme_toggle_icons; ?>
        </button>

        <button class="site-header__toggle" data-action="nav-toggle" aria-label="导航切换">
            <?php echo waxy_icon('menu-hamburger'); ?>
        </button>

        <div class="nav" id="main-menu">
            <ul class="nav__list">
                <li class="nav__item<?php if($this->is('index')): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a></li>

                <?php $this->widget('Widget_Metas_Category_List')->to($category); $lestCategory = null; ?>

                <?php if ($this->options->menuDropdown == 5): $lestLevel = -1; ?>
                <li class="nav__item"><a class="nav__link">分类<span class="nav__caret"></span></a>
                    <ul class="nav__sub">
                <?php while ($category->next()): ?>
                <?php if ($lestLevel == -1) { $lestLevel = $category->levels; $lestSlug = $category->slug; $lestName = $category->name; $lestLink = $category->permalink; continue; }; ?>
                <?php if ($lestLevel < $category->levels): ?>
                    <li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link more" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?></a>
                        <ul class="nav__sub">
                <?php else: ?>
                    <li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?></a></li>
                <?php endif; ?>
                <?php $level = $lestLevel - $category->levels; if ($lestLevel > $category->levels) { $x = (int)$level; while ($x > 0) { echo "</ul></li>"; $x--; } } ?>
                <?php $lestLevel = $category->levels; $lestSlug = $category->slug; $lestName = $category->name; $lestLink = $category->permalink; ?>
                <?php endwhile; ?>
                <?php if ($lestLevel >= 0): ?><li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?></a></li><?php $x = (int)$lestLevel; while ($x > 0) { echo "</ul></li>"; $x--; } ?><?php endif; ?></ul></li>
                <?php endif; ?>

                <?php if ($this->options->menuDropdown == 4): ?>
                <li class="nav__item"><a class="nav__link">分类<span class="nav__caret"></span></a>
                    <ul class="nav__sub">
                <?php while ($category->next()): ?>
                <li class="nav__item<?php if($this->is('category', $category->slug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>"><?php $category->name(); ?></a></li>
                <?php endwhile; ?></ul></li>
                <?php endif; ?>

                <?php if ($this->options->menuDropdown == 3): $lestLevel = -1; ?>
                <?php while ($category->next()): ?>
                <?php if ($lestLevel == -1) { $lestLevel = $category->levels; $lestSlug = $category->slug; $lestName = $category->name; $lestLink = $category->permalink; continue; }; ?>
                <?php if ($lestLevel < $category->levels): ?>
                    <li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link<?php if (!$lestLevel == 0): ?> more<?php endif; ?>" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?><?php if ($lestLevel == 0): ?><span class="nav__caret"></span><?php endif; ?></a>
                        <ul class="nav__sub">
                <?php else: ?>
                    <li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?></a></li>
                <?php endif; ?>
                <?php $level = $lestLevel - $category->levels; if ($lestLevel > $category->levels) { $x = (int)$level; while ($x > 0) { echo "</ul></li>"; $x--; } } ?>
                <?php $lestLevel = $category->levels; $lestSlug = $category->slug; $lestName = $category->name; $lestLink = $category->permalink; ?>
                <?php endwhile; ?>
                <?php endif; ?>

                <?php if ($this->options->menuDropdown == 2): $lestLevel = -1; ?>
                <?php while ($category->next()): ?>
                <?php if ($lestLevel == -1) { $lestLevel = $category->levels; $lestSlug = $category->slug; $lestName = $category->name; $lestLink = $category->permalink; continue; }; ?>
                <?php if ($lestLevel < $category->levels && $lestLevel == 0): ?>
                    <li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link<?php if (!$lestLevel == 0): ?> more<?php endif; ?>" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?><?php if ($lestLevel == 0): ?><span class="nav__caret"></span><?php endif; ?></a>
                        <ul class="nav__sub">
                <?php else: ?>
                    <li class="nav__item<?php if($this->is('category', $lestSlug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php echo $lestLink; ?>" title="<?php echo $lestName; ?>"><?php echo $lestName; ?></a></li>
                <?php endif; ?>
                <?php if ($lestLevel > $category->levels && $category->levels == 0) { echo "</ul></li>"; } ?>
                <?php $lestLevel = $category->levels; $lestSlug = $category->slug; $lestName = $category->name; $lestLink = $category->permalink; ?>
                <?php endwhile; ?>
                <?php endif; ?>

                <?php if ($this->options->menuDropdown == 1): ?>
                <?php while ($category->next()): ?>
                <?php if ($category->levels == 0): ?>
                <li class="nav__item<?php if($this->is('category', $category->slug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>"><?php $category->name(); ?></a></li>
                <?php endif; ?>
                <?php endwhile; ?>
                <?php endif; ?>

                <?php if ($this->options->menuDropdown == 0): ?>
                <?php while ($category->next()): ?>
                <li class="nav__item<?php if($this->is('category', $category->slug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>"><?php $category->name(); ?></a></li>
                <?php endwhile; ?>
                <?php endif; ?>

                <?php if ($this->options->pageDropdown): ?>
                <li class="nav__item"><a class="nav__link">独立页面<span class="nav__caret"></span></a>
                    <ul class="nav__sub">
                <?php endif; ?>
                <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                <?php while ($pages->next()): ?>
                <li class="nav__item<?php if($this->is('page', $pages->slug)): ?> nav__item--active<?php endif; ?>"><a class="nav__link" href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a></li>
                <?php endwhile; ?>
                <?php if ($this->options->pageDropdown): ?></ul></li><?php endif; ?>

                <?php add_menu_link($this); ?>
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
    <div class="waxy-drawer__body" id="waxy-drawer-body"></div>
</div>

