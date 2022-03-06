function buttons() {
    return {
        export: null,
        updateAll: null
    }
}

function getStatusStr(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonConfigStatus, config.data.jsonConfigColor, data,'status'));
}

function getTypes(td, data) {
    $(td).html($.getValue(config.data.jsonFormTypes, config.data.jsonFormTypes, data));
}

// 设置排序
function updateSortsValue(td, data) {
    $(td).html('<input style="width: 50px;" type="text" name="sort" class="sorts" value="' + data + '" />')
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
        $("#show-table").dataTable().fnDraw(false);
    },500)
}