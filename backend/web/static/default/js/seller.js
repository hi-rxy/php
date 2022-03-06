$(function () {
	//表单验证
	$("form").Validform({
		tiptype: function (msg, o, cssctl) {
			if (!o.obj.is("form")) {
				$('.layui-form-mid').show();
				//默认表单
				var objtip = o.obj.parents(".layui-form-item").find(".Validform_checktip");
				cssctl(objtip, o.type);
				objtip.text(msg);
			}
		},
		showAllError: true
	});
    $('input[type=number]').blur(function () {
        var min = parseInt($(this).attr('min'));
        var value = $(this).val();
        if (value < min) {
            $(this).val(min);
        }
    });
});


function confirm_msg(obj) {
    var url = $(obj).attr('url');
    layer.confirm('确定要删除吗?', function (index) {
        del_tr(obj);
        location.href = url;
        layer.close(index);
    })
    return false;
}
//删除行
function del_tr(obj) {
    $(obj).parents("tr").remove();
}
