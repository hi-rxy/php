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

//判断数组是否为空
function is_empty (value) {
	return (Array.isArray(value) && value.length === 0) || (Object.prototype.isPrototypeOf(value) && Object.keys(value).length === 0);
}

$(document).ready(function () {
	//商品规格选择
	$(function () {
		$(".theme-options").each(function () {
			var i = $(this);
			var p = i.find("ul>li");
			p.click(function () {
				if (!!$(this).hasClass("selected")) {
					$(this).removeClass("selected");
				} else {
					$(this).addClass("selected").siblings("li").removeClass("selected");
				}
			});
		});
	});
	var $ww = $(window).width();
	$('.theme-login').click(function () {
		//禁止遮罩层下面的内容滚动
		//$(document.body).css("position", "fixed");

		$(this).addClass("selected");
		$(this).parent().addClass("selected");
		var to = $(this).prev().offset().top + 30;
		var th = $(this).offset().top;
		var tl = $(this).offset().left - 200;

		var goods_id = $(this).attr('goods_id');
		var attr_value_arr = new Array();
		var obj = $(this);
		obj.parents('.item-props-can').find(".sku-line").each(function () {
			attr_value_arr.push($(this).attr('attr_value'));
		});
		var standard_id = obj.attr('standard_id');
		var standard_name = obj.attr('standard_name');
		var goods_stock = obj.attr('goods_stock');
		var goods_price = obj.attr('goods_price');
		var goods_del_price = obj.attr('goods_del_price');
		spec_compose[standard_name] = {
			'standard_id': standard_id,
			'standard_name': standard_name,
			'goods_stock': goods_stock,
			'goods_price': goods_price,
			'goods_del_price': goods_del_price
		};
		var goods_num = obj.parents(".item-content").find("[name=goods_num]").val();
		$.post('/Goods/cartSpec', {goods_id: goods_id, standard_name: standard_name}, function (data) {
			_html = '<div class="theme-signin-left">';
			if (!is_empty(data.goods_spec_data)) {
				$.each(data.goods_spec_data, function (k, v) {
					_html += '	<div class="theme-options">';
					_html += '		<div class="cart-title">' + k + '：</div>';
					_html += '		<ul>';
					if (v.length > 0) {
						$.each(v, function (k1, v1) {
							if ($.inArray(v1, attr_value_arr) >= 0) {
								_html += '			<li class="sku-line spec selected" title="' + v1 + '" goods_id="' + data.goods_one['goods_id'] + '">';
							} else {
								_html += '			<li class="sku-line spec " title="' + v1 + '" goods_id="' + data.goods_one['goods_id'] + '">';
							}
							if (!is_empty(data.goods_pic_data.length) && (data.goods_pic_data[v1]['attr_pic'] != "")) {
								_html += '<img src="' + data.goods_pic_data[v1]['attr_pic'] + '" alt=""><a href="JavaScript"></a><i></i>';
							} else {
								_html += '<a href="javascript:;">' + v1 + '</a><i></i>';
							}
							_html += '<input type="hidden" name="spec[goods_attr_id]" value="' + data.goods_pic_data[v1]['goods_attr_id'] + '"/>';
							_html += '</li>';

						});
					}

					_html += '		</ul>';
					_html += '	</div>';
				})

			}
			_html += '	<div class="theme-options">';
			_html += '		<div class="cart-title number">数量</div>';
			_html += '		<dd>';
			_html += '			<input class="min min_goods_num am-btn am-btn-default"  type="button" value="-" />';
			_html += '			<input class="goods_num text_box" name="goods_num" type="text" value="' + goods_num + '" style="width:30px;" />';
			_html += '			<input class="add add_goods_num am-btn am-btn-default"  type="button" value="+" />';
			_html += '			<span class="tb-hidden">库存<span class="stock" goods_stock="' + data.goods_one.goods_stock + '">' + goods_stock + '</span>件</span>';
			_html += '		</dd>';
			_html += '';
			_html += '	</div>';
			_html += '	<div class="clear"></div>';
			_html += '	<div class="btn-op">';
			_html += '		<div class="btn am-btn am-btn-warning" onclick="cart_change(this,' + goods_id + ',' + standard_id + ')">确认</div>';
			_html += '		<div class="btn close am-btn am-btn-warning">取消</div>';
			_html += '	</div>';
			_html += '';
			_html += '</div>';
			_html += '<div class="theme-signin-right">';
			_html += '	<div class="img-info">';
			_html += '		<img src="' + data.goods_one.goods_thumb + '" />';
			_html += '	</div>';
			_html += '	<div class="text-info">';
			_html += '		<span class="J_Price price-now sys_item_price" goods_price="' + data.goods_one.goods_price + '">¥' + goods_price + '</span>';
			_html += '		<span  class="tb-hidden">库存<span class="stock" goods_stock="' + data.goods_one.goods_stock + '">' + goods_stock + '</span>件</span>';
			_html += '	</div>';
			_html += '</div>';
			$(".theme-popbod").html(_html);
			$('.theme-span').show();
			$('.theme-popover-mask').show();
			$('.theme-popover-mask').height($(document).height());
			$('.theme-popover').slideDown(200);
		}, 'json');

		if ($ww > 640) {
			$('.theme-popover').css("top", th);
			$('.theme-popover').css("left", tl);
			$('.theme-popover-mask').hide();
		}
		if ($ww > 1024) {
			$('.theme-popover').css("top", to);
			$('.theme-popover').css("left", tl);
			$('.theme-popover-mask').hide();
		}

	})
	$('.theme-poptit .close,.btn-op .close').live("click", function () {
		$(document.body).css("position", "static");
		//					滚动条复位
		$('.theme-signin-left').scrollTop(0);
		$('.theme-login').removeClass("selected");
		$('.item-props-can').removeClass("selected");
		$('.theme-span').hide();
		$('.theme-popover-mask').hide();
		$('.theme-popover').slideUp(200);
	})
});

//减少数量
function decrease_quantity (goods_id, standard_id) {
	var item = $("#cart_" + goods_id + "_" + standard_id);
	var orig = Number(item.val());
	if (orig > 1) {
		item.val(orig - 1);
		item.keyup();
	}
}

//增加
function add_quantity (goods_id, standard_id) {
	var item = $("#cart_" + goods_id + "_" + standard_id);
	var orig = Number(item.val());
	var max = item.attr('data-max');
	if (max >= (orig + 1)) {
		item.val(orig + 1);
		item.keyup();
	}
}

//数量发生改变时候
function change_quantity (standard_id, input, goods_id, goods_stock) {
	if (cart.is_lock == true) {
		layer.msg('正在处理中请稍后', {icon: 2});
		return false;
	}
	var _v = input.value;
	if (_v <= 0 || _v == "" || !/^\d+$/.test(_v)) {
		input.value = 1;
		_v = 1;
	}
	if (_v > goods_stock) {
		_v = goods_stock;
	}
	var url = cart_save_url;
	$.ajax({
		beforeSend: cart.ajax_before,
		type: "POST",
		url: url,
		data: {
			standard_id: standard_id, goods_num: _v, goods_id: goods_id
		},
		dataType: 'json',
		success: function (data) {
			if (data.status == 1) {
				$(input).val(data.goods_num);
				if (data.goods_num == 1) {
					$("#decrease_" + goods_id + "_" + standard_id).attr('disabled', true);
					$("#increment_" + goods_id + "_" + standard_id).attr('disabled', false)
				} else if (data.goods_num < goods_stock) {
					$("#decrease_" + goods_id + "_" + standard_id).attr('disabled', false);
					$("#increment_" + goods_id + "_" + standard_id).attr('disabled', false)
				} else if (data.goods_num == goods_stock) {
					$("#decrease_" + goods_id + "_" + standard_id).attr('disabled', false);
					$("#increment_" + goods_id + "_" + standard_id).attr('disabled', true)
				}
				if (goods_stock == 1) {
					$("#decrease_" + goods_id + "_" + standard_id).attr('disabled', true);
					$("#increment_" + goods_id + "_" + standard_id).attr('disabled', true)
				}
				$("#J_Total").html(data.count_price);
				$("#goods_total_" + goods_id + "_" + standard_id).html(data.product_total);
			} else {
				$(input).val(data.goods_stock);
			}
		},
		complete: cart.ajax_complete
	});
}

//自己本身
function self (obj, goods_id, standard_id) {
	var value = $(obj).attr('value');
	if (value == 1) {
		$(obj).attr('value', 0);
	} else {
		$(obj).attr('value', 1);
	}
	//当前选中的长度
	var _length_checked = $(".cart_check:checked").length;
	var _length = $(".cart_check").length;
	if (_length_checked == _length) {
		$(".check-all").attr('value', 1).attr("checked", true);
	} else {
		$(".check-all").attr('value', 0).attr("checked", false);
	}
	//判断当前是否是选中
	if ($(obj).attr('checked')) {
		var cart_status = 1;
	} else {
		var cart_status = 0;
	}
	$.post(url, {standard_id: standard_id, goods_id: goods_id, cart_status: cart_status}, function (data) {
		if (data.status == 1) {
			$("#J_Total").html(data.count_price);
			$("#goods_total_" + goods_id + "_" + standard_id).html(data.product_total);
			$("#J_SelectedItemsCount").html(data.count_status_1);
			$(".cart-info .num").html(data.total_rows);
		}
	}, 'json')
}

//总的全选
function quanbu (obj) {
	var value = $(obj).attr('value');
	if (value == 1) {
		$(obj).attr('value', 0);
	} else {
		$(obj).attr('value', 1);
	}
	var input = $(".cart_check");
	if (value == 1) {
		input.removeAttr("checked");
	} else {
		input.attr('checked', 'checked');
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
	var url = cart_status_all_url;
	$.post(url, {standard_id: standard_id, goods_id: goods_id, cart_status: cart_status}, function (data) {
		if (data.status == 1) {
			$("#J_Total").html(data.count_price);
			$("#J_SelectedItemsCount").html(data.count_status_1);
			$(".cart-info .num").html(data.total_rows);
		}
	}, 'json')
}

//单个删除
function remove_self (self, goods_id, standard_id) {
	layer.confirm('您确定要删除吗', function (index) {
		if (!($("#input_" + goods_id + "_" + standard_id).attr("checked") == "checked")) {
			layer.msg('请勾选列表项目', {icon: 2});
			return false;
		}
		$.ajax({
			url: del_url,
			data: {
				goods_id: goods_id,
				standard_id: standard_id,
			},
			dataType: 'json',
			type: 'POST',
			success (data) {
				if (data.status == 1) {
					//自己的父级
					var obj = $(self).parents(".item-content");
					//店铺的父级
					var obj_parents = $(self).parents(".item-list");
					var _length = obj_parents.find(".item-content").length;
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
						$("#J_Total").html(data.count_price);
						$("#J_SelectedItemsCount").html(data.count_status_1);
						$(".cart-info .num").html(data.total_rows);
					}
				} else {
					layer.msg(data.info, {icon: 2});
				}
			}
		})
		layer.close(index);
	})
}

//总删除
function remove_all () {
	layer.confirm('您确定要删除吗', function (index) {
		if ($("input[name='cart_goods_standard_id[]']:checked").length == 0) {
			layer.msg('请勾选列表项目', {icon: 2});
			return false;
		}
		//发送ajax请求
		var standard_id = new Array();
		var goods_id = new Array();
		$("input[name='cart_goods_standard_id[]']").each(function () {
			if (this.checked) {
				standard_id.push($(this).attr('standard_id'));
				goods_id.push($(this).attr('goods_id'));
			}
		})
		$.ajax({
			url: del_all_url,
			data: {
				goods_id: goods_id,
				standard_id: standard_id,
			},
			dataType: 'json',
			type: 'POST',
			success (data) {
				if (data.status == 1) {
					$.each(standard_id, function (k, v) {
						//自己的父级
						var obj = $("#input_" + goods_id[k] + "_" + v).parents(".item-content");
						//店铺的父级
						var obj_parents = $("#input_" + goods_id[k] + "_" + v).parents(".item-list");
						var _length = obj_parents.find(".item-content").length;
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
					})

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
						$("#J_Total").html(data.count_price);
						$("#J_SelectedItemsCount").html(data.count_status_1);
						$(".cart-info .num").html(data.total_rows);
					}
				} else {
					layer.msg(data.info, {icon: 2});
				}
			}
		})
		layer.close(index);
	})
}

/**
 * 更改商品规格
 * @param obj
 * @param goods_id
 * @param standard_id
 */
function cart_change (obj, goods_id, standard_id) {
	if ($(obj).hasClass('disabled')) {
		return false;
	}
	var spec_id = new Array();
	//当前选中的标签
	$(obj).parents(".theme-signin-left").find(".theme-options li.selected").each(function () {
		spec_id.push($(this).children("input").val());
	});
	//当前的数量
	var goods_num = $(obj).parents(".theme-signin-left").find("[name=goods_num]").val();
	//运行添加规格的长度
	var allowd_spec = $(obj).parents(".theme-signin-left").find("div.pic").length;
	//当前的规格
	if (allowd_spec && spec_id == "") {
		layer.msg('请选择商品规格后，再添加到购物车上面', {icon: 2});
		return false;
	}
	//判断用户有没有选择规格
	var had_spec = spec_id.join(",");
	if (allowd_spec && !spec_compose[had_spec]) {
		layer.msg('请把商品规格勾选完,再添加到购物车上', {icon: 2});
		return false;
	}
	var new_standard_id = spec_compose[had_spec]['standard_id'];
	//ajax更改规格
	$.post(change_spec_url, {
		goods_id: goods_id,
		new_standard_id: new_standard_id,
		standard_id: standard_id,
		goods_num: goods_num,
		spec_id: spec_id
	}, function (data) {
		if (data.status == 1) {
			$("#J_Total").html(data.count_price);
			$("#J_SelectedItemsCount").html(data.count_status_1);
			$(".cart-info .num").html(data.total_rows);
			//关闭弹框
			$(document.body).css("position", "static");
			//滚动条复位
			$('.theme-signin-left').scrollTop(0);
			$('.theme-login').removeClass("selected");
			$('.item-props-can').removeClass("selected");
			$('.theme-span').hide();
			$('.theme-popover-mask').hide();
			$('.theme-popover').slideUp(200);
			var _html = "";
			$.each(data.spec_data, function (k, v) {
				_html += '<span class="sku-line" attr_value="' + v.attr_value + '">' + v.attr_name + '：' + v.attr_value + '</span>';
			});
			//替换对应的值 规格存在替换原有的
			if ($("#spec_data_" + goods_id + "_" + data.standard_id).length > 0) {
				$("#spec_data_" + goods_id + "_" + data.standard_id).html(_html);
				$("#goods_total_" + goods_id + "_" + data.standard_id).html(data.product_total);
				$("#cart_" + goods_id + "_" + data.standard_id).val(data.goods_num);
			} else {
				$("#spec_data_" + goods_id + "_" + standard_id).html(_html);
				$("#goods_total_" + goods_id + "_" + standard_id).html(data.product_total);
				$("#cart_" + goods_id + "_" + standard_id).val(data.goods_num);

				//划线价 售价
				$("#goods_del_price_" + goods_id + "_" + standard_id).html(data.goods_del_price);
				$("#goods_price_" + goods_id + "_" + standard_id).html(data.goods_price);

				$("#goods_del_price_" + goods_id + "_" + standard_id).attr("id", "goods_del_price_" + goods_id + "_" + data.standard_id);
				$("#goods_price_" + goods_id + "_" + standard_id).attr("id", "goods_price_" + goods_id + "_" + data.standard_id);
				//复选框
				$("#input_" + goods_id + "_" + standard_id).attr("standard_id", data.standard_id);
				$("#input_" + goods_id + "_" + standard_id).attr("onclick", "self(this," + goods_id + "," + data.standard_id + ")");
				$("#input_" + goods_id + "_" + standard_id).attr("id", "input_" + goods_id + "_" + data.standard_id);

				//规格
				$("#spec_data_" + goods_id + "_" + standard_id).attr('standard_id', data.standard_id);
				$("#spec_data_" + goods_id + "_" + standard_id).parents(".item-props-can").find(".edit-sku-btn").attr('standard_id', data.standard_id);
				$("#spec_data_" + goods_id + "_" + standard_id).parents(".item-props-can").find(".edit-sku-btn").attr('standard_name', spec_id.join(","));
				//自己更改
				$("#spec_data_" + goods_id + "_" + standard_id).attr('goods_price', data.goods_price);
				$("#spec_data_" + goods_id + "_" + standard_id).attr('goods_del_price', data.goods_del_price);
				$("#spec_data_" + goods_id + "_" + standard_id).attr('standard_name', spec_id.join(","));

				//修改规格弹框的参数
				$("#sku_" + goods_id + "_" + standard_id).attr('goods_price', data.goods_price);
				$("#sku_" + goods_id + "_" + standard_id).attr('goods_del_price', data.goods_del_price);
				$("#sku_" + goods_id + "_" + standard_id).attr('goods_stock', data.goods_stock);
				$("#sku_" + goods_id + "_" + standard_id).attr('standard_name', spec_id.join(","));
				$("#sku_" + goods_id + "_" + standard_id).attr('standard_id', data.standard_id);
				$("#sku_" + goods_id + "_" + standard_id).attr("id", "sku_" + goods_id + "_" + data.standard_id);

				$("#sku_i_" + goods_id + "_" + standard_id).attr('goods_price', data.goods_price);
				$("#sku_i_" + goods_id + "_" + standard_id).attr('goods_del_price', data.goods_del_price);
				$("#sku_i_" + goods_id + "_" + standard_id).attr('goods_stock', data.goods_stock);
				$("#sku_i_" + goods_id + "_" + standard_id).attr('standard_name', spec_id.join(","));
				$("#sku_i_" + goods_id + "_" + standard_id).attr('standard_id', data.standard_id);
				$("#sku_i_" + goods_id + "_" + standard_id).attr("id", "sku_i_" + goods_id + "_" + data.standard_id);

				$("#spec_data_" + goods_id + "_" + standard_id).attr("id", "spec_data_" + goods_id + "_" + data.standard_id);
				//数量 减值
				$("#decrease_" + goods_id + "_" + standard_id).attr("onclick", "decrease_quantity(" + goods_id + "," + data.standard_id + ")");
				$("#decrease_" + goods_id + "_" + standard_id).attr("id", "decrease_" + goods_id + "_" + data.standard_id);
				//输入框
				$("#cart_" + goods_id + "_" + standard_id).attr('onkeyup','change_quantity('+data.standard_id+',this,'+goods_id+','+data.goods_stock+')');
				//更改库存
				$('#cart_' + goods_id + '_' + standard_id).attr('data-max', data.goods_stock);
				$("#cart_" + goods_id + "_" + standard_id).attr("id", "cart_" + goods_id + "_" + data.standard_id);

				//数量 加值
				$("#increment_" + goods_id + "_" + standard_id).attr("onclick", "add_quantity(" + goods_id + "," + data.standard_id + ")");
				$("#increment_" + goods_id + "_" + standard_id).attr("id", "increment_" + goods_id + "_" + data.standard_id);
				//金额
				$("#goods_total_" + goods_id + "_" + standard_id).attr("id", "goods_total_" + goods_id + "_" + data.standard_id);
				//删除
				$("#del_" + goods_id + "_" + standard_id).attr("onclick", "remove_self(this," + goods_id + "," + data.standard_id + ")");
				$("#del_" + goods_id + "_" + standard_id).attr("id", "del_" + goods_id + "_" + data.standard_id);

				//收藏
				$("#collect" + goods_id + "_" + standard_id).attr("onclick", "collect(this," + goods_id + "," + data.standard_id + ")");
				$("#collect" + goods_id + "_" + standard_id).attr("id", "collect" + goods_id + "_" + data.standard_id);
			}

		} else {

		}
	}, 'json')
}
