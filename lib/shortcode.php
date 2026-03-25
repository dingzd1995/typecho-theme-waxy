<?php
/**
 * 短代码引擎
 * 移植自 WordPress shortcode API，用于在文章内容中解析 [tag] 语法。
 *
 * 主要流程：
 *   1. add_shortcode()  — 注册标签名与处理函数的映射
 *   2. do_shortcode()   — 扫描内容，依次展开所有已注册的短代码
 *   3. 辅助函数负责正则构建、属性解析、HTML 标签内的安全处理
 */

/**
 * 全局注册表：tag => callable
 * 键为短代码标签名，值为对应的处理函数。
 */
$shortcode_tags = array();

/**
 * 注册一个短代码标签及其处理函数。
 *
 * @param string   $tag  标签名，不能含 < > & / [ ] = 及控制字符
 * @param callable $func 处理函数，签名：function( $atts, $content, $tag )
 */
function add_shortcode($tag, $func) {
    global $shortcode_tags;
    // 标签名不能为空
    if ( '' == trim( $tag ) ) {
        return;
    }
    // 标签名不能含保留字符（防止与 HTML / 短代码语法冲突）
    if ( 0 !== preg_match( '@[<>&/\[\]\x00-\x20=]@', $tag ) ) {
        return;
    }
    $shortcode_tags[ $tag ] = $func;
}

/**
 * 展开内容中所有已注册的短代码。
 *
 * 处理顺序：
 *   1. 快速判断：内容中没有 [ 则直接返回
 *   2. 修复 Typecho 将 [tag] 包裹在 <p> 里的情况（去掉多余的 <p>）
 *   3. 扫描内容，取出实际出现的已注册标签名
 *   4. 先处理 HTML 属性内的短代码（避免破坏 HTML 结构）
 *   5. 再用正则替换正文中的短代码
 *   6. 还原被转义的方括号实体
 *
 * @param  string $content      待处理的 HTML 内容
 * @param  bool   $ignore_html  为 true 时跳过 HTML 标签属性内的短代码处理
 * @return string               展开后的内容
 */
function do_shortcode( $content, $ignore_html = false ) {
    global $shortcode_tags;

    // 内容中不含 [ 则不可能有短代码，直接返回
    if ( false === strpos( $content, '[' ) ) {
        return $content;
    }
    if (empty($shortcode_tags) || !is_array($shortcode_tags)) {
        return $content;
    }

    // Typecho Markdown 渲染后会把独占一行的 [tag] 包进 <p>...</p>
    // 此处将 <p>[tag]</p> 还原为 [tag]，保证短代码能被正常匹配
    $content = preg_replace( "/<p>\[(.*?)\]<\/p>/", '[${1}]', $content );

    // 从内容中提取所有出现的潜在标签名，与注册表取交集
    preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
    $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );
    if ( empty( $tagnames ) ) {
        return $content;
    }

    // 先处理出现在 HTML 标签属性里的短代码（需要额外的安全过滤）
    $content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );

    // 用正则匹配并替换正文中的短代码
    $pattern = get_shortcode_regex( $tagnames );
    $content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

    // 还原转义的方括号，避免破坏 <!--[if IE ]> 等条件注释
    $content = strtr( $content, array( '&#91;' => '[', '&#93;' => ']' ) );
    return $content;
}

/**
 * preg_replace_callback 的回调：处理单个匹配到的短代码。
 *
 * $m 捕获组含义（与 get_shortcode_regex() 对应）：
 *   $m[0] — 完整匹配字符串
 *   $m[1] — 可选的前置 [（用于转义语法 [[tag]]）
 *   $m[2] — 标签名
 *   $m[3] — 属性字符串（原始文本）
 *   $m[4] — 自闭合斜杠（[tag /] 形式）
 *   $m[5] — 闭合标签之间的内容（[tag]content[/tag] 形式）
 *   $m[6] — 可选的后置 ]（用于转义语法 [[tag]]）
 *
 * @param  array  $m 正则捕获组
 * @return string    替换后的 HTML 字符串
 */
function do_shortcode_tag( $m ) {
    global $shortcode_tags;

    // [[tag]] 语法：双括号表示转义，去掉外层括号后原样输出
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }

    $tag  = $m[2];
    $attr = shortcode_parse_atts( $m[3] );

    // 处理函数不可调用时原样返回，避免报错
    if ( ! is_callable( $shortcode_tags[ $tag ] ) ) {
        return $m[0];
    }

    if ( isset( $m[5] ) ) {
        // 闭合标签形式：[tag]content[/tag]，将 content 作为第二参数传入
        return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, $m[5], $tag ) . $m[6];
    } else {
        // 自闭合形式：[tag] 或 [tag /]，content 为 null
        return $m[1] . call_user_func( $shortcode_tags[$tag], $attr, null, $tag ) . $m[6];
    }
}

/**
 * 将已注册属性的默认值与用户传入的属性合并。
 * 类似 HTML 属性的 defaults 机制：用户传了就用用户的，否则用默认值。
 *
 * 用法示例：
 *   $args = shortcode_atts( ['src' => '', 'loop' => false], $atts );
 *
 * @param  array $pairs 默认属性键值对
 * @param  array $atts  用户传入的原始属性（由 shortcode_parse_atts() 解析得到）
 * @return array        合并后的属性数组，键集合与 $pairs 完全一致
 */
function shortcode_atts( $pairs, $atts) {
    $atts = (array)$atts;
    $out  = array();
    foreach ($pairs as $name => $default) {
        $out[$name] = array_key_exists($name, $atts) ? $atts[$name] : $default;
    }
    return $out;
}

/**
 * 解析短代码属性字符串，返回关联数组或索引数组。
 *
 * 支持四种属性格式：
 *   key="value"   — 双引号
 *   key='value'   — 单引号
 *   key=value     — 无引号（值不含空格）
 *   "value"       — 仅值（无键名，存入索引数组）
 *   value         — 裸值（无键名，存入索引数组）
 *
 * 额外处理：
 *   - 将不间断空格（U+00A0）和零宽空格（U+200B）替换为普通空格
 *   - 拒绝含未闭合 HTML 元素的属性值（安全防护）
 *
 * @param  string       $text 属性原始字符串
 * @return array|string       解析结果；无法匹配时返回去除首部空白的原始字符串
 */
function shortcode_parse_atts($text) {
    $atts    = array();
    $pattern = '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

    // 统一空白字符，避免特殊空格导致解析失败
    $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

    if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
        foreach ($match as $m) {
            if (!empty($m[1]))                    // key="value"
                $atts[strtolower($m[1])] = stripcslashes($m[2]);
            elseif (!empty($m[3]))                // key='value'
                $atts[strtolower($m[3])] = stripcslashes($m[4]);
            elseif (!empty($m[5]))                // key=value
                $atts[strtolower($m[5])] = stripcslashes($m[6]);
            elseif (isset($m[7]) && strlen($m[7])) // "value"（仅值）
                $atts[] = stripcslashes($m[7]);
            elseif (isset($m[8]))                 // 裸值
                $atts[] = stripcslashes($m[8]);
        }
        // 安全过滤：属性值中含未闭合 HTML 元素时清空该值
        foreach( $atts as &$value ) {
            if ( false !== strpos( $value, '<' ) ) {
                if ( 1 !== preg_match( '/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value ) ) {
                    $value = '';
                }
            }
        }
    } else {
        // 完全无法解析时返回裸字符串
        $atts = ltrim($text);
    }
    return $atts;
}

/**
 * 构建用于匹配短代码的正则表达式。
 *
 * 返回的正则包含 6 个捕获组（与 do_shortcode_tag() 的 $m 对应）：
 *   1 — 可选的前置 [（转义语法）
 *   2 — 标签名
 *   3 — 属性字符串
 *   4 — 自闭合斜杠（可选）
 *   5 — 闭合标签之间的内容（可选）
 *   6 — 可选的后置 ]（转义语法）
 *
 * 警告：修改此正则前须同步更新 do_shortcode_tag()。
 *
 * @param  array|null $tagnames 要匹配的标签名列表；为 null 时使用全部已注册标签
 * @return string               正则表达式字符串（不含定界符）
 */
function get_shortcode_regex( $tagnames = null ) {
    global $shortcode_tags;
    if ( empty( $tagnames ) ) {
        $tagnames = array_keys( $shortcode_tags );
    }
    // 将所有标签名转义后用 | 连接，构成候选分支
    $tagregexp = join( '|', array_map('preg_quote', $tagnames) );

    return
          '\\['                              // 开头的 [
        . '(\\[?)'                           // 1: 可选的第二个 [（转义语法 [[tag]]）
        . "($tagregexp)"                     // 2: 标签名（从注册列表中匹配）
        . '(?![\\w-])'                       // 标签名后不能紧跟单词字符或连字符
        . '('                                // 3: 属性区域（展开循环，避免回溯）
        .     '[^\\]\\/]*'                   //    不是 ] 也不是 /
        .     '(?:'
        .         '\\/(?!\\])'               //    / 后面不是 ]（排除自闭合斜杠）
        .         '[^\\]\\/]*'               //    不是 ] 也不是 /
        .     ')*?'
        . ')'
        . '(?:'
        .     '(\\/)'                        // 4: 自闭合斜杠 [tag /]
        .     '\\]'                          //    紧跟 ]
        . '|'
        .     '\\]'                          //    普通闭合 ]
        .     '(?:'
        .         '('                        // 5: 闭合标签之间的内容（可选）
        .             '[^\\[]*+'             //    不是 [（占有量词，防止回溯）
        .             '(?:'
        .                 '\\[(?!\\/\\2\\])' //    [ 后面不是 [/tagname]
        .                 '[^\\[]*+'         //    不是 [
        .             ')*+'
        .         ')'
        .         '\\[\\/\\2\\]'             //    对应的闭合标签 [/tagname]
        .     ')?'
        . ')'
        . '(\\]?)';                          // 6: 可选的第二个 ]（转义语法 [[tag]]）
}

/**
 * 处理出现在 HTML 标签属性内的短代码。
 *
 * 普通的文本替换会破坏 HTML 属性结构（例如属性值含 [ 时被错误展开）。
 * 此函数先把内容按 HTML 标签边界拆分，对每个 HTML 元素单独处理：
 *   - 若属性值中的短代码来自不可信输入，展开后需经 KSES 过滤
 *   - 若来自管理员直接写入，则直接展开不过滤
 *   - 不含短代码的 HTML 元素只需对 [ ] 做实体转义
 *
 * @param  string $content      原始内容
 * @param  bool   $ignore_html  为 true 时对所有 HTML 标签内的 [ ] 只做转义
 * @param  array  $tagnames     当前内容中出现的已注册标签名列表
 * @return string               处理后的内容
 */
function do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames ) {
    // 先把已经是实体的 &#91; / &#93; 临时替换，防止被二次处理
    $trans   = array( '&#91;' => '&#091;', '&#93;' => '&#093;' );
    $content = strtr( $content, $trans );

    // 之后需要把 [ ] 替换为实体，保护 HTML 属性内的方括号
    $trans   = array( '[' => '&#91;', ']' => '&#93;' );
    $pattern = get_shortcode_regex( $tagnames );

    // 将内容按 HTML 标签边界拆分为文本节点和标签节点交替的数组
    $textarr = html_split( $content );

    foreach ( $textarr as &$element ) {
        // 跳过空字符串和非 HTML 标签节点（奇数索引才是标签）
        if ( '' == $element || '<' !== $element[0] ) {
            continue;
        }

        $noopen  = false === strpos( $element, '[' );
        $noclose = false === strpos( $element, ']' );
        if ( $noopen || $noclose ) {
            // 该标签不含短代码方括号
            if ( $noopen xor $noclose ) {
                // 只有单边括号时转义，避免破坏 HTML
                $element = strtr( $element, $trans );
            }
            continue;
        }

        if ( $ignore_html || '<!--' === substr( $element, 0, 4 ) || '<![CDATA[' === substr( $element, 0, 9 ) ) {
            // 注释和 CDATA 节点：直接转义方括号，不做短代码展开
            $element = strtr( $element, $trans );
            continue;
        }

        // 尝试解析 HTML 元素的属性列表
        $attributes = kses_attr_parse( $element );
        if ( false === $attributes ) {
            // 解析失败（非常规 HTML）：仅对以 [ 开头的元素尝试展开
            if ( 1 === preg_match( '%^<\s*\[\[?[^\[\]]+\]%', $element ) ) {
                $element = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $element );
            }
            $element = strtr( $element, $trans );
            continue;
        }

        // 从解析结果中取出标签名（用于 KSES 过滤时指定元素上下文）
        $front   = array_shift( $attributes );
        $back    = array_pop( $attributes );
        $matches = array();
        preg_match('%[a-zA-Z0-9]+%', $front, $matches);
        $elname  = $matches[0];

        // 逐个属性检查是否含短代码，并做差异化处理
        foreach ( $attributes as &$attr ) {
            $open  = strpos( $attr, '[' );
            $close = strpos( $attr, ']' );
            if ( false === $open || false === $close ) {
                continue; // 无方括号，跳过；稍后统一转义
            }

            $double = strpos( $attr, '"' );
            $single = strpos( $attr, "'" );

            if ( ( false === $single || $open < $single ) && ( false === $double || $open < $double ) ) {
                // 属性值未被引号包裹（如 name=[shortcode]）
                // 视为管理员直接写入的不过滤内容，直接展开
                $attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr );
            } else {
                // 属性值被引号包裹（如 name="[shortcode]"）
                // 来源未知，展开后需经 KSES 过滤
                $count   = 0;
                $new_attr = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $attr, -1, $count );
                if ( $count > 0 ) {
                    $new_attr = kses_one_attr( $new_attr, $elname );
                    if ( '' !== trim( $new_attr ) ) {
                        $attr = $new_attr;
                    }
                }
            }
        }

        // 重组 HTML 元素，并对剩余的 [ ] 转义
        $element = $front . implode( '', $attributes ) . $back;
        $element = strtr( $element, $trans );
    }

    return implode( '', $textarr );
}

/**
 * 将内容字符串按 HTML 标签边界拆分为数组。
 * 偶数索引为文本节点，奇数索引为 HTML 标签节点。
 *
 * @param  string $input 原始内容
 * @return array         拆分结果
 */
function html_split( $input ) {
    return preg_split( get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
}

/**
 * 构建用于按 HTML 标签边界拆分内容的正则表达式。
 * 结果缓存在静态变量中，多次调用只构建一次。
 *
 * 能正确处理三类特殊节点：
 *   - HTML 注释：<!-- ... -->
 *   - CDATA 节：<![CDATA[ ... ]]>
 *   - 普通 HTML 标签：<tag ...>
 *
 * @return string 正则表达式字符串（含定界符）
 */
function get_html_split_regex() {
    static $regex;
    if ( ! isset( $regex ) ) {
        // 匹配 HTML 注释 <!-- ... -->（使用占有量词防止回溯）
        $comments =
              '!'
            . '(?:'
            .     '-(?!->)'   // - 后面不是 ->（避免提前结束）
            .     '[^\-]*+'   // 消耗所有非 - 字符
            . ')*+'
            . '(?:-->)?';     // 注释结束符（容忍不闭合）

        // 匹配 CDATA 节 <![CDATA[ ... ]]>
        $cdata =
              '!\[CDATA\['
            . '[^\]]*+'
            . '(?:'
            .     '](?!]>)'   // ] 后面不是 ]>
            .     '[^\]]*+'
            . ')*+'
            . '(?:]]>)?';     // CDATA 结束符（容忍不闭合）

        // 判断是否为转义节点（注释或 CDATA），再选择对应分支
        $escaped =
              '(?='
            .    '!--'
            . '|'
            .    '!\[CDATA\['
            . ')'
            . '(?(?=!-)'      // 条件表达式：以 !- 开头 → 注释分支
            .     $comments
            . '|'
            .     $cdata      // 否则 → CDATA 分支
            . ')';

        $regex =
              '/('
            .     '<'
            .     '(?'        // 条件表达式
            .         $escaped
            .     '|'
            .         '[^>]*>?' // 普通标签（容忍不闭合）
            .     ')'
            . ')/';
    }
    return $regex;
}

/**
 * 解析单个 HTML 开始标签，返回其属性列表数组。
 * 数组首元素为标签开头（<tagname），末元素为标签结尾（>），中间为各属性字符串。
 *
 * @param  string      $element 单个 HTML 标签字符串
 * @return array|false          属性数组；输入为闭合标签或解析失败时返回 false
 */
function kses_attr_parse( $element ) {
    $valid = preg_match('%^(<\s*)(/\s*)?([a-zA-Z0-9]+\s*)([^>]*)(>?)$%', $element, $matches);
    if ( 1 !== $valid ) {
        return false;
    }
    $begin  = $matches[1];
    $slash  = $matches[2];
    $elname = $matches[3];
    $attr   = $matches[4];
    $end    = $matches[5];

    // 闭合标签（</tag>）不处理属性
    if ( '' !== $slash ) {
        return false;
    }

    // 检测并去除 XHTML 自闭合斜杠（<tag />）
    if ( 1 === preg_match( '%\s*/\s*$%', $attr, $matches ) ) {
        $xhtml_slash = $matches[0];
        $attr        = substr( $attr, 0, -strlen( $xhtml_slash ) );
    } else {
        $xhtml_slash = '';
    }

    // 将属性字符串拆分为独立的属性片段数组
    $attrarr = kses_hair_parse( $attr );
    if ( false === $attrarr ) {
        return false;
    }

    // 在数组首尾补充标签开头和结尾，保证完整重组时不丢失内容
    array_unshift( $attrarr, $begin . $slash . $elname );
    array_push( $attrarr, $xhtml_slash . $end );

    return $attrarr;
}

/**
 * 将 HTML 标签的属性字符串拆分为独立属性片段的数组。
 *
 * 每个片段保留原始格式（含引号和尾部空格），以便重组时还原。
 * 使用"先验证再提取"的两遍正则策略，确保只在合法属性串上操作。
 *
 * @param  string      $attr HTML 标签中所有属性组成的字符串
 * @return array|false       属性片段数组；格式非法时返回 false
 */
function kses_hair_parse( $attr ) {
    if ( '' === $attr ) {
        return array();
    }

    $regex =
      '(?:'
    .     '[-a-zA-Z:]+'               // 合法属性名
    . '|'
    .     '\[\[?[^\[\]]+\]\]?'        // 属性名位置上的短代码（管理员不过滤场景）
    . ')'
    . '(?:'                           // 属性值（可选）
    .     '\s*=\s*'
    .     '(?:'
    .         '"[^"]*"'               // 双引号值
    .     '|'
    .         "'[^']*'"               // 单引号值
    .     '|'
    .         '[^\s"\']+(?:\s|$)'     // 无引号值（后须跟空格或字符串结束）
    .     ')'
    . '|'
    .     '(?:\s|$)'                  // 布尔属性（无值，后须跟空格或结束）
    . ')'
    . '\s*';                          // 属性间的可选空白

    $validation = "%^($regex)+$%";
    $extraction = "%$regex%";

    if ( 1 === preg_match( $validation, $attr ) ) {
        preg_match_all( $extraction, $attr, $attrarr );
        return $attrarr[0];
    } else {
        return false;
    }
}

/**
 * 对单个 HTML 属性字符串做安全过滤，用于短代码展开后的 KSES 处理。
 *
 * 处理步骤：
 *   1. 去除控制字符和危险的 CSS expression（&{...}）
 *   2. 保留首尾空白
 *   3. 分离属性名和属性值
 *   4. 对属性值中的引号、尖括号、HTML 实体做转义
 *
 * @param  string $string  单个属性字符串（如 href="http://..."）
 * @param  string $element 所属 HTML 元素名（保留参数，扩展用）
 * @return string          过滤后的属性字符串
 */
function kses_one_attr( $string, $element ) {
    // 去除控制字符和 CSS expression 注入（& { ... }）
    $string = preg_replace( ['/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '%&\s*\{[^}]*(\}\s*;?|$)%'], '', $string );

    // 记录并还原首尾空白
    $matches = array();
    preg_match('/^\s*/', $string, $matches);
    $lead  = $matches[0];
    preg_match('/\s*$/', $string, $matches);
    $trail = $matches[0];
    $string = empty($trail)
        ? substr( $string, strlen($lead) )
        : substr( $string, strlen($lead), -strlen($trail) );

    // 按第一个 = 分割属性名和属性值
    $split = preg_split( '/\s*=\s*/', $string, 2 );
    $name  = $split[0];

    if ( count( $split ) == 2 ) {
        $value = $split[1];
        // 确定引号类型并去除外层引号
        $quote = ( '' == $value ) ? '' : $value[0];
        if ( '"' == $quote || "'" == $quote ) {
            if ( substr( $value, -1 ) != $quote ) {
                return ''; // 引号不匹配，丢弃
            }
            $value = substr( $value, 1, -1 );
        } else {
            $quote = '"'; // 无引号时统一改为双引号
        }
        // 转义属性值中的特殊字符
        $value  = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
        $string = "$name=$quote$value$quote";
    }
    // else：布尔属性（无值），name 直接保留

    return $lead . $string . $trail;
}

/**
 * 将纯文本段落转换为 HTML <p> 标签（wpautop 移植版）。
 *
 * 主要处理逻辑：
 *   1. 保护 <pre> 内容不被修改
 *   2. 将连续的 <br> 转换为段落分隔
 *   3. 在块级元素前后添加双换行
 *   4. 将双换行分割点变为 <p> 包裹
 *   5. 清理多余的 <p> 和 <br />
 *   6. 还原 <pre> 内容
 *
 * @param  string $pee 原始文本内容
 * @param  bool   $br  是否将单个换行转换为 <br />（默认 true）
 * @return string      转换后的 HTML
 */
function autop( $pee, $br = true ) {
    $pre_tags = array();
    if ( trim( $pee ) === '' ) {
        return '';
    }

    $pee = $pee . "\n";

    // 保护 <pre> 块：用占位符替换，避免被段落化处理破坏
    if ( strpos( $pee, '<pre' ) !== false ) {
        $pee_parts = explode( '</pre>', $pee );
        $last_pee  = array_pop( $pee_parts );
        $pee       = '';
        $i         = 0;
        foreach ( $pee_parts as $pee_part ) {
            $start = strpos( $pee_part, '<pre' );
            if ( $start === false ) {
                $pee .= $pee_part;
                continue;
            }
            $name              = "<pre wp-pre-tag-$i></pre>";
            $pre_tags[ $name ] = substr( $pee_part, $start ) . '</pre>';
            $pee .= substr( $pee_part, 0, $start ) . $name;
            $i++;
        }
        $pee .= $last_pee;
    }

    // 连续两个 <br> 视为段落分隔
    $pee = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee );

    // 块级元素列表（在其前后插入双换行以触发段落分割）
    $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
    $pee = preg_replace( '!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee );
    $pee = preg_replace( '!(</' . $allblocks . '>)!', "$1\n\n", $pee );

    // 统一换行符
    $pee = str_replace( array( "\r\n", "\r" ), "\n", $pee );

    // HTML 标签内的换行用占位符保护，避免被误转为 <br />
    $pee = replace_in_html_tags( $pee, array( "\n" => ' <!-- wpnl --> ' ) );

    // 折叠 <option> 元素周围的换行
    if ( strpos( $pee, '<option' ) !== false ) {
        $pee = preg_replace( '|\s*<option|', '<option', $pee );
        $pee = preg_replace( '|</option>\s*|', '</option>', $pee );
    }

    // 折叠 <object> 内部的换行
    if ( strpos( $pee, '</object>' ) !== false ) {
        $pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
        $pee = preg_replace( '|\s*</object>|', '</object>', $pee );
        $pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
    }

    // 折叠 <audio>/<video> 内 <source>/<track> 周围的换行
    if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
        $pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
        $pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
        $pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
    }

    // 折叠 <figcaption> 周围的换行
    if ( strpos( $pee, '<figcaption' ) !== false ) {
        $pee = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $pee );
        $pee = preg_replace( '|</figcaption>\s*|', '</figcaption>', $pee );
    }

    // 超过两个连续换行合并为两个
    $pee = preg_replace( "/\n\n+/", "\n\n", $pee );

    // 按双换行分割为段落，并用 <p> 包裹
    $pees = preg_split( '/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY );
    $pee  = '';
    foreach ( $pees as $tinkle ) {
        $pee .= '<p>' . trim( $tinkle, "\n" ) . "</p>\n";
    }

    // 清理空 <p>
    $pee = preg_replace( '|<p>\s*</p>|', '', $pee );
    // <div>/<address>/<form> 内缺少闭合 </p> 时补上
    $pee = preg_replace( '!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $pee );
    // 块级元素被 <p> 包裹时去掉多余的 <p>
    $pee = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee );
    // <li> 被 <p> 包裹时去掉
    $pee = preg_replace( '|<p>(<li.+?)</p>|', '$1', $pee );
    // <blockquote> 被 <p> 包裹时将 <p> 移入内部
    $pee = preg_replace( '|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $pee );
    $pee = str_replace( '</blockquote></p>', '</p></blockquote>', $pee );
    // 块级开/闭标签前有多余 <p> 时去掉
    $pee = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $pee );
    // 块级开/闭标签后有多余 </p> 时去掉
    $pee = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee );

    if ( $br ) {
        // 保护 <script>/<style> 内的换行不被转为 <br />
        $pee = preg_replace_callback( '/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee );
        // 统一 <br> 格式
        $pee = str_replace( array( '<br>', '<br/>' ), '<br />', $pee );
        // 单个换行（前面不是 <br />）转为 <br />
        $pee = preg_replace( '|(?<!<br />)\s*\n|', "<br />\n", $pee );
        // 还原 <script>/<style> 内的换行占位符
        $pee = str_replace( '<PreserveNewline />', "\n", $pee );
    }

    // 块级标签后紧跟的 <br /> 去掉
    $pee = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $pee );
    // 特定块级开/闭标签前的 <br /> 去掉
    $pee = preg_replace( '!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee );
    // 末尾 </p> 前的换行去掉
    $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

    // 还原被占位符替换的 <pre> 内容
    if ( ! empty( $pre_tags ) ) {
        $pee = str_replace( array_keys( $pre_tags ), array_values( $pre_tags ), $pee );
    }

    // 还原 HTML 标签内被保护的换行占位符
    if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
        $pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
    }

    return $pee;
}

/**
 * autop() 的辅助回调：将 <script>/<style> 块内的换行替换为占位符。
 * 防止 autop() 把这些块内的换行错误地转为 <br />。
 *
 * @param  array  $matches preg_replace_callback 捕获组
 * @return string          换行替换为占位符后的字符串
 */
function _autop_newline_preservation_helper( $matches ) {
    return str_replace( "\n", '<PreserveNewline />', $matches[0] );
}

/**
 * 在 HTML 标签节点内批量替换字符串，文本节点不受影响。
 * 利用 html_split() 将内容拆分后，只对奇数索引（标签节点）做替换。
 *
 * @param  string $haystack      原始内容
 * @param  array  $replace_pairs 替换映射，格式：['needle' => 'replacement', ...]
 * @return string                替换后的内容
 */
function replace_in_html_tags( $haystack, $replace_pairs ) {
    $textarr = html_split( $haystack );
    $changed = false;

    if ( 1 === count( $replace_pairs ) ) {
        // 单条替换：直接用 str_replace，避免 strtr 的额外开销
        foreach ( $replace_pairs as $needle => $replace ) {}
        for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
            if ( false !== strpos( $textarr[$i], $needle ) ) {
                $textarr[$i] = str_replace( $needle, $replace, $textarr[$i] );
                $changed     = true;
            }
        }
    } else {
        // 多条替换：用 strtr 一次性处理所有映射，效率更高
        $needles = array_keys( $replace_pairs );
        for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
            foreach ( $needles as $needle ) {
                if ( false !== strpos( $textarr[$i], $needle ) ) {
                    $textarr[$i] = strtr( $textarr[$i], $replace_pairs );
                    $changed     = true;
                    break; // 已替换，跳出内层循环处理下一个标签节点
                }
            }
        }
    }

    if ( $changed ) {
        $haystack = implode( $textarr );
    }
    return $haystack;
}
