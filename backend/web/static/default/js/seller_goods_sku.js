$(function () {
	// 添加规格值
	$(document).on("click", ".add-value-btn", function () {
		var _val = $(this).parents('tr').prev().find(".addIpnut").val();
		if (typeof _val != undefined && _val == "") {
			layer.msg("规格名不能为空", {icon: 2});
			return false;
		}
		var _html = '<div class="chbox"><div class="layui-input-inline checkedBox"><input type="text" class="layui-input addIpnut" datatype="*" nullmsg="请添加规格值"><i class="am-icon am-icon-trash del"></i></div><span class="pop-img upgoods_url"><input type="file" class="upimgs"  onchange="uplaodImg(this)"><img src="/static/index/images/popimg_add.png" style="display:block;"><span class="am-icon-trash delete_img" onclick="del_standard_img(this);" title="删除"></span><input class="upload-pic" type="hidden" name="goods_attr_pic[' + _val + '][]"></span></div>';
		$(this).before(_html);
	});
	// 删除规格值
	$(document).on("click", ".checkedBox .del", function () {
		var obj = $(this);
		layer.confirm('确定要删除规格值', {
			time: 0 //不自动关闭
			,
			btn: ['确定', '取消'],
			yes: function (index) {
				layer.close(index);
				layer.msg('删除成功', {
					skin: 'saveFrame-success',
					icon: 1
				});
				obj.parents(".chbox").remove();
				step.Creat_Table();

			}
		});

	});
	//添加规格列表
	$(document).on("click", ".add-speclist .btn", function () {
		var itemLen = $(".Father_Item").length; //自增值
		var _html = '<table class="layui-table list"><tr class="Father_Title"><th width="100" class="spec">规格名</th><th><div class="name"><input type="text" class="layui-input addIpnut" datatype="*" nullmsg="请添加规格名"></div><i class="am-icon am-icon-trash del"></i></th></tr><tr class="spec-value"><td class="spec">规格值</td><td class="Father_Item Father_Item' + itemLen + '"><a class="layui-btn layui-btn-normal add-value-btn" href="javascript:;" attr_name="">+添加规格值</a></td></tr></table>';
		$(this).before(_html);
	});
	// 删除规格列表
	$(document).on("click", ".Father_Title .del", function () {
		var obj = $(this);
		layer.confirm('确定要删除规格', {
			time: 0 //不自动关闭
			,
			btn: ['确定', '取消'],
			yes: function (index) {
				layer.close(index);
				layer.msg('删除成功', {
					skin: 'saveFrame-success',
					icon: 1
				});
				obj.parents(".list").remove();
				step.Creat_Table();
			}
		});
	});
	// 失焦事件
	$(document).on("blur", ".name .addIpnut", function () {
		var obj = $(this);
		console.log(obj);
		var add_name = obj.val().trim();
		if (add_name == '') {
			return false;
		}
		//规格名数组
		var add_name_arr = [];
		$(".name .addIpnut").each(function () {
			if ($.trim($(this).val()) != "") {
				add_name_arr.push($.trim($(this).val()));
			}
		})
		$(".Father_Title .next-checkbox-label").each(function () {
			if ($.trim($(this).text()) != "") {
				add_name_arr.push($.trim($(this).text()));
			}
		})
		//数组是否重复
		add_name_arr = add_name_arr.slice().sort();
		for (var i = 0; i < add_name_arr.length; i++) {
			if (add_name_arr[i] == add_name_arr[i + 1]) {
				layer.msg("规格名：" + add_name_arr[i]+"已经存在，请换一个试试", {icon: 2});
				obj.val('');
				return false;
			}
		}
		if (add_name !== '') {
			obj.parents(".list").find(".add-value-btn").attr('attr_name', add_name);

			obj.parents(".Father_Title").next().find(".Father_Item .addIpnut").attr('name', 'store_arr[' + add_name + '][]');

			obj.parents(".Father_Title").next().find(".Father_Item .upload-pic").attr('name', 'goods_attr_pic[' + add_name + '][]');
			// $(this).parent().text(add_name);
			step.Creat_Table();
		}
	});
	$(document).on("blur", ".checkedBox .addIpnut", function () {
		var obj = $(this);
		console.log(obj);
		console.log(1111);
		var add_name = obj.val().trim();
		var add_title = obj.parents('.Father_Item').find('a').attr('attr_name');
		console.log(add_title);
		if (add_name == "") {
			return false;
		}
		//规格值数组
		var spec_value_arr = [];
		$(".Father_Item").find(".checkedBox .addIpnut").each(function () {
			if ($.trim($(this).val()) != "") {
				spec_value_arr.push($.trim($(this).val()));
			}
		})
		$(".Father_Item").parents("td").find(".next-checkbox-label").each(function () {
			if ($.trim($(this).text()) != "") {
				spec_value_arr.push($.trim($(this).text()));
			}
		})
		//数组是否重复
		spec_value_arr = spec_value_arr.slice().sort();
		for (var i = 0; i < spec_value_arr.length; i++) {
			if (spec_value_arr[i] == spec_value_arr[i + 1]) {
				layer.msg("规格值：" + spec_value_arr[i]+"已经存在，请换一个试试", {icon: 2});
				obj.val('');
				return false;
			}
		}
		var attr_name = $(this).parents("tr").find(".add-value-btn").attr('attr_name');
		var _html = '<i style="display:none;"></i><input type="checkbox" checked class="checkbox check_item" value="' + add_name + '" />';

		_html += '<input class="layui-input addIpnut xs" type="text" value="' + add_name + '" name="store_arr[' + attr_name + '][]"/><i class="am-icon am-icon-trash del"></i>';

		obj.parents(".chbox").find('.upload-pic').attr('name', 'goods_attr_pic[' + add_title + '][' + add_name + ']');
		if (attr_name !== '' && add_name !== '') {
			$(this).parents(".checkedBox").html(_html);
			step.Creat_Table();
		}
	});

	var addAttrNum = 0;
	// 添加属性值
	$(document).on("click", ".add-prop-btn", function () {
		var _html = '<div class="attribute-wrap">' +
			'<input class="layui-input-inline layui-input attribute-name" placeholder="属性名" name="custom_attr_name[]" />' +
			'<input class="layui-input-inline layui-input attribute-name" placeholder="属性值" name="custom_attr_value[]" />' +
			'<span class="layui-input-inline layui-btn layui-btn-primary del-btn-attr"><i class="am-icon-trash"></i> 删除</span>' +
			'</div>';
		addAttrNum++;
		$(this).before(_html);
	});
	// 删除自定义属性
	$(document).on("click", ".attribute-wrap .del-btn-attr", function () {
		var obj = $(this);
		layer.confirm('确定要删除商品类目属性?', {
			time: 0 //不自动关闭
			,
			title: '删除类目属性',
			btn: ['确定', '取消'],
			yes: function (index) {
				layer.msg('删除成功', {
					skin: 'saveFrame-success',
					icon: 1
				});
				layer.close(index);
				obj.parents(".attribute-wrap").remove();

			}
		});
	});
	//规格明细初始值设置
	//setInterval(function(){step.Creat_Table()}, 1000);

	$(document).on('change', '.choose_config label', function () {
		var parent = $(this).parents('.Father_Item');
		var _this = $('.checkbox', this);
		// 是否全选
		$('.checkbox', parent).each(function () {
			var bCheck2 = true;
			if (_this.hasClass('check_all')) {
				if (_this.get(0).checked) {
					bCheck2 = true;
					$('.check_item', parent).prop('checked', bCheck2);
				} else {
					bCheck2 = false;
					$('.check_item', parent).prop('checked', bCheck2);
				}
				return false;
			} else {
				if ((!this.checked) && (!$(this).hasClass('check_all'))) {
					bCheck2 = false;
					$('.check_all', parent).prop('checked', bCheck2);
					return false;
				}
			}
			$('.check_all', parent).prop('checked', bCheck2);
		});

		step.Creat_Table();
	});

	var step = {
		// 信息组合
		Creat_Table: function () {
			step.hebingFunction();
			var SKUObj = $('.Father_Title');
			var arrayTile = new Array(); // 表格标题数组
			var arrayInfor = new Array(); // 盛放每组选中的CheckBox值的对象
			var arrayColumn = new Array(); // 指定列，用来合并哪些列
			var bCheck = true; // 是否全选，只有全选，表格才会生成
			var specName = new Array();
			var columnIndex = 0;
			$.each(SKUObj, function (i, item) {
				arrayColumn.push(columnIndex++);
				var objDom = SKUObj.eq(i).find(".name");
				if ($(objDom).find('.addIpnut').length > 0) {
					var attr = $(objDom).find('.addIpnut').val();
				} else {
					var attr = SKUObj.eq(i).find(".next-checkbox-label").text();
				}
				var itemName = '.Father_Item' + i;
				var bCheck2 = true; // 是否全选
				// 获取选中的checkbox的值
				var order = new Array();
				$(itemName + ' .check_item:checked').each(function () {
					order.push($(this).val());
					arrayTile.push(attr);
				});
				if (order.length > 0) {
					arrayInfor.push(order);
				}
			})
			//去重复
			arrayTile = unique(arrayTile);
			// 开始生成表格
			if (bCheck && arrayInfor.length > 0) {
				$('#createTable').html('');
				var table = $('<table class="layui-table list"></table>');
				table.appendTo($('#createTable'));
				var thead = $('<thead></thead>');
				thead.appendTo(table);
				var trHead = $('<tr></tr>');
				trHead.appendTo(thead);
				// 创建表头
				var str = '';
				$.each(arrayTile, function (index, item) {
					str += '<th>' + item + '</th>';
					specName.push(item);
				})
				str += '<th>销售价格（元）</th><th>划线价（元）</th><th>库存（件）</th><th>重量（kg）</th><th>体积（m<sup>3</sup>）</th><th>商品编码<i class="bm"></i></th>';
				trHead.append(str);
				var tbody = $('<tbody></tbody>');
				tbody.appendTo(table);

				var zuheDate = step.doExchange(arrayInfor);

				if (zuheDate.length > 0) {
					//创建行
					$.each(zuheDate, function (index, item) {
						var td_array = item.split(',');
						var specids = new Array();
						var tr = $('<tr></tr>');
						tr.appendTo(tbody);
						var str = '';
						$.each(td_array, function (i, values) {
							str += '<td>' + values + '</td>';
							var specidRow = {
								'spec_name': specName[i],
								'spec_value': values
							}
							specids.push(specidRow);
						});
						//初始化默认值
						var _length = $("[name='standard_price_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_price = $("[name='standard_price_hidden\[" + item + "\]']").val();
						} else {
							var standard_price = "";
						}
						var _length = $("[name='standard_del_price_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_del_price = $("[name='standard_del_price_hidden\[" + item + "\]']").val();
						} else {
							var standard_del_price = "";
						}

						var _length = $("[name='standard_stock_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_stock = $("[name='standard_stock_hidden\[" + item + "\]']").val();
						} else {
							var standard_stock = "";
						}
						var _length = $("[name='standard_weight_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_weight = $("[name='standard_weight_hidden\[" + item + "\]']").val();
						} else {
							var standard_weight = "";
						}
						var _length = $("[name='standard_volume_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_volume = $("[name='standard_volume_hidden\[" + item + "\]']").val();
						} else {
							var standard_volume = "";
						}
						var _length = $("[name='standard_sn_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_sn = $("[name='standard_sn_hidden\[" + item + "\]']").val();
						} else {
							var standard_sn = "";
						}
						//规格id
						var _length = $("[name='standard_id_hidden\[" + item + "\]']").length;
						if (_length > 0) {
							var standard_id = $("[name='standard_id_hidden\[" + item + "\]']").val();
						} else {
							var standard_id = "";
						}
						str += '<td ><input datatype="standard_price" nullmsg="请填写销售价格"  errormsg="销售价格只能是数字或者小数点2位" name="standard_price[' + index + ']" class="layui-input inpbox-mini standard_price" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_price\')" value="' + standard_price + '">';
						$.each(specids, function (speckey, item) {
							var specid = JSON.stringify(item);
							str += '<input name="spec_id[' + index + '][]"  type="hidden" value=\'' + specid + '\'>';
						})
						str += '<input name="standard_name[' + index + ']"  type="hidden" value=\'' + item + '\'>';
						str += '<span class="Validform_checktip"></span></td>';
						str += '<td ><input datatype="standard_price" nullmsg="请填写划线价格"  errormsg="划线价格只能是数字或者小数点2位" name="standard_del_price[' + index + ']" class="layui-input inpbox-mini standard_del_price" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_del_price\')" value="' + standard_del_price + '"><span class="Validform_checktip"></span></td>';
						str += '<td ><input datatype="standard_stock" nullmsg="请填写库存"  errormsg="库存只能是数字" name="standard_stock[' + index + ']" class="layui-input inpbox-mini standard_stock" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_stock\')" value="' + standard_stock + '"><span class="Validform_checktip"></span></td>';
						str += '<td ><input name="standard_weight[' + index + ']" class="layui-input inpbox-mini" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_weight\')"  value="' + standard_weight + '"><span class="Validform_checktip"></span></td>';
						str += '<td ><input name="standard_volume[' + index + ']" class="layui-input inpbox-mini" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_volume\')"  value="' + standard_volume + '"><span class="Validform_checktip"></span></td>';
						str += '<td ><input name="standard_sn[' + index + ']" class="layui-input inpbox-mini" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_sn\')"  value="' + standard_sn + '" placeholder="备注货号"><span class="Validform_checktip"></span></td>';
						str += '<input type="hidden" name="standard_id[' + index + ']" class="layui-input inpbox-mini" type="text" Onkeyup="standard_price(\'' + item + '\',this,\'standard_id\')"  value="' + standard_id + '">';
						tr.append(str);
					});
					$('.spec_list_table').show();
					$('.spec_stock_price').show();
					$("[name=goods_price]").attr('disabled',true);
					$("[name=goods_del_price]").attr('disabled',true);
					$("[name=goods_stock]").attr('disabled',true);
				}

				//结束创建Table表
				arrayColumn.pop(); //删除数组中最后一项
				//合并单元格
				$(table).mergeCell({
					// 目前只有cols这么一个配置项, 用数组表示列的索引,从0开始
					cols: arrayColumn
				});
			} else {
				//未全选中,清除表格
				document.getElementById('createTable').innerHTML = "";
				$('.spec_list_table').hide();
				$('.spec_stock_price').hide();
				$("[name=goods_price]").removeAttr('disabled');
				$("[name=goods_del_price]").removeAttr('disabled');
				$("[name=goods_stock]").removeAttr('disabled');
			}
		},
		hebingFunction: function () {
			$.fn.mergeCell = function (options) {
				return this.each(function () {
					var cols = options.cols;
					for (var i = cols.length - 1; cols[i] != undefined; i--) {
						mergeCell($(this), cols[i]);
					}
					dispose($(this));
				})
			};

			function mergeCell ($table, colIndex) {
				$table.data('col-content', ''); // 存放单元格内容
				$table.data('col-rowspan', 1); // 存放计算的rowspan值 默认为1
				$table.data('col-td', $()); // 存放发现的第一个与前一行比较结果不同td(jQuery封装过的), 默认一个"空"的jquery对象
				$table.data('trNum', $('tbody tr', $table).length); // 要处理表格的总行数, 用于最后一行做特殊处理时进行判断之用
				// 进行"扫面"处理 关键是定位col-td, 和其对应的rowspan
				$('tbody tr', $table).each(function (index) {
					// td:eq中的colIndex即列索引
					var $td = $('td:eq(' + colIndex + ')', this);
					// 获取单元格的当前内容
					var currentContent = $td.html();
					var _length = $(this).find("td").length;
					// 第一次时走次分支
					if ($table.data('col-content') == '') {
						$table.data('col-content', currentContent);
						$table.data('col-td', $td);
					} else {
						// 上一行与当前行内容相同
						if ($table.data('col-content') == currentContent && (colIndex != _length - 1) && (colIndex != _length - 2) && (colIndex != _length - 3) && (colIndex != _length - 4)) {
							// 上一行与当前行内容相同则col-rowspan累加, 保存新值
							var rowspan = $table.data('col-rowspan') + 1;
							$table.data('col-rowspan', rowspan);
							// 值得注意的是 如果用了$td.remove()就会对其他列的处理造成影响
							$td.hide();
							// 最后一行的情况比较特殊一点
							// 比如最后2行 td中的内容是一样的, 那么到最后一行就应该把此时的col-td里保存的td设置rowspan
							// 最后一行不会向下判断是否有不同的内容
							if (++index == $table.data('trNum')) {
								$table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
							}
						} else {
							// col-rowspan默认为1, 如果统计出的col-rowspan没有变化, 不处理
							if ($table.data('col-rowspan') != 1) {
								$table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
							}
							// 保存第一次出现不同内容的td, 和其内容, 重置col-rowspan
							$table.data('col-td', $td);
							$table.data('col-content', $td.html());
							$table.data('col-rowspan', 1);
						}
					}
				})
			}

			// 同样是个private函数 清理内存之用
			function dispose ($table) {
				$table.removeData();
			}
		},
		doExchange: function (doubleArrays) {
			// 二维数组，最先两个数组组合成一个数组，与后边的数组组成新的数组，依次类推，知道二维数组变成以为数组，所有数据两两组合
			var len = doubleArrays.length;
			if (len >= 2) {
				var arr1 = doubleArrays[0];
				var arr2 = doubleArrays[1];
				var len1 = arr1.length;
				var len2 = arr2.length;
				var newLen = len1 * len2;
				var temp = new Array(newLen);
				var index = 0;
				for (var i = 0; i < len1; i++) {
					for (var j = 0; j < len2; j++) {
						temp[index++] = arr1[i] + ',' + arr2[j];
					}
				}
				var newArray = new Array(len - 1);
				newArray[0] = temp;
				if (len > 2) {
					var _count = 1;
					for (var i = 2; i < len; i++) {
						newArray[_count++] = doubleArrays[i];
					}
				}
				return step.doExchange(newArray);
			} else {
				return doubleArrays[0];
			}
		}
	};
	if (window.isspec == false) {
		var pectime = setInterval(function () {
			if (window.isspec == true) {
				clearInterval(pectime);
				step.Creat_Table();
			}
		}, 1000);
	}
	step.Creat_Table();
})

//去重复
function unique (arr) {
	var result = [],
		hash = {};
	for (var i = 0, elem;
	     (elem = arr[i]) != null; i++) {
		if (!hash[elem]) {
			result.push(elem);
			hash[elem] = true;
		}
	}
	return result;
}

function standard_price (item, obj, type) {
	var val = $(obj).val();
	var _length = $("[name='" + type + "_hidden\[" + item + "\]']").length;
	if (_length > 0) {
		$("[name='" + type + "_hidden\[" + item + "\]']").val(val);
	} else {
		$(".control-hidden").append('<input name="' + type + '_hidden[' + item + ']"  type="hidden" value="' + val + '">');
	}
	//判断新增价格最小值
	if (type == "standard_price") {
		price_min(type, 1);

	} else if (type == "standard_del_price") {
		price_min(type, 1);
	} else if (type == "standard_stock") {
		price_min(type, 2);
	}
}

//获取价格最小值
function price_min (standard_price, type) {
	var input = $("." + standard_price);
	var standard_price_arr = [];
	input.each(function (k, v) {
		if ($(this).val().length > 0) {
			standard_price_arr.push($(this).val());
		}
	});
	//判断有没有数据
	if (standard_price_arr.length > 0) {
		if (type == 1) {
			var min_price = Math.min.apply(null, standard_price_arr);
		} else if (type == 2) {
			var goods_stock = 0
			$.each(standard_price_arr, function (k, v) {
				goods_stock += parseInt(v);
			})
			var min_price = goods_stock;
		}
		standard_price = standard_price.replace(/standard/g, "goods");
		$("[name='" + standard_price + "']").val(min_price);
	}

}

/**
 * 多级联动 商品分类
 */
function change_goods_class (obj) {
	var class_id = $(obj).val().trim();
	$(obj).nextAll().remove();
	if (class_id == "") {
		return false;
	}
	$.post(ajax_goods_class, {class_id: class_id}, function (data) {
		if (data.status == 1) {
			if (data.class_data.length > 0) {
				var _html = ' <select lay-ignore name="class_id[]"  Onchange="change_goods_class(this)">';
				_html += '<option value="">请选择分类</option>';
				$.each(data.class_data, function (k, v) {
					_html += '<option value="' + v.class_id + '">--' + v.class_name + '--</option>';
				});
				_html += '</select>';
				$(obj).after(_html);
			}
			//规格
			if (data.spec_data.length > 0) {
				class_spec_data(data.spec_data)
			} else {
				var _html = ' <div class="add-speclist"><span class="layui-btn btn">添加规格名</span></div>';
			}
			//隐藏批量修改价格和库存
			$(".spec_stock_price").hide();
			//属性
			if (data.attr_data.length > 0) {
				class_attr_data(data.attr_data)
			}
			//已选择规格置空
			$("#createTable").html('');
			$("[name=goods_price]").removeAttr('disabled');
			$("[name=goods_del_price]").removeAttr('disabled');
			$("[name=goods_stock]").removeAttr('disabled');
		}
	}, 'json');
}

/**
 * 生成规格
 * @param data
 */
function class_spec_data (data) {
	var i = 0;
	if (data.length > 0) {
		var _html = '<table class="layui-table list">';
		$.each(data, function (k, v) {
			_html += '<!--规格开始-->';
			_html += '    <tr>';
			_html += '	<th width="90" class="spec">规格名：</th>';
			_html += '	<td class="spec Father_Title">';
			_html += '	    <div class="name">';
			_html += '		<label>';
			_html += '		    <span class="next-checkbox-label">' + v.attr_name + '</span>';
			_html += '		</label>';
			_html += '	    </div>';
			_html += '	</td>';
			_html += '    </tr>';
			_html += '    <tr class="spec-value">';
			_html += '	<td>规格值</td>';
			_html += '	<td class="Father_Item Father_Item' + i + '">';
			if (v.attr_value.length > 0) {
				$.each(v.attr_value, function (k1, v1) {
					_html += '	    <div class="chbox">';
					_html += '		<div class="layui-input-inline checkedBox">';
					_html += '			<label>';
					_html += '			    <span class="next-checkbox">';
					_html += '			   <input style="display: block;margin-top: 4px;" lay-ignore type="checkbox" class="checkbox check_item" value="' + v1 + '" name="store_arr[' + v.attr_name + '][' + v1 + ']">';
					_html += '			   </span>';
					_html += '			    <span class="next-checkbox-label">' + v1 + '</span>';
					_html += '			</label>';
					_html += '		  </div>';
					_html += '		  <span class="pop-img upgoods_url">';
					_html += '			    <input type="file" class="upimgs"  onchange="uplaodImg(this)">';
					_html += '			    <img src="/static/index/images/popimg_add.png" style="display:block;">';
					_html += '			    <span class="am-icon-trash delete_img" onclick="del_standard_img(this);" title="删除">';
					_html += '			    </span>';
					_html += '			    <input type="hidden" class="upload-pic" name="goods_attr_pic[' + v.attr_name + '][' + v1 + ']">';
					_html += '		  </span>';
					_html += '	    </div>';
				});
			}
			_html += '	    <a class="layui-btn layui-btn-normal add-value-btn" href="javascript:;" attr_name="' + v.attr_name + '">+添加规格值</a>';
			_html += '	</td>';
			_html += '    </tr>';
			_html += '    <!--规格结束-->';
			i++;
		})
		_html += '</table>';
		_html +='<div class="add-speclist"><span class="layui-btn btn">添加规格名</span></div>';
		$(".spec-list").html(_html);
	}
}

function class_attr_data (data) {
	if (data.length > 0) {
		var _html = "";
		$.each(data, function (k, v) {
			_html += '<div class="layui-col-xs6">';
			_html += '	<div class="sell-cat-prop-item">';
			_html += '	    <label class="layui-form-label">' + v.attr_name + '：</label>';
			_html += '	    <div class="layui-input-inline">';
			if (v.attr_type == 1) {
				if (v.attr_value.length > 0) {
					$.each(v.attr_value, function (k1, v1) {
						_html += '<label class="cklabel">';
						_html += '    <span class="next-checkbox">';
						_html += '   <input style="display: block;margin-top: 4px;" lay-ignore type="radio" class="checkbox check_item"  value="' + v1 + '" name="goods_attr['+v.attr_id+'][]">';
						_html += '   </span>';
						_html += '    <span class="next-checkbox-label">'+v1+'</span>';
						_html += '</label>';
					});
				}
			}else if(v.attr_type == 2){
				if (v.attr_value.length > 0) {
					$.each(v.attr_value, function (k1, v1) {
						_html += '<label class="cklabel">';
						_html += '    <span class="next-checkbox">';
						_html += '   <input style="display: block;margin-top: 4px;" lay-ignore type="checkbox" class="checkbox check_item"  value="' + v1 + '" name="goods_attr['+v.attr_id+'][]">';
						_html += '   </span>';
						_html += '    <span class="next-checkbox-label">'+v1+'</span>';
						_html += '</label>';
					});
				}
			}else if(v.attr_type == 3){
				
				_html += '<input type="text" class="layui-input" name="goods_attr['+v.attr_id+'][]" value="">';
					
				
			}else if(v.attr_type == 4){
				if (v.attr_value.length > 0) {
					_html += '		<select lay-ignore name="goods_attr['+v.attr_id+'][]">';
					_html += '		    <option value="">请选择</option>';
					$.each(v.attr_value, function (k1, v1) {
						_html += '		    <option value="'+v1+'">'+v1+'</option>';

					});
					_html += '		</select>';
				}
			}
			_html += '	    </div>';
			_html += '	</div>';
			_html += '</div>';

		});
		$(".goods_attr_content").html(_html);
	}
}
/**
 * 多级联动 商品分类
 */
function change_store_class (obj) {
	var class_id = $(obj).val().trim();
	$(obj).nextAll().remove();
	if (class_id == "") {
		return false;
	}
	$.post(ajax_store_class, {class_id: class_id}, function (data) {
		if (data.status == 1) {
			if (data.class_data.length > 0) {
				var _html = ' <select lay-ignore name="store_class_id[]"  Onchange="change_store_class(this)">';
				_html += '<option value="">请选择分类</option>';
				$.each(data.class_data, function (k, v) {
					_html += '<option value="' + v.class_id + '">--' + v.class_name + '--</option>';
				});
				_html += '</select>';
				$(obj).after(_html);
			}
		}
	}, 'json');
}