var button = $('#save');

// 异步提交
button.submit(_save);

// 提交数据
function _save(evt) {
    evt.preventDefault();
    var $fm = $(this);
    if ($fm.validate().form()) {
        var status = parseInt($('input[name="status"]:checked').val());
        var content = $.trim($('textarea[name="content"]').val());
        if (status == 2) {
            if (content == '') return layer.alert('请输入备注内容', {icon:5});
        }
        ajax($fm.attr('action'), $fm.serialize(), _success);
    }
}

// 异步回调成功方法
function _success(res) {
    var index = layer.alert('保存成功', {
        skin: 'layui-layer-lan'
        ,closeBtn: 0
        ,anim: 0 //动画类型
    },function () {
        // 关闭 layer_alert 弹窗
        layer.close(index);
        // 执行加载层
        layer.load(0, {shade: false});

        setTimeout(function () {
            // 刷新父级页面数据表格
            parent.$("#show-table").dataTable().fnDraw(false);
            // 关闭所有层
            parent.layer.closeAll();
        },500)
    });
}