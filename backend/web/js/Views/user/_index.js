function buttons() {
    return {
        updateAll: null
    }
}

function userStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonUserStatus, config.data.jsonUserStatusColor, data,'status'));
}

function cardStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonIDCardStatus, config.data.jsonIDCardStatusColor, data,'user_card_status'));
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

    var index = layer.confirm('确定要操作么',{title:'更新'},function () {
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