var thumb = $('#thumb');

// 编辑器
// KindEditor.ready(function (K) {
//     window.editor = K.create('#editor', {
//         width: '100%',
//         height: '300px',
//         formatUploadUrl: false,
//         filterMode : true, // true:开启过滤模式, false:关闭过滤模式
//         urlType: 'domain',
//         afterBlur: function () {
//             this.sync();
//         }
//     });
// });

// 图片上传
aceFileUpload('#thumb', config.url.uploadUrl);

// 修改图片
if (!config.isNewRecord)
{
    if (config.thumbPath) { // 判断图片路径是否存在
        thumb.ace_file_input("show_file_list", [config.thumbPath]);
    } else {
        thumb.ace_file_input("reset_input");
    }
}

// 添加类
$('.ace-file-input').addClass('col-xs-12 col-sm-10').find('.remove').attr('href','javascript:;');

// 删除上传图片
$(document).on('click','.ace-file-input a.remove',delete_images);

// 删除图片回调方法
function delete_images() {
    var hidden = $('input[name="thumb"]'),
        v = hidden.val(),
        field = thumb.attr('input-name');

    // 上传地址
    config.uploadUrl += config.uploadUrl.indexOf('?') >= 0 ? '&' : '?';
    config.uploadUrl += 'sField=' + field;

    // 异步发送删除图片
    if (v) {
        var arr = {};
        arr[field] = v;
        ajax(config.uploadUrl,arr,function (res) {
            layer.closeAll();
        })
    }

    // 设置隐藏域值为空
    hidden.val('');
    // 重置图片插件
    thumb.ace_file_input("reset_input");
}

// 异步提交
$('#save').submit(_save);

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
        layer.load(0, {shade: false});

        setTimeout(function () {
            // 刷新父级页面数据表格
            parent.$("#show-table").dataTable().fnDraw(false);
            // 关闭所有层
            parent.layer.closeAll();
        },500)
    });
}