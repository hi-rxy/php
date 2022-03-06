var count = 1;

var button = $('#save');

// 异步提交
button.submit(_save);

// js动态添加行
function _tr() {
    var html = '';
    html += '<tr>';
    html += '<td><input type="text" name="attrs[name][]" required placeholder="请输入参数名称">' +
        '<input type="hidden" value="0"name="attrs[attr_id][]"></td>';
    html += '<td>';
    html += '<select name="attrs[style][]">';
    html += '<option value="0">参数</option>';
    html += '<option value="1">规格</option>';
    html += '</select>';
    html += '</td>';
    html += '<td>';
    html += '<select name="attrs[type][]">';
    html += '<option value="1">单选框</option>';
    html += '<option value="2">复选框</option>';
    html += '<option value="3">输入框</option>';
    html += '<option value="4">下拉框</option>';
    html += '</select>';
    html += '</td>';
    html += '<td><input type="text" name="attrs[value][]" placeholder="请输入参数值" required></td>';
    html += '<td><input type="text" name="attrs[unit][]" placeholder="请输入参数单位"></td>';
    html += '<td><input type="checkbox" name="attrs[search][]" value="1"></td>';
    html += '<td><input type="text" name="attrs[sort][]" style="width: 50px" value="' + (count++) + '" placeholder="请输入排序"></td>';
    html += '<td><i style="padding-top: 5px;cursor: pointer;padding-left: 10px;" class="ace-icon fa fa-trash-o bigger-150 delete-row"></i></td>';
    html += '</tr>';
    return html;
}

// 删除行
function _delTr() {
    count = 1;
    $(this).closest('tr').remove();
}

// 添加行
function _addTr() {
    button.find('table tbody').append(_tr());
}

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
        // 执行加载层
        //layer.load(0, {shade: false});
        // 刷新父级页面数据表格
        parent.$("#show-table").dataTable().fnDraw(false);
        // 关闭所有层
        parent.layer.closeAll();
    });
}

// 增加行
$(document).on('click', '.add-attrs', _addTr);

// 删除行
$(document).on('click', '.delete-row', _delTr);