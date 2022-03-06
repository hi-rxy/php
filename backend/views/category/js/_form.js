var save = $('#save');

// 异步提交
save.submit(_save);

// 提交数据
function _save(evt) {
    evt.preventDefault();
    var $fm = $(this);
    if ($fm.validate().form()) {
        ajax($fm.attr('action'), $fm.serialize(), _success);
    }
}

// 异步回调成功方法
function _success(res) {
    var index = layer.alert('保存成功', {
        skin: 'layui-layer-lan'
        , closeBtn: 0
        , anim: 0 //动画类型
    }, function () {
        // 关闭 layer_alert 弹窗
        layer.close(index);
        // 刷新父级页面数据表格
        parent.$("#show-table").dataTable().fnDraw(false);
        // 关闭所有层
        parent.layer.closeAll();
    });
}

