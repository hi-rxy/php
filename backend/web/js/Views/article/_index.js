function buttons() {
    return {
        create: {
            'data-func': 'publish',
            text: '发布文章',
            icon: 'ace-icon fa fa-plus-circle green',
            className: 'btn btn-white btn-primary btn-bold'
        },
        updateAll: null
    }
}

function operationBtn() {
    return {
        see: null,
        update: {
            bShow: true,
            className: 'btn-primary',
            cClass: 'me-table-article',
            sClass: 'red',
            show: function (rows) {
                return true;
            }
        },
        comment: {
            bShow: true,
            //'button-title': '文章评论',
            className: 'btn-info',
            cClass: 'me-table-comment',
            sClass: 'red',
            icon: 'icon icon-comment',
            'min-icon': 'icon icon-comment',
            show: function (rows) {
                return true;
            }
        }
    }
}

// 标题
function articleTitle(td, data) {
    $(td).html('<button style="padding: 0 !important;" class="btn btn-link title">' + data + '</button>');
}

// 头条
function articleHeadLine(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleBoolStatus, config.data.jsonArticleStatusColor, data, 'is_headline'));
}

// 推荐
function articleRecommend(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleBoolStatus, config.data.jsonArticleStatusColor, data, 'is_recommend'));
}

// 幻灯
function articleSlide(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleBoolStatus, config.data.jsonArticleStatusColor, data, 'is_slide_show'));
}

// 特别推荐
function articleSpecial(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleBoolStatus, config.data.jsonArticleStatusColor, data, 'is_special_recommend'));
}

// 滚动
function articleRoll(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleBoolStatus, config.data.jsonArticleStatusColor, data, 'is_roll'));
}

// 加粗
function articleBold(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleBoolStatus, config.data.jsonArticleStatusColor, data, 'is_bold'));
}

// 文章状态
function articleStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonArticleStatus, config.data.jsonArticleStatusColor, data, 'status'));
}

// 显示上级分类
function articleCategory(td, data) {
    $(td).html($.getValue(config.data.category, data, '顶级分类'));
}

// 设置排序
function updateSortsValue(td, data) {
    $(td).html('<input style="width: 50px;" type="text" name="sorts" class="sorts" value="' + data + '" />')
}

// 发布文章
function publish_article() {
    layerOpen('发布文章', config.url.createUrl, 100, 100);
}

// 评论文章
function comment_article() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        title = m.table.data()[row].title;

    layerOpen(title, config.url.commentUrl + '?id=' + id, 100, 100);
}

// 修改文章
function update_article() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        title = m.table.data()[row].title;

    layerOpen(title, config.url.updateUrl + '?id=' + id, 100, 100);
}

// 查看文章
function show_article() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        title = m.table.data()[row].title;

    layerOpen(title, config.url.viewUrl + '?id=' + id, 100, 100);
}


// 更新值
function _update_single_value(evt) {
    var row = getRowData(this),
        data = {
            id: m.table.data()[row].id,
            value: $(this).val(),
            name: $(this).attr('name')
        };

    evt.preventDefault();

    if (evt.currentTarget.name == 'sorts') {
        return ajax(config.url.updateSingleUrl, data, _success);
    }

    var index = layer.confirm('确定要启用此项么',{title:'更新'},function () {
        layer.close(index);
        ajax(config.url.updateSingleUrl, data, _success);
    })
}

// 异步更新值 成功方法
function _success(res) {
    var loading = layer.load(0, {shade: false});
    // 设置定时关闭加载层 同时 刷新数据表格
    setTimeout(function () {
        layer.close(loading);
        $("#show-table").dataTable().fnDraw(false);
    },500)
}