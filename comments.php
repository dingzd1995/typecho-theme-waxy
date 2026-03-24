<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
    <h3><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h3>

    <?php
    // ── 收集所有评论（含嵌套回复） ────────────────────────────
    // Typecho 1.2.1 的 listComments 无 callback 参数，但 threadedCommentsCallback()
    // 会在全局函数 threadedComments() 存在时调用它（含递归子评论）。
    // ob_start 丢弃默认 HTML 输出，只保留收集到的数据。
    global $waxy_coms_collect;
    $waxy_coms_collect = [];

    if (!function_exists('threadedComments')) {
        function threadedComments($comment, $options)
        {
            global $waxy_coms_collect;
            $mail = $comment->mail ?? '';
            $waxy_coms_collect[$comment->coid] = [
                'coid'    => $comment->coid,
                'parent'  => $comment->parent,
                'author'  => $comment->author,
                'url'     => $comment->url,
                'created' => $comment->created,
                'content' => $comment->content,
                'avatar'  => 'https://cravatar.cn/avatar/' . md5(strtolower(trim($mail))) . '?s=40&d=mp&r=g',
            ];
            // 递归收集子评论（调用 widget 的 threadedComments() 方法，
            // 它会对每个子评论再次触发本函数）
            $comment->threadedComments();
        }
    }

    ob_start();
    $comments->listComments();
    ob_end_clean();

    $coms = $waxy_coms_collect;
    $waxy_coms_collect = [];

    // ── 向上追溯根评论 ────────────────────────────────────────
    $find_root = null;
    $find_root = function ($coid) use (&$coms, &$find_root) {
        if (!isset($coms[$coid]) || $coms[$coid]['parent'] == 0) return $coid;
        return $find_root($coms[$coid]['parent']);
    };

    // ── 分组：根评论 / 各根下的回复（按时间排序） ─────────────
    $roots   = [];
    $replies = [];
    foreach ($coms as $coid => $c) {
        if ($c['parent'] == 0) {
            $roots[$coid] = $c;
        } else {
            $replies[$find_root($coid)][] = $c;
        }
    }
    foreach ($replies as &$list) {
        usort($list, function ($a, $b) { return $a['created'] - $b['created']; });
    }
    unset($list);

    // ── 渲染单条评论 ──────────────────────────────────────────
    $can_reply = $this->allow('comment') && $this->options->commentsReply != '0';

    $render = function ($c) use (&$coms, $can_reply) {
        $name   = htmlspecialchars($c['author']);
        $author = $c['url']
            ? '<a href="' . htmlspecialchars($c['url']) . '" target="_blank" rel="noopener noreferrer">' . $name . '</a>'
            : $name;
        $time      = date('Y-m-d H:i', $c['created']);
        $reply_to  = ($c['parent'] && isset($coms[$c['parent']]))
            ? '<span class="comment__reply-to">@ ' . htmlspecialchars($coms[$c['parent']]['author']) . '</span>'
            : '';
        $reply_btn = $can_reply
            ? '<a class="comment__reply-link" href="#" data-coid="' . $c['coid'] . '" data-author="' . addslashes($name) . '" onclick="waxySetReply(this);return false;">回复</a>'
            : '';
        ?>
        <div class="comment__wrap">
            <img class="comment__avatar" src="<?= htmlspecialchars($c['avatar']) ?>" width="40" height="40" alt="<?= $name ?>" />
            <div class="comment__main">
                <div class="comment__meta">
                    <cite class="fn"><?= $author ?></cite>
                    <?= $reply_to ?>
                    <time class="comment__time"><?= $time ?></time>
                    <?= $reply_btn ?>
                </div>
                <div class="comment__content"><?= $c['content'] ?></div>
            </div>
        </div>
        <?php
    };
    ?>

    <ol class="comment-list">
    <?php foreach ($roots as $c): ?>
        <li id="comment-<?= $c['coid'] ?>" class="comment comment--root">
            <?php $render($c); ?>
            <?php if (!empty($replies[$c['coid']])): ?>
            <ol class="comment__children">
                <?php foreach ($replies[$c['coid']] as $reply): ?>
                <li id="comment-<?= $reply['coid'] ?>" class="comment comment--reply">
                    <?php $render($reply); ?>
                </li>
                <?php endforeach; ?>
            </ol>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ol>

    <?php $comments->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>

    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
    <div id="respond" class="respond">
        <div id="waxy-form-wrap">
            <h3 id="waxy-form-title" class="addco"><?php _e('添加新评论'); ?></h3>
            <div id="waxy-replying-to" style="display:none;">
                回复 <strong id="waxy-reply-to-name"></strong>
                <a href="#" id="waxy-cancel-btn">取消</a>
            </div>
            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
                <input type="hidden" name="parent" id="waxy-parent" value="0" />
                <?php if ($this->user->hasLogin()): ?>
                <p><?php _e('登录身份: '); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a></p>
                <?php else: ?>
                <p>
                    <input type="text" name="author" placeholder="昵称（必填）" id="author" class="form-field" value="<?php $this->remember('author'); ?>" required />
                </p>
                <p>
                    <input type="email" name="mail" id="mail" placeholder="邮件（必填，仅管理员可见，支持Gravatar头像）" class="form-field" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />
                </p>
                <p>
                    <input type="url" name="url" id="url" class="form-field" placeholder="<?php _e('您的网址（非必填，请带上http://或https://）'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
                </p>
                <?php endif; ?>
                <p>
                    <textarea rows="5" cols="50" name="text" id="textarea" class="form-field" placeholder="<?php _e('留下您伟大的看法......'); ?>" required><?php $this->remember('text'); ?></textarea>
                </p>
                <p>
                    <button type="submit" class="btn btn--default btn--lg"><?php _e('提交评论'); ?></button>
                </p>
            </form>
        </div>
    </div>
    <script>
    (function () {
        var formWrap    = document.getElementById('waxy-form-wrap');
        var respondDiv  = document.getElementById('respond');
        var parentInput = document.getElementById('waxy-parent');
        var formTitle   = document.getElementById('waxy-form-title');
        var replyingTo  = document.getElementById('waxy-replying-to');
        var replyName   = document.getElementById('waxy-reply-to-name');
        var cancelBtn   = document.getElementById('waxy-cancel-btn');
        var inlineWrap  = null;

        function resetForm() {
            parentInput.value = 0;
            replyName.textContent = '';
            replyingTo.style.display = 'none';
            formTitle.style.display = '';
            formWrap.classList.remove('is-inline');
            respondDiv.appendChild(formWrap);
            respondDiv.style.display = '';
            if (inlineWrap) { inlineWrap.remove(); inlineWrap = null; }
        }

        window.waxySetReply = function (anchor) {
            var coid     = anchor.dataset.coid;
            var author   = anchor.dataset.author;
            var targetLi = document.getElementById('comment-' + coid);
            if (!targetLi) return;

            if (inlineWrap) { inlineWrap.remove(); inlineWrap = null; }

            respondDiv.style.display = 'none';

            // 插在 .comment-wrap 正后方（子回复列表之前），用 <div> 避免 li>li 非法嵌套
            inlineWrap = document.createElement('div');
            inlineWrap.className = 'comment__reply-form';
            inlineWrap.appendChild(formWrap);
            formWrap.classList.add('is-inline');
            targetLi.querySelector('.comment__wrap').insertAdjacentElement('afterend', inlineWrap);

            parentInput.value = coid;
            replyName.textContent = author;
            formTitle.style.display = 'none';
            replyingTo.style.display = '';

            document.getElementById('textarea').focus();
        };

        cancelBtn.addEventListener('click', function (e) {
            e.preventDefault();
            resetForm();
        });
    })();
    </script>
    <?php else: ?>
    <h3><?php _e('评论已关闭'); ?></h3>
    <?php endif; ?>
</div>
