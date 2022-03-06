var mobile_reg = /(^1[3|4|5|7|8]\d{9}$)|(^09\d{8}$)/; //手机号正则
var email_reg = /(^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$)/; //手机号正则
var count = 60; //间隔函数，1秒执行
var intver_val_obj; //timer变量，控制时间
var cur_count; //当前剩余秒数
$(function () {
	//收藏
	if ($(".s-item-wrap").length > 0) {
		//鼠标hover
		$(".s-item").hover(function () {
			$(this).parent('.s-item-wrap').addClass('hover').siblings().removeClass('hover');
		}, function () {
			$(this).parent('.s-item-wrap').removeClass('hover')
		});

	}
	if ($(".password_form").length > 0) {
		$(".password_form").submit(function () {
			//判断旧密码
			var old_user_password = $("#old_user_password").val().trim();
			if (old_user_password.length == 0) {
				layer.msg('旧密码不能为空', {icon: 2});
				return false;
			}
			//判断新密码
			var user_password = $("#user_password").val().trim();
			if (user_password.length == 0) {
				layer.msg('新密码不能为空', {icon: 2});
				return false;
			}
			//判断新密码的长度
			if ((user_password.length < 6) || (user_password.length > 18)) {
				layer.msg('新密码的长度不能小于6或者大于18', {icon: 2});
				return false;
			}
			var re_user_password = $("#re_user_password").val().trim();
			if (user_password != re_user_password) {
				layer.msg('两次输入的密码不一样', {icon: 2});
				return false;
			}
		});
	}
	if ($(".payword_form").length > 0) {
		$(".payword_form").submit(function () {
			if ($("#old_user_payword").length > 0) {
				//判断旧密码
				var old_user_payword = $("#old_user_payword").val().trim();
				if (old_user_payword.length == 0) {
					layer.msg('旧支付密码不能为空', {icon: 2});
					return false;
				}
			}
			//判断新密码
			var user_payword = $("#user_payword").val().trim();
			if (user_payword.length == 0) {
				layer.msg('新支付密码不能为空', {icon: 2});
				return false;
			}
			//判断新密码的长度
			if ((user_payword.length < 6) || (user_payword.length > 18)) {
				layer.msg('新支付密码的长度不能小于6或者大于18', {icon: 2});
				return false;
			}
			var re_user_payword = $("#re_user_payword").val().trim();
			if (user_payword != re_user_payword) {
				layer.msg('两次输入的密码不一样', {icon: 2});
				return false;
			}
		});
	}
	//绑定邮箱
	if ($(".bind_email_form").length > 0) {
		$(".bind_email_form").submit(function () {
			if ($("#old_user_email_code").length > 0) {
				//判断旧手机号码验证码不能为空
				var old_user_email_code = $("#old_user_email_code").val().trim();
				if (old_user_email_code.length == 0) {
					layer.msg('旧邮箱验证码不能为空', {icon: 2});
					return false;
				}
			}
			//判断邮箱
			var user_email = $("#user_email").val().trim();
			if (user_email.length == 0) {
				layer.msg('新的邮箱不能为空', {icon: 2});
				return false;
			}
			if (!email_reg.test(user_email)) {
				layer.msg('请输入有效的邮箱', {icon: 2});
				return false;
			}
			var user_email_code = $("#user_email_code").val().trim();
			if (user_email_code.length == 0) {
				layer.msg('新的邮箱验证码不能为空', {icon: 2});
				return false;
			}
		})
	}
	//绑定手机
	if ($(".bind_mobile_form").length > 0) {
		$(".bind_mobile_form").submit(function () {
			if ($("#old_user_mobile_code").length > 0) {
				//判断旧手机号码验证码不能为空
				var old_user_mobile_code = $("#old_user_mobile_code").val().trim();
				if (old_user_mobile_code.length == 0) {
					layer.msg('旧手机验证码不能为空', {icon: 2});
					return false;
				}
			}
			//判断手机号码
			var user_mobile = $("#user_mobile").val().trim();
			if (user_mobile.length == 0) {
				layer.msg('新的手机号码不能为空', {icon: 2});
				return false;
			}
			if (!mobile_reg.test(user_mobile)) {
				layer.msg('请输入有效的手机号码', {icon: 2});
				return false;
			}
			var user_mobile_code = $("#user_mobile_code").val().trim();
			if (user_mobile_code.length == 0) {
				layer.msg('新的手机验证码不能为空', {icon: 2});
				return false;
			}
		})
	}
	//实名认证
	if ($(".bind_id_card_form").length > 0) {
		$(".bind_id_card_form").submit(function () {
			//判断手机号码
			var user_id_card_truename = $("#user_id_card_truename").val().trim();
			if (user_id_card_truename.length == 0) {
				layer.msg('真实姓名不能为空', {icon: 2});
				return false;
			}
			var user_id_card = $("#user_id_card").val().trim();
			if (user_id_card.length == 0) {
				layer.msg('身份证不能为空', {icon: 2});
				return false;
			}
			var res = id_card_no(user_id_card);
			if (res == 0) {
				layer.msg('身份证格式不正确', {icon: 2});
				return false;
			}
		})
	}
	//安全问题
	if ($(".user_question_form").length > 0) {
		//选择框
		$("#user_question_1").change(function () {
			var _val = $(this).val();
			$("#user_question_2 option").attr('style', 'display:block');
			if (_val == 0) {
				return false;
			}
			$("#user_question_2 option:eq(" + _val + ")").attr('style', 'display:none');
		})
		$(".user_question_form").submit(function () {
			//问题1
			var user_question_1 = $("#user_question_1").val().trim();
			if (user_question_1 == 0) {
				layer.msg('请选择问题1', {icon: 2});
				return false;
			}
			var user_answer_1 = $("#user_answer_1").val().trim();
			if (user_answer_1.length == 0) {
				layer.msg('请填写答案1', {icon: 2});
				return false;
			}
			var user_question_2 = $("#user_question_2").val().trim();
			if (user_question_2 == 0) {
				layer.msg('请选择问题2', {icon: 2});
				return false;
			}
			var user_answer_2 = $("#user_answer_2").val().trim();
			if (user_answer_2.length == 0) {
				layer.msg('请填写答案2', {icon: 2});
				return false;
			}
		})
	}
	layui.use(['form', 'upload'], function () {
		var upload = layui.upload;
		upload_img('user_avatar');

		function upload_img (id) {
			//选完文件后自动上传
			upload.render({
				elem: '#' + id,
				url: "/Common/upload",
				auto: true,
				accept: 'file', //普通文件
				data: {type: id, name: 'file'},
				before: function (obj) {
					var img_url = $('input[name=' + id + ']').val();
					// 删除老数据
					if (img_url != '') {
						$.ajax({
							url: "/Common/delImg",
							type: 'POST',
							data: {
								img_url: img_url
							},
						});
					}
					//本地本地预览示例
					obj.preview(function (index, file, result) {
						$("#" + id + "_thumb").attr('src', result).show();
					});
				},
				done: function (res) {
					//上传完毕回调
					if (res.code > 0) {
						return layer.msg('上传失败');
					} else {
						$("#" + id + "_thumb").attr('src', res.data.src).show();
						$('input[name=' + id + ']').val(res.data.src);
					}
				}
			});
		}
	});
});

/**
 * 验证身份证合法性
 * @param value
 * @returns {boolean}
 */
function id_card_no (value) {
	//验证身份证号方法
	var area = {
		11: "北京",
		12: "天津",
		13: "河北",
		14: "山西",
		15: "内蒙古",
		21: "辽宁",
		22: "吉林",
		23: "黑龙江",
		31: "上海",
		32: "江苏",
		33: "浙江",
		34: "安徽",
		35: "福建",
		36: "江西",
		37: "山东",
		41: "河南",
		42: "湖北",
		43: "湖南",
		44: "广东",
		45: "广西",
		46: "海南",
		50: "重庆",
		51: "四川",
		52: "贵州",
		53: "云南",
		54: "西藏",
		61: "陕西",
		62: "甘肃",
		63: "青海",
		64: "宁夏",
		65: "xinjiang",
		71: "台湾",
		81: "香港",
		82: "澳门",
		91: "国外"
	}
	var Y, JYM;
	var id_card = value;
	var S, M;
	var id_card_array = id_card.split("");
	if (area[parseInt(id_card.substr(0, 2))] == null) return false;
	switch (id_card.length) {
		case 15:
			if ((parseInt(id_card.substr(6, 2)) + 1900) % 4 == 0 || ((parseInt(id_card.substr(6, 2)) + 1900) % 100 == 0 && (parseInt(id_card.substr(6, 2)) + 1900) % 4 == 0)) {
				ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/; //测试出生日期的合法性
			}
			else {
				ereg = /^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/; //测试出生日期的合法性
			}
			if (ereg.test(id_card)) {
				var res = true;
			} else {
				var res = false;
			}
			return res;
			break;
		case 18:
			if (parseInt(id_card.substr(6, 4)) % 4 == 0 || (parseInt(id_card.substr(6, 4)) % 100 == 0 && parseInt(id_card.substr(6, 4)) % 4 == 0)) {
				ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/; //闰年出生日期的合法性正则表达式
			} else {
				ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/; //平年出生日期的合法性正则表达式
			}
			if (ereg.test(id_card)) {
				S = (parseInt(id_card_array[0]) + parseInt(id_card_array[10])) * 7 + (parseInt(id_card_array[1]) + parseInt(id_card_array[11])) * 9 + (parseInt(id_card_array[2]) + parseInt(id_card_array[12])) * 10 + (parseInt(id_card_array[3]) + parseInt(id_card_array[13])) * 5 + (parseInt(id_card_array[4]) + parseInt(id_card_array[14])) * 8 + (parseInt(id_card_array[5]) + parseInt(id_card_array[15])) * 4 + (parseInt(id_card_array[6]) + parseInt(id_card_array[16])) * 2 + parseInt(id_card_array[7]) * 1 + parseInt(id_card_array[8]) * 6 + parseInt(id_card_array[9]) * 3;
				Y = S % 11;
				M = "F";
				JYM = "10X98765432";
				M = JYM.substr(Y, 1);
				if (M == id_card_array[17]) {
					var res = true;
				} else {
					var res = false;
				}
			} else {
				res = false;
			}
			return res;
			break;
		default:
			res = false;
			return res;
			break;
	}
}

/**
 * 发送手机验证码
 * @param obj
 * @returns {boolean}
 */
function send_mobile_code (obj, mobile) {
	if (mobile == 0) {
		mobile = $.trim($('#user_mobile').val());
		var old_code = 0;
	} else {
		var old_code = 1;
	}
	if (!mobile_reg.test(mobile)) {
		layer.msg('请输入有效的手机号码', {icon: 2});
		return false;
	}
	ajax_send_code(mobile, 'mobile', old_code, obj)
}

/**
 * 发送邮箱验证码
 * @param obj
 * @returns {boolean}
 */
function send_email_code (obj, email) {
	if (email == 0) {
		email = $.trim($('#user_email').val());
		var old_code = 0;
	} else {
		var old_code = 1;
	}
	if (!email_reg.test(email)) {
		layer.msg('请输入有效的邮箱');
		return false;
	}
	ajax_send_code(email, 'email', old_code, obj)
}

/**
 * 公共发送验证码请求
 * @param email
 * @param type
 * @param old_code
 * @param obj
 */
function ajax_send_code (email, type, old_code, obj) {
	if (type == "email") {
		var data = {
			email: email,
			type: "email",
			old_code: old_code
		}
	} else {
		var data = {
			mobile: email,
			type: "mobile",
			old_code: old_code
		}
	}
	//发送时手机号码
	$.ajax({
		url: '/User/sendCode',
		async: false,
		type: "POST",
		data: data,
		dataType: "json",
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				layer.msg(data.info, {icon: 1});
				cur_count = count;
				//设置button效果，开始计时
				$(obj).attr("disabled", "true");
				$(obj).find("div").html(+cur_count + "秒再获取");
				intver_val_obj = window.setInterval(function () {
					if (cur_count == 0) {
						window.clearInterval(intver_val_obj); //停止计时器
						$(obj).removeAttr("disabled"); //启用按钮
						$(obj).find("div").html("重新发送");
					} else {
						cur_count--;
						$(obj).find("div").html(+cur_count + "秒再获取");
					}
				}, 1000); //启动计时器，1秒执行一次
				//向后台发送处理数据
			}
		}
	})
}

/**
 * 取消商品收藏
 * @param obj
 * @param goods_id
 */
function cancel_goods_collect (obj, goods_id) {
	layer.confirm('您确定要取消收藏商品?', function () {
		layer.closeAll();
		cancel_goods_collect_common(obj, goods_id, 1);
	})
}

/**
 * 取消店铺收藏
 * @param obj
 * @param store_id
 */
function cancel_store_collect (obj, store_id) {
	layer.confirm('您确定要取消收藏店铺?', function () {
		layer.closeAll();
		cancel_goods_collect_common(obj, store_id, 2);
	})
}

/**
 * 公共发送函数
 * @param obj
 * @param goods_id
 * @param type
 */
function cancel_goods_collect_common (obj, goods_id, type) {
	$.ajax({
		url: '/User/CancelGoodsCollect',
		type: "POST",
		async: false,
		dataType: "json",
		data: {goods_id: goods_id, type: type},
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				layer.msg(data.info, {icon: 1});
				window.location.href = "";
			}
		}
	})
}

