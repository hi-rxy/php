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
            'button-title': '参数列表',
            className: 'btn-primary',
            cClass: 'me-table-params',
            sClass: 'red',
            show: function (rows) {
                return true;
            }
        }
    }
}

function getStatusStr(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonConfigStatus, config.data.jsonConfigColor, data,'status'));
}

// 设置排序
function updateSortsValue(td, data) {
    $(td).html('<input style="width: 50px;" type="text" name="sort" class="sorts" value="' + data + '" />')
}

function openFrame() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        name = m.table.data()[row].name,
        title = m.table.data()[row].title;

    layerOpen(title + ' [' + name + ']', config.url.configUrl + '?id=' + id, 90, 90);
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