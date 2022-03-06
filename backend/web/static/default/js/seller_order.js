/**订单列表start**/
layui.use(['laydate'], function () {
	var laydate = layui.laydate,
		$ = layui.jquery;

	//执行一个laydate实例
	laydate.render({
		elem: '#start_time', //指定元素
		type: 'date'
	});
	laydate.render({
		elem: '#end_time', //指定元素
		type: 'date'
	});
});

//删除订单
function del_order (obj) {
	$(obj).parent().parent('.order-item').remove();
}

/**订单列表end**/
/**发货页面start**/
layui.use(['layer'], function () {
	var $ = layui.jquery;
});

//发货
function send_trade (obj, order_goods_id) {
	//更改里面提交的值
	$(".confirm_delivery").attr('onclick', 'send_trade_submit(this,' + order_goods_id + ')');
	$("[name=shipping_sn]").val('');
	layer.open({
		type: 1,
		title: '发货信息',
		content: $('#tradeFrame'),
		cancel: function () {
			$("#tradeFrame").hide();
		},
	});
}

/**
 * 发货提交
 * @param obj
 * @param order_goods_id
 */
function send_trade_submit (obj, order_goods_id) {
	var order_goods_id_arr = new Array();
	if (order_goods_id == 0) {//点击批量发货
		if ($(".order_goods_id:checked").length == 0) {
			layer.msg('请勾选要批量发货的商品', {icon: 2});
			setTimeout(function () {
				layer.closeAll();
			}, 1000)
			return false;
		}
		$(".order_goods_id:checked").each(function () {
			order_goods_id_arr.push($(this).val());
		})
	} else {
		order_goods_id_arr.push(order_goods_id);
	}
	//物流编号
	var shipping_sn = $.trim($("[name=shipping_sn]").val());
	if (shipping_sn == "") {
		layer.msg('请填写运单号', {icon: 2});
		return false;
	}
	var shipping_id = $("[name=shipping_id]").val();
	var shipping_name = $("[name=shipping_id] option:selected").text();
	//发货提交
	$.post('', {
		order_goods_id: order_goods_id_arr,
		shipping_sn: shipping_sn,
		shipping_id: shipping_id,
		shipping_name: shipping_name
	}, function (data) {
		if (data.status == 1) {
			$.each(order_goods_id_arr, function (k, v) {
				//移除复选框
				$("#order_goods_id_" + v).parents("tr").find(".order-item-status a").removeAttr('Onclick').html('已发货');
				$("#order_goods_id_" + v).slideUp('slow', function () {
					$("#order_goods_id_" + v).remove();
				});
				//发货完成
				if (data.order_status == 2) {
					$(".trade-all").slideUp('slow', function () {
						$(".trade-all").remove();
					})
				}
				$("#tradeFrame").hide();
				//关闭提醒框
				layer.msg(data.info, {icon: 1});
				layer.closeAll();
			})
		}
	}, 'json')
}

/**
 * 编辑收货地址
 * @param obj
 * @param order_id
 */
function address_edit (obj, order_id) {
	$.post('/seller_order/editAddress', {order_id: order_id}, function (data) {
		if (data.status == 1) {
			$("input[name='address[address_username]']").attr('value', data.address_one.address_username);
			$("input[name='address[address_mobile]']").attr('value', data.address_one.address_mobile);
			$("textarea[name='address[user_address]']").attr('value', data.address_one.user_address);

			//省份
			var _html = '<option value="0">请选择省份</option>';
			$.each(data.province_data, function (k, v) {
				if (v.region_id == data.address_one.address_province) {
					_html += '<option value="' + v.region_id + '" selected>==' + v.region_name + '==</option>';
				} else {
					_html += '<option value="' + v.region_id + '">==' + v.region_name + '==</option>';
				}

			});
			$("#address_province").html(_html);
			//城市
			var _html = '<option value="0">请选择城市</option>';
			$.each(data.city_data, function (k, v) {
				if (v.region_id == data.address_one.address_city) {
					_html += '<option value="' + v.region_id + '" selected>==' + v.region_name + '==</option>';
				} else {
					_html += '<option value="' + v.region_id + '">==' + v.region_name + '==</option>';
				}

			});
			$("#address_city").html(_html);
			//地区
			var _html = '<option value="0">请选择地区</option>';
			$.each(data.district_data, function (k, v) {
				if (v.region_id == data.address_one.address_district) {
					_html += '<option value="' + v.region_id + '" selected>==' + v.region_name + '==</option>';
				} else {
					_html += '<option value="' + v.region_id + '">==' + v.region_name + '==</option>';
				}

			});
			$("#address_district").html(_html);
			layer.open({
				type: 1,
				title: '地址信息修改',
				content: $('#addressFrame'),
				area: ['550px', '400px'],
				cancel: function () {
					$("#addressFrame").hide();
				},
			});
		}

	}, 'json')

}

/**
 * 修改收货地址
 * @param obj
 * @param order_id
 */
function edit_address_submit (obj, order_id) {
	//用户名
	var address_username = $("input[name='address[address_username]']");
	var address_mobile = $("input[name='address[address_mobile]']");
	var user_address = $("textarea[name='address[user_address]']");
	//省份 城市 地区
	var address_province = $("select[name='address[address_province]'] option:selected").text();
	var address_city = $("select[name='address[address_city]'] option:selected").text();
	var address_district = $("select[name='address[address_district]'] option:selected").text();
	//用户名
	if (address_username.val().length == 0) {
		address_username.focus();
		layer.msg('收货人姓名不能为空', {icon: 2});
		return false;
	}
	//手机号码
	if (address_mobile.val().length == 0) {
		address_mobile.focus();
		layer.msg('收货人手机号码不能为空', {icon: 2});
		return false;
	} else if (!/^1[0-9]{10}$/.test(address_mobile.val())) {
		address_mobile.focus();
		layer.msg('手机号码格式不正确', {icon: 2});
		return false;
	}
	//收货地址
	if (user_address.val().length == 0) {
		user_address.focus();
		layer.msg('收货详细地址不能为空', {icon: 2});
		return false;
	}
	var _postdata = {};

	_postdata.address_username = $.trim(address_username.val());
	_postdata.address_mobile = $.trim(address_mobile.val());
	_postdata.user_address = $.trim(user_address.val());
	//省份
	_postdata.address_province = $.trim(address_province.replace(/==/g, ''));
	_postdata.address_city = $.trim(address_city.replace(/==/g, ''));
	_postdata.address_district = $.trim(address_district.replace(/==/g, ''));
	//判断地区不能为空
	if (_postdata.address_province.length == 0 || _postdata.address_city.length == 0 || _postdata.address_district.length == 0) {
		layer.msg('请选择完整的收货地区', {icon: 2});
		return false;
	}
	_postdata.order_id = order_id;
	$.ajax({
		type: "POST",
		url: '/seller_order/editAddressDo',
		data: _postdata,
		dataType: 'JSON',
		async: true,
		success: function (data) {
			if (data.status == 1) {
				$(".address_mobile").html('联系电话：' + data.address_one.address_mobile);
				$(".user_address").html('所在地区：' + data.address_one.address_province + ' ' + data.address_one.address_city + ' ' + data.address_one.address_district + ' ' + data.address_one.user_address + ' ' + '<a href="javascript:;" onclick="address_edit(this,' + order_id + ');" class="layui-btn">修改地址</a>');
				//提醒关闭弹框
				$("#addressFrame").hide();
				layer.msg(data.info, {icon: 1});
				layer.closeAll();
			}
		}

	})
}

/**退款页面图片放大start**/
function view_img (obj) {
	var vimg = $(obj).attr("src");
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

/**退款页面图片放大end**/

/**退款处理start**/
/**
 * 拒绝退款
 */
function refund_dialog () {
	layer.open({
		type: 1,
		title: '拒绝理由',
		content: $('#refuseFrame'),
		cancel: function () {
			$("#refuseFrame").hide();
		},
	});
}

/**
 * 同意或者拒绝退款
 * @param return_id
 * @param refund_type
 * @param refund_statsu
 */
function refund (return_id, refund_type, refund_status) {
	if (refund_status == 1) {
		var msg = "同意";
	} else {
		var msg = "拒绝";
	}
	var return_seller_desc = "";
	if (refund_status == 2) {
		return_seller_desc = $.trim($("[name=return_seller_desc]").val());
		if (return_seller_desc == "") {
			layer.msg('请填写拒绝理由', {icon: 2});
			return false;
		}
	}
	layer.confirm('您确定要' + msg + '吗？', {
		icon: 3,
		title: '提示'
	}, function (index) {
		$.post(refund_url, {
			refund_status: refund_status,
			return_id: return_id,
			refund_type: refund_type,
			return_seller_desc: return_seller_desc
		}, function (data) {
			if (data.status == 1) {
				if (refund_type == 1) {
					var type = "退款";
					var type = "退货退款";
				}
				var status = refund_status == 1 ? type + "成功" : "已拒绝" + type;
				$(".order-status").html(status);
				$(".set-refund").remove();
				layer.msg(data.info, {icon: 1});
			} else {
				layer.msg(data.info, {icon: 2});
			}
		}, 'json')

		layer.closeAll();
	});
}


/**发货页面end**/
$(function () {
	count_down(true, $(".timeBox")); //参数一：开启天数 参数二：倒计时时间选择
	// 搜索条件
	$('.more-btn').on('click', function () {
		if ($('.search-form-box').hasClass('none')) {
			$('.search-form-box').removeClass('none');
			$(this).html('收起 <i class="am-icon am-icon-caret-up"></i>');
		} else {
			$('.search-form-box').addClass('none');
			$(this).html('展开搜索条件 <i class="am-icon am-icon-caret-down"></i>');
		}
	});
	/**查看物流start**/
	$('.order-goods-num').hover(function () {
		var shipping_sn = $(this).attr('shipping_sn');
		var obj = $(this);
		$.post('/seller_order/logistics', {shipping_sn: shipping_sn}, function (data) {
			if (data.status == 1) {
				var _html = '';
				$.each(data.delivery_data, function (k, v) {
					_html += '<li class="latest">';
					_html += '<p class="text">' + v['context'] + '</p>';
					_html += '<div class="time-list">';
					if (v['status'] == 0) {
						_html += '<span class="date hidden">' + v['time'][0] + '</span>';
						_html += '<span class="week hidden">' + v['week'] + '</span>';
					} else {
						_html += '<span class="date">' + v['time'][0] + '</span>';
						_html += '<span class="week">' + v['week'] + '</span>';
					}

					_html += '<span class="time">' + v['time'][1] + '</span>';
					_html += '</div>';
					_html += '</li>';

				})
				obj.parent('.order-item-title').find(".order-goods-trade ul").html(_html);
				obj.parent('.order-item-title').find('.order-goods-trade').show();
			}
		}, 'json')

	}, function () {
		$('.order-goods-trade').hide();
	});
	/**查看物流end**/
});