/* 回到顶部按钮 */
function initBackToTop() {
    var bt = document.getElementById('back-to-top');
    if (!bt) return;
    if (window.innerWidth > 480) {
        window.addEventListener('scroll', function() {
            bt.style.display = window.scrollY > 400 ? 'block' : 'none';
        });
        bt.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
}

/* 导航汉堡菜单 */
function initNavToggle() {
    var btn = document.querySelector('[data-action="nav-toggle"]');
    var menu = document.getElementById('main-menu');
    if (!btn || !menu) return;
    btn.addEventListener('click', function() {
        menu.classList.toggle('is-open');
        btn.classList.toggle('is-open');
    });
}

/* 下拉菜单（移动端触摸支持） */
function initMenuDropdown() {
    var items = document.querySelectorAll('.nav__item');
    items.forEach(function(item) {
        var sub = item.querySelector('.nav__sub');
        if (!sub) return;
        item.addEventListener('click', function(e) {
            if (window.innerWidth <= 767) {
                e.stopPropagation();
                item.classList.toggle('is-open');
            }
        });
    });
    document.addEventListener('click', function() {
        if (window.innerWidth <= 767) {
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
        ph.className = 'waxy-diamond-loader waxy-lazy-placeholder';
        ph.innerHTML = '<span></span><span></span><span></span><span></span>';
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

/* 页面加载完成后初始化 */
document.addEventListener('DOMContentLoaded', function() {
    initNavToggle();
    initMenuDropdown();
    initBackToTop();
    initLoadingFade();
    initStickySlider();
    initAlertDismiss();
    initShrinkBox();
    initLazyLoad();
    initLightbox();
    initTocSidebar();
});
