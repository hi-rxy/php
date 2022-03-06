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
                return rows.level < 3;
            }
        }
    }
}

// 显示上级分类
function parentStatus(td, data) {
    $(td).html($.getValue(config.data.jsonGoodsClass, data, '顶级分类'));
}

// 设置排序
function updateSortsValue(td, data) {
    $(td).html('<input style="width: 50px;" type="text" name="sort" class="sorts" value="' + data + '" />')
}

// 商品状态
function getGoodsClassStatusStr(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonGoodsClassStatus, config.data.jsonGoodsClassStatusColor, data,'status'));
}

// 商品是否显示在导航
function getGoodsNavStatusStr(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonGoodsClassNavStatus, config.data.jsonGoodsClassStatusColor, data,'is_nav'));
}

// 添加子级
function _alert() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        name = m.table.data()[row].name,
        url = config.url.childrenUrl + '?id=' + id;

    layerOpen(name, url, 60, 80);
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

    if (evt.currentTarget.name == 'sort') {
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
        //$("#show-table").dataTable().fnDraw(false);
    },500)
}