function buttons() {
    return {
        create: null,
        updateAll: null,
        deleteAll: null,
        export: null,
        optimize: {
            'data-func': 'optimize',
            text: '优化表',
            icon: 'ace-icon fa fa-plus-circle green',
            className: 'btn btn-white btn-primary btn-bold'
        },
        repair: {
            'data-func': 'repair',
            text: '修复表',
            icon: 'ace-icon fa fa-plus-circle green',
            className: 'btn btn-white btn-primary btn-bold'
        },
        backup: {
            'data-func': 'backup',
            text: '备份表',
            icon: 'ace-icon fa fa-plus-circle green',
            className: 'btn btn-white btn-primary btn-bold'
        }
    }
}

function operationBtn() {
    return {
        see: null,
        update: null,
        delete: null,
        optimize: {
            bShow: true,
            'button-title': '优化表',
            className: 'btn-success',
            cClass: 'me-table-optimize',
            sClass: 'red',
            show: function (rows) {
                return true;
            }
        },
        repair: {
            bShow: true,
            'button-title': '修复表',
            className: 'btn-danger',
            cClass: 'me-table-repair',
            sClass: 'red',
            show: function (rows) {
                return true;
            }
        }
    }
}

// 获取选中项的表名称
function getSelectedTableName() {
    var data = new Array();
    $('#show-table tbody').find('input').each(function (k, v) {
        if ($(v).prop('checked')) {
            var row = parseInt($(v).val());
            var tmp = m.table.data()[row];
            data.push(tmp.Name);
        }
    });
    return data;
}

// 备份数据表
function fBackup() {
    var tabs = getSelectedTableName();

    if (!tabs.length) {
        return layer.msg('请选择需要备份的表');
    }

    var msg = layer.msg('正在发送备份请求');
    setTimeout(function () {
        layer.close(msg);
        _ajax(config.url.backupUrl, {tables: tabs}, 'POST', function (res) {
            if (res.code == 0) {
                layer.msg(res.msg);
                tables = res.data.tables;
                backup(res.data.tab);
                window.onbeforeunload = function () {
                    return "正在备份数据库,请不要关闭! "
                }
            } else {
                layer.msg(res.msg);
            }
        });
    }, 500)
}

function backup(tab, status) {
    _ajax(config.url.backupUrl, tab, 'GET', function (res) {
        if (res.code == 0) {
            if (!$.isPlainObject(res.data.tab)) {
                layer.msg('备份完成');
                window.onbeforeunload = function () {
                    return null
                };
                return;
            }
            backup(res.data.tab, tab.id != res.data.tab.id);
        } else {
            layer.msg(res.info);
        }
    });
}

// 顶部批量处理优化表
function fOptimize() {
    var tabs = getSelectedTableName();

    if (!tabs.length) {
        return layer.msg('请选择需要优化的表');
    }

    ajaxSendData(config.url.optimizeUrl, tabs);
}

// 顶部批量处理修复表
function fRepair() {
    var tabs = getSelectedTableName();

    if (!tabs.length) {
        return layer.msg('请选择需要修复的表');
    }

    ajaxSendData(config.url.repairUrl, tabs);
}

// 公共异步方法
function ajaxSendData(url, tabs) {
    ajax(url, {name: tabs}, _success)
}

// 点击事件的回调方法
function rowClickEvent(evt) {
    var text = $(this).text(),
        row = getRowData(this),
        data = {name: m.table.data()[row].Name};

    evt.preventDefault();

    if (text == '优化表') {
        url = config.url.optimizeUrl;
    } else {
        url = config.url.repairUrl;
    }

    var index = layer.confirm('确定要操作么', {title: m.table.data()[row].Name}, function () {
        layer.close(index);
        ajax(url, data, _success);
    })
}

// 异步ajax执行成功的方法
function _success() {
    var loading = layer.load(0, {shade: false});
    // 设置定时关闭加载层 同时 刷新数据表格
    setTimeout(function () {
        layer.close(loading);
        $("#show-table").dataTable().fnDraw(false);
    }, 500)
}