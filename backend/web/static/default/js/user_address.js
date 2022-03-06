$(document).ready(function () {
	$(".new-option-r").click(function () {
		$(this).parent('.user-addresslist').addClass("defaultAddr").siblings().removeClass("defaultAddr");
	});
	//地址表单
	if ($(".form_add_address").length > 0) {
		$(".form_add_address").submit(function () {
			//用户名
			var address_username = $("input[name='address_username']");
			var address_mobile = $("input[name='address_mobile']");
			var user_address = $("textarea[name='user_address']");
			//省份 城市 地区
			var address_province = $("select[name='address_province']");
			var address_city = $("select[name='address_city']");
			var address_district = $("select[name='address_district']");

			var is_default = $("input[name='is_default']:checked").val();

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
			//省份
			var  address_province = $.trim(address_province.val());
			var  address_city = $.trim(address_city.val());
			var  address_district = $.trim(address_district.val());
			//判断地区不能为空
			if (address_province < 1 || address_city < 1 || address_district < 1) {
				layer.msg('请选择完整的收货地区', {icon: 2});
				return false;
			}
			//收货地址
			if (user_address.val().length == 0) {
				user_address.focus();
				layer.msg('收货详细地址不能为空', {icon: 2});
				return false;
			}
		})
	}
});
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
		var li = $(obj).parents("li");
		$.ajax({
			type: 'POST',
			url: "/Address/del",
			data: {
				address_id: address_id
			},
			dataType: 'json',
			async: true,
			success: function (data) {
				if (data.status == 1) {
					li.slideUp('slow', function () {
						li.remove();
					});
				}
			}
		});
		layer.close(index);
	})
}