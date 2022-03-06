function buttons() {
    return {
        export: null,
        updateAll: null
    }
}

function operationBtn() {
    return {
        config: {
            bShow: true,
            'button-title': '添加子级',
            className: 'btn-primary',
            cClass: 'me-table-children',
            sClass: 'red',
            show: function (rows) {
                return rows.parent_id == 0;
            }
        }
    }
}

// 显示上级分类
function parentStatus(td, data) {
    $(td).html($.getValue(config.data.category, data, '顶级分类'));
}

// 设置排序
function updateSortsValue(td, data) {
    $(td).html('<input style="width: 50px;" type="text" name="sort" class="sorts" value="' + data + '" />')
}

// 添加子级
function _alert() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        name = m.table.data()[row].name,
        url = config.url.childrenUrl + '?id=' + id;

    layerOpen(name, url, 60, 55);
}

// 更新排序
function _update_sorts(evt) {
    var row = getRowData(this),
        data = {
            id: m.table.data()[row].id,
            sort: $(this).val()
        };

    evt.preventDefault();

    ajax(config.url.updateSortUrl, data, _success);
}

// 异步更新排序值 成功方法
function _success(res) {
    var loading = layer.load(0, {shade: false});
    // 设置定时关闭加载层 同时 刷新数据表格
    setTimeout(function () {
        layer.close(loading);
        //$("#show-table").dataTable().fnDraw(false);
    },500)
}