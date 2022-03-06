$(function () {
	//全选反选
	$('table thead th input:checkbox').on('click', function () {
		$("tbody input:checkbox").prop("checked", $(this).prop('checked'));
	});

	$("table tbody td input:checkbox").on('click', function () {
		//当选中的长度等于checkbox的长度的时候,就让控制全选反选的checkbox设置为选中,否则就为未选中
		if ($("tbody input:checkbox").length === $("tbody input:checked").length) {
			$('table thead th input').prop("checked", true);
		} else {
			$('table thead th input').prop("checked", false);
		}
	});
	//顶部登录之后的样式
	user_top_nav();
	//分类样式
	$("#js_climit_li li").hover(function () {
		$(".category-content .category-list li.first .menu-in").css("display", "none");
		$(".category-content .category-list li.first").removeClass("hover");
		$(this).addClass("hover");
		$(this).children("div.menu-in").css("display", "block");
	}, function () {
		$(this).removeClass("hover");
		$(this).children("div.menu-in").css("display", "none");
	});
	//其它共用导航分类样式移入样式
	$(".slideall .long-title").hover(function () {
		$(this).parent("div").find("#nav").show();
	}, function () {
		$(this).parent("div").find("#nav").hide();
	});

	$(".slideall #nav").hover(function () {
		$(this).show();
	}, function () {
		$(this).hide();
	});
	$(".category-info h3").mouseover(function () {
		var parent = $(this).parents("li");
		var class_id = parent.attr('class_id');
		$.post('/common/ajaxNav', {class_pid: class_id}, function (data) {
			if (data.status == 1) {
				var _html = "";
				$.each(data.class_data, function (k, v) {
					_html += '<dl class="dl-sort">';
					_html += '<dt><span title="' + v.class_name + '">' + v.class_name + '</span></dt>';
					$.each(v.child, function (k1, v1) {
						_html += '<dd><a title = "' + v1.class_name + '" href = "' + v.url + '"target = "_blank" ><span> ' + v1.class_name + ' </span></a></dd>';
					});
					_html += '</dl>';
				});
				parent.find(".sort-side").html(_html).show();
			}
		}, 'json');
	});

	//搜索边上二维码关闭
	$(".qr-ft").on('click', function () {
		$(".nav-extra").fadeOut();
	});
	//返回顶部
	$(".return-top").click(function () {
		$('html,body').animate({
			scrollTop: 0
		}, 'slow');
	});
	// 在线客服
	$(".am-icon-whatsapp.online").hover(function () {
		$(this).removeClass("hover");
	}, function () {
		$(this).addClass("hover");
	});
	//搜索筛选切换
	$(".theme-popover-tag .expand").click(function () {
		$(this).hide();
		$(".theme-popover-tag .collapse").show();
		$(this).parent().removeClass("show-expand");
		$(".theme-popover .select").show();
	});
	$(".theme-popover-tag .collapse").click(function () {
		$(this).hide();
		$(".theme-popover-tag .expand").show();
		$(this).parent().addClass("show-expand");
		$(".theme-popover .select").hide();
	});
	//select筛选
	$(".select-list dd").click(function () {
		$(this).addClass("selected").siblings().removeClass("selected");
	});
	//sort筛选
	$(".sort li").click(function () {
		$(this).addClass("active").siblings().removeClass("active");
	});
	//搜索边栏标题样式
	$(".side-list li").mouseover(function () {
		$(this).find(".title").show();
	});
	$(".side-list li").mouseout(function () {
		$(this).find(".title").hide();
	});
	$(".sidebar-cart-box").css({
		"height": "1000px"
	});
	//右侧边栏点击购买物车
	$(".tip #shop-cart").live('click', function () {
		$(".tip").addClass('open');
	});
	$(".open #shop-cart,.close-open-cart").live('click', function () {
		$(".tip").removeClass('open');
	});
	side_cart();
})

function side_cart () {
	$(".tip .sc-main-list .sc-order").hover(function () {
		$(this).addClass("hover").siblings().removeClass("hover");
	}, function () {
		$(this).removeClass("hover");
	});
}

//全选
function input_select_all (t, input_class) {
	if (t.checked) {
		$("." + input_class).attr('checked', true);
	} else {
		$("." + input_class).attr('checked', false);
	}
}

//单个选
function input_select_single (t, input_class, input_all) {
	if (t.checked) {
		var _checked_length = $("." + input_class + ":checked").length;
		var _length = $("." + input_class).length;
		if (_checked_length == _length) {
			$("." + input_all).attr("checked", true);
		} else {
			$("." + input_all).attr("checked", false);
		}
	}
}

/**
 * 顶部登录之后的样式
 */
function user_top_nav () {
	//顶部用户登陆后样式
	$("#menu-user .logined").hover(function () {
		$(".nav-user-panel").show();
		$(this).css({
			"background": "#ffffff",
		});
	}, function () {
		$(".nav-user-panel").hide();
		$(this).css({
			"background": "#f2f2f2",
		});
	});
	$(".nav-user-panel").hover(function () {
		$(this).show();
		$("#menu-user .logined").css({
			"background": "#ffffff",
		});
	}, function () {
		$(this).hide();
		$("#menu-user .logined").css({
			"background": "#f2f2f2",
		});
	});
}

//确认删除
function confirm_msg (obj, msg = '') {
	if (msg == '') {
		msg = '删除';
	} else {
		msg = msg;
	}
	var url = $(obj).attr('url');
	layer.confirm('确认要' + msg + '吗?', function (index) {
		location.href = url;
	})
	return false;
}

//触发表单删除提交功能
function formAutoSubmit (input_class, right_form) {
	var data_tag = 0;
	//判断checkbox是否选中
	$("." + input_class).each(function () {
		if (this.checked) {
			data_tag = 1;
		}
	});
	//如果选中我们进行删除提交
	if (data_tag > 0) {
		layer.confirm('确认要删除吗?', function (index) {
			$("." + right_form).submit();
			return true;
		})
	}
	return false;
}

//保留两位小数
function return_float (value) {
	var value = Math.round(parseFloat(value) * 100) / 100;
	var xsd = value.toString().split(".");
	if (xsd.length == 1) {
		value = value.toString() + ".00";
		return value;
	}
	if (xsd.length > 1) {
		if (xsd[1].length < 2) {
			value = value.toString() + "0";
		}
		return value;
	}
}

//获取图片地址
function ajax_upload (file, type) {
	/*var url = null ;
	if (window.createObjectURL!=undefined)
	{ // basic
		url = window.createObjectURL(file) ;
	}
	else if (window.URL!=undefined)
	{
		// mozilla(firefox)
		url = window.URL.createObjectURL(file) ;
	}
	else if (window.webkitURL!=undefined) {
		// webkit or chrome
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url ;*/
	var form_data = new FormData();
	form_data.append('type', type);
	form_data.append(type, file);
	form_data.append("name", type);
	var url = "";
	$.ajax({
		url: "/Common/upload",
		type: 'POST',
		cache: false,
		async: false,
		dataType: 'json',
		processData: false,
		contentType: false,
		data: form_data,
		success: function (data) {
			if (data.code == 0) {
				url = data.data.src;
			} else {
				return false;
			}
		}
	});
	return url;
}

var spec_compose = {"compose": {}};//可以选择的组合
//商品规格选择
$(function () {
	//选择商品规格处理
	$(".spec").live('click', function () {
		//判断是否可以选择
		if ($(this).hasClass('ban')) {
			return false;
		}
		//同辈清除激活状态
		$(this).siblings().removeClass("selected");
		//给自己增加样式
		$(this).hasClass("selected") ? $(this).removeClass('selected') : $(this).addClass('selected');
		//如果下级选中 清空选中状态
		var next_spec_item = $(this).parents('.theme-options').nextAll(".theme-options");
		if (next_spec_item.find('.selected').length > 0) {
			next_spec_item.find(".selected").removeClass("selected");
		}
		var obj = $(this);
		var spec = active_spec();//当前选中的规格
		var goods_id = $(this).attr('goods_id');
		$.post(getGoodsStandard, {goods_id: goods_id}, function (res) {
			if (res.code == 0) {
				$(".select-pop").html('已选择规格:' + "<i>" + spec.spec_value.join("+") + "</i>");
				$(".select-pop-data").html('已选择规格:' + "<i>" + spec.spec_value.join("+") + "</i>");
				$.each(res.data, function (k, v) {
					spec_compose[v.name] = v;
					spec_compose['compose'][v.name] = v.name.split(",");
				});
				var allowd = allow_spec(spec_compose.compose, spec.spec_id);
				//当选择一个规格后,给不能选择加上禁用样式
				obj.parents(".theme-options").nextAll().find(".spec").each(function () {
					if ($(this).hasClass("selected") == 0) {
						var obj = $(this);
						$('input', $(this)).each(function () {
							if ($.inArray($(this).val(), allowd) == -1) {
								$(this).parent().addClass('ban');
							} else {
								var has_spec = spec.spec_id.join(",") + "," + $(this).val();
								//去除第一个逗号
								if (has_spec.substr(0, 1) == ",") {
									has_spec = has_spec.substr(1);
								}
								if (spec_compose[has_spec]) {
									//库存为0直接禁用
									if (spec_compose[has_spec]['stock'] == 0) {
										$(this).parent().removeClass('ban').addClass('ban');
									} else {
										$(this).parent().removeClass('ban')
									}
								} else {
									$(this).parent().removeClass('ban')
								}
							}
						})
					}
				});
				//为每个规格产品的不同价格做显示
				var had_spec = spec.spec_id.join(",");
				if (spec_compose[had_spec]) {
					//库存
					$(".stock").text(spec_compose[had_spec]['stock']);
					//价格
					$(".sys_item_price,.price-now").text(spec_compose[had_spec]['price']);
					//划线价
					$(".sys_item_mktprice").text(spec_compose[had_spec]['market_price']);

					if (spec_compose[had_spec]['stock'] == 0) {
						$(".tb-btn-buy,.tb-btn-basket").addClass('disabled');
						$(".btn-op div").addClass('disabled');
					} else {
						$(".tb-btn-buy,.tb-btn-basket").removeClass('disabled');
						$(".btn-op div").removeClass('disabled');
					}
					//获取商品规格的库存
					_val = obj.parents(".theme-signin-left").find("[name=goods_num]").val();
					if (_val >= spec_compose[had_spec]['stock']) {
						obj.parents(".theme-signin-left").find("[name=goods_num]").val(spec_compose[had_spec]['stock']);
						$(".add_goods_num").attr('disabled', true);
						$(".min_goods_num").attr('disabled', false);
					} else {
						if (_val == 1) {
							$(".min_goods_num").attr('disabled', true);
						}
						obj.parents(".theme-signin-left").find("[name=goods_num]").val(1);
						$(".add_goods_num").attr('disabled', false);
					}
				} else {
					$(".tb-btn-buy,.tb-btn-basket").removeClass("disabled");
					$('.stock').text($('.stock').attr('stock'));
					$('.sys_item_price,.price-now').text($('.sys_item_price').attr('price'));
					$('.sys_item_mktprice').text($('.sys_item_mktprice').attr('market_price'));
				}
			}
		}, 'json');
	});
	//数量增加操作
	$(".add_goods_num").live("click", function () {
		var t = $(".goods_num");
		var stock = Number($(".stock").html());
		var orig = Number(t.val());
		if (stock >= (orig + 1)) {
			if (stock == (orig + 1)) {
				$(".add_goods_num").attr('disabled', true);
			}
			t.val(orig + 1);
			t.keyup();
		} else {
			t.val(stock);
		}
		$(".min_goods_num").attr('disabled', false);
	});
	//数量减
	$(".min_goods_num").live("click", function () {
		var t = $(".goods_num");
		var stock = Number($(".stock").html());
		var orig = Number(t.val());
		if (orig > 1) {
			t.val(orig - 1);
			t.keyup();
		}
		if (parseInt(t.val()) == 1) {
			$(".min_goods_num").attr('disabled', true);
		}
		$(".add_goods_num").attr('disabled', false);
	});
	//加入购物车
	$("#LikBasket").on('click', function (event) {
		var obj = $(this);
		addToCart(obj, event);
	});
	//点击验证码
	$(".verify img").click(function () {

		$(this).attr('src', '/login/verify' + "?" + Math.round(Math.random() * 1000));
	});
	//获取购物车列表数据
	$(".shopping-cart-v2").hover(function () {
		//异步获取购物车数据
		$.ajax({
			url: ajax_cart_data,
			type: "GET",
			async: false,
			dataType: "jsonp",
			jsonp: "callback",
			success: function (data) {
				var _html = "";
				if (data.cart_data != null) {
					$.each(data.cart_data, function (k, v) {
						_html += '<li>';
						_html += '	<a rel="nofollow" href="' + v.url + '" target="_blank" class="imgbox"> ';
						_html += '		<img src="' + v.goods_thumb + '" alt="' + v.goods_name + '" width="45">';
						_html += '	</a> ';
						_html += '	<a rel="nofollow" href="' + v.url + '" target="_blank">' + v.goods_name_12 + '</a>';
						if (v.standard_id > 0) {
							_html += '	<span class="info">';
							$.each(v.spec_data, function (k1, v1) {
								_html += v1['attr_name'] + '：' + v1['attr_value'] + '<br />';
							})
							_html += '</span>';

						}
						_html += '	<span class="price">¥' + v.goods_price + '</span> ';
						_html += '	<span data-stockid="116ykg6e" class="del" onclick="del_cart(this,' + v.goods_id + ',' + v.standard_id + ')">删除</span> ';
						_html += '</li>';
					});
				}
				$(".empty-cart .max_height_ie6").html(_html);
				$(".empty-cart").show();
				$(".cart-info .num").html(data.count);
				$(".cart_num").html(data.count);
			}
		})
	}, function () {
		$(".empty-cart").hide();
	})
});

/**
 * ajax异步删除购物车
 * @param self
 * @param goods_id
 * @param standard_id
 */
function del_cart (self, goods_id, standard_id) {
	//异步获取购物车数据
	$.ajax({
		url: ajax_cart_del_url,
		data: {goods_id: goods_id, standard_id: standard_id},
		type: "GET",
		async: false,
		dataType: "jsonp",
		jsonp: "callback",
		success: function (data) {
			if (data.status == 1) {
				//头部购物车
				var cart_header = $(self).parent();
				//删除自己
				cart_header.slideUp('slow', function () {
					cart_header.remove();
				});
				//购物车列表
				var del_node = $("#del_" + goods_id + "_" + standard_id);

				var obj = del_node.parents(".item-content");
				var obj_parents = del_node.parents(".item-list");
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
					$(".cart_num").html(data.total_rows);
					$("#wrap .item:eq(1)").mouseenter();
				}
			} else {
				layer.msg(data.info, {icon: 2});
			}
		}
	})
}

/**
 * 键盘按下事件
 */
function change_goods_quantity (self) {
	var obj = $(self);
	var _v = obj.val();
	if (_v <= 0 || _v == "" || !/^\d+$/.test(_v)) {
		_v = 1;
	}
	var stock = Number($(".stock").html());
	if (_v >= stock) {
		_v = stock;
		$(".add_goods_num").attr('disabled', true);
		$(".min_goods_num").attr('disabled', false);
	} else if (_v == 1) {
		$(".add_goods_num").attr('disabled', false);
		$(".min_goods_num").attr('disabled', true);
	} else {
		$(".add_goods_num").attr('disabled', false);
		$(".min_goods_num").attr('disabled', false);
	}
	obj.val(_v);
}

/**
 * 当前选中的规格
 */
function active_spec () {
	var spec = {
		'spec_value': [],
		'spec_id': [],
	}
	//当前选中的标签
	$(".theme-options li.selected").each(function () {
		spec.spec_value.push($(this).attr('title'));
		spec.spec_id.push($(this).children("input").val());
	});
	return spec;
}

/**
 * 计算当选择规格后,能够选择的值
 * @param all 所有的规格组合
 * @param spec 当前选中的值
 */
function allow_spec (all, select) {
	var allow = [];
	$.each(all, function (k, v) {
		if (a_belong_b(select, v)) {
			$.each(v, function (a_k, a_v) {
				if ($.inArray(a_v, allow) == -1 && $.inArray(a_v, select) == -1) {
					allow.push(a_v);
				}
			})
		}
	})
	return allow;
}

/**
 * 判断a 数组是否是b数组的子集
 */
function a_belong_b (a, b) {
	var flag = true;
	for (i = 0; i < a.length; i++) {
		if ($.inArray(a[i], b) == -1) {
			flag = false;
			break;
		}
	}
	return flag;
}

/**
 * ajax获取店铺地区
 * @param region_id
 * @param region_type
 */
function load_region (region_id, region_type) {
	$.post('/common/ajaxCity', {'region_pid': region_id}, function (data) {
		if (data.status == 1) {
			if (region_type == "store_city") {
				$("#" + region_type).html('<option value="0">请选择城市</option>');
				$("#store_district").html('<option value="0">请选择地区</option>');
			} else if (region_type == "store_district") {
				$("#store_district").html('<option value="0">请选择地区</option>');
			}
			if (region_type != "null") {
				$.each(data.region_data, function (k, vo) {
					$("#" + region_type).append('<option value="' + vo.region_id + '">' + vo.region_name + '</option>')
				})
			}
		} else {

		}
	}, 'json');
}

/**
 * ajax获取地址地区
 * @param region_id
 * @param region_type
 */
function load_address (region_id, region_type) {
	$.post(ajaxCity, {'region_pid': region_id}, function (res) {
		if (res.code == 0) {
			if (region_type == "address_city") {
				$("#" + region_type).html('<option value="0">请选择城市</option>');
				$("#address_district").html('<option value="0">请选择地区</option>');
			} else if (region_type == "address_district") {
				$("#address_district").html('<option value="0">请选择地区</option>');
			}
			if (region_type != "null") {
				$.each(res.data, function (k, vo) {
					$("#" + region_type).append('<option value="' + vo.id + '">' + vo.name + '</option>')
				})
			}
		}
	}, 'json');
}

//登陆弹窗
function check_login () {
	layer.open({
		type: 1,
		title: '会员登陆',
		shade: 0.5,
		shadeClose: true,
		content: $('#loginBox'),
		end: function () {
			$("#loginBox").hide();
		}
	});
}

//手机提交
function login_common () {
	var username = $.trim($('#login_username').val());
	if (username.length == 0) {
		layer.msg('账号不能为空', {icon: 2});
		return false;
	}
	//请填写密码
	var password = $("#login_password").val().trim();
	if (password.length == 0) {
		layer.msg('密码不能为空', {icon: 2});
		return false;
	}
	if (show_verify == 1) {
		//请填写手机验证码
		var code = $("#login_code").val().trim();
		if (code.length == 0) {
			layer.msg('请填写验证码', {icon: 2});
			return false;
		}
	} else {
		var code = 0;
	}

	var goods_id = $("[name=goods_id]").val();
	var store_id = $("[name=store_id]").val();
	var data = {
		username: username,
		password: password,
		code: code,
		goods_id:goods_id,
		store_id:store_id,
	}
	ajax_login_common(data);
	return false;
}

/**
 * 注册发送ajax请求
 * @param data
 */
function ajax_login_common (data) {
	$.ajax({
		url: login_url,
		async: false,
		type: "GET",
		dataType: "jsonp",
		jsonp: "callback",
		data: data,
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				layer.msg(data.info, {icon: 1});
				var _html = "";
				_html += '<a href="' + user_index_url + '" class="home fl logined">' + data.user_mobile + '<i class="am-icon-angle-down"></i></a>';
				_html += '<div class="nav-user-panel">';
				_html += '	<div class="site-nav-user-wrapper">';
				_html += '		<a href="' + user_index_url + '" class="site-nav-user-avatar">';
				_html += '			<img id="nav-user-avatar" src="/static/index/images/getAvatar.do.jpg" width="80" height="80"';
				_html += '									   alt="' + data.user_mobile + '的头像">';
				_html += '		</a>';
				_html += '	</div>';
				_html += '	<div class="site-nav-user-info">';
				_html += '		<p class="site-nav-user-operate"><a href="javascript:;" target="_top">账号管理</a> <span';
				_html += '				class="site-nav-pipe">|</span> <a href="' + logout_url + '" target="_top">退出</a></p>';
				_html += '		<p class="level-info tao-score">积分：511</p>';
				_html += '		<p class="level-info ">普通会员</p>';
				_html += '	</div>';
				_html += '</div>';
				//替换样式 用户已登录
				$("#menu-user").html(_html);
				//替换购物车数量
				$(".cart-info .num").html(data.total_rows);
				$(".cart_num").html(data.total_rows);
				//用户id
				$("[name=login_uid]").val(data.uid);
				//关闭弹框
				layer.closeAll();
				//顶部用户登陆后样式
				user_top_nav();
				//商品收藏样式和店铺收藏样式
				common_store_goods(data);
			}
		}
	})
}

/**
 *登录成功之后商品样式和店铺样式
 * @param data
 */
function common_store_goods (data) {
	if(data.goods_collect_status == 1){
		$(".collection").addClass('no').removeAttr('Onclick').find("a").html('已收藏');
	}
	if(data.store_collect_status == 1){
		$(".fav-shop").removeAttr('Onclick').html('已收藏');
		$(".collect-store").find('a').removeAttr('Onclick').html('已收藏');
	}
}
/**
 * 倒计时
 * @param _boolean
 * @param _this
 */
function count_down (_boolean, _this) {
	var sh = [];
	_this.each(function (index, el) {

		var thisObj = $(this);
		sh[index] = setInterval(function () {
			var endtime = thisObj.attr("data-times"); //获得timeBox的data值，即结束时间
			nowtime = new Date(); //获得当前时间
			nowtime = parseInt(nowtime.getTime() / 1000);
			lefttime = parseInt(endtime - nowtime); //结束时间-当前时间得到毫秒数，再除以1000得到两个时间相差的秒数

			if (_boolean) {
				d = parseInt(lefttime / 3600 / 24);
				h = parseInt((lefttime / 3600) % 24);
			} else {
				d = parseInt(lefttime / 3600 / 24) * 24;
				h = parseInt((lefttime / 3600) % 24) + d;
			}

			m = parseInt((lefttime / 60) % 60);
			s = parseInt(lefttime % 60);
			if (endtime <= nowtime) {
				d = h = m = s = "0";
				clearInterval(sh[index]);
			}
			// 三目运算符
			d = d < 10 ? "0" + d : d;
			h = h < 10 ? "0" + h : h;
			m = m < 10 ? "0" + m : m;
			s = s < 10 ? "0" + s : s;

			if (_boolean) {
				thisObj.find(".date").text(d);
			}

			thisObj.find(".hour").text(h);
			thisObj.find(".minutes").text(m);
			thisObj.find(".seconds").text(s);

			if (lefttime <= 0) {
				d = h = m = s = "00";
				clearInterval(sh[index]);
			}
		}, 1000);
	});
}

//删除上传控件
function remove_file (obj) {
	var file_img = $(obj);
	file_img.after(file_img.clone().val(""));
	file_img.remove();
}

/**
 * 删除图片
 * @param img_url
 */
function del_img (img_url) {
	if (img_url != '') {
		$.ajax({
			url: "/Common/delImg",
			type: 'POST',
			data: {
				img_url: img_url
			},
		});
	}
}

//关闭消息
function msg_close () {
	$('.msg-box').hide(600);
}

/**
 * 收藏店铺
 * @param store_id
 */
function store_collect (obj, store_id) {
	//判断用户有没有登录
	if ($("[name=login_uid]").val() == 0) {
		check_login();
		return false;
	}
	if ($(obj).hasClass('no')) {
		return false;
	}
	layer.confirm('您确定要收藏店铺', function (index) {
		$.ajax({
			url: store_collect_url,
			async: false,
			type: "GET",
			dataType: "jsonp",
			jsonp: "callback",
			data: {store_id: store_id},
			success: function (data) {
				if (data.status == 0) {
					layer.msg(data.info, {icon: 2});
				} else {
					$(obj).addClass('no').html('已收藏');
					layer.msg(data.info, {icon: 1});
				}
			}
		})
	})
}

//构建websocket请求
if (uid > 0) {

	var lockReconnect = false;  //避免ws重复连接
	var ws = null;          // 判断当前浏览器是否支持WebSocket
	var wsUrl = "ws://192.168.0.21/websocket/";

	//createWebSocket(wsUrl);   //连接ws

	function createWebSocket (url) {
		try {
			if ('WebSocket' in window) {
				ws = new WebSocket(url);
			} else if ('MozWebSocket' in window) {
				ws = new MozWebSocket(url);
			} else {
				alert("您的浏览器不支持websocket协议,建议使用新版谷歌、火狐等浏览器，请勿使用IE10以下浏览器，360浏览器请使用极速模式，不要使用兼容模式！");
			}
			initEventHandle();
		} catch (e) {
			reconnect(url);
			console.log(e);
		}
	}

	function initEventHandle () {
		ws.onclose = function () {
			reconnect(wsUrl);
		};
		ws.onerror = function () {
			reconnect(wsUrl);
		};
		ws.onopen = function () {
			ws.send(uid);
			heartCheck.reset().start();      //心跳检测重置
		};
		ws.onmessage = function (result) {    //如果获取到消息，心跳检测重置
			eval("data=" + result.data);
			var url = "javascript:;";
			if (data.info_url) {
				url = data.info_url;
			}
			console.log(data);
			var _html = '<li><p>' + data.info_content + '&nbsp;&nbsp;&nbsp;&nbsp;<a href="' + url + '" target="_blank">立即查看</a></p></li>';
			var _length = 10;
			if (is_mobile == 1) {
				_length = 3;
			}
			//总长度不能超过10个
			if ($(".msg-con ul li").length == _length) {
				$(".msg-con ul li:last").remove();
			}
			//如果是长度为0就追加 否则放到第一个位置前面
			if ($(".msg-con ul li").length == 0) {
				$(".msg-con ul").append(_html);
			} else {
				$(".msg-con ul li:first").before(_html);
			}
			$(".msg-box").show();
			var audio = $("#audio_source_1");
			//音乐
			var info_music = data.info_music;
			var music_src = 0;
			if (info_music != "") {
				music_src = info_music;
			} else {
				music_src = "100003.mp3";
			}
			audio.attr('src', '/static/index/audio/' + music_src);
			audio[0].play();
			heartCheck.reset().start();      //拿到任何消息都说明当前连接是正常的
		}
	}

// 监听窗口关闭事件，当窗口关闭时，主动去关闭websocket连接，防止连接还没断开就关闭窗口，server端会抛异常。
	window.onbeforeunload = function () {
		//ws.close();
	}

	function reconnect (url) {
		if (lockReconnect) return;
		lockReconnect = true;
		setTimeout(function () {     //没连接上会一直重连，设置延迟避免请求过多
			createWebSocket(url);
			lockReconnect = false;
		}, 2000);
	}

//心跳检测
	var heartCheck = {
		timeout: 540000,        //9分钟发一次心跳
		timeoutObj: null,
		serverTimeoutObj: null,
		reset: function () {
			clearTimeout(this.timeoutObj);
			clearTimeout(this.serverTimeoutObj);
			return this;
		},
		start: function () {
			var self = this;
			this.timeoutObj = setTimeout(function () {
				//这里发送一个心跳，后端收到后，返回一个心跳消息，
				//onmessage拿到返回的心跳就说明连接正常
				self.serverTimeoutObj = setTimeout(function () {//如果超过一定时间还没重置，说明后端主动断开了
					ws.close();     //如果onclose会执行reconnect，我们执行ws.close()就行了.如果直接执行reconnect 会触发onclose导致重连两次
				}, self.timeout)
			}, this.timeout)
		}
	}
}