function buttons() {
    return {
        create: null,
        updateAll: null,
        deleteAll: null,
        export: null
    }
}

function operationBtn() {
    return {
        see: null,
        update: null,
        import: {
            bShow: true,
            'button-title': '还原',
            className: 'btn-success',
            cClass: 'me-table-import',
            sClass: 'red'
        },
        download: {
            bShow: true,
            'button-title': '下载',
            className: 'btn-primary',
            cClass: 'me-table-download',
            sClass: 'red'
        }
    }
}

// 下载
function download(evt) {
    var row = getRowData(this),
        data = {title: m.table.data()[row].title};

    _ajax(config.url.downloadUrl, data, 'POST', function () {});
}

// 还原文件
function importFile() {
    var row = getRowData(this),
        data = {title: m.table.data()[row].title};

    _ajax(config.url.importUrl, data, 'POST', _success);

    window.onbeforeunload = function () {
        return "正在还原数据库，请不要关闭！"
    }
}

// 异步ajax执行成功的方法
function _success (res) {
    if (res.code == 0) {
        if (res.data.part) {
            ajax(config.url.importUrl, {part: res.data.part, start: res.data.start}, "GET", _success);
        } else {
            window.onbeforeunload = function () {
                return null;
            }
        }
    } else {
        return layer.msg(res.data.info);
    }
}