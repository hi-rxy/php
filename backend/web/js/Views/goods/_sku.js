var step = {
    // 信息组合
    SKU_Table: function () {
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
        });
        //去重复
        arrayTile = unique(arrayTile);
        // 开始生成表格
        if (bCheck && arrayInfor.length > 0) {
            $('.spec-details').empty();
            var table = $('<table class="table table-striped table-bordered table-hover list"></table>');
            table.appendTo($('.spec-details'));
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
                    var td_array    = item.split(',');
                    var specids     = new Array();
                    var tr          = $('<tr></tr>');
                    tr.appendTo(tbody);

                    var str = '';
                    $.each(td_array, function (i, values) {
                        str += '<td style="vertical-align: middle">' + values + '</td>';
                        var specidRow = {
                            'spec_name': specName[i],
                            'spec_value': values
                        }
                        specids.push(specidRow);
                    });

                    //初始化默认值
                    var standard_price_hidden       = $("[name='standard_price_hidden\[" + item + "\]']");
                    var standard_del_price_hidden   = $("[name='standard_del_price_hidden\[" + item + "\]']");
                    var standard_stock_hidden       = $("[name='standard_stock_hidden\[" + item + "\]']");
                    var standard_weight_hidden      = $("[name='standard_weight_hidden\[" + item + "\]']");
                    var standard_volume_hidden      = $("[name='standard_volume_hidden\[" + item + "\]']");
                    var standard_sn_hidden          = $("[name='standard_sn_hidden\[" + item + "\]']");
                    var standard_id_hidden          = $("[name='standard_id_hidden\[" + item + "\]']");

                    var _length_1 = standard_price_hidden.length;
                    var _length_2 = standard_del_price_hidden.length;
                    var _length_3 = standard_stock_hidden.length;
                    var _length_4 = standard_weight_hidden.length;
                    var _length_5 = standard_volume_hidden.length;
                    var _length_6 = standard_sn_hidden.length;
                    var _length_7 = standard_id_hidden.length;

                    var standard_price = (_length_1 > 0) ? standard_price_hidden.val() : '';
                    var standard_del_price = (_length_2 > 0) ? standard_del_price_hidden.val() : '';
                    var standard_stock = (_length_3 > 0) ? standard_stock_hidden.val() : '';
                    var standard_weight = (_length_4 > 0) ? standard_weight_hidden.val() : '';
                    var standard_volume = (_length_5 > 0) ? standard_volume_hidden.val() : '';
                    var standard_sn = (_length_6 > 0) ? standard_sn_hidden.val() : '';
                    var standard_id = (_length_7 > 0) ? standard_id_hidden.val() : '';

                    str += '<td><input style="width: 120px" name="standard_price[' + index + ']" class="standard_price spec_item" type="text" data-item="' + item + '" data-name="standard_price" value="' + standard_price + '">';
                    $.each(specids, function (speckey, item) {
                        var specid = JSON.stringify(item);
                        str += '<input name="spec_id[' + index + '][]"  type="hidden" value=\'' + specid + '\'>';
                    })
                    str += '<input name="standard_name[' + index + ']"  type="hidden" value=\'' + item + '\'>';
                    str += '<td><input style="width: 120px" name="standard_del_price[' + index + ']" class="standard_del_price spec_item" type="text" data-item="' + item + '" data-name="standard_del_price" value="' + standard_del_price + '"></td>';
                    str += '<td><input style="width: 120px" name="standard_stock[' + index + ']" class="standard_stock spec_item" type="text" data-item="' + item + '" data-name="standard_stock" value="' + standard_stock + '"></td>';
                    str += '<td><input style="width: 120px" name="standard_weight[' + index + ']" class="spec_item" type="text" data-item="' + item + '" data-name="standard_weight" value="' + standard_weight + '"></td>';
                    str += '<td><input style="width: 120px" name="standard_volume[' + index + ']" class="spec_item" type="text" data-item="' + item + '" data-name="standard_volume" value="' + standard_volume + '"></td>';
                    str += '<td><input style="width: 120px" name="standard_sn[' + index + ']" class="spec_item" type="text" data-item="' + item + '" data-name="standard_sn" value="' + standard_sn + '" placeholder="备注货号"></td>';
                    str += '<input type="hidden" name="standard_id[' + index + ']" class="spec_item" type="text" value="' + standard_id + '">';
                    tr.append(str);
                });
                $('.spec_list_table').show();
                $('.spec_stock_price').show();
                $("[name=goods_price]").attr('readonly', true);
                $("[name=goods_del_price]").attr('readonly', true);
                $("[name=goods_stock]").attr('readonly', true);
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
            $('.spec-details').empty();
            $('.spec_list_table').hide();
            $('.spec_stock_price').hide();
            $("[name=goods_price]").removeAttr('readonly');
            $("[name=goods_del_price]").removeAttr('readonly');
            $("[name=goods_stock]").removeAttr('readonly');
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

        function mergeCell($table, colIndex) {
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
        function dispose($table) {
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
            step.SKU_Table();
        }
    }, 1000);
}
step.SKU_Table();