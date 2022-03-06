function show_more () {
	if ($('.more-part').hasClass('active')) {
		$('.search-more-btn').html('简单筛选条件 <i class="am-icon-chevron-up"></i>');
		$('.more-part').removeClass('active');
	} else {
		$('.search-more-btn').html('更多筛选条件 <i class="am-icon-chevron-down"></i>');
		$('.more-part').addClass('active');
	}
}

$(function () {
	if ($("[name='order_id[]']").length > 0) {
		$(".all-pay").show();
	} else {
		$(".all-pay").hide();
	}
	//开始时间 结束时间
	$('#time-start').datepicker().on('changeDate.datepicker.amui', function (event) {
		$(this).datepicker('close');
	});

	$('#time-end').datepicker().on('changeDate.datepicker.amui', function (event) {
		$(this).datepicker('close');
	});
	count_down(true, $(".timeBox")); //参数一：开启天数 参数二：倒计时时间选择
	//判断物流节点是否存在
	if ($(".shipping_sn").length > 0) {
		$(".shipping_sn").each(function () {
			var shipping_sn = $(this).attr('shipping_sn');
			var obj = $(this);
			$.post('/user_order/logistics', {shipping_sn: shipping_sn}, function (data) {
				if (data.status == 1) {
					var _html = "";
					$.each(data.delivery_data, function (k, v) {
						if (k == 0) {
							obj.find(".latest-logistics .text").html('<p class="text">' + v['context'] + '</p>');

							if (v['status'] == 0) {
								_html += '<span class="date hidden">' + v['time'][0] + '</span> ';
								_html += '<span class="week hidden">' + v['week'] + '</span> ';
							} else {
								_html += ' <span class="date">' + v['time'][0] + '</span> ';
								_html += ' <span class="week">' + v['week'] + '</span> ';
							}
							_html += ' <span class="time">' + v['time'][1] + '</span> ';
						}
					});
					obj.find('.latest-logistics .time-list').html(_html);
					obj.find('.latest-logistics .inquire').show();
					obj.find('.latest-logistics .icon-log').show();
				}
			}, 'json')
		})
	}
	//退款提交
	if ($(".refund_form").length > 0) {
		//表单验证
		$(".refund_form").submit(function () {
			//退款原因
			var return_cause = $("[name=return_cause]").val();
			if (return_cause == "") {
				layer.msg('请选择退款原因', {icon: 2});
				return false;
			}
			//退款描述
			var return_desc = $.trim($("[name=return_desc]").val());
			if (return_desc == "") {
				layer.msg('请选择退款描述', {icon: 2});
				return false;
			}
		})
		$(".return_imgs").live('change', function () {
			var imgLen = $(this).prop("files").length;
			var _html = '';
			var parent = $(this).parent();
			var maxLen = $(this).prop("max"); //获取最多上传文件个数
			var haveLen = parent.siblings(".desc").find("img").length;//获取已上传文件个数
			if ((maxLen - haveLen) >= imgLen) {
				for (var i = 0; i < imgLen; i++) {
					var imgUrl = ajax_upload(this.files[i], 'goods_refund');
					var _hidden = '<input name="return_imgs[]" type="hidden"  value="' + imgUrl + '"/>';
					_html += '<div class="desc-box"><img src="' + imgUrl + '" alt=""><a href="javascript:;" class="am-icon am-icon-trash img_del"></a>' + _hidden + '</div>';

				}
				parent.siblings(".desc").append(_html);

			} else {
				layer.msg('图片最多支持3张', {icon: 2});
				return false;
			}
			remove_file($(this));
		});
		$(document).on("click", '.img_del', function () {
			var img_url = $(this).prev().attr('src');
			//删除图片
			del_img(img_url);
			$(this).parent().remove();
		});
		//下拉选择
		$(".return_cause").change(function () {
			var _val = $(this).find("option:selected").text();
			if (_val == "请选择退款原因") {
				_val = "";
			}
			$("[name=return_cause]").val(_val);
		})
	}
	//评价提交
	if ($(".comment_form").length > 0) {
		//表单提交
		$(".comment_form").submit(function () {
			var flag = 1;
			//评价等级
			$(".comment_grade").each(function () {
				if($.trim($(this).val()) == ""){
					flag =0;
				}
			});
			if(flag == 0){
				layer.msg('请选择评价等级',{icon:2});
				return false;
			}
			//评价内容
			$(".comment_content").each(function () {
				if($.trim($(this).val()) == ""){
					flag =0;
				}
			});
			if(flag == 0){
				layer.msg('请填写评价内容',{icon:2});
				return false;
			}
		})
		$(".comment-list .item-opinion span").click(function() {
			$(this).siblings().children('i').removeClass("active");
			$(this).children('i').addClass("active");
			_val = $(this).attr('value');
			$(this).nextAll('input').val(_val);
		});
		$(".comment_imgs").live('change', function () {
			var imgLen = $(this).prop("files").length;
			var order_goods_id = $(this).attr('order_goods_id');
			var _html = '';
			var parent = $(this).parent();
			var maxLen = $(this).prop("max"); //获取最多上传文件个数
			var haveLen = $(this).siblings(".upload-img").find("img").length;//获取已上传文件个数
			if ((maxLen - haveLen) >= imgLen) {
				for (var i = 0; i < imgLen; i++) {
					var imgUrl = ajax_upload(this.files[i], 'goods_comment');
					var _hidden = '<input name="comment_pic['+order_goods_id+'][]" type="hidden"  value="' + imgUrl + '"/>';
					_html += '<div class="desc-box"><img src="' + imgUrl + '" alt=""><a href="javascript:;" class="am-icon am-icon-trash img_del"></a>' + _hidden + '</div>';

				}
				$(this).siblings(".upload-img").append(_html);

			} else {
				layer.msg('图片最多支持3张', {icon: 2});
				return false;
			}
			remove_file($(this));
		});
		//删除图片
		$(document).on("click", '.img_del', function () {
			var img_url = $(this).prev().attr('src');
			//删除图片
			del_img(img_url);
			$(this).parent().remove();
		});
	}
});

/**
 * 合并付款
 * @param obj
 */
function all_pay (obj) {
	var input = $("[name='order_id[]']:checked");
	if (input.length == 0) {
		layer.msg('请勾选付款订单', {icon: 2});
		return false;
	}
	layer.confirm('您确定要合并付款吗', function (index) {
		//发送ajax
		var order_id = new Array();
		input.each(function () {
			order_id.push($(this).val());
		})
		var url = make_order_main_url;
		$.post(url, {order_id: order_id}, function (data) {
			if (data.status == 1) {
				window.open("/order/OrderPay/order_sn/" + data.order_sn, "_blank")
			}
		}, 'json')
		layer.close(index);
	})
}

/**
 * 取消订单
 * @param obj
 * @param order_sn
 */
function cancel_order (obj, order_sn) {
	layer.confirm('您确定要取消订单吗', function (index) {
		$.post(cancel_order_url, {order_sn: order_sn}, function (data) {
			if (data.status == 1) {
				//替换订单状态
				$(obj).parents(".item-status").find(".Mystatus").html('订单已取消');
				//替换订单状态
				$(obj).parents("li").next().html('<div class="am-btn am-btn-danger anniu">删除订单</div>');
				//移除倒计时
				$(obj).parents(".order-status1").find(".timeBox").remove();
				//自己移除掉
				$(obj).slideUp('slow', function () {
					$(obj).remove();
				});
				//订单详情页 删除倒计时
				$(".order-status-time-box").remove();
			} else {
				layer.msg(data.info, {icon: 2});
			}
		}, 'json')
		layer.close(index);
	})
}

/**
 * 提醒发货
 * @param obj
 * @param order_sn
 */
function remind_order (obj, order_sn) {
	layer.confirm('您确定要提醒发货吗', function (index) {
		$.post(remind_order_url, {order_sn: order_sn}, function (data) {
			if (data.status == 1) {
				//移除倒计时
				layer.msg(data.info, {icon: 1});
			} else {
				layer.msg(data.info, {icon: 2});
			}
		}, 'json')
		layer.close(index);
	})
}

/**
 * 确认收货
 * @param obj
 * @param order_sn
 */
function take_order (obj, order_sn) {
	layer.confirm('您确定要收货吗', function (index) {
		$.post(take_order_url, {order_sn: order_sn}, function (data) {
			if (data.status == 1) {
				//移除倒计时
				$(obj).parents(".order-status1").find(".timeBox").remove();
				//替换订单状态
				$(obj).parent().prev().find(".Mystatus").html('待评价');
				$(obj).parent().prev().find(".item-status .delay_take_order").remove();
				//替换订单状态
				$(obj).parent().html('<a href="/user_order/commentorder/order_sn/' + order_sn + '" target="_blank"><div class="am-btn am-btn-danger anniu">评价商品</div></a>');

				//订单详情页 删除倒计时
				$(".order-status-time-box").remove();
			} else {
				layer.msg(data.info, {icon: 2});
			}
		}, 'json')
		layer.close(index);
	})
}