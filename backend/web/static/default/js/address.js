//地址选择
$(function () {
	$(".user-addresslist").live("click", function () {
		if (is_mobile == 1) {
			location.href = '/address/index';
		} else {
			var address_id = $(this).prev().data('address_id');
			$(this).addClass("defaultAddr").siblings().removeClass("defaultAddr");
			set_address(address_id);
		}
	});
	$(".logistics ul li").click(function () {
		$(this).addClass("selected").siblings().removeClass("selected");
		var value = $(this).attr('value');
		$("[name=pay_type]").val(value);
	});
	var address = {
		'address_province': 0,
		'address_city': 0,
		'address_district': 0
	}
	//添加地址
	$('.add_address').click(function () {
		address.address_province = 0;
		address.address_city = 0;
		address.address_district = 0;
		$("input[name='address[address_username]']").attr('value', '');
		$("input[name='address[address_mobile]']").attr('value', '');
		$("textarea[name='address[user_address]']").attr('value', '');
		$("input[name='address[is_default]']").eq(1).attr('checked', true);
		//省份
		var _html = '<option value="0">请选择省份</option>';
		$.each(province_data, function (k, v) {
			_html += '<option value="' + v.id + '">==' + v.name + '==</option>';
		});
		$("#address_province").html(_html);
		$("#address_city").html('<option value="0">请选择城市</option>');
		$("#address_district").html('<option value="0">请选择地区</option>');
		$(".am-padding .am-text-lg").html('添加地址');
		$(".am-padding small").html('添加地址');
		//禁止遮罩层下面的内容滚动
		//$(document.body).css("overflow", "hidden");
		$(this).addClass("selected");
		$(this).parent().addClass("selected");
		$('.theme-popover-mask').show();
		$('.theme-popover-mask').height($(window).height());
		$('.theme-popover').slideDown(200);
		$(".address_submit .am-btn-danger:eq(0)").attr('method', 'add');
	});
	//提交地址
	$(".address_submit .am-btn-danger:eq(0)").live("click", function () {
		var method = $(this).attr('method');
		address_form(method, $(this));
	});
	//取消按钮
	$('.address_submit .close').click(function () {
		$(document.body).css("overflow", "visible");
		$('.theme-login').removeClass("selected");
		$('.item-props-can').removeClass("selected");
		$('.theme-popover-mask').hide();
		$('.theme-popover').slideUp(200);
	});
	//表单提交
	$("form").submit(function () {
		//判断收货地址是否为空
		var  address_id = $("[name=address_id]").val();
		if(address_id == ""){
			layer.msg('收货地址不能为空', {icon: 2});
			return false;
		}
	})
});

/**
 * 编辑收货地址
 * @param obj
 * @param address_id
 */
function edit_address (obj, address_id) {
	var _posturl = ajaxGetAddressInfo;
	$.ajax({
		type: 'POST',
		url: _posturl,
		data: {
			address_id: address_id
		},
		dataType: 'json',
		async: true,
		success: function (data) {
			if (data.code == 0) {
				$("input[name='address[address_username]']").attr('value', data.data.address_one.consignee_username);
				$("input[name='address[address_mobile]']").attr('value', data.data.address_one.consignee_mobile);
				$("textarea[name='address[user_address]']").attr('value', data.data.address_one.consignee_address);

				//省份
				var _html = '<option value="0">请选择省份</option>';
				$.each(data.data.province_data, function (k, v) {
					if (v.id == data.data.address_one.consignee_province) {
						_html += '<option value="' + v.id + '" selected>==' + v.name + '==</option>';
					} else {
						_html += '<option value="' + v.id + '">==' + v.name + '==</option>';
					}

				});
				$("#address_province").html(_html);
				//城市
				var _html = '<option value="0">请选择城市</option>';
				$.each(data.data.city_data, function (k, v) {
					if (v.id == data.data.address_one.consignee_city) {
						_html += '<option value="' + v.id + '" selected>==' + v.name + '==</option>';
					} else {
						_html += '<option value="' + v.id + '">==' + v.name + '==</option>';
					}

				});
				$("#address_city").html(_html);
				//地区
				var _html = '<option value="0">请选择地区</option>';
				$.each(data.data.district_data, function (k, v) {
					if (v.id == data.data.address_one.consignee_district) {
						_html += '<option value="' + v.id + '" selected>==' + v.name + '==</option>';
					} else {
						_html += '<option value="' + v.id + '">==' + v.name + '==</option>';
					}

				});
				$("#address_district").html(_html);
				//默认地址
				if (data.data.address_one.is_default == 1) {
					$("input[name='address[is_default]']").eq(0).attr('checked', true);
				} else {
					$("input[name='address[is_default]']").eq(1).attr('checked', true);
				}
				//编辑地址
				$(".address_submit .am-btn-danger:eq(0)").attr('method', 'edit').attr('address_id', address_id);
				$(".am-padding .am-text-lg").html('编辑地址');
				$(".am-padding small").html('编辑地址');
				//禁止遮罩层下面的内容滚动
				$(document.body).css("overflow", "hidden");
				$('.theme-popover-mask').show();
				$('.theme-popover-mask').height($(window).height());
				$('.theme-popover').slideDown(200);
			}
		}
	})
}

/**
 * 地址表单
 */
function address_form (method, obj) {
	method = method || add;
	var _posturl = method == 'add' ? ajaxAddressCreate : ajaxAddressUpdate;
	//用户名
	var address_username = $("input[name='address[address_username]']");
	var address_mobile = $("input[name='address[address_mobile]']");
	var user_address = $("textarea[name='address[user_address]']");
	//省份 城市 地区
	var address_province = $("select[name='address[address_province]']");
	var address_city = $("select[name='address[address_city]']");
	var address_district = $("select[name='address[address_district]']");

	var is_default = $("input[name='address[is_default]']:checked").val();

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
	_postdata.address_province = $.trim(address_province.val());
	_postdata.address_city = $.trim(address_city.val());
	_postdata.address_district = $.trim(address_district.val());
	//默认
	_postdata.is_default = is_default;
	//判断地区不能为空
	if (_postdata.address_province < 1 || _postdata.address_city < 1 || _postdata.address_district < 1) {
		layer.msg('请选择完整的收货地区', {icon: 2});
		return false;
	}
	//编辑地址
	if (method == "edit") {
		_postdata.address_id = obj.attr('address_id');
	}
	$.ajax({
		type: "POST",
		url: _posturl,
		data: _postdata,
		dataType: 'JSON',
		async: true,
		success: function (data) {
			if (data.code == 0) {
				var _html = "";
				if (method == "edit") {
					$("[data-address_id=" + data.data.id + "]").attr('data-address_province', data.data.province.name);
					$("[data-address_id=" + data.data.id + "]").attr('data-address_city', data.data.city.name);
					$("[data-address_id=" + data.data.id + "]").attr('data-address_district', data.data.district.name);
					$("[data-address_id=" + data.data.id + "]").attr('data-address_username', data.data.consignee_username);
					$("[data-address_id=" + data.data.id + "]").attr('data-user_address', data.data.consignee_address);
					$("[data-address_id=" + data.data.id + "]").attr('data-address_mobile', data.data.consignee_mobile);
					$("[data-address_id=" + data.data.id + "]").attr('data-is_default', data.data.is_default);
					var li = $("[data-address_id=" + data.data.id + "]").next();
					li.find(".buy-user").html(data.data.consignee_username);
					li.find(".buy-phone").html(data.data.consignee_mobile);
					li.find(".province").html(data.data.province.name);
					li.find(".city").html(data.data.city.name);
					li.find(".district").html(data.data.district.name);
					li.find(".street").html(data.data.consignee_address);
					set_post_address(data.data);
					//设置默认之后 其他地址去除默认标识
					if (data.data.is_default == 1) {
						//去除所有的默认操作
						$(".user-addresslist").removeClass('defaultAddr');
						$(".default-address").removeClass('defaultAddr');
						//默认图片隐藏
						$(".deftip").html('');
						$(".is_default").removeClass('hidden');
						//删除地址
						$(".del_address").each(function () {
							$(this).attr('onclick','del_address(this,'+$(this).attr('address_id')+',0)');
						});
						//自己
						li.addClass("defaultAddr").find(".default-address").addClass('defaultAddr');
						li.find(".deftip").html("默认地址");
						li.find(".new-addr-btn a:eq(0)").addClass('hidden');
						li.find(".del_address").attr('onclick','del_address(this,'+data.data.id+',1)');
					}
					$(document.body).css("overflow", "visible");
					$('.theme-login').removeClass("selected");
					$('.item-props-can').removeClass("selected");
					$('.theme-popover-mask').hide();
					$('.theme-popover').slideUp(200);
				} else {
					_html += '<div class="per-border" data-address_id="' + data.data.id + '" data-address_province="' + data.data.province.name + '" data-address_city="' + data.data.city.name + '"  data-address_district="' + data.data.district.name + '" data-user_address="' + data.data.consignee_address + '" data-address_username="' + data.data.consignee_username + '" data-address_mobile="' + data.data.consignee_mobile + '" data-is_default="' + data.data.is_default + '"></div>';
					if (data.data.is_default == 1) {
						_html += '<li class="user-addresslist defaultAddr">';
					} else {
						_html += '<li class="user-addresslist">';
					}

					_html += '	<div class="address-left">';
					_html += '		<div class="user defaultAddr">';
					_html += '<span class="buy-address-detail">';
					_html += '<span class="buy-user">' + data.data.consignee_username + '</span>';
					_html += '<span class="buy-phone">' + data.data.consignee_mobile + '</span>';
					_html += '</span>';
					_html += '		</div>';
					if (data.data.is_default == 1) {
						_html += '		<div class="default-address defaultAddr">';
					} else {
						_html += '		<div class="default-address">';
					}
					_html += '			<span class="buy-line-title buy-line-title-type">收货地址：</span>';
					_html += '			<span class="buy--address-detail">';
					_html += '				<span class="province">' + data.data.province.name + '</span>';
					_html += '<span class="city">' + data.data.city.name + '</span>';
					_html += '<span class="dist">' + data.data.district.name + '</span>';
					_html += '<span class="street">' + data.data.consignee_address + '</span>';
					_html += '			</span>';
					_html += '		</div>';
					if (data.data.is_default == 1) {
						_html += '			<ins class="deftip">默认地址</ins>';
					} else {
						_html += '			<ins class="deftip"></ins>';
					}
					_html += '';
					_html += '	</div>';
					_html += '	<div class="address-right">';
					_html += '		<a href="javascript:;">';
					_html += '			<span class="am-icon-angle-right am-icon-lg"></span></a>';
					_html += '	</div>';
					_html += '	<div class="clear"></div>';
					_html += '	<div class="new-addr-btn">';
					if (data.data.is_default == 1) {
						_html += '		<a href="javascript:;" class="is_default  hidden" onclick="set_default_address(this,' + data.data.id + ')">设为默认</a>';
					} else {
						_html += '			<a href="javascript:;" class="is_default" onclick="set_default_address(this,' + data.data.id + ')">设为默认</a>';
					}
					_html += '		<span class="new-addr-bar hidden">|</span>';
					_html += '		<a href="javascript:;" onclick="edit_address(this,' + data.data.id + ');">编辑</a>';
					_html += '		<span class="new-addr-bar">|</span>';
					_html += '		<a href="javascript:void(0);" onclick="del_address(this,' + data.data.id + ',' + data.data.is_default + ');">删除</a>';
					_html += '	</div>';
					_html += '</li>';
					set_post_address(data.data);
					//设置默认之后 其他地址去除默认标识
					if (data.data.is_default == 1) {
						//去除所有的默认操作
						$(".user-addresslist").removeClass('defaultAddr');
						$(".default-address").removeClass('defaultAddr');
						//默认图片隐藏
						$(".deftip").html('');
						$(".is_default").removeClass('hidden');
						//删除地址
						$(".del_address").each(function () {
							$(this).attr('onclick','del_address(this,'+$(this).attr('address_id')+',0)');
						});
					}
					$(".address ul").append(_html);
					$(document.body).css("overflow", "visible");
					$('.theme-login').removeClass("selected");
					$('.item-props-can').removeClass("selected");
					$('.theme-popover-mask').hide();
					$('.theme-popover').slideUp(200);
				}
			} else {
				layer.msg(data.data.msg, {icon: 2});
				return false;
			}
			/*$(document.body).css("position", "static");
			$('.theme-signin-left').scrollTop(0);
			$('.theme-login').removeClass("selected");
			$('.item-props-can').removeClass("selected");
			$('.theme-popover-mask').hide();
			$('.theme-popover').slideUp(200);*/
		}
	})
}

/**
 * 设置默认地址
 * @param obj
 * @param address_id
 */
function set_default_address (obj, address_id) {
	layer.confirm('您确定要设置为默认地址吗', function (index) {
		var li = $("[data-address_id=" + address_id + "]").next();
		$.ajax({
			type: 'POST',
			url: ajaxSetDefaultAddress,
			data: {
				address_id: address_id, is_default: 1
			},
			dataType: 'json',
			async: true,
			success: function (data) {
				if (data.code == 0) {
					//去除所有的默认操作
					$(".user-addresslist").removeClass('defaultAddr');
					$(".default-address").removeClass('defaultAddr');
					//默认图片隐藏
					$(".deftip").html('');
					$(".is_default").removeClass('hidden');
					//删除地址
					$(".del_address").each(function () {
						$(this).attr('onclick','del_address(this,'+$(this).attr('address_id')+',0)');
					});
					//自己
					li.addClass("defaultAddr").find(".default-address").addClass('defaultAddr');
					li.find(".deftip").html("默认地址");
					li.find(".new-addr-btn a:eq(0)").addClass('hidden');
					li.find(".del_address").attr('onclick','del_address(this,'+address_id+',1)');
				}
			}
		});
		layer.close(index);
	})
}

/**
 * 设置地址
 * @param address_id
 */
function set_address (address_id) {
	var obj = $("[data-address_id=" + address_id + "]");
	var address_province = obj.data('address_province');
	var address_city = obj.data('address_city');
	var address_district = obj.data('address_district');
	var address_username = obj.data('address_username');
	var address_mobile = obj.data('address_mobile');
	var user_address = obj.data('user_address');
	$(".pay-address .buy--address-detail .province").html(address_province);
	$(".pay-address .buy--address-detail .city").html(address_city);
	$(".pay-address .buy--address-detail .dist").html(address_district);
	$(".pay-address .buy--address-detail .street").html(user_address);
	$(".pay-address .buy-footer-address .buy-user").html(address_username);
	$(".pay-address .buy-footer-address .buy-phone").html(address_mobile);

	$("[name=address_id]").val(address_id);
}

/**
 * 保存收货地址
 */
function set_post_address (data) {
	$(".pay-address .buy--address-detail .province").html(data.province.name);
	$(".pay-address .buy--address-detail .city").html(data.city.name);
	$(".pay-address .buy--address-detail .dist").html(data.district.name);
	$(".pay-address .buy--address-detail .street").html(data.consignee_address);
	$(".pay-address .buy-footer-address .buy-user").html(data.consignee_username);
	$(".pay-address .buy-footer-address .buy-phone").html(data.consignee_mobile);

	$("[name=address_id]").val(data.id);
}

/**
 * 删除地址
 * @param obj
 * @param address_id
 * @param is_default
 */
function del_address (obj, address_id, is_default) {
	if (is_default == 1) {
		layer.msg('默认地址不允许删除', {icon: 2});
		return false;
	}
	layer.confirm('您确定要删除地址吗', function (index) {
		var li = $("[data-address_id=" + address_id + "]").next();
		$.ajax({
			type: 'POST',
			url: ajaxGetAddressDelete,
			data: {
				address_id: address_id
			},
			dataType: 'json',
			async: true,
			success: function (data) {
				if (data.code == 0) {
					if (data.data.consignee_address != null) {
						set_post_address(data.data);
					}
					li.slideUp('slow', function () {
						li.remove();
					});
					$("[data-address_id=" + address_id + "]").slide('slow', function () {
						$("[data-address_id=" + address_id + "]").slow();
					});
					//如果地址都删除完了
					if ($(".address ul li").length == 0) {
						$("[name=address_id]").val('');
					}
				}
			}
		});
		layer.close(index);
	})
}