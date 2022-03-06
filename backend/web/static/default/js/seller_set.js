$(function () {
	if ($(".seller_set_form").length > 0) {
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
	}
});
if ($(".seller_set_upload").length > 0) {
	layui.use(['form', 'upload'], function () {
		var upload = layui.upload;
		upload_img('store_logo');
		upload_img('store_card_front');
		upload_img('store_card_side');

		function upload_img (id) {
			//选完文件后自动上传
			upload.render({
				elem: '#' + id,
				url: "/common/upload",
				auto: true,
				accept: 'file', //普通文件
				data: {type: id, name: 'file'},
				before: function (obj) {
					var img_url = $('input[name=' + id + ']').val();
					// 删除老数据
					if (img_url != '') {
						$.ajax({
							url: "/common/delImg",
							type: 'POST',
							data: {
								img_url: img_url
							},
						});
					}
					//本地本地预览示例
					obj.preview(function (index, file, result) {
						$("#" + id + "_thumb").attr('src', result).show();
					});
				},
				done: function (res) {
					//上传完毕回调
					if (res.code > 0) {
						return layer.msg('上传失败');
					} else {
						$("#" + id + "_thumb").attr('src', res.data.src).show();
						$('input[name=' + id + ']').val(res.data.src);
					}
				}
			});
		}
	});
}

function set_template (obj) {
	layer.confirm('确认要切换吗？', function (index) {
		if ($(obj).parents("ul").hasClass('selected')) {
			return false;
		}
		var store_template = $(obj).attr('store_template');
		$.post('', {store_template: store_template}, function (data) {
			if (data.status == 1) {
				$(obj).parents('.template-box ').find('.template-inner-box').siblings().find('ul').removeClass("selected");
				$(obj).parents("ul").addClass("selected");
				layer.msg('主题切换成功!', {
					icon: 1,
					time: 1000
				});
			}

		}, 'json')

	});
}

function view_template (obj) {
	var vimg = $(obj).attr("vsrc");
	var imgHtml = "<img class='viewimg' src='" + vimg + "' />";
	layer.open({
		type: 1,
		shade: [0.6, '#000000'],
		shadeClose: true,
		title: false,
		area: [700 + 'px', 700 + 'px'],
		content: imgHtml,
	});
}