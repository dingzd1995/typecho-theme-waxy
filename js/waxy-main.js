/*回到顶部按钮*/
function initBackToTop() {
    var bt = $('#back-to-top');
    if ($(document).width() > 480) {
        $(window).scroll(function() {
            var st = $(window).scrollTop();
            if (st > 400) {
                bt.css('display', 'block');
            } else {
                bt.css('display', 'none');
            }
        });
        bt.click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    }
}

/*下拉菜单 */
function initMenuDropdown(){
    $('.menu ul li').hover(function(){
        $(this).children("ul").show();
    },function(){
        $(this).children("ul").hide();
    });
}

/*图片懒加载（IntersectionObserver，JS替换 data-src，防爬虫）*/
function initLazyLoad() {
    var images = document.querySelectorAll('img[data-src]');
    if (!images.length) return;
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    var src = img.dataset.src;
                    io.unobserve(img);
                    var preload = new Image();
                    preload.onload = function () {
                        img.removeAttribute('data-src');
                        img.src = src;
                        img.classList.add('waxy-lazy-loaded');
                    };
                    preload.onerror = function () {
                        img.removeAttribute('data-src');
                        img.src = src;
                    };
                    preload.src = src;
                }
            });
        }, { rootMargin: '200px 0px' });
        images.forEach(function (img) { io.observe(img); });
    } else {
        images.forEach(function (img) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
}

/*图片灯箱*/
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
        img.onload = function () {
            overlay.classList.remove('waxy-lb-loading');
            img.style.opacity = '1';
        };
        img.onerror = function () { overlay.classList.remove('waxy-lb-loading'); };
        img.src = src;
    }
    function closeLightbox() { overlay.classList.remove('active', 'waxy-lb-loading'); img.src = ''; img.style.opacity = '0'; }

    overlay.addEventListener('click', closeLightbox);
    img.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLightbox(); });
    document.addEventListener('click', function (e) {
        var a = e.target.closest('[data-lightbox]');
        if (a) { e.preventDefault(); openLightbox(a.href); }
    });
}

/* 页面加载完成后初始化功能 */
$(document).ready(function(){
    initMenuDropdown();
    initBackToTop();
    initLazyLoad();
    initLightbox();
});

/* 鼠标特效（已注释备用）*/
/*
var a_idx = 0;
jQuery(document).ready(function($) {
    $("body").click(function(e) {
        var a = new Array("富强", "民主", "文明", "和谐", "自由", "平等", "公正", "法治", "爱国", "敬业", "诚信", "友善");
        var $i = $("<span></span>").text(a[a_idx]);
        a_idx = (a_idx + 1) % a.length;
        var x = e.pageX, y = e.pageY;
        $i.css({
            "z-index": 9999999,
            "top": y - 20,
            "left": x,
            "position": "absolute",
            "font-weight": "bold",
            "color": "rgb("+~~(255*Math.random())+","+~~(255*Math.random())+","+~~(255*Math.random())+")"
        });
        $("body").append($i);
        $i.animate({"top": y - 180, "opacity": 0}, 1500, function() { $i.remove(); });
    });
});*/
