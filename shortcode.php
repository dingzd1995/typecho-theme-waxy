<?php
/**
 *
 * 注册短代码
 *
 * @author  MaiCong <i@maicong.me>
 * @link    https://github.com/maicong/stay
 * @since   1.5.7
 *
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once __DIR__ . '/lib/shortcode.php';

// 一般提示
function shortcode_panel_info( $atts, $content = '' ) {
    return '<div class="hint hint-info"><span class="glyphicon glyphicon-info-sign hint-info-icon" aria-hidden="true"></span><span class="sr-only">info:</span>' . $content . '</div>';
}
add_shortcode( 'info' , 'shortcode_panel_info' );

// 警告提示
function shortcode_panel_warning( $atts, $content = '' ) {
    return '<div class="hint hint-warning"><span class="glyphicon glyphicon-question-sign hint-warning-icon" aria-hidden="true"></span><span class="sr-only">warning:</span>' . $content . '</div>';
}
add_shortcode( 'warning' , 'shortcode_panel_warning' );

// 危险提示
function shortcode_panel_danger( $atts, $content = '' ) {
    return '<div class="hint hint-danger"><span class="glyphicon glyphicon-exclamation-sign hint-danger-icon" aria-hidden="true"></span><span class="sr-only">Error:</span>' . $content . '</div>';
}
add_shortcode( 'danger' , 'shortcode_panel_danger' );

// 特别强调文字
function shortcode_panel_em( $atts, $content = '' ) {
    return '<div class="wrap_em">' . $content . '</div>';
}
add_shortcode( 'em' , 'shortcode_panel_em' );

// 高亮文字
function shortcode_panel_hi( $atts, $content = '' ) {
    return '<span class="wrap_hi">' . $content . '</span>';
}
add_shortcode( 'hi' , 'shortcode_panel_hi' );

// 不重要文字
function shortcode_panel_lo( $atts, $content = '' ) {
    return '<div class="wrap_lo">' . $content . '</div>';
}
add_shortcode( 'lo' , 'shortcode_panel_lo' );


// 代办事项已完成
function shortcode_panel_check( $atts, $content = '' ) {
    return '<input type="checkbox" checked="checked" disabled="true">';
}
add_shortcode( 'check' , 'shortcode_panel_check' );

// 代办事项未完成
function shortcode_panel_uncheck( $atts, $content = '' ) {
    return '<input type="checkbox"  disabled="true">';
}
add_shortcode( 'uncheck' , 'shortcode_panel_uncheck' );

// 音频播放
function shortcode_audio( $atts, $content = '' ) {
    $args = shortcode_atts( array(
        'src' => '',
        'controls' => 'controls',
        'preload' => 'metadata'
    ), $atts );
    if (!empty($atts['autoplay'])) {
        $args['autoplay'] = 'autoplay';
    }
    if (!empty($atts['loop'])) {
        $args['loop'] = 'loop';
    }
    $attr_strings = array();
    foreach ( $args as $k => $v ) {
        $attr_strings[] = $k . '="' . htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' ) . '"';
    }
    //$audio = sprintf( '<audio class="post-audio__source" %s controls>%s</audio>', join( ' ', $attr_strings ), $content );
    //return "<div class=\"mc-audio\"><div class=\"mc-audio__bar\"></div><div class=\"mc-audio__ctl\"><div class=\"mc-audio__ctl-btn\"></div><div class=\"mc-audio__ctl-time\"></div></div>{$audio}</div>";
    return sprintf( '<audio class="post-audio" %s controls >%s 您的浏览器不支持 audio 元素。</audio>', join( ' ', $attr_strings ), $content );
}
add_shortcode( 'audio' , 'shortcode_audio' );

// 视频播放
function shortcode_video( $atts, $content = '' ) {
    $args = shortcode_atts( array(
        'src'      => '',
        'poster'   => '',
        'controls' => 'controls',
        'preload'  => 'metadata'
        
    ), $atts );
    if (!empty($atts['autoplay'])) {
        $args['autoplay'] = 'autoplay';
    }
    if (!empty($atts['loop'])) {
        $args['loop'] = 'loop';
    }
    if (!empty($atts['muted'])) {
        $args['muted'] = 'muted';
    }
    //默认播放器大小100%
    /*if (!empty($atts['width'])) {
       $args['width'] = $atts['width'];
     }
    if (!empty($atts['height'])) {
        $args['height'] = $atts['height'];
    }*/
    $args['width'] ='100%';
    $args['height'] ='100%';
    $attr_strings = array();
    foreach ( $args as $k => $v ) {
        $attr_strings[] = $k . '="' . htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' ) . '"';
    }
    return sprintf( '<video id="post-video" class="post-video" %s >%s  您的浏览器不支持 video 元素。</video>', join( ' ', $attr_strings ), $content );
}
add_shortcode( 'video' , 'shortcode_video' );

//收缩框
function shortcode_shrinks( $atts, $content = '' ) {
    $args = shortcode_atts( array(
        'title' => '',
        'style' => 'default'
        
    ), $atts );
    if (!empty($atts['checked'])) {
        $args['checked'] = 'active';
    }
    $attr_strings = array();
    foreach ( $args as $k => $v ) {
        $attr_strings[] = $k . '="' . htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' ) . '"';
    }
    return '<div class="panel panel-'.$args['style'].' shrinkBox '.$args['checked'].'" >
                        <div class="panel-heading shrinkBox-title" onclick="$(this).parent().toggleClass(\'active\');">'.$args['title'].'</div><div class="panel-body shrinkBox-content">'.$content.'</div></div>';
}
add_shortcode( 'shrinks' , 'shortcode_shrinks' );


//简易提示框
function shortcode_alert( $atts, $content = '' ) {
    $closebutton='';
    $args = shortcode_atts( array(
        'style' => 'success'
        
    ), $atts );
    if (!empty($atts['close'])) {
        $args['close'] = 'alert-dismissible';
        $closebutton = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
    }
    $attr_strings = array();
    foreach ( $args as $k => $v ) {
        $attr_strings[] = $k . '="' . htmlspecialchars( $v, ENT_QUOTES, 'UTF-8' ) . '"';
    }
    return '<div class="alert alert-'.$args['style'].' '.$args['close'].'" role="alert">'.$closebutton.$content.'</div>';
}
add_shortcode( 'alert' , 'shortcode_alert' );
