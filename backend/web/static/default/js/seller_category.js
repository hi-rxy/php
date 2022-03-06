$(function () {
    //分类排序不能小于1
    $('input[type=number]').blur(function () {
        var min = parseInt($(this).attr('min'));
        var value = $(this).val();
        if (value < min) {
            $(this).val(min);
        }
    });
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
});
