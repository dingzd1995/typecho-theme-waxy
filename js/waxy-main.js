$(document).ready((function(_this) {
        return function() {
          var bt;
          bt = $('#back-to-top');
          if ($(document).width() > 480) {
            $(window).scroll(function() {
              var st;
              st = $(window).scrollTop();
              if (st > 400) {
                return bt.css('display', 'block');
              } else {
                return bt.css('display', 'none');
              }
            });
            return bt.click(function() {
              $('body,html').animate({
                scrollTop: 0
              }, 800);
              return false;
            });
          }
          };
        })(this));

$(function() {
          $("img").lazyload({ 
          placeholder : "/loading.gif",
                 effect: "fadeIn"
           });  
      });
      
/* 鼠标特效 */
/*
var a_idx = 0;
jQuery(document).ready(function($) {
    $("body").click(function(e) {
        var a = new Array("富强", "民主", "文明", "和谐", "自由", "平等", "公正", "法治", "爱国", "敬业", "诚信", "友善");
        var $i = $("<span></span>").text(a[a_idx]);
        a_idx = (a_idx + 1) % a.length;
        var x = e.pageX,
        y = e.pageY;
        $i.css({
            "z-index": 999999999999999999999999999999999999999999999999999999999999999999999,
            "top": y - 20,
            "left": x,
            "position": "absolute",
            "font-weight": "bold",
            "color": "rgb("+~~(255*Math.random())+","+~~(255*Math.random())+","+~~(255*Math.random())+")"
        });
        $("body").append($i);
        $i.animate({
            "top": y - 180,
            "opacity": 0
        },
        1500,
        function() {
            $i.remove();
        });
    });
});*/
