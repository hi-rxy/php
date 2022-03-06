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
            'button-title': '配置属性',
            className: 'btn-primary',
            cClass: 'me-table-type-attrs',
            sClass: 'red',
            show: function (rows) {
                return true;
            }
        }
    }
}

function getTypeStatusStr(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonTypeStatus, config.data.jsonTypeStatusColor, data,'status'));
}

// 打开属性页面
function openFrame() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        name = m.table.data()[row].name;

    layerOpen(name, config.url.attrUrl + '?id=' + id, 60, 60);
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