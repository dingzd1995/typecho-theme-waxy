/* 回到顶部按钮 */
function initBackToTop() {
    var bt = document.getElementById('back-to-top');
    if (!bt) return;
    bt.style.display = 'flex';
    bt.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

/* 抽屉菜单（导航 + 侧边栏，<1000px） */
function initNavToggle() {
    var btn = document.querySelector('[data-action="nav-toggle"]');
    var overlay = document.getElementById('waxy-drawer-overlay');
    var drawer = document.getElementById('waxy-drawer');
    var drawerBody = document.getElementById('waxy-drawer-body');

    if (!btn || !drawer) return;

    /* 首次在移动端时填充抽屉内容 */
    if (window.innerWidth < 1000 && drawerBody && !drawerBody.hasChildNodes()) {
        var nav = document.getElementById('main-menu');
        if (nav) {
            var navWrap = document.createElement('div');
            navWrap.className = 'waxy-drawer__nav';

            var navTitle = document.createElement('h4');
            navTitle.className = 'widget__title';
            navTitle.textContent = '导航';
            navWrap.appendChild(navTitle);

            var navContent = document.createElement('div');
            navContent.innerHTML = nav.innerHTML;
            navContent.querySelectorAll('.is-open').forEach(function(el) { el.classList.remove('is-open'); });
            navWrap.appendChild(navContent);

            drawerBody.appendChild(navWrap);
        }
        var sidebar = document.querySelector('.layout__sidebar');
        if (sidebar) {
            var sidebarWrap = document.createElement('div');
            sidebarWrap.className = 'waxy-drawer__sidebar';
            sidebarWrap.appendChild(sidebar.cloneNode(true));
            drawerBody.appendChild(sidebarWrap);
        }
    }

    function openDrawer() {
        drawer.classList.add('is-open');
        if (overlay) overlay.classList.add('is-open');
        btn.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        drawer.classList.remove('is-open');
        if (overlay) overlay.classList.remove('is-open');
        btn.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    btn.addEventListener('click', function() {
        drawer.classList.contains('is-open') ? closeDrawer() : openDrawer();
    });
    if (overlay) overlay.addEventListener('click', closeDrawer);
}

/* 下拉菜单（移动端触摸支持，事件委托，<1000px） */
function initMenuDropdown() {
    document.addEventListener('click', function(e) {
        if (window.innerWidth >= 1000) return;
        var item = e.target.closest('.nav__item');
        if (item && item.querySelector(':scope > .nav__sub')) {
            /* 有子菜单：只展开/收起，阻止链接跳转 */
            e.stopPropagation();
            e.preventDefault();
            item.classList.toggle('is-open');
        } else if (!e.target.closest('.nav__item')) {
            document.querySelectorAll('.nav__item.is-open').forEach(function(el) {
                el.classList.remove('is-open');
            });
        }
    });
}

/* 置顶文章滚动 */
function initStickySlider() {
    var parent = document.querySelector('.js-slide-list');
    if (!parent) return;
    var items = parent.querySelectorAll('li');
    if (items.length < 2) return;
    setInterval(function() {
        var first = parent.querySelector('li:first-child');
        if (!first) return;
        var height = first.offsetHeight;
        first.style.transition = 'margin-top 0.5s ease';
        first.style.marginTop = '-' + height + 'px';
        setTimeout(function() {
            first.style.transition = '';
            first.style.marginTop = '0';
            parent.appendChild(first);
        }, 500);
    }, 2000);
}

/* 加载动画淡出 */
function initLoadingFade() {
    var el = document.getElementById('loading');
    if (!el) return;
    el.style.transition = 'opacity 0.5s';
    el.style.opacity = '0';
    setTimeout(function() {
        el.style.display = 'none';
    }, 500);
}

/* Alert 关闭按钮 */
function initAlertDismiss() {
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('[data-action="alert-close"]');
        if (btn) {
            var alert = btn.closest('.alert');
            if (alert) alert.remove();
        }
    });
}

/* 收缩框 */
function initShrinkBox() {
    document.addEventListener('click', function(e) {
        var title = e.target.closest('[data-action="shrink-toggle"]');
        if (title) {
            var box = title.closest('.shrink-box');
            if (box) box.classList.toggle('shrink-box--active');
        }
    });
}

/* 图片懒加载（IntersectionObserver，JS替换 data-src，防爬虫）*/
function initLazyLoad() {
    var images = document.querySelectorAll('img[data-src]');
    if (!images.length) return;

    /* 为每张图插入 CSS loader 占位，观察占位元素（img display:none 无法触发 IO）*/
    var pairs = [];
    images.forEach(function(img) {
        var ph = document.createElement('div');
        ph.className = 'waxy-lazy-placeholder';
        var inner = document.createElement('div');
        inner.className = 'waxy-diamond-loader';
        inner.innerHTML = '<span></span><span></span><span></span><span></span>';
        ph.appendChild(inner);
        img.parentNode.insertBefore(ph, img);
        pairs.push({ img: img, ph: ph });
    });

    function loadImage(img, ph) {
        var src = img.dataset.src;
        var preload = new Image();
        preload.onload = function() {
            img.removeAttribute('data-src');
            img.src = src;
            ph.remove();
            img.classList.add('waxy-lazy-loaded');
        };
        preload.onerror = function() {
            img.removeAttribute('data-src');
            img.src = src;
            ph.remove();
        };
        preload.src = src;
    }

    if ('IntersectionObserver' in window) {
        var map = new Map();
        pairs.forEach(function(p) { map.set(p.ph, p.img); });
        var io = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var ph = entry.target;
                    io.unobserve(ph);
                    loadImage(map.get(ph), ph);
                }
            });
        }, { rootMargin: '200px 0px' });
        pairs.forEach(function(p) { io.observe(p.ph); });
    } else {
        pairs.forEach(function(p) { loadImage(p.img, p.ph); });
    }
}

/* 图片灯箱 */
function initLightbox() {
    var overlay = document.createElement('div');
    overlay.id = 'waxy-lightbox';
    var closeBtn = document.createElement('span');
    closeBtn.id = 'waxy-lightbox-close';
    closeBtn.innerHTML = '&times;';
    var spinner = document.createElement('div');
    spinner.id = 'waxy-lightbox-spinner';
    var img = document.createElement('img');
    overlay.appendChild(closeBtn);
    overlay.appendChild(spinner);
    overlay.appendChild(img);
    document.body.appendChild(overlay);

    function openLightbox(src) {
        img.style.opacity = '0';
        overlay.classList.add('active', 'waxy-lb-loading');
        img.onload = function() {
            overlay.classList.remove('waxy-lb-loading');
            img.style.opacity = '1';
        };
        img.onerror = function() { overlay.classList.remove('waxy-lb-loading'); };
        img.src = src;
    }
    function closeLightbox() {
        overlay.classList.remove('active', 'waxy-lb-loading');
        img.src = '';
        img.style.opacity = '0';
    }

    overlay.addEventListener('click', closeLightbox);
    img.addEventListener('click', function(e) { e.stopPropagation(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeLightbox(); });
    document.addEventListener('click', function(e) {
        var a = e.target.closest('[data-lightbox]');
        if (a) { e.preventDefault(); openLightbox(a.href); }
    });
}

/* 文章目录侧边栏 */
function initTocSidebar() {
    var toc = document.getElementById('waxy-toc-sidebar');
    if (!toc) return;

    var sidebarCol = toc.closest('.layout__sidebar');
    var links      = toc.querySelectorAll('.waxy-toc-sidebar__link');
    var progressEl = document.getElementById('waxy-toc-progress');
    var barEl      = document.getElementById('waxy-toc-bar');

    // 占位 div，固定时撑开原来的空间
    var ph = document.createElement('div');
    toc.parentNode.insertBefore(ph, toc);
    ph.style.display = 'none';

    var isFixed = false;

    function fix() {
        ph.style.height  = toc.offsetHeight + 'px';
        ph.style.display = 'block';
        toc.style.width  = (sidebarCol ? sidebarCol.offsetWidth : toc.offsetWidth) + 'px';
        toc.classList.add('is-fixed');
        isFixed = true;
    }

    function unfix() {
        toc.classList.remove('is-fixed');
        toc.style.width  = '';
        ph.style.display = 'none';
        isFixed = false;
    }

    function onScroll() {
        // 吸附判断：用占位元素（fixed 时）或自身（正常时）的视口位置
        var refTop = (isFixed ? ph : toc).getBoundingClientRect().top;
        if (!isFixed && refTop <= 0) fix();
        else if (isFixed && refTop > 0) unfix();

        // 固定后同步宽度（防 resize 错位）
        if (isFixed && sidebarCol) toc.style.width = sidebarCol.offsetWidth + 'px';

        // 进度 & 当前标题
        var scrollTop = window.scrollY || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        var pct       = docHeight > 0 ? Math.min(100, Math.round(scrollTop / docHeight * 100)) : 0;
        if (progressEl) progressEl.textContent = pct + '%';
        if (barEl)      barEl.style.width = pct + '%';

        var activeIdx = -1;
        for (var i = 0; i < links.length; i++) {
            var id = links[i].getAttribute('href').slice(1);
            var el = document.getElementById(id);
            if (el && el.getBoundingClientRect().top <= 80) activeIdx = i;
        }
        links.forEach(function(l) { l.classList.remove('is-active'); });
        if (activeIdx >= 0) links[activeIdx].classList.add('is-active');
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    onScroll();
}

/* 文章页悬浮目录（<1000px） */
function initTocFloat() {
    var toc = document.getElementById('waxy-toc-sidebar');
    if (!toc) return;

    var srcLinks = toc.querySelectorAll('.waxy-toc-sidebar__link');
    if (!srcLinks.length) return;

    /* 创建悬浮按钮 */
    var btn = document.createElement('button');
    btn.className = 'waxy-toc-float-btn';
    btn.setAttribute('aria-label', '目录');
    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4.5 11.5A.5.5 0 0 1 5 11h10a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5m-2-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m-2-4A.5.5 0 0 1 1 3h10a.5.5 0 0 1 0 1H1a.5.5 0 0 1-.5-.5"/></svg>';
    document.body.appendChild(btn);

    /* 创建面板 */
    var panel = document.createElement('div');
    panel.className = 'waxy-toc-float-panel';

    var header = document.createElement('div');
    header.className = 'waxy-toc-float-panel__header';
    header.innerHTML =
        '<span class="waxy-toc-float-panel__title">目录</span>' +
        '<div class="waxy-toc-sidebar__bar"><div class="waxy-toc-sidebar__bar-fill" id="waxy-toc-float-bar"></div></div>' +
        '<span class="waxy-toc-sidebar__progress" id="waxy-toc-float-progress">0%</span>';
    panel.appendChild(header);

    var origList = toc.querySelector('.waxy-toc-sidebar__list');
    if (origList) {
        var list = origList.cloneNode(true);
        panel.appendChild(list);
    }
    document.body.appendChild(panel);

    var floatLinks   = panel.querySelectorAll('.waxy-toc-sidebar__link');
    var floatBarEl   = document.getElementById('waxy-toc-float-bar');
    var floatPctEl   = document.getElementById('waxy-toc-float-progress');

    /* 开关面板 */
    function openPanel()  { panel.classList.add('is-open');    btn.classList.add('is-open'); }
    function closePanel() { panel.classList.remove('is-open'); btn.classList.remove('is-open'); }

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        panel.classList.contains('is-open') ? closePanel() : openPanel();
    });

    /* 点击链接后关闭面板 */
    floatLinks.forEach(function(link) {
        link.addEventListener('click', function() { closePanel(); });
    });

    /* 点击面板外区域关闭 */
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.waxy-toc-float-btn') && !e.target.closest('.waxy-toc-float-panel')) {
            closePanel();
        }
    });

    /* 滚动同步：进度 + 当前标题高亮 */
    window.addEventListener('scroll', function() {
        var scrollTop = window.scrollY || document.documentElement.scrollTop;
        var docHeight = document.documentElement.scrollHeight - window.innerHeight;
        var pct = docHeight > 0 ? Math.min(100, Math.round(scrollTop / docHeight * 100)) : 0;
        if (floatBarEl) floatBarEl.style.width = pct + '%';
        if (floatPctEl) floatPctEl.textContent = pct + '%';

        var activeIdx = -1;
        for (var i = 0; i < srcLinks.length; i++) {
            var el = document.getElementById(srcLinks[i].getAttribute('href').slice(1));
            if (el && el.getBoundingClientRect().top <= 80) activeIdx = i;
        }
        floatLinks.forEach(function(l) { l.classList.remove('is-active'); });
        if (activeIdx >= 0) floatLinks[activeIdx].classList.add('is-active');
    }, { passive: true });
}

/* 亮暗色切换 */
function initThemeToggle() {
    var btns = document.querySelectorAll('[data-action="theme-toggle"]');
    if (!btns.length) return;
    btns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('waxy-theme', isDark ? 'dark' : 'light');
        });
    });
}

/* 页面加载完成后初始化 */
document.addEventListener('DOMContentLoaded', function() {
    initNavToggle(); // 导航菜单抽屉（移动端）
    initMenuDropdown(); // 导航菜单下拉（移动端）
    initBackToTop(); // 回到顶部按钮
    initLoadingFade(); // 加载动画淡出
    initStickySlider(); // 置顶文章滚动（移动端）
    initAlertDismiss(); // Alert 关闭按钮
    initShrinkBox(); // 收缩框
    initLazyLoad(); // 图片懒加载
    initLightbox(); // 图片灯箱
    initTocSidebar(); // 文章目录侧边栏
    initTocFloat(); // 文章目录悬浮按钮（移动端）
    initThemeToggle(); // 亮暗色切换
});
