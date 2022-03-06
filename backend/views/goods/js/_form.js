$(function () {
    // 提交数据
    function _save(evt) {
        evt.preventDefault();
        var $fm = $(this);
        if ($fm.validate().form()) {
            ajax($fm.attr('action'), $fm.serialize(), _success);
        }
    }

    // 异步回调成功方法
    function _success(res) {
        var index = layer.alert('保存成功', {
            skin: 'layui-layer-lan'
            ,closeBtn: 0
            ,anim: 0 //动画类型
        },function () {
            // 关闭 layer_alert 弹窗
            layer.close(index);
            // 执行加载层
            layer.load(0, {shade: false});

            setTimeout(function () {
                // 刷新父级页面数据表格
                parent.$("#show-table").dataTable().fnDraw(false);
                // 关闭所有层
                parent.layer.closeAll();
            },500)
        });
    }

    //相册图拖动排序
    $(".ui-sortable").sortable({
        cursor: "move",
        items: "li", //只是li可以拖动
        opacity: 0.6, //拖动时，透明度为0.6
        revert: true, //释放时，增加动画
    });
    // 编辑器
    /*KindEditor.ready(function (K) {
        window.editor = K.create('#editor', {
            width: '100%',
            height: '300px',
            formatUploadUrl: false,
            urlType: 'domain',
            afterBlur: function () {
                this.sync();
            }
        });
    });*/

    var button = $('#save');

    // 异步提交
    button.submit(_save);

    $(".imgFile input").each(function () {
        var $fileBox    = $(this).parent(),
            maxLen      = $(this).prop("maxlength"), //获取最多上传文件个数
            haveLen     = $fileBox.siblings(".imgBox").find(".imgList").length, //获取已上传文件个数
            needLen     = maxLen - haveLen;
        if (needLen === 0) {
            $fileBox.hide();
        }
        $fileBox.siblings(".imgPrompt").html('<p class="imgPrompt">共<i>' + haveLen + '</i>张，还能上传<i>' + needLen + '</i>张</p>');
    });

    // 添加规格值节点
    $(document).on("click", ".add-value-btn", createGoodsStandardByName);
    // 删除规格值节点
    $(document).on("click", ".checkedBox .del", deleteGoodsStandardByValue);
    // 用户自定义输入规格值
    $(document).on("blur", ".checkedBox .addIpnut", createGoodsCustomizeStandardByValue);
    // 用户选中属性
    $(document).on('change', '.spec-list label', checkGoodsStandardCheckedStatus);
    // 规格明细
    $(document).on('keyup', '.spec-details .spec_item', createGoodsStandardDetails);
    // 添加自定义规格名
    $(document).on("click", ".add-spec .add-standard-name-btn", createGoodsCustomizeStandardByName);
    $(document).on("blur", ".name .addIpnut", createGoodsCustomizeStandardByName2);
    // 删除自定义规格名
    $(document).on("click", ".Father_Title .del", deleteGoodsCustomizeStandardByName);
    // 添加商家自定义属性值
    $(document).on("click", ".add-customize-property-btn", createCustomizeAttrsByName);
    // 删除商家自定义属性值
    $(document).on("click", ".attribute-wrap .del-btn-attr", deleteGoodsCustomizeAttrsByValue);
    $(document).on("change", ".imgFile input", uploadGoodsMainImg)
    $(document).on("click", '.imgDel', deleteUploadGoodsImg);
});