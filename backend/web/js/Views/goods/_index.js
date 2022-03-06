function buttons() {
    return {
        create:null,
        updateAll: null,
        deleteAll: null,
        publish: {
            'data-func': 'createGoods',
            text: '发布商品',
            icon: '',
            className: 'btn btn-white btn-info btn-bold'
        },
        audit: {
            'data-func': 'allAllow',
            text: '全部通过',
            //icon: 'ace-icon fa fa-check green',
            className: 'btn btn-white btn-success btn-bold'
        },
        deny: {
            'data-func': 'allDeny',
            text: '全部拒绝',
            //icon: 'ace-icon fa fa-plus-circle green',
            className: 'btn btn-white btn-danger btn-bold'
        }
    }
}

function operationBtn() {
    return {
        see:null,
        update: {
            bShow: true,
            'button-title': '修改商品',
            className: 'btn-primary',
            cClass: 'me-table-update-goods',
            sClass: 'red'
        },
        audit: {
            bShow: true,
            'button-title': '点击审核',
            className: 'btn-warning',
            cClass: 'me-table-audit-goods',
            icon: 'fa-legal',
            sClass: 'red',
            'min-icon': 'fa-legal',
        },
        delete: null
    }
}

/*商品状态*/
function showGoodsView(td, data) {
    $(td).html('<button style="padding: 0 !important;" class="btn btn-link name">' + data + '</button>');
}

/*商品状态*/
function getGoodsStatusStr(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonGoodsStatus, config.data.jsonGoodsColor, data, 'status'));
}

/*展示商品主图*/
function showGoodsThumb(td, data) {
    $(td).html('<img src="'+data+'" width="80" />');
}

/*发布商品*/
function create() {
    layerOpen('发布商品', config.url.postCreateUrl, 100, 100);
}

/*修改商品*/
function update() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        title = m.table.data()[row].name;

    layerOpen('修改商品 ['+title+']', config.url.postUpdateUrl + '?id=' + id, 100, 100);
}

/*商品详情*/
function details() {
    var row = getRowData(this),
        id = m.table.data()[row].id,
        title = m.table.data()[row].name;

    layerOpen('商品详情 ['+title+']', config.url.goodsViewUrl + '?id=' + id, 100, 100);
}

/*审核商品*/
function audit() {

    var row = getRowData(this),
        id = m.table.data()[row].id,
        title = m.table.data()[row].name;

    layerOpen('审核商品 ['+title+']',  config.url.goodsAuditUrl + '?id=' + id, 45, 45);
}


/*全部通过*/
function allAllow() {
    var ids = getRowsIds();
    if (!ids.length) return layer.alert('请选择商品',{icon:5});
    ajax(config.url.goodsAuditUrl,{ids:ids,status:1},function (res) {
        // 刷新父级页面数据表格
        $("#show-table").dataTable().fnDraw(false);
        return layer.alert('修改成功',{icon:6});
    });
}

/*全部拒绝*/
function allDeny() {
    var ids = getRowsIds();
    if (!ids.length) return layer.alert('请选择商品',{icon:5});
    ajax(config.url.goodsAuditUrl,{ids:ids,status:2},function (res) {
        // 刷新父级页面数据表格
        $("#show-table").dataTable().fnDraw(false);
        return layer.alert('修改成功',{icon:6});
    });
}

/*批量获取ID*/
function getRowsIds() {
    var ids = new Array();
    $('#show-table tbody').find('input').each(function (k, v) {
        if ($(v).prop('checked')) {
            var row = parseInt($(v).val());
            var tmp = m.table.data()[row];
            ids.push(tmp.id);
        }
    });
    return ids;
}