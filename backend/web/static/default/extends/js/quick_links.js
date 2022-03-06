var cart = {
	is_lock: false,
};
//请求前
cart.ajax_before = function () {
	cart.is_lock = true;
}
//请求完成
cart.ajax_complete = function () {
	cart.is_lock = false;
}
$(function () {
	//鼠标悬停信息
	$("#wrap .item").mouseenter(function () {
		var _index = $(this).index();
		if (_index == 1) {
			$.ajax({
				url: ajax_cart_side_data,
				type: "GET",
				async: false,
				dataType: 'jsonp',
				jsonp: "callback",
				success: function (data) {
					if (data.status == 1) {
						var _html = "";
						_html += '<div class="sc-top">';
						_html += '	<a class="sc-cart-btn" href="' + cart_index_url + '" title="全屏查看">全屏查看</a>';
						_html += '	<div class="sc-click">';
						if (data.count_status > 0) {
							if (data.count_status_1 == data.count_status) {
								_html += '		<input id="sc-select-cart" type="checkbox" class="sc-select-cart sidebar_all" onclick="sidebar_quanbu(this)" value="1" checked="checked">';
							} else {
								_html += '		<input id="sc-select-cart" type="checkbox" class="sc-select-cart sidebar_all" onclick="sidebar_quanbu(this)" value="0">';
							}
						}

						_html += '	</div>';
						_html += '	<label for="sc-celect-cart">全选</label>';
						_html += '</div>';
						if (data.page_data != null) {
							$.each(data.page_data, function (k, v) {
								_html += '<div class="sc-bundle">';
								_html += '	<div class="bundle-header">';
								_html += '		<div class="sc-chk">';
								if (v.store.cart_status_1 == v.store.cart_status) {
									_html += '			<input class="sc-store-input store_sidebar_check" type="checkbox" value="1" onclick="sidebar_store_quanbu(this)" checked="checked">';
								} else {
									_html += '			<input class="sc-store-input store_sidebar_check" type="checkbox" value="0" onclick="sidebar_store_quanbu(this)">';
								}
								_html += '		</div>';
								_html += '		<div class="sc-title"><span title="' + v.store.store_name + '">' + v.store.store_name + '</span></div>';
								_html += '		<div class="sc-cost"><strong class="sc-sum-price" id="sidebar_store_total_' + v.store.store_id + '">¥' + v.store.total_price + '</strong></div>';
								_html += '	</div>';
								_html += '	<div class="sc-main-list">';
								$.each(v.child, function (k1, v1) {
									_html += '		<div  class="sc-order">';
									_html += '			<div class="sc-chk">';
									if (v1.cart_status == 1) {
										_html += '				<input class="sc-select sidebar_check" type="checkbox" standard_id="' + v1.standard_id + '" goods_id="' + v1.goods_id + '" id="sidebar_input_' + v1.goods_id + '_' + v1.standard_id + '" checked="checked" onclick="sidebar_self(this,' + v1.goods_id + ',' + v1.standard_id + ');">';
									} else {
										_html += '				<input class="sc-select sidebar_check" type="checkbox" standard_id="' + v1.standard_id + '" goods_id="' + v1.goods_id + '" id="sidebar_input_' + v1.goods_id + '_' + v1.standard_id + '" onclick="sidebar_self(this,' + v1.goods_id + ',' + v1.standard_id + ',' + v.store.store_id + ');">';
									}

									_html += '			</div>';
									_html += '			<div class="sc-item">';
									_html += '				<a class="sc-pic" href="' + v1.url + '" title="' + v1.goods_name + '"';
									_html += '				   target="_blank"><img height="50" src="' + v1.goods_thumb + '"></a>';
									_html += '			</div>';
									_html += '			<div class="sc-amount">';
									_html += '				<a href="javascript:void(0)" class="am-icon am-icon-minus sc-minus" onclick="sidebar_decrease_quantity(' + v1.goods_id + ',' + v1.standard_id + ');" id="sidebar_decrease_' + v1.goods_id + '_' + v1.standard_id + '"></a>';
									_html += '<span class="sc-quantity" onclick="sidebar_change_quantity(' + v1.standard_id + ',this,' + v1.goods_id + ',' + v1.goods_stock + ',' + v.store.store_id + ')" id="sidebar_' + v1.goods_id + '_' + v1.standard_id + '" data-max="' + v1.goods_stock + '" value="' + v1.goods_num + '">' + v1.goods_num + '</span>';
									_html += '				<a href="javascript:void(0)" class="am-icon am-icon-plus sc-plus" onclick="sidebar_add_quantity(' + v1.goods_id + ',' + v1.standard_id + ');" id="sidebar_increment_' + v1.goods_id + '_' + v1.standard_id + '}"></a>';
									_html += '			</div>';
									_html += '			<div class="sc-cost">';
									_html += '				<a href="javascript:void(0)" class="am-icon am-icon-trash-o sc-del" onclick="sidebar_remove_self(this,' + v1.goods_id + ',' + v1.standard_id + ',' + v.store.store_id + ')" id="sidebar_del_' + v1.goods_id + '_' + v1.standard_id + '" title="删除"></a><strong class="sc-price" id="sidebar_goods_total_' + v1.goods_id + '_' + v1.standard_id + '">¥' + v1.total_price + '</strong>';
									_html += '			</div>';
									_html += '		</div>';
								})
								_html += '	</div>';
								_html += '</div>';
							})
						}
						$(".sclist").html(_html);
						$(".sc-checked-num").html(data.count_status_1);
						$(".sc-total-fee").html("¥" + data.count_price);
						$(".cart-info .num").html(data.total_rows);
						$(".cart_num").html(data.total_rows);
						side_cart();

					}
				}
			})
		}
		$(this).children(".mp_tooltip").animate({left: -92, queue: true});
		$(this).children(".mp_tooltip").css("visibility", "visible");
		$(this).children(".ibar_login_box").css("display", "block");
	});
	$("#wrap .item").mouseleave(function () {
		$(this).children(".mp_tooltip").css("visibility", "hidden");
		$(this).children(".mp_tooltip").animate({left: -121, queue: true});
		$(this).children(".ibar_login_box").css("display", "none");
	});
	$(".quick_toggle li").mouseover(function () {
		$(this).children(".mp_qrcode").show();
		$(this).children(".mp_tooltip").animate({left: -92, queue: true});
		$(this).children(".mp_tooltip").css("visibility", "visible");
	});
	$(".quick_toggle li").mouseleave(function () {
		$(this).children(".mp_qrcode").hide();
		$(this).children(".mp_tooltip").css("visibility", "hidden");
		$(this).children(".mp_tooltip").animate({left: -121, queue: true});
	});

	$(".return_top").click(function () {
		ds.scrollTo(0, 0);
		hideReturnTop();
	})
});

//减少数量
function sidebar_decrease_quantity (goods_id, standard_id) {
	var item = $("#sidebar_" + goods_id + "_" + standard_id);
	var orig = Number(item.attr('value'));
	if (orig > 1) {
		item.html(orig - 1).attr('value', orig - 1);
		item.click();
	}
}

//增加
function sidebar_add_quantity (goods_id, standard_id) {
	var item = $("#sidebar_" + goods_id + "_" + standard_id);
	var orig = Number(item.attr('value'));
	var max = item.attr('data-max');
	if (max >= (orig + 1)) {
		item.html(orig + 1).attr('value', orig + 1);
		item.click();
	}
}

//数量发生改变时候
function sidebar_change_quantity (standard_id, input, goods_id, goods_stock, store_id) {
	if (cart.is_lock == true) {
		layer.msg('正在处理中请稍后', {icon: 2});
		return false;
	}
	var _v = $(input).attr('value');
	if (_v <= 0 || _v == "" || !/^\d+$/.test(_v)) {
		input.value = 1;
		_v = 1;
	}
	if (_v > goods_stock) {
		_v = goods_stock;
	}
	var url = cart_update_url;
	$.ajax({
		beforeSend: cart.ajax_before,
		type: "GET",
		url: url,
		async: false,
		data: {
			standard_id: standard_id, goods_num: _v, goods_id: goods_id
		},
		dataType: 'jsonp',
		jsonp: "callback",
		success: function (data) {
			if (data.status == 1) {
				$(input).val(data.goods_num);

				$(".sc-checked-num").html(data.count_status_1);
				$(".sc-total-fee").html("¥" + data.count_price);
				$(".cart-info .num").html(data.total_rows);
				$(".cart_num").html(data.total_rows);
				$("#sidebar_goods_total_" + goods_id + "_" + standard_id).html(data.product_total);
				//当前店铺的总价
				$("#sidebar_store_total_" + store_id).html("¥" + data.store_total);
			} else {
				$(input).attr('value', data.goods_stock);
			}
		},
		complete: cart.ajax_complete
	});
}

//自己本身
function sidebar_self (obj, goods_id, standard_id, store_id) {
	var value = $(obj).attr('value');
	if (value == 1) {
		$(obj).attr('value', 0);
	} else {
		$(obj).attr('value', 1);
	}
	//总的全选
	var _length_checked = $(".sidebar_check:checked").length;
	var _length = $(".sidebar_check").length;
	if (_length_checked == _length) {
		$(".sidebar_all").attr('value', 1).attr("checked", true);
	} else {
		$(".sidebar_all").attr('value', 0).attr("checked", false);
	}
	//店铺全选
	var _length_store_checked = $(obj).parents(".sc-bundle").find(".sidebar_check:checked").length;
	var _length_store = $(obj).parents(".sc-bundle").find(".sidebar_check").length;
	if (_length_store_checked == _length_store) {
		$(obj).parents(".sc-bundle").find(".store_sidebar_checkd").attr('value', 1).attr("checked", "checked");
	} else {
		$(obj).parents(".sc-bundle").find(".store_sidebar_checkd").attr('value', 0).removeAttr("checked");
	}
	//判断当前是否是选中
	if ($(obj).attr('checked')) {
		var cart_status = 1;
	} else {
		var cart_status = 0;
	}
	$.ajax({
		type: "GET",
		url: cart_status_url,
		async: false,
		data: {standard_id: standard_id, goods_id: goods_id, cart_status: cart_status},
		dataType: 'jsonp',
		jsonp: "callback",
		success: function (data) {
			if (data.status == 1) {
				$(".sc-checked-num").html(data.count_status_1);
				$(".sc-total-fee").html("¥" + data.count_price);
				$(".cart-info .num").html(data.total_rows);
				$(".cart_num").html(data.total_rows);
				//当前店铺的总价
				$("#sidebar_store_total_" + store_id).html("¥" + data.store_total);
			}
		}
	});
}

//总的全选
function sidebar_quanbu (obj) {
	var value = $(obj).attr('value');
	if (value == 1) {
		$(obj).attr('value', 0);
	} else {
		$(obj).attr('value', 1);
	}
	var input = $(".sidebar_check");
	if (value == 1) {
		input.removeAttr("checked");
		$(".store_sidebar_check").attr('value', 0).removeAttr('checked')
	} else {
		input.attr('checked', 'checked');
		$(".store_sidebar_check").attr('value', 1).attr('checked', 'checked')
	}
	//发送ajax请求
	var standard_id = new Array();
	var goods_id = new Array();
	input.each(function () {
		standard_id.push($(this).attr('standard_id'));
		goods_id.push($(this).attr('goods_id'));
	})
	//判断当前是否是选中
	if ($(obj).attr('checked')) {
		var cart_status = 1;
	} else {
		var cart_status = 0;
	}
	var url = cart_ajax_status_all_url;
	$.ajax({
		type: "GET",
		url: url,
		async: false,
		data: {standard_id: standard_id, goods_id: goods_id, cart_status: cart_status},
		dataType: 'jsonp',
		jsonp: "callback",
		success: function (data) {
			if (data.status == 1) {
				$(".sc-checked-num").html(data.count_status_1);
				$(".sc-total-fee").html("¥" + data.count_price);
				$(".cart-info .num").html(data.total_rows);
				$(".cart_num").html(data.total_rows);
			}
		}
	});
}

//店铺全选和全不选
function sidebar_store_quanbu (obj) {
	var value = $(obj).attr('value');
	if (value == 1) {
		$(obj).attr('value', 0);
	} else {
		$(obj).attr('value', 1);
	}
	var input = $(obj).parents(".sc-bundle").find(".sidebar_check");
	if (value == 1) {
		input.removeAttr("checked");
	} else {
		input.attr('checked', 'checked');
	}
	//总的全选
	var _length_checked = $(".sidebar_check:checked").length;
	var _length = $(".sidebar_check").length;
	if (_length_checked == _length) {
		$(".sidebar_all").attr('value', 1).attr("checked", true);
	} else {
		$(".sidebar_all").attr('value', 0).attr("checked", false);
	}
	//发送ajax请求
	var standard_id = new Array();
	var goods_id = new Array();
	input.each(function () {
		standard_id.push($(this).attr('standard_id'));
		goods_id.push($(this).attr('goods_id'));
	})
	//判断当前是否是选中
	if ($(obj).attr('checked')) {
		var cart_status = 1;
	} else {
		var cart_status = 0;
	}
	var url = cart_ajax_status_all_url;
	$.ajax({
		type: "GET",
		url: url,
		async: false,
		data: {standard_id: standard_id, goods_id: goods_id, cart_status: cart_status},
		dataType: 'jsonp',
		jsonp: "callback",
		success: function (data) {
			if (data.status == 1) {
				$(".sc-checked-num").html(data.count_status_1);
				$(".sc-total-fee").html("¥" + data.count_price);
				$(".cart-info .num").html(data.total_rows);
				$(".cart_num").html(data.total_rows);
			}
		}
	});
}


//单个删除
function sidebar_remove_self (self, goods_id, standard_id,store_id) {
	layer.confirm('您确定要删除吗', function (index) {
		if (!($("#sidebar_input_" + goods_id + "_" + standard_id).attr("checked") == "checked")) {
			layer.msg('请勾选列表项目', {icon: 2});
			return false;
		}
		$.ajax({
			url: ajax_cart_del_url,
			async:false,
			data: {
				goods_id: goods_id,
				standard_id: standard_id,
			},
			dataType: 'jsonp',
			type: 'GET',
			success (data) {
				if (data.status == 1) {

					var obj = $(self).parents(".sc-order");
					var obj_parents = $(self).parents(".sc-bundle");
					var _length = obj_parents.find(".sc-order").length;
					//判断同级数据在不在 不在删除 整个店铺
					if (_length > 1) {
						obj.slideUp('slow', function () {
							obj.remove();
						})
					} else {
						obj_parents.slideUp('slow', function () {
							obj_parents.remove();
						})
					}
					//删除所有内容
					if (data.count_price == null) {
						$(".cart-info .num").html(0);
						var _html = '<div class="concent">';
						_html += '        <div class="cart-null">';
						_html += '            <i class="am-icon am-icon-opencart"></i>';
						_html += '            <span class="cart-info">购物车暂无宝贝</span>';
						_html += '            <div class="go-buy"><a class="am-btn am-btn-primary" href="/">马上去挑选</a></div>';
						_html += '        </div>';
						_html += '    </div>';
						$(".concent").html(_html);
					} else {
						$(".sc-checked-num").html(data.count_status_1);
						$(".sc-total-fee").html("¥" + data.count_price);
						$(".cart-info .num").html(data.total_rows);
						$(".cart_num").html(data.total_rows);
						//当前店铺的总价
						$("#sidebar_store_total_" + store_id).html("¥" + data.store_total);
					}
				} else {
					layer.msg(data.info, {icon: 2});
				}
			}
		})
		layer.close(index);
	})
}