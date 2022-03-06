
//手机注册提交
function login_submit () {
	var username = $.trim($('#username').val());
	if (username.length == 0) {
		layer.msg('账号不能为空', {icon: 2});
		return false;
	}
	//请填写密码
	var password = $("#password").val().trim();
	if (password.length == 0) {
		layer.msg('密码不能为空', {icon: 2});
		return false;
	}
	if(show_verify == 1){
		//请填写手机验证码
		var code = $("#code").val().trim();
		if (code.length == 0) {
			layer.msg('请填写验证码', {icon: 2});
			return false;
		}
	}else{
		var code = 0;
	}

	//记住账号
	var remember_me = $("#remember-me:checked").val();
	var data = {
		username: username,
		password: password,
		code: code,
		remember_me: remember_me,
	}
	ajax_login(data);
	return false;
}

/**
 * 注册发送ajax请求
 * @param data
 */
function ajax_login (data) {
	$.ajax({
		url: '',
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
					location.href = skip_url;
				}, 1000)
			}
		}
	})
}