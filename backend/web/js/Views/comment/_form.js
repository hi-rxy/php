ajaxData(null, 1);

$("#submit-recomment").click(function () {
    var submitData = {name: $("#name").val(), content: $("#message").val()};
    reComment(submitData, null);
});

// 获取评论
function ajaxData(_this, page) {
    page = parseInt(page);
    if (isNaN(page) || page < 1) return false;
    $.ajax({
        type: 'get',
        data: {page : page, articleId : comment.articleId},
        dataType: 'json',
        url: comment.url.commentListUrl,
        success: function (jsonData) {
            var appStr = "";
            for (var i in jsonData['data']['data']) {
                appStr += commentTmpl(jsonData['data']['data'][i]);
                appStr += '<ul class="ds-comments" style="padding-left:25px;">';
                for (var y in jsonData['data']['data'][i]['child']) {
                    appStr += commentTmpl(jsonData['data']['data'][i]['child'][y], true) + '</li>';
                }
                appStr += '     </ul>';
                appStr += '</li> ';
            }
            $("#comments-list").html(appStr);
            $("#recomment-count").html(jsonData['data']['count']);   //评论条数
            $("#cmpage").html(jsonData['data']['link']);
        }
    });
}

// 评论列表
function commentTmpl(data, child) {
    var appStr = '';
    appStr += '<li class="ds-post">';
    appStr += '<div data-source="duoshuo" class="ds-post-self">';
    if (!child) {
        appStr += '<div class="ds-avatar">';
        appStr += '<img alt="smister" src="/images/avatar1.png"/>';
        appStr += '</div>';
    } else {
        appStr += '<div class="ds-avatar">';
        appStr += '<img alt="smister" src="/images/avatar3.png"/>';
        appStr += '</div>';
    }
    appStr += '<div class="ds-comment-body">';
    appStr += '<div class="ds-comment-header">';
    appStr += '<span data-qqt-account="" class="ds-user-name"><a class="ds-user-name ds-highlight">' + data.nickname + '</a></span>';
    appStr += '</div>';
    appStr += '<p>';
    if (child) {
        appStr += '<a class="ds-comment-context">回复 ' + data.reply_name + ': </a>';
    }
    appStr += data['content'];
    appStr += '</p>';
    appStr += '<div class="ds-comment-footer ds-comment-actions">';
    appStr += '<span class="ds-time">' + data.created_at + '</span>';
    if (!child) {
        appStr += '<a href="javascript:void(0);" class="ds-post-reply" onclick="showComment(this);" data-id="' + data.id + '">';
        appStr += '<span class="ds-icon ds-icon-reply"></span>回复';
        appStr += '</a>';
    }
    appStr += '</div>';
    appStr += '</div>';
    appStr += '</div>';
    return appStr;
}

// 子级回复框
function showComment(_this) {
    var parentObj = $(_this).parent().parent();
    if (parentObj.find(".recomment-box").length > 0) {
        parentObj.find(".recomment-box").remove();
        return false;
    } else {
        $(".recomment-box").remove();
        var appStr = '';
        appStr += '<div class="ds-replybox recomment-box">';
        appStr += '<a class="ds-avatar" href="javascript:void(0);">';
        appStr += '<img src="/images/avatar2.png" alt="smister">';
        appStr += '</a>';
        appStr += '<form method="post" class="comment-form">';
        appStr += '<div class="ds-textarea-wrapper ds-rounded-top">';
        appStr += '<textarea class="message" data-commentId="' + $(_this).attr('data-id') + '" placeholder="= 。= .评论吧."></textarea>';
        appStr += '<pre class="ds-hidden-text"></pre>';
        appStr += '</div>';
        appStr += '<div class="ds-post-toolbar">';
        appStr += '<div class="ds-post-options ds-gradient-bg"><span class="ds-sync"></span></div>';
        appStr += '<button class="ds-post-button" type="button" onclick="sendComment(this);" >发布</button>';
        appStr += '<div class="ds-toolbar-buttons">';
        appStr += '</div>';
        appStr += '</div>';
        appStr += '</form>';
        appStr += '</div>';
        parentObj.append(appStr);
    }
}

// 子级发布评论
function sendComment(_this) {
    var parentObj = $(_this).parent().parent();
    var messageObj = parentObj.find(".message");
    var data = {
        content: messageObj.val(),
        comment_id: messageObj.attr('data-commentId')
    };
    if (!reComment(data, parentObj.parent())) messageObj.focus();
}

// 发布评论
function reComment(data, obj) {
    if (data.name === "") {
        return layer.alert("名称不能为空", {icon: 5});
    }
    if (data.content === "") {
        return layer.alert("评论的信息不能为空", {icon: 5});
    }

    data.articleId = comment.articleId;

    ajax(comment.url.commentUrl, data, function (res) {
        $("#name").val('');
        $("#message").val('');
        ajaxData(null,1);
        layer.alert('评论成功', {icon:6});
    })
}