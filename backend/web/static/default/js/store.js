$(function () {
	/*$(".store-nav li").live("click", function () {
		$(this).addClass("active").siblings().removeClass("active");
	});*/

	var top1 = $(".store-nav-box").offset().top;
	$(window).scroll(function () {
		//				动态获取当前页面的位置
		var win_top = $(this).scrollTop();
		//				动态获取需要固定的div的位置
		var top = $(".store-nav-box").offset().top;
		//				把当前页面位置和要固定div的位置想对比
		//				如果页面位置大于等于需要固定的div位置说明此时需要固定了，只需要给它添加一个css样式即可
		if (win_top >= top) {
			$(".store-nav-box").addClass("sfixed");
		}
		//把当前页面位置和要固定div的位置想对比
		//				如果页面位置小于需要固定的div位置说明此时不需要固定，只需要给它移除之前添加的css样式即可
		if (win_top < top1) {
			$(".store-nav-box").removeClass("sfixed");
		}
	});
});
//商品关键词描红
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
 */
function goods_order_by (obj, order, desc) {
	var order_by = "";
	if (desc == "desc") {
		order_by = "asc";
	} else {
		order_by = "desc";
	}
	var goods_min_price = $.trim($("[name=goods_min_price]").val());
	var goods_max_price = $.trim($("[name=goods_max_price]").val());
	if (all_class == 1) {
		var _html = "&keywords=" + $.trim($("input[name=keywords]:eq(1)").val());
	} else {
		var _html = "";
	}
	location.href = base_url + "?goods_min_price=" + goods_min_price + "&goods_max_price=" + goods_max_price + _html + "&order=" + order + "&order_by=" + order_by;
}

/**
 * 根据价格搜索
 * @param obj
 * @param order
 * @param desc
 */
function search_goods_price (obj, order, order_by) {
	var goods_min_price = $.trim($("[name=goods_min_price]").val());
	var goods_max_price = $.trim($("[name=goods_max_price]").val());
	if (all_class == 1) {
		var _html = "&keywords=" + $.trim($("input[name=keywords]:eq(1)").val());
	} else {
		var _html = "";
	}
	location.href = base_url + "?goods_min_price=" + goods_min_price + "&goods_max_price=" + goods_max_price + _html + "&order=" + order + "&order_by=" + order_by;
}