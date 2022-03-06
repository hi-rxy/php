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
	//ajax更新相关信息
	$(".ajax_get").live('click', function () {
		var type = $(this).attr('type');
		var value = $(this).attr('value');
		var class_id = $(this).attr('class_id');
		var obj = $(this);
		var _html = obj.attr('txt');
		layer.confirm('确认' + _html + '吗？', function (index) {
			layer.closeAll();
			$.post(ajax_url, {type: type, value: value, class_id: class_id}, function (data) {
				if (data.status == 1) {
					value = parseInt(value);
					if (value == 1) {
						if (type == "class_status") {
							obj.attr('title', '点我停用').html('已启用').attr('txt', '停用');
						}else if(type == "class_is_nav"){
							obj.attr('title', '点我关闭导航栏').html('已显示').attr('txt', '关闭');
						}
						obj.addClass('label-success').removeClass('label-default');
						obj.attr('value', 0);
					} else {
						if (type == "class_status") {
							obj.attr('title', '点我启用').html('停用').attr('txt', '启用');
						}else if(type == "class_is_nav"){
							obj.attr('title', '点我显示导航栏').html('关闭').attr('txt', '显示');
						}
						obj.addClass('label-default').removeClass('label-success');
						obj.attr('value', 1);
					}
				} else {
					layer.msg(data.info);
				}
			}, 'json')
		})
	});
	//ajax排序
	$(".class_sort").on("change",function(){
		var value = $(this).val();
		var type = $(this).attr('name');
		var class_id = $(this).attr('class_id');
		$.post(ajax_url, {type:type, value: value, class_id: class_id}, function (data) {
			if (data.status == 1) {
				//layer.msg(data.info, {icon: 1});
			} else {
				layer.msg(data.info);
			}
		}, 'json');
	})
});

