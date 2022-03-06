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
var comment = {
	is_lock: false,
};
//请求前
comment.ajax_before = function () {
	comment.is_lock = true;
}
//请求完成
comment.ajax_complete = function () {
	comment.is_lock = false;
}

/**
 添加购物车
 **/
function addToCart (obj, event) {
	if (cart.is_lock == true) {
		layer.msg('正在处理中请稍后', {icon: 2});
		return false;
	}
	var goods_id = obj.attr('goods_id');
	//当前规格组合
	var spec_list = active_spec();
	//当前的数量
	var goods_num = $("[name=goods_num]").val();
	//运行添加规格的长度
	var allowd_spec = $(".theme-signin-left div.pic").length;
	//当前的规格
	if (allowd_spec && spec_list.spec_id == "") {
		layer.msg('请选择商品规格后，再添加到购物车上面', {icon: 2});
		return false;
	}
	//判断用户有没有选择规格
	var had_spec = spec_list.spec_id.join(",");
	if (allowd_spec && !spec_compose[had_spec]) {
		layer.msg('请把商品规格勾选完,再添加到购物车上', {icon: 2});
		return false;
	}
	//商品数量小于1
	if (goods_num < 1) {
		layer.msg('至少需要购买一件商品', {icon: 2});
		$("[name=goods_num]").focus();
		return false;
	}
	$.ajax({
		beforeSend: cart.ajax_before,
		type: 'POST',
		url: '/cart/addToCart',
		data: {spec_id: spec_list.spec_id, goods_num: goods_num, goods_id: goods_id},
		dataType: 'json',
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				$(".cart-info .num").html(data.total_rows);
				$(".cart_num").html(data.total_rows);
				var $ww = $(window).width();
				if ($ww < 1025) {
					$('.u-flyer').hide();
				} else {
					// 加车动画
					var addcar = obj;
					var offset = $(".cart_num").offset();
					var img = $('.tb-s310').find('img').attr('src');
					var flyer = $('<img class="u-flyer" src="' + img + '">');
					flyer.fly({
						start: {
							left: event.pageX,
							top: event.pageY
						},
						end: {
							left: offset.left + 10,
							top: offset.top + 10,
							width: 0,
							height: 0
						},
						onEnd: function () {
							$("#msg").show().animate({
								width: '250px'
							}, 200).fadeOut(1000);
							addcar.css("cursor", "default").removeClass('orange');
							//右侧边栏点击购买物车
							$(".tip").addClass('open');
							this.destory();
						}
					});
					$("#wrap .item:eq(1)").mouseenter();
				}
				setTimeout(function () {
					layer.msg(data.info, {icon: 1});
				}, 1000)
			}
		},
		complete: cart.ajax_complete
	});
}

/**
 * 立即购买
 * @param obj
 * @param goods_id
 */
function goods_buy (obj, goods_id) {
	//当前规格组合
	var spec_list = active_spec();
	//当前的数量
	var goods_num = $("[name=goods_num]").val();
	//运行添加规格的长度
	var allowd_spec = $(".theme-signin-left div.pic").length;
	//当前的规格
	if (allowd_spec && spec_list.spec_id == "") {
		layer.msg('请选择商品规格后，再添加到立即购买', {icon: 2});
		return false;
	}
	//判断用户有没有选择规格
	var had_spec = spec_list.spec_id.join(",");
	if (allowd_spec && !spec_compose[had_spec]) {
		layer.msg('请把商品规格勾选完,再添加到立即购买', {icon: 2});
		return false;
	}
	//规格id
	var standard_id = spec_compose[had_spec] ? spec_compose[had_spec]['id'] : 0;
	//商品数量小于1
	if (goods_num < 1) {
		layer.msg('至少需要购买一件商品', {icon: 2});
		$("[name=goods_num]").focus();
		return false;
	}
	//判断用户有没有登录
	if ($("[name=login_uid]").val() == 0) {
		check_login();
		return false;
	}
	$.ajax({
		type: "POST",
		url: checkGoodsStock,
		data: {spec_id: spec_list.spec_id, goods_num: goods_num, goods_id: goods_id},
		dataType: 'json',
		success: function (data) {
			if (parseInt(data.code)) {
				return layer.msg(data.msg, {icon: 2});
			} else {
				location.href = buyGoods+"?goods_id=" + goods_id + "&standard_id=" + standard_id + "&goods_num=" + goods_num;
			}
		}
	})
}

//分页
$.urlParam = function (name, url) {
	var url = url || window.location.href;
	var results = new RegExp('[\\/]' + name + '/([^&#]*)').exec(url);
	if (!results) return false;
	return results[1] || 0;
}

/**
 * ajax异步获取评论
 * @param goods_id
 * @param page
 */
function ajax_goods_comment (goods_id, page, comment_grade) {
	if (comment.is_lock == true) {
		layer.msg('正在处理中请稍后', {icon: 2});
		return false;
	}
	$.ajax({
		beforeSend: comment.ajax_before,
		type: 'POST',
		url: '/goods/ajaxComment',
		data: {goods_id: goods_id, page: page, comment_grade: comment_grade},
		dataType: 'json',
		success: function (data) {
			if (data.status == 0) {
				layer.msg(data.info, {icon: 2});
			} else {
				var _html = "";
				if (data.page_data.length > 0) {
					$.each(data.page_data, function (k, v) {
						_html += ' <li class="am-comment">';
						_html += '	<!-- 评论容器 -->';
						_html += '	<a href="javascript:;">';
						_html += '	    <img class="am-comment-avatar" src="' + v.user_avatar + '" />';
						_html += '	    <!-- 评论者头像 -->';
						_html += '	</a>';
						_html += '';
						_html += '	<div class="am-comment-main">';
						_html += '	    <!-- 评论内容容器 -->';
						_html += '	    <header class="am-comment-hd">';
						_html += '		<!--<h3 class="am-comment-title">评论标题</h3>-->';
						_html += '		<div class="am-comment-meta">';
						_html += '		    <!-- 评论元数据 -->';
						_html += '		    <a href="#link-to-user" class="am-comment-author">' + v.user_name + ' (匿名)</a>';
						_html += '		    <!-- 评论者 -->';
						_html += '		    评论于';
						_html += '		    <time>' + v.comment_time_goods + '</time>';
						_html += '		</div>';
						_html += '	    </header>';
						_html += '';
						_html += '	    <div class="am-comment-bd">';
						_html += '		<div class="tb-rev-item ">';
						_html += '		    <div class="J_TbcRate_ReviewContent tb-tbcr-content ">';
						_html += v.comment_content;
						_html += '		    </div>';
						if (v.standard_attr.length > 0) {
							_html += '		    <div class="tb-r-act-bar">';
							$.each(v.standard_attr, function (k1, v1) {
								_html += v1.attr_name + "&nbsp;&nbsp;" + v1.attr_value;
							})

							_html += '		    </div>';
						}

						_html += '		</div>';
						_html += '';
						_html += '	    </div>';
						_html += '	    <!-- 评论内容 -->';
						_html += '	</div>';
						_html += '</li>';
					});
				}
				$(".am-comments-list").html(_html);
				$(".am-pagination").html(data.page);
				$(".comment_0").html("(" + data.page_total + ")");
				$(".comment_5").html("(" + data.page_total_5 + ")");
				$(".comment_3").html("(" + data.page_total_3 + ")");
				$(".comment_1").html("(" + data.page_total_1 + ")");
			}
		},
		complete: comment.ajax_complete
	});
}

/**
 * 收藏商品
 * @param goods_id
 */
function goods_collect (obj, goods_id) {
	//判断用户有没有登录
	if ($("[name=login_uid]").val() == 0) {
		check_login();
		return false;
	}
	if ($(obj).hasClass('no')) {
		return false;
	}
	layer.confirm('您确定要收藏商品', function (index) {
		$.ajax({
			url: "/User/goodsCollect",
			async: false,
			type: "POST",
			dataType: "json",
			data: {goods_id: goods_id},
			success: function (data) {
				if (data.status == 0) {
					layer.msg(data.info, {icon: 2});
				} else {
					$(obj).addClass('no').find("a").html('已收藏');
					layer.msg(data.info, {icon: 1});
				}
			}
		})
	})
}

$(document).ready(function () {
	var $ww = $(window).width();
	if ($ww < 1025) {
		$('.theme-login').click(function () {
			$(document.body).css("position", "fixed");
			$('.theme-popover-mask').show();
			$('.theme-popover').slideDown(200);

		})

		$('.theme-poptit .close,.btn-op .close,.btn-op .btn').click(function () {
			$(document.body).css("position", "static");
			//					滚动条复位
			$('.theme-signin-left').scrollTop(0);
			$('.theme-popover-mask').hide();
			$('.theme-popover').slideUp(200);
		})
	}
	//导航栏固定
	var $ww = $(window).width();
	var dv = $('ul.am-tabs-nav.am-nav.am-nav-tabs'),
		st;
	if ($ww < 623) {
		var tp = $ww + 240;
		$(window).scroll(function () {
			st = Math.max(document.body.scrollTop || document.documentElement.scrollTop);
			if (st >= tp) {
				if (dv.css('position') != 'fixed') dv.css({
					'position': 'fixed',
					top: 50,
					'z-index': 1000009
				});

			} else if (dv.css('position') != 'static') dv.css({
				'position': 'static'
			});
		});
		//滚动条复位（需要减去固定导航的高度）
		$('.introduceMain ul li').click(function () {
			sts = tp;
			$(document).scrollTop(sts);
		});
	} else {
		dv.attr('otop', dv.offset().top); //存储原来的距离顶部的距离
		var tp = parseInt(dv.attr('otop')) + 36;
		$(window).scroll(function () {
			st = Math.max(document.body.scrollTop || document.documentElement.scrollTop);
			if (st >= tp) {

				if (dv.css('position') != 'fixed') dv.css({
					'position': 'fixed',
					top: 0,
					'z-index': 998
				});

				//滚动条复位
				$('.introduceMain ul li').click(function () {
					sts = tp - 35;
					$(document).scrollTop(sts);
				});

			} else if (dv.css('position') != 'static') dv.css({
				'position': 'static'
			});
		});
	}
	$(".jqzoom").imagezoom();
	$("#thumblist li a").click(function () {
		$(this).parents("li").addClass("tb-selected").siblings().removeClass("tb-selected");
		$(".jqzoom").attr('src', $(this).find("img").attr("mid"));
		$(".jqzoom").attr('rel', $(this).find("img").attr("big"));
	});
	$(".theme-options.pic li").click(function () {
		$("#thumblist li").removeClass("tb-selected");
		$(".jqzoom").attr('src', $(this).find('img').attr("img-mid"));
		$(".jqzoom").attr('rel', $(this).find('img').attr("img-big"));
		$(".theme-signin-right .img-info img").attr('src', $(this).find('img').attr("img-mid"));
	});
	//优惠券
	$(".hot span").click(function () {
		$(".shopPromotion.gold .coupon").toggle();
	});
	$(window).load(function () {
		$('.flexslider').flexslider({
			animation: "slide",
			start: function (slider) {
				$('body').removeClass('loading');
			}
		});
	});
	var $citypicker = $('#city-picker');
	$citypicker.citypicker({
		province: '贵州省',
		city: '贵阳市',
		district: '云岩区'
	});
	$('#reset').click(function () {
		$citypicker3.citypicker('reset');
	});
	$('#destroy').click(function () {
		$citypicker3.citypicker('destroy');
	});
	//商品评价
	$(".introduceMain ul li:eq(1)").click(function () {
		var goods_id = $(this).attr('goods_id');
		ajax_goods_comment(goods_id, 1, 0);
	});
	//ajax分页
	$(".am-pagination a").live('click', function () {
		var urlstr = $(this).attr('href').toString();
		var page = $.urlParam('page', urlstr);
		if (page) {
			goods_id = $(".introduceMain ul li:eq(1)").attr('goods_id');
			var comment_grade = $(".tb-taglist-li-current").attr('comment_grade');
			ajax_goods_comment(goods_id, page, comment_grade);
		}
		return false;
	})
	//评论等级切换
	$(".tb-taglist-li").live('click', function () {
		$(this).addClass('tb-taglist-li-current').siblings('li').removeClass('tb-taglist-li-current');
		var comment_grade = $(this).attr('comment_grade');
		var goods_id = $(this).attr('goods_id');
		ajax_goods_comment(goods_id, 1, comment_grade)
	});
})