<?php
/* 注册短代码 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once __DIR__ . '/lib/shortcode.php';

// ── 提示框（info / warning / danger）──────────────────────────────
$_waxy_hints = [
    'info'    => ['hint--info',    'info-sign',        'hint--info-icon',    'info:'],
    'warning' => ['hint--warning', 'question-sign',    'hint--warning-icon', 'warning:'],
    'danger'  => ['hint--danger',  'exclamation-sign', 'hint--danger-icon',  'Error:'],
];
foreach ($_waxy_hints as $_tag => $_cfg) {
    add_shortcode($_tag, function($atts, $content = '') use ($_cfg) {
        list($modifier, $icon, $icon_class, $label) = $_cfg;
        return '<div class="hint ' . $modifier . '">'
            . waxy_icon($icon, $icon_class)
            . '<span class="sr-only">' . $label . '</span>'
            . $content
            . '</div>';
    });
}
unset($_waxy_hints, $_tag, $_cfg);

// ── 文字样式（em / hi / lo）──────────────────────────────────────
$_waxy_text = [
    'em' => ['div',  'wrap_em'],
    'hi' => ['span', 'wrap_hi'],
    'lo' => ['div',  'wrap_lo'],
];
foreach ($_waxy_text as $_tag => $_cfg) {
    add_shortcode($_tag, function($atts, $content = '') use ($_cfg) {
        list($el, $class) = $_cfg;
        return "<{$el} class=\"{$class}\">{$content}</{$el}>";
    });
}
unset($_waxy_text, $_tag, $_cfg);

// ── 复选框（check / uncheck）─────────────────────────────────────
add_shortcode('check',   function() { return '<input type="checkbox" checked="checked" disabled="disabled">'; });
add_shortcode('uncheck', function() { return '<input type="checkbox" disabled="disabled">'; });

// ── 音频播放 ──────────────────────────────────────────────────────
function shortcode_audio($atts, $content = '') {
    $args = shortcode_atts(['src' => '', 'preload' => 'metadata'], $atts);
    foreach (['autoplay', 'loop'] as $bool) {
        if (!empty($atts[$bool])) $args[$bool] = $bool;
    }
    $attr_str = implode(' ', array_map(function($k, $v) {
        return $k . '="' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '"';
    }, array_keys($args), array_values($args)));
    return '<audio class="post-audio" ' . $attr_str . ' controls>'
        . $content . ' 您的浏览器不支持 audio 元素。</audio>';
}
add_shortcode('audio', 'shortcode_audio');

// ── 视频播放 ──────────────────────────────────────────────────────
function shortcode_video($atts, $content = '') {
    $args = shortcode_atts([
        'src'     => '',
        'poster'  => '',
        'preload' => 'metadata',
        'width'   => '100%',
        'height'  => '100%',
    ], $atts);
    foreach (['autoplay', 'loop', 'muted'] as $bool) {
        if (!empty($atts[$bool])) $args[$bool] = $bool;
    }
    $attr_str = implode(' ', array_map(function($k, $v) {
        return $k . '="' . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . '"';
    }, array_keys($args), array_values($args)));
    return '<video class="post-video" ' . $attr_str . ' controls>'
        . $content . ' 您的浏览器不支持 video 元素。</video>';
}
add_shortcode('video', 'shortcode_video');

// ── 收缩框 ────────────────────────────────────────────────────────
function shortcode_shrinks($atts, $content = '') {
    $args = shortcode_atts(['title' => '', 'style' => 'default'], $atts);
    $style_class  = $args['style'] !== 'default' ? ' shrink-box--' . preg_replace('/[^a-z0-9-]/', '', $args['style']) : '';
    $active_class = !empty($atts['checked']) ? ' shrink-box--active' : '';
    return '<div class="shrink-box' . $style_class . $active_class . '">'
        . '<div class="shrink-box__title" data-action="shrink-toggle">'
        . htmlspecialchars($args['title'], ENT_QUOTES, 'UTF-8')
        . '</div>'
        . '<div class="shrink-box__body"><div class="shrink-box__body-inner"><div class="shrink-box__body-content">'
        . $content
        . '</div></div></div>'
        . '</div>';
}
add_shortcode('shrinks', 'shortcode_shrinks');

// ── 简易提示框 ────────────────────────────────────────────────────
function shortcode_alert($atts, $content = '') {
    $args  = shortcode_atts(['style' => 'success'], $atts);
    $style = preg_replace('/[^a-z0-9-]/', '', $args['style']);
    $close = '';
    $dismissible = '';
    if (!empty($atts['close'])) {
        $close       = '<button type="button" class="alert__close" data-action="alert-close" aria-label="关闭">&times;</button>';
        $dismissible = ' alert--dismissible';
    }
    return '<div class="alert alert--' . $style . $dismissible . '" role="alert">' . $close . $content . '</div>';
}
add_shortcode('alert', 'shortcode_alert');

// ── Blog 统计面板 ──────────────────────────────────────────────────
// 用法：[blogstats]
function shortcode_blogstats($atts, $content = '') {
    static $cache = null;
    if ($cache !== null) return $cache;

    $db = Typecho_Db::get();

    // 文章：只取 text + created，一次查询兼顾字数和建站天数
    $posts = $db->fetchAll(
        $db->select('table.contents.text', 'table.contents.created')
            ->from('table.contents')
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish')
    );
    $post_count = count($posts);
    $word_count = 0;
    $first_ts   = PHP_INT_MAX;
    foreach ($posts as $p) {
        $word_count += mb_strlen(strip_tags($p['text'] ?? ''), 'UTF-8');
        if ((int)$p['created'] < $first_ts) $first_ts = (int)$p['created'];
    }
    $run_days = $first_ts < PHP_INT_MAX ? (int)floor((time() - $first_ts) / 86400) : 0;
    $word_str = $word_count >= 10000
        ? round($word_count / 10000, 1) . ' 万'
        : number_format($word_count);

    // 评论（已通过）
    $comment_count = count($db->fetchAll(
        $db->select()->from('table.comments')
            ->where('table.comments.status = ?', 'approved')
    ));

    // 分类 / 标签
    $metas    = $db->fetchAll($db->select('table.metas.type')->from('table.metas'));
    $cat_count = $tag_count = 0;
    foreach ($metas as $m) {
        if ($m['type'] === 'category') $cat_count++;
        elseif ($m['type'] === 'tag')  $tag_count++;
    }

    $items = [
        ['icon' => 'bookmark',    'value' => $post_count,    'label' => '文章'],
        ['icon' => 'comment',     'value' => $comment_count, 'label' => '评论'],
        ['icon' => 'folder-open', 'value' => $cat_count,     'label' => '分类'],
        ['icon' => 'tag',         'value' => $tag_count,     'label' => '标签'],
        ['icon' => 'toc',         'value' => $word_str,      'label' => '总字数'],
        ['icon' => 'calendar',    'value' => $run_days . ' 天', 'label' => '已运行'],
    ];

    $html = '<div class="blog-stats">';
    foreach ($items as $item) {
        $html .= '<div class="blog-stats__item">'
            . '<div class="blog-stats__icon">' . waxy_icon($item['icon']) . '</div>'
            . '<div class="blog-stats__value">' . htmlspecialchars((string)$item['value'], ENT_QUOTES, 'UTF-8') . '</div>'
            . '<div class="blog-stats__label">' . $item['label'] . '</div>'
            . '</div>';
    }
    $html .= '</div>';

    $cache = $html;
    return $html;
}
add_shortcode('blogstats', 'shortcode_blogstats');

// ── 文章统计面板 ──────────────────────────────────────────────────
// 用法：[poststats]（在文章正文中使用）
function shortcode_poststats($atts, $content = '') {
    $archive = Typecho_Widget::widget('Widget_Archive');
    if (!$archive->is('single') && !$archive->is('page')) return '';

    $db  = Typecho_Db::get();
    $cid = (int)$archive->cid;

    // 字数
    $row = $db->fetchRow(
        $db->select('table.contents.text')->from('table.contents')
            ->where('table.contents.cid = ?', $cid)->limit(1)
    );
    $word_count  = $row ? mb_strlen(strip_tags($row['text'] ?? ''), 'UTF-8') : 0;
    $read_min    = max(1, (int)ceil($word_count / 350));
    $word_str    = $word_count >= 10000
        ? round($word_count / 10000, 1) . ' 万字'
        : number_format($word_count) . ' 字';

    // 阅读量（只读，不递增）
    $views = 0;
    $vrow  = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($vrow && array_key_exists('views', $vrow)) {
        $views = (int)$vrow['views'];
    }

    // 评论数
    $comment_count = (int)($archive->commentsNum ?? 0);

    // 发布 / 修改时间
    $pub_date = $archive->date('Y年m月d日');
    $mod_date = date('Y年m月d日', $archive->modified);

    $items = [
        ['icon' => 'toc',      'value' => $word_str,                  'label' => '字数'],
        ['icon' => 'bookmark', 'value' => '约 ' . $read_min . ' 分钟', 'label' => '阅读时长'],
        ['icon' => 'user',     'value' => number_format($views),       'label' => '阅读量'],
        ['icon' => 'comment',  'value' => $comment_count,              'label' => '评论'],
        ['icon' => 'calendar', 'value' => $pub_date,                   'label' => '发布'],
        ['icon' => 'star',     'value' => $mod_date,                   'label' => '最后更新'],
    ];

    $html = '<div class="post-stats">';
    foreach ($items as $item) {
        $html .= '<div class="post-stats__item">'
            . '<div class="post-stats__icon">' . waxy_icon($item['icon']) . '</div>'
            . '<div class="post-stats__body">'
            . '<span class="post-stats__value">' . htmlspecialchars((string)$item['value'], ENT_QUOTES, 'UTF-8') . '</span>'
            . '<span class="post-stats__label">' . $item['label'] . '</span>'
            . '</div>'
            . '</div>';
    }
    $html .= '</div>';
    return $html;
}
add_shortcode('poststats', 'shortcode_poststats');
