function buttons() {
    return {
        create: null,
        export: null,
        updateAll: null
    }
}

function operationBtn() {
    return {
        update: null,
        delete: null
    }
}

// 文章评论状态
function articleCommentStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonCommentStatus, config.data.jsonCommentStatusColor, data,'status'));
}