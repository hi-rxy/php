// 设置排序
function updateSortsValue(td, data) {
    $(td).html('<input style="width: 50px;" type="text" name="sort" class="sorts" value="' + data + '" />')
}

// 店铺分类状态
function storeClassStatus(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonStoreClassStatus, config.data.jsonStoreClassStatusColor, data,'status'));
}

// 店铺分类是否显示
function storeClassIsNav(td, data) {
    $(td).html(_showButtonHtml(config.data.jsonStoreClassNavStatus, config.data.jsonStoreClassNavStatusColor, data,'is_nav'));
}

// 自动获取店铺分类父级
function AutoGetParentsByStoreId (store_id, store_class_pid) {
    ajax(config.url.getParentsUrl,{store_id:store_id, pid:store_class_pid},function(res){
        $('select[name="pid"]').html(res);
    })
}

// 点击店铺获取父级分类信息
function _getParents (){
    var store_id = $(this).val();
    AutoGetParentsByStoreId(store_id, 0);
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