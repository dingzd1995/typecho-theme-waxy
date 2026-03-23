<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html class="no-js" lang="zh-cmn-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="<?php $this->options->faviconUrl(); ?>" type="image/x-icon" />
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>

    <?php if ($this->options->load_html): ?>
    <style>
        :root { --waxy-primary: #f4645f; }
        .loading__overlay {
            position: fixed; width: 100%; height: 100%;
            background-color: var(--waxy-surface, #fff); z-index: 99999;
        }
        .loading {
            position: relative; width: 85px; height: 40px;
            margin: -20px auto auto -40px; top: 50%; left: 50%;
            display: flex; align-items: center; justify-content: space-between;
        }
        .loading span {
            display: inline-block; width: 8px; height: 100%;
            border-radius: 4px;
            background: var(--waxy-primary, #f4645f);
            opacity: 0.45;
            animation: waxy-load 1s ease infinite;
            -webkit-animation: waxy-load 1s ease infinite;
        }
        @keyframes waxy-load {
            0%, 100% { height: 40px; opacity: 0.45; }
            50% { height: 70px; margin: -15px 0; opacity: 1; }
        }
        @-webkit-keyframes waxy-load {
            0%, 100% { height: 40px; opacity: 0.45; }
            50% { height: 70px; margin: -15px 0; opacity: 1; }
        }
        .loading span:nth-child(2) { animation-delay: 0.2s; -webkit-animation-delay: 0.2s; }
        .loading span:nth-child(3) { animation-delay: 0.4s; -webkit-animation-delay: 0.4s; }
        .loading span:nth-child(4) { animation-delay: 0.6s; -webkit-animation-delay: 0.6s; }
        .loading span:nth-child(5) { animation-delay: 0.8s; -webkit-animation-delay: 0.8s; }
    </style>
    <div id="loading" class="loading__overlay">
        <div class="loading">
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span>
        </div>
    </div>
    <?php endif; ?>

    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/waxy-main.css'); ?>">

    <?php if ($this->options->codeHighlightControl): ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('lib/prism/css/'); ?><?php $this->options->codeHighlightTheme(); ?>" />
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

