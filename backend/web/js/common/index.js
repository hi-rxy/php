/**
 * 打开弹窗
 * @param title
 * @param url
 * @param width
 * @param height
 */
function layerOpen(title, url, width = 60, height = 80) {
    layer.open({
        type: 2,
        area: [width + '%', height + '%'],
        title: title,
        content: url,
        anim: 2,
        maxmin: true
    });
}

/**
 * 获取当前行索引
 * @param obj
 * @returns {jQuery}
 */
function getRowData(obj) {
    return $(obj).closest('tr').find('input[type="checkbox"]').attr('data-row');
}

/**
 * 设置button按钮
 * @param field
 * @param data
 * @param color
 * @param value
 * @returns {string}
 */
function _showButtonHtml(data, color, value, field) {
    var defaultClass = 'btn btn-xs ' + field;
    return '<button type="button" name="'+field+'" value="' + value + '" class="' + defaultClass + ' ' + (color[value] ? color[value] : '') + '"> ' + (data[value] ? data[value] : value) + ' </button>';
}

/**
 *
 * @param url
 * @param data
 * @param type
 * @param callback
 * @param fail
 * @returns {*}
 * @private
 */
function _ajax(url, data, type, callback, fail) {
    var loading = layer.load();
    return $.ajax({
        url: url,
        data: data,
        type: type,
        dataType: 'json',
    }).done(function (json) {
        callback(json)
    }).fail(function () {
        layer.msg('服务器繁忙,请稍后再试...', {icon: 5})
    }).always(function () {
        layer.close(loading)
    })
}