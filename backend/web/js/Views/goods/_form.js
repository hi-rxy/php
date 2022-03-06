// 添加规格值节点
function createGoodsStandardByName() {
    var _val = $(this).parents('tr').prev().find(".addIpnut").val();
    if (typeof _val != undefined && _val == "") {
        return layer.msg("规格名不能为空", {icon: 2});
    }
    var _html = '<div class="chbox"><div class="checkedBox">' +
        '<input type="text" class="addIpnut" style="padding:8px;"><i class="ace-icon fa fa-trash-o bigger-120 del"></i>' +
        '</div><span class="pop-img upload_standard_src">' +
        '<input type="file" class="uploadImg" onchange="ajaxGetUploadImgPath(this)">' +
        '<img src="/images/popimg_add.png" style="display:block;max-width: 100px;width:100%;">' +
        '<span class="ace-icon fa fa-trash-o bigger-100 delete_img" onclick="deleteUploadImgByStandard(this);" title="删除"></span>' +
        '<input class="upload-pic" type="hidden" name="goods_attr_pic[' + _val + '][]"></span>' +
        '</div>';
    $(this).before(_html);
}

// 删除规格值节点
function deleteGoodsStandardByValue() {
    var obj = $(this);
    layer.confirm('确定要删除规格值', {
        time: 0, //不自动关闭
        btn: ['确定', '取消'],
        yes: function (index) {
            layer.close(index);
            layer.msg('删除成功', {
                skin: 'saveFrame-success',
                icon: 1
            });
            obj.parents(".chbox").remove();
            step.SKU_Table();
        }
    });
}

// 添加自定义规格名
function createGoodsCustomizeStandardByName() {
    var itemLen = $(".Father_Item").length; //自增值
    var _html = '<table class="table table-striped table-bordered table-hover list">' +
        '<tr class="Father_Title">' +
        '<th width="90" class="spec">规格名</th>' +
        '<td><div class="name"><input type="text" class="addIpnut"><i class="ace-icon fa fa-trash-o bigger-120 del"></i></div></td>' +
        '</tr><tr class="spec-value">' +
        '<td class="spec">规格值</td><td class="Father_Item Father_Item' + itemLen + '">' +
        '<button type="button" style="margin-left: 10px;margin-top:15px;" class="btn btn-warning btn-xs add-value-btn" data-name="">添加规格值</button></td>' +
        '</tr></table>';
    $(this).before(_html);
}
function createGoodsCustomizeStandardByName2() {
    var obj = $(this);
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
        obj.parents(".list").find(".add-value-btn").attr('data-name', add_name);

        obj.parents(".Father_Title").next().find(".Father_Item .addIpnut").attr('name', 'spec_details[' + add_name + '][]');

        obj.parents(".Father_Title").next().find(".Father_Item .upload-pic").attr('name', 'goods_attr_pic[' + add_name + '][]');

        step.SKU_Table();
    }
}

// 删除自定义规格名
function deleteGoodsCustomizeStandardByName() {
    var obj = $(this);
    layer.confirm('确定要删除规格', {
        time: 0 ,
        btn: ['确定', '取消'],
        yes: function (index) {
            layer.close(index);
            layer.msg('删除成功', {
                skin: 'saveFrame-success',
                icon: 1
            });
            obj.parents(".list").remove();
            step.SKU_Table();
        }
    });
}

// 用户自定义输入规格值
function createGoodsCustomizeStandardByValue() {
    var obj = $(this);
    var fatherItem = $(".Father_Item");
    var add_name = obj.val().trim();
    var add_title = obj.parents('.Father_Item').find('button').attr('data-name');

    if (add_name == "") {
        return false;
    }
    //规格值数组
    var spec_value_arr = [];
    fatherItem.find(".checkedBox .addIpnut").each(function () {
        if ($.trim($(this).val()) != "") {
            spec_value_arr.push($.trim($(this).val()));
        }
    })
    fatherItem.parents("td").find(".next-checkbox-label").each(function () {
        if ($.trim($(this).text()) != "") {
            spec_value_arr.push($.trim($(this).text()));
        }
    })
    //数组是否重复
    spec_value_arr = spec_value_arr.slice().sort();
    for (var i = 0; i < spec_value_arr.length; i++) {
        if (spec_value_arr[i] == spec_value_arr[i + 1]) {
            layer.msg("规格值：" + spec_value_arr[i] + "已经存在，请换一个试试", {icon: 2});
            obj.val('');
            return false;
        }
    }
    var attr_name = $(this).parents("tr").find(".add-value-btn").attr('data-name');
    var _html = '<input style="display: none;margin-top: 4px;min-height: unset;" type="checkbox" checked class="checkbox check_item" value="' + add_name + '" />';

    _html += '<input class="addIpnut" type="text" value="' + add_name + '" name="spec_details[' + attr_name + '][]"/><i class="ace-icon fa fa-trash-o bigger-120 del"></i>';

    obj.parents(".chbox").find('.upload-pic').attr('name', 'goods_attr_pic[' + add_title + '][' + add_name + ']');
    if (attr_name !== '' && add_name !== '') {
        $(this).parents(".checkedBox").html(_html);
        step.SKU_Table();
    }
}

// 添加商家自定义属性值
function createCustomizeAttrsByName() {
    var addAttrNum = 0;
    var _html = '<div class="form-group attribute-wrap"><div class="col-sm-9">' +
        '<input type="text" placeholder="属性名" name="custom_attr_name[]" />' +
        '<input type="text" style="margin-left: 10px;" placeholder="属性值" name="custom_attr_value[]" />' +
        '<i style="cursor:pointer;margin-left: 10px;" class="ace-icon fa fa-trash-o bigger-120 del-btn-attr"></i>' +
        '</div></div>';
    addAttrNum++;
    $(this).before(_html);
}

// 删除商家自定义属性值
function deleteGoodsCustomizeAttrsByValue() {
    var obj = $(this);
    layer.confirm('确定要删除商品类目属性?', {
        time: 0 ,
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
}

// 用户选中属性
function checkGoodsStandardCheckedStatus() {
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

    step.SKU_Table();
}

// 商品分类联动
function getGoodsClassBySelect(obj) {
    var class_id = $(obj).val();
    $(obj).nextAll().remove();
    if (class_id == 0) {
        return false;
    }
    ajaxGetChildGoodsClass(obj,class_id);
}

// 店铺分类联动
function getStoreClassBySelect(obj) {
    var class_id = $(obj).val();
    $(obj).nextAll().remove();
    if (class_id == 0) {
        return false;
    }
    ajaxGetChildStoreClass(obj,class_id);
}

// 异步获取商品分类子分类
function ajaxGetChildGoodsClass(obj,class_id) {
    ajax(config.url.getChildGoodsClass, {class_id: class_id}, function (res) {
        if (res.class.length > 0) {
            var _html = '<select name="class_id[]" required="true" Onchange="getGoodsClassBySelect(this)" style="width: 200px">';
            _html += '<option value="">请选择分类</option>';
            $.each(res.class, function (k, v) {
                _html += '<option value="' + v.id + '">--' + v.name + '--</option>';
            });
            _html += '</select>';
            $(obj).after(_html);
        }

        if (res.specs.length > 0) createGoodsStandardTable(res.specs);

        if (res.attrs.length > 0) createGoodsAttrsTable(res.attrs);

        $('.spec-details').empty();
        $("[name=goods_price]").val('').removeAttr('readonly');
        $("[name=goods_del_price]").val('').removeAttr('readonly');
        $("[name=goods_stock]").val('').removeAttr('readonly');
    })
}

// 异步获取店铺分类子分类
function ajaxGetChildStoreClass(obj,class_id) {
    ajax(config.url.getChildStoreClass, {class_id: class_id}, function (res) {
        if (res.class.length > 0) {
            var _html = ' <select name="store_class_id[]"  Onchange="getStoreClassBySelect(this)" style="width: 200px">';
            _html += '<option value="">请选择分类</option>';
            $.each(res.class, function (k, v) {
                _html += '<option value="' + v.id + '">--' + v.name + '--</option>';
            });
            _html += '</select>';
            $(obj).after(_html);
        }
    })
}

// 生成商品规格表格
function createGoodsStandardTable(data) {
    var i = 0;
    if (data.length > 0) {
        var _html = '<table class="table table-striped table-bordered table-hover">';
        $.each(data, function (k, v) {
            _html += '<!--规格开始-->';
            _html += '<tr>';
            _html += '<th width="90" class="spec">规格名：</th>';
            _html += '<td class="spec Father_Title">';
            _html += '<div class="name">';
            _html += '<label>';
            _html += '<span class="next-checkbox-label">' + v.name + '</span>';
            _html += '</label>';
            _html += '</div>';
            _html += '</td>';
            _html += '</tr>';
            _html += '<tr class="spec-value">';
            _html += '<td style="padding: 9px 15px;vertical-align: middle;">规格值</td>';
            _html += '<td class="Father_Item Father_Item' + i + '">';
            if (v.value.length > 0) {
                $.each(v.value, function (k1, v1) {
                    _html += '<div class="chbox">';
                    _html += '<div class="checkedBox">';
                    _html += '<label>';
                    _html += '<span class="next-checkbox">';
                    _html += '<input style="display: block;margin-top: 4px;min-height: unset;" type="checkbox" class="checkbox check_item" value="' + v1 + '" name="spec_details[' + v.name + '][' + v1 + ']">';
                    _html += '</span>';
                    _html += '<span class="next-checkbox-label">' + v1 + '</span>';
                    _html += '</label>';
                    _html += '</div>';
                    _html += '<span class="pop-img upload_standard_src">';
                    _html += '<input type="file" class="uploadImg" onchange="showUploadImgByStandard(this)">';
                    _html += '<img src="/images/popimg_add.png" style="display:block;max-width: 100px;width:100%;">';
                    _html += '<span class="ace-icon fa fa-trash-o bigger-100 delete_img" onclick="deleteUploadImgByStandard(this);" title="删除">';
                    _html += '</span>';
                    _html += '<input type="hidden" class="upload-pic" name="goods_attr_pic[' + v.name + '][' + v1 + ']">';
                    _html += '</span>';
                    _html += '</div>';
                });
            }
            _html += '<button style="margin-top: 15px;margin-left: 10px;" type="button" class="btn btn-warning btn-xs add-value-btn" data-name="' + v.name + '">添加规格值</button>';
            _html += '</td>';
            _html += '</tr>';
            _html += '<!--规格结束-->';
            i++;
        })
        _html += '</table>';
        _html += '<div class="add-spec"><button type="button" class="btn btn-warning btn-xs add-standard-name-btn">添加规格名</button></div>';
        $(".spec-list").html(_html);
    }
}

// 生成类目属性
function createGoodsAttrsTable(data) {
    if (data.length > 0) {
        var _html = "";
        $.each(data, function (k, v) {
            _html += '<div class="col-sm-6">';
            _html += '<div class="form-group sell-cat-prop-item">';
            _html += '<label class="col-sm-2 control-label no-padding-left">' + v.name + '：</label>';
            _html += '<div class="col-sm-10">';
            switch (v.type) {
                case 1 :
                    if (v.value.length > 0) {
                        $.each(v.value, function (k1, v1) {
                            _html += '<label class="cklabel">';
                            _html += '<span class="next-checkbox">';
                            _html += '<input style="display: block;margin-top: 4px;min-height: unset;" type="radio" class="checkbox check_item"  value="' + v1 + '" name="goods_attr[' + v.id + '][]">';
                            _html += '</span>';
                            _html += '<span class="next-checkbox-label">' + v1 + '</span>';
                            _html += '</label>';
                        });
                    }
                    break;
                case 2 :
                    if (v.value.length > 0) {
                        $.each(v.value, function (k1, v1) {
                            _html += '<label class="cklabel">';
                            _html += '<span class="next-checkbox">';
                            _html += '<input style="display: block;margin-top: 4px;min-height: unset;" type="checkbox" class="checkbox check_item"  value="' + v1 + '" name="goods_attr[' + v.id + '][]">';
                            _html += '</span>';
                            _html += '<span class="next-checkbox-label">' + v1 + '</span>';
                            _html += '</label>';
                        });
                    }
                    break;
                case 3 :
                    _html += '<input type="text" name="goods_attr[' + v.id + '][]" value="">';
                    break;
                case 4 :
                    if (v.value.length > 0) {
                        _html += '<select name="goods_attr[' + v.id + '][]">';
                        _html += '<option value="">请选择</option>';
                        $.each(v.value, function (k1, v1) {
                            _html += '<option value="' + v1 + '">' + v1 + '</option>';

                        });
                        _html += '</select>';
                    }
                    break;
            }
            _html += '</div>';
            _html += '</div>';
            _html += '</div>';
        });
        $(".goods_attr_content").html(_html);
    }
}

// 规格明细
function createGoodsStandardDetails() {
    var val     = $(this).val();
    var item    = $(this).attr('data-item');
    var type    = $(this).attr('data-name');
    var _length = $("[name='" + type + "_hidden\[" + item + "\]']").length;
    if (_length > 0) {
        $("[name='" + type + "_hidden\[" + item + "\]']").val(val);
    } else {
        $(".control-hidden").append('<input name="' + type + '_hidden[' + item + ']"  type="hidden" value="' + val + '">');
    }
    //判断新增价格最小值
    switch (type) {
        case "standard_price" :
        case "standard_del_price" :
            getMinPriceByStandard(type, 1);
            break;
        case "standard_stock" :
            getMinPriceByStandard(type, 2);
            break;
    }
}

//获取价格最小值
function getMinPriceByStandard(standard_price, type) {
    var input = $("." + standard_price);
    var min_price = null;
    var standard_price_arr = [];
    input.each(function (k, v) {
        if ($(this).val().length > 0) {
            standard_price_arr.push($(this).val());
        }
    });
    //判断有没有数据
    if (standard_price_arr.length > 0) {
        switch (type) {
            case 1 :
                min_price = Math.min.apply(null, standard_price_arr);
                break;
            case 2 :
                var goods_stock = 0
                $.each(standard_price_arr, function (k, v) {
                    goods_stock += parseInt(v);
                })
                min_price = goods_stock;
                break;
        }
        standard_price = standard_price.replace(/standard/g, "goods");
        $("[name='" + standard_price + "']").val(min_price);
    }
}

// 去重
function unique(arr) {
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

//获取图片地址
function ajaxGetUploadImgPath(file, type) {
    var form_data = new FormData();
    var temp = new Array();
    form_data.append("GoodsUploadForm["+type+"]", file);
    $.ajax(config.url.uploadGoodsImg + '?sField=' + type,{
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        processData: false,//important
        contentType: false,//important
        data: form_data,
        success: function (res) {
            if (res.code == '0') {
                temp[0] = res.data.absolute_path;
                temp[1] = res.data.relative_path;
            }
        }
    });
    return temp;
}

//上传规格图片
function showUploadImgByStandard(obj) {
    var file    = $(obj).get(0).files[0];
    var arrPath = ajaxGetUploadImgPath(file, 'attr_pic');

    if (!empty(arrPath[0])) {
        $(obj).next('img').attr('src', arrPath[0]).show();
        $(obj).next().next().show();
        $(obj).next().next().next().val(arrPath[1]);
        $(obj).parents(".upload_standard_src").attr('onmouseover', "mouseOverImgShow(this);");
        $(obj).parents(".upload_standard_src").attr('onmouseout', "mouseOutImgHide(this);");
    }
}

//删除规格图片
function deleteUploadImgByStandard(obj) {
    $.ajax(config.url.uploadGoodsImg + '?sField=thumb',{
        type: 'POST',
        dataType: 'json',
        data: {thumb:$(obj).next().val()},
        success: function (res) {
            $(obj).prev().attr('src', "/images/popimg_add.png");
            $(obj).hide();
            $(obj).next().val('');
            $(obj).parents(".upload_standard_src").removeAttr('onmouseover');
            $(obj).parents(".upload_standard_src").removeAttr('onmouseout');
            //置空上传域
            file = $(obj).prev().prev();
            resetUploadFile(file);
        }
    });
}

//删除文本域
function resetUploadFile(obj) {
    var file_img = $(obj);
    file_img.after(file_img.clone().val(""));
    file_img.remove();
}

//鼠标经过图片
function mouseOverImgShow(obj) {
    $(obj).find(".delete_img").show();
}

//鼠标离开图片
function mouseOutImgHide(obj) {
    $(obj).find(".delete_img").hide();
}

//上传商品主图
function uploadGoodsMainImg() {
    var _html       = '',
        _name       = $(this).attr('name'),
        imgLen      = $(this).prop("files").length,
        $fileBox    = $(this).parent(),
        maxLen      = $(this).prop("maxlength"), //获取最多上传文件个数
        haveLen     = $fileBox.siblings(".imgBox").find(".imgList").length, //获取已上传文件个数
        needLen     = maxLen - haveLen;
    if ((maxLen - haveLen) >= imgLen) {
        for (var i = 0; i < imgLen; i++) {
            var imgUrl = ajaxGetUploadImgPath(this.files[i], 'album');
            var _hidden = "";
            if (_name == "goods_gallery_file") {
                _hidden = '<input name="goods_gallery[]" type="hidden" value="' + imgUrl[1] + '" />';
            }
            _html += '<li class="imgList"><img width="100%" src="' + imgUrl[0] + '" />' + _hidden + '<span class="ace-icon fa fa-trash-o bigger-160 imgDel"><i></i></span></li>';
            if (haveLen == 0) {
                $("[name=goods_gallery_hidden]").val(imgUrl[1]);
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
    resetUploadFile($(this));
}

//删除上传商品主图
function deleteUploadGoodsImg() {
    var that    = $(this),
        $imgBox = that.parents(".imgBox"),
        haveLen = $imgBox.find(".imgList").length - 1,
        maxLen  = $imgBox.siblings(".imgFile").find("input").prop("maxlength"),
        path    = that.prev().val(),
        needLen = maxLen - haveLen;

    $.ajax(config.url.uploadGoodsImg + '?sField=album',{
        type: 'POST',
        dataType: 'json',
        data: {album:path},
        success: function (res) {
            that.parents(".imgBox").siblings(".imgFile").show().siblings(".imgPrompt").html('<p class="imgPrompt">共<i>' + haveLen + '</i>张，还能上传<i>' + needLen + '</i>张</p>');
            var goods_gallery_hidden_length = $("[name=goods_gallery_hidden]").length;
            //针对多图上传
            if (haveLen == 0 && goods_gallery_hidden_length > 0) {
                $("[name=goods_gallery_hidden]").val('');
            }
            that.parent().remove();
        }
    });
}