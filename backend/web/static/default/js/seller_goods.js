$(function () {
	//相册图拖动排序
	$(".ui-sortable").sortable({
		cursor: "move",
		items: "li", //只是li可以拖动
		opacity: 0.6, //拖动时，透明度为0.6
		revert: true, //释放时，增加动画
	});
	var $fileTag = $(".imgFile input"); //上传文件选择器控件
	$fileTag.each(function () {
		var $fileBox = $(this).parent(),
			maxLen = $(this).prop("maxlength"), //获取最多上传文件个数
			haveLen = $fileBox.siblings(".imgBox").find(".imgList").length, //获取已上传文件个数
			needLen = maxLen - haveLen;
		if (needLen === 0) {
			$fileBox.hide();
		}
		$fileBox.siblings(".imgPrompt").html('<p class="imgPrompt">共<i>' + haveLen + '</i>张，还能上传<i>' + needLen + '</i>张</p>');
	});
	$fileTag.live("change", function () {
		var _name = $(this).attr('name');
		var imgLen = $(this).prop("files").length,
			_html = '',
			$fileBox = $(this).parent(),
			maxLen = $(this).prop("maxlength"), //获取最多上传文件个数
			haveLen = $fileBox.siblings(".imgBox").find(".imgList").length, //获取已上传文件个数
			needLen = maxLen - haveLen;
		if ((maxLen - haveLen) >= imgLen) {
			for (var i = 0; i < imgLen; i++) {
				var imgUrl = ajax_upload(this.files[i], 'goods_gallery');
				var _hidden = "";
				if (_name == "goods_gallery_file") {
					_hidden = '<input name="goods_gallery[]" type="hidden" value="' + imgUrl + '" />';
				}
				_html += '<li class="imgList"><img src="' + imgUrl + '" />' + _hidden + '<span class="am-icon-trash imgDel"><i></i></span></li>';
				if (haveLen == 0) {
					$("[name=goods_gallery_hidden]").val(imgUrl);
				}
			}
			haveLen += imgLen;
			needLen = maxLen - haveLen;
			$fileBox.siblings(".imgBox").append(_html);
			$fileBox.siblings(".imgPrompt").html('<p class="imgPrompt">共<i>' + haveLen + '</i>张，还能上传<i>' + needLen + '</i>张</p>');
		} else {
			$fileBox.siblings(".imgPrompt").html('<p class="imgPrompt">最多上传<i>' + needLen + '</i>张</p>');
		}
		if (maxLen === haveLen) {
			$fileBox.hide();
		}
		remove_file($(this));
	});
	$(document).on("click", '.imgDel', function () {
		var $imgBox = $(this).parents(".imgBox"),
			haveLen = $imgBox.find(".imgList").length - 1,
			maxLen = $imgBox.siblings(".imgFile").find("input").prop("maxlength"),
			needLen = maxLen - haveLen;
		$(this).parents(".imgBox").siblings(".imgFile").show().siblings(".imgPrompt").html('<p class="imgPrompt">共<i>' + haveLen + '</i>张，还能上传<i>' + needLen + '</i>张</p>');
		var goods_gallery_hidden_length = $("[name=goods_gallery_hidden]").length;
		//针对多图上传
		if (haveLen == 0 && goods_gallery_hidden_length > 0) {
			$("[name=goods_gallery_hidden]").val('');

		}
		var img_url = $(this).prev().val();
		//删除图片
		del_img(img_url);
		$(this).parent().remove();
	});
	//表单验证
	$("form").Validform({
		tiptype: function (msg, o, cssctl) {
			if (!o.obj.is("form")) {
				$('.layui-form-mid').show();
				//默认表单
				var objtip = o.obj.parents(".layui-form-item").find(".Validform_checktip");
				var objtip_1 = o.obj.parents("td").find(".Validform_checktip");
				objtip = objtip.length == 1 ? objtip : objtip_1;
				cssctl(objtip, o.type);
				objtip.text(msg);
			}
		},
		datatype: {
			"standard_stock": function (gets, obj, curform, regxp) {
				/*参数gets是获取到的表单元素值，
				 obj为当前表单元素，
				 curform为当前验证的表单，
				 regxp为内置的一些正则表达式的引用。*/
				var val = $.trim(obj.val());
				if (val == '') {
					return false;
				} else {
					if (!/^(\d+)$/.test(val)) {
						return false;
					} else {
						return true;
					}
				}
			},
			"standard_price": function (gets, obj, curform, regxp) {
				/*参数gets是获取到的表单元素值，
				  obj为当前表单元素，
				  curform为当前验证的表单，
				  regxp为内置的一些正则表达式的引用。*/
				var val = $.trim(obj.val());
				if (val == 0 || val == '0.00' || val == '') {
					return false
				}
				if (!/(^(\d+)$)|(^(\d+)[\.]{1}[0-9]{1,2}$)/.test(val)) {
					return false;
				} else {
					return true;
				}
			},
		},
		showAllError: true
	});
});

//上传规格图片
function uplaodImg (obj) {
	var file = $(obj).get(0).files[0];
	var src = ajax_upload(file, 'standard_thumb');
	if (src != '') {
		$(obj).next('img').attr('src', src).show();
		$(obj).next().next().show();
		$(obj).next().next().next().val(src);
		$(obj).parents(".upgoods_url").attr('onmouseover', "img_mouseover(this);");
		$(obj).parents(".upgoods_url").attr('onmouseout', "img_mouseout(this);");
	}
}

//删除规格图片
function del_standard_img (obj) {
	$(obj).prev().attr('src', "/static/index/images/popimg_add.png");
	$(obj).hide();
	//删除图片
	var img_url = $(obj).next().val();
	del_img(img_url);
	$(obj).next().val('');
	$(obj).parents(".upgoods_url").removeAttr('onmouseover');
	$(obj).parents(".upgoods_url").removeAttr('onmouseout');
	//置空上传控件
	file = $(obj).prev().prev();
	remove_file(file);
}
//鼠标经过图片
function img_mouseover (obj) {
	$(obj).find(".delete_img").show();
	$(obj).find(".delete_pic_ss").show();
}

//鼠标离开图片
function img_mouseout (obj) {
	$(obj).find(".delete_img").hide();
	$(obj).find(".delete_pic_ss").hide();
}

//显示对应frame
function show_frame (type) {
	var frameWrap = "";
	var title = "";
	if (type == 1) {
		frameWrap = '#stock_frame';
		title = '批量修改库存';
	} else if (type == 2) {
		frameWrap = '#price_frame';
		title = '批量修改价格';
	} else {
		frameWrap = '#price_del_frame';
		title = '批量修改划线价格';
	}
	layer.open({
		type: 1,
		title: title,
		area: ['500px', '300px'],
		content: $(frameWrap),
		cancel: function (index) {
			$(frameWrap).hide();
		}
	});
}

//确认库存或者价格
$(".stock-price-btn").on("click", function () {
	$(".frame-wrap").hide();
	layer.closeAll();
	var standard_type = $(this).attr('value');
	//1表示库存 2表示价格
	if (standard_type == 1) {
		var standard_class = $('.standard_stock');
	} else if (standard_type == 2) {
		var standard_class = $('.standard_price');
	} else {
		var standard_class = $('.standard_del_price');
	}
	var value = 0;
	var type = 0;
	var stock_price = $(this).parents('form').find('input[name=stock_price]:checked').val();
	if (parseInt(stock_price) == 1) {
		value = $(this).parents('form').find('input[name=stock_price_1]').val();
		if (value == "") {
			return false;
		}
		if (standard_type == 1) {
			value = parseInt(value);
		} else {
			value = return_float(value);
		}
		standard_class.val(value);
	} else {
		value = $(this).parents('form').find('input[name=stock_price_2]').val();
		if (value == "") {
			return false;
		}
		//1表示加 2表示减
		type = $(this).parents('form').find('select[name=stock_type]').val();
		$(standard_class).each(function () {
			var obj = $(this);
			var stock = price_stock_add_reduce(obj.val(), value, type);
			//1表示库存 2表示价格
			if (standard_type == 1) {
				stock = parseInt(stock);
			} else {
				stock = return_float(stock);
			}
			obj.val(stock);
		});
	}
	//判断新增价格最小值
	if (standard_type == 1) {
		price_min('standard_stock', 2);

	} else if (standard_type == 2) {
		price_min('standard_price', 1);
	} else if (standard_type == 3) {
		price_min('standard_del_price', 1);
	}
});

//库存加减
function price_stock_add_reduce (arg1, arg2, type) {
	var r1, r2, m;
	try {
		r1 = arg1.toString().split(".")[1].length
	} catch (e) {
		r1 = 0
	}
	try {
		r2 = arg2.toString().split(".")[1].length
	} catch (e) {
		r2 = 0
	}
	m = Math.pow(10, Math.max(r1, r2));
	if (type == 1) {
		return (arg1 * m + arg2 * m) / m;
	} else {
		if (arg1 == 0) {
			return 0;
		}
		var arg3 = (arg1 * m - arg2 * m) / m;
		if (arg3 < 0) {
			arg3 = 0;
		}
		return arg3;
	}
}
