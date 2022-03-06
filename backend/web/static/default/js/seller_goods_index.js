function orderBy (obj, order, desc) {
	var order_by = "";
	if (desc == "desc") {
		order_by = 'asc';
	} else {
		order_by = "desc";
	}
	location.href = skip_order_url + "/order/" + order + "/order_by/" + order_by;
}

/**
 * 多级联动 商品分类
 */
function change_class (obj, type) {
	var class_id = $(obj).val().trim();
	$(obj).nextAll().remove();
	if (class_id == "") {
		return false;
	}
	if (type == 1) {
		var url = ajax_goods_class;
		var name = "class_id[]";
	} else {
		var url = ajax_store_class;
		var name = "store_class_id[]";
	}

	$.post(url, {class_id: class_id}, function (data) {
		if (data.status == 1) {
			if (data.class_data.length > 0) {
				var _html = ' <select lay-ignore name="' + name + '"  Onchange="change_class(this,' + type + ')">';
				_html += '<option value="">请选择分类</option>';
				$.each(data.class_data, function (k, v) {
					_html += '<option value="' + v.class_id + '">--' + v.class_name + '--</option>';
				});
				_html += '</select>';
				$(obj).after(_html);
			}
		}
	}, 'json');
}