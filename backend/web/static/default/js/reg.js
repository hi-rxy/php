$(function () {
	//邮箱和手机注册切换
	$(".am-tabs-nav li").on('click', function () {
		var index = $(this).index();
		$(this).addClass('am-active').siblings().removeClass('am-active');
		$(".am-tab-panel:eq(" + index + ")").addClass('am-active').siblings().removeClass('am-active');
	});
});
var phoneReg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/; //手机号正则
var emailReg = /(^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$)/; //手机号正则
var count = 60; //间隔函数，1秒执行
var InterValObj; //timer变量，控制时间
var curCount; //当前剩余秒数
/**
 * 发送手机验证码
 * @param obj
 * @returns {boolean}
 */
function sendMobileCode (obj) {
	curCount = count;
	var mobile = $.trim($('#mobile').val());
	if (!phoneReg.test(mobile)) {
		layer.msg('请输入有效的手机号码', {icon: 2});
		return false;
	}
	//判断图像验证码是否必填
	var verify = $('#mobile_verify').val().trim();
	if (verify.length == 0) {
		layer.msg('请填写图形验证码', {icon: 2});
		return false;
	}
	//发送时手机号码
	$.ajax({
		url: send_code,
		async: false,
		type: "POST",
		data: {mobile: mobile, verify: verify, type: 'mobile'},
		dataType: "json",
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				layer.msg(data.info, {icon: 1});
				//设置button效果，开始计时
				$(obj).attr("disabled", "true");
				$(obj).val(+curCount + "秒再获取");
				InterValObj = window.setInterval(SetMobileRemainTime, 1000); //启动计时器，1秒执行一次
				//向后台发送处理数据

			}
		}
	})

}

/**
 * 发送邮箱验证码
 * @param obj
 * @returns {boolean}
 */
function sendEmailCode (obj) {
	curCount = count;
	var email = $.trim($('#email').val());
	if (!emailReg.test(email)) {
		layer.msg('请输入有效的邮箱');
		return false;
	}
	//判断图像验证码是否必填
	var verify = $('#email_verify').val().trim();
	if (verify.length == 0) {
		layer.msg('请填写图形验证码', {icon: 2});
		return false;
	}
	//发送时邮箱
	$.ajax({
		url: send_code,
		async: false,
		type: "POST",
		data: {email: email, verify: verify, type: 'email'},
		dataType: "json",
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				layer.msg(data.info, {icon: 1});
				//设置button效果，开始计时
				$(obj).attr("disabled", "true");
				$(obj).val(+curCount + "秒再获取");
				InterValObj = window.setInterval(SetEmailRemainTime, 1000); //启动计时器，1秒执行一次
				//向后台发送处理数据
			}
		}
	})


}

/**
 * 手机倒计时
 * @constructor
 */
function SetMobileRemainTime () {
	if (curCount == 0) {
		window.clearInterval(InterValObj); //停止计时器
		$('.dyMobileButton').removeAttr("disabled"); //启用按钮
		$('.dyMobileButton').val("重新发送");
	} else {
		curCount--;
		$('.dyMobileButton').val(+curCount + "秒再获取");
	}
}

/**
 * 邮箱倒计时
 * @constructor
 */
function SetEmailRemainTime () {
	if (curCount == 0) {
		window.clearInterval(InterValObj); //停止计时器
		$('.dyEmailButton').removeAttr("disabled"); //启用按钮
		$('.dyEmailButton').val("重新发送");
	} else {
		curCount--;
		$('.dyEmailButton').val(+curCount + "秒再获取");
	}
}

//手机注册提交
function mobile_submit () {
	var mobile = $.trim($('#mobile').val());
	if (!phoneReg.test(mobile)) {
		layer.msg('请输入有效的手机号码', {icon: 2});
		return false;
	}
	//请填写手机验证码
	var mobile_code = $("#mobile_code").val().trim();
	if (mobile_code.length == 0) {
		layer.msg('请填写手机验证码', {icon: 2});
		return false;
	}
	//请填写密码
	var password = $("#mobile_password").val().trim();
	if (password.length == 0) {
		layer.msg('密码不能为空', {icon: 2});
		return false;
	}
	//判断密码长度
	if ((password.length < 6) || (password.length > 18)) {
		layer.msg('密码不能小于6位或者大于18位', {icon: 2});
		return false;
	}
	//判断两次输入的密码是否一致
	var re_password = $("#mobile_re_password").val().trim();
	if (password != re_password) {
		layer.msg('两次输入的密码不一致', {icon: 2});
		return false;
	}
	//判断当前的协议是否勾选
	var reader_mobile = $("#reader_mobile:checked").val();
	if (reader_mobile != 1) {
		layer.msg('请同意勾选商城协议', {icon: 2});
		return false;
	}
	var data = {
		type:'mobile',
		mobile:mobile,
		code:mobile_code,
		password:password
	}
	ajax_reg(data);
	return false;
}
//邮箱注册提交
function email_submit () {
	var email = $.trim($('#email').val());
	if (!emailReg.test(email)) {
		layer.msg('请输入有效的邮箱', {icon: 2});
		return false;
	}
	//请填写email验证码
	var email_code = $("#email_code").val().trim();
	if (email_code.length == 0) {
		layer.msg('请填写邮箱验证码', {icon: 2});
		return false;
	}
	//请填写email密码
	var password = $("#email_password").val().trim();
	if (password.length == 0) {
		layer.msg('密码不能为空', {icon: 2});
		return false;
	}
	//判断密码长度
	if ((password.length < 6) || (password.length > 18)) {
		layer.msg('密码不能小于6位或者大于18位', {icon: 2});
		return false;
	}
	//判断两次输入的密码是否一致
	var re_password = $("#email_re_password").val().trim();
	if (password != re_password) {
		layer.msg('两次输入的密码不一致', {icon: 2});
		return false;
	}
	//判断当前的协议是否勾选
	var reader_email = $("#reader_email:checked").val();
	if (reader_email != 1) {
		layer.msg('请同意勾选商城协议', {icon: 2});
		return false;
	}
	var data = {
		type:'email',
		email:email,
		code:email_code,
		password:password
	}
	ajax_reg(data);
	return false;
}

/**
 * 注册发送ajax请求
 * @param data
 */
function  ajax_reg (data) {
	$.ajax({
		url:'',
		async: false,
		type: "POST",
		data: data,
		dataType: "json",
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				layer.msg(data.info, {icon: 1});
				setTimeout(function () {
					location.href=skip_url;
				},1000)
			}
		}
	})
}