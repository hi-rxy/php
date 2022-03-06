$(function () {
	//表单验证
	$("form").Validform({
		tiptype: function (msg, o, cssctl) {
			if (!o.obj.is("form")) {
				//默认表单
				var objtip = o.obj.parents(".layui-form-item").find(".Validform_checktip");
				cssctl(objtip, o.type);
				objtip.text(msg);
			}
		},
		showAllError: true
	});
});
