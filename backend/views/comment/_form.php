<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = $model->title;
?>
<link rel="stylesheet" href="/css/View/comment/comment.css?v=<?= time() ?>">
<link rel="stylesheet" href="/css/View/comment/detail.css?v=<?= time() ?>">
<?php $this->beginBlock('javascript') ?>
<script src="/js/Views/comment/_form.js?v=<?= time() ?>"></script>
<?php $this->endBlock(); ?>
<script type="text/javascript">
    var comment = {
        articleId : <?=Yii::$app->request->get('id')?>,
        url : {
            commentUrl : "<?=Url::toRoute(['create'])?>",
            commentListUrl : "<?=Url::toRoute(['list'])?>",
        }
    }
</script>

<div class="card">
    <div class="comments">
        <div id="ds-thread" class="ds-thread">
            <div id="ds-reset">
                <!-- 评论回复框 -->
                <div class="ds-replybox">
                    <a class="ds-avatar" href="javascript:void(0);"><img src="/images/recomment.jpg" alt="smister"></a>
                    <form method="post" class="comment-form">
                        <div class="ds-post-options ds-gradient-bg" style="margin-bottom:10px;">
                            <input type="text" value="" placeholder="昵称" id="name" class="ds-name" />
                        </div>
                        <div class="ds-textarea-wrapper ds-rounded-top" style="border: 1px solid #ccc;border-bottom: none;">
                            <textarea id="message" placeholder="评论内容"></textarea>
                            <pre class="ds-hidden-text"></pre>
                        </div>
                        <div class="ds-post-toolbar">
                            <div class="ds-post-options ds-gradient-bg"><span class="ds-sync"></span></div>
                            <button class="ds-post-button" type="button" id="submit-recomment">发布</button>
                            <div class="ds-toolbar-buttons"></div>
                        </div>
                    </form>
                </div>

                <div class="ds-comments-info">
                    <ul class="ds-comments-tabs">
                        <li class="ds-tab">
                            <a href="javascript:void(0);" class="ds-comments-tab-duoshuo ds-current">
                                <span class="ds-highlight" id="recomment-count">2</span>条评论
                            </a>
                        </li>
                    </ul>
                </div>

                <ul id="comments-list" class="ds-comments"></ul>
            </div>
            <div id="cmpage" class="ds-paginator" style="text-align: center;"></div>
        </div>
    </div>
</div>