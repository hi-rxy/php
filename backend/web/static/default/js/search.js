//商品搜索字描红
if (typeof keywords != 'undefined' && keywords != "") {
	var keywords = keywords.split("");
	if (keywords.length > 0) {
		var goods_name = $(".goods_name");
		goods_name.unhighlight();
		goods_name.highlight(keywords);
	}
}

/**
 * 商品排序
 * @param obj
 * @param order
 * @param desc
 * @param category
 */
function goods_order_by (obj, order, desc, category = "") {
	var order_by = "";
	if (desc == "desc") {
		order_by = "asc";
	} else {
		order_by = "desc";
	}
	if (category == "") {
		var _html = "?keywords=" + $.trim($("input[name=keywords]:eq(0)").val());
		var url = base_url + _html + "&order=" + order + "&order_by=" + order_by;

	} else {
		var url = base_url + "/order/" + order + "/order_by/" + order_by;
	}
	location.href = url;
}