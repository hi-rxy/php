<div id="sales" class="tab-pane">
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">商品规格</label>
        <div class="col-sm-8">
            <div class="table-responsive spec-list">
                <table class="table table-striped table-bordered table-hover">
                    <!--规格开始-->
                    <?php $a = 0;?>
                    <?php if (isset($goods['zong_spec_data']) && $goods['zong_spec_data']) :
                        foreach ($goods['zong_spec_data'] as $key => $item) :?>
                            <?php $spec_key = $key;?>
                            <?php if(isset($goods['spec_data_value'][$spec_key]) && !empty($goods['spec_data_value'][$spec_key])):?>
                                <tr>
                                    <th width="90" class="spec">规格名：</th>
                                    <td class="spec Father_Title">
                                        <div class="name">
                                            <label>
                                                <span class="next-checkbox-label"><?=$spec_key?></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spec-value">
                                    <td style="padding: 9px 15px;vertical-align: middle;">规格值</td>
                                    <td class="Father_Item Father_Item<?=$a;?>">
                                        <?php foreach ($item as $k => $v) :?>
                                            <?php if(isset($goods['spec_data_value'][$spec_key][$k])  && !empty($goods['spec_data_value'][$spec_key][$k])):?>
                                                <div class="chbox">
                                                    <div class="checkedBox">
                                                        <label>
                                                            <span class="next-checkbox"><input style="display: block;margin-top: 4px;min-height: unset;" type="checkbox" class="checkbox check_item" value="<?=$v?>" name="spec_details[<?=$spec_key?>][]" <?php if(isset($goods['goods_spec_data'][$spec_key]) && in_array($v,$goods['goods_spec_data'][$spec_key])):?>checked<?php endif;?>></span>
                                                            <span class="next-checkbox-label"><?=$v?></span>
                                                        </label>
                                                    </div>
                                                    <span class="pop-img upload_standard_src" <?php if(isset($goods['goods_pic_data'][$v]) &&!empty($goods['goods_pic_data'][$v])):?>onmouseover="mouseOverImgShow(this);" onmouseout="mouseOutImgHide(this);"<?php endif;?>>
                                                    <input type="file" class="uploadImg"  onchange="ajaxGetUploadImgPath(this)">
                                                    <img width="100%" src="<?php if(isset($goods['goods_pic_data'][$v]) &&!empty($goods['goods_pic_data'][$v])){echo '/uploads'.$goods['goods_pic_data'][$v];} else {?>/images/popimg_add.png<?php }?>" style="display:block;">
                                                    <span class="ace-icon fa fa-trash-o bigger-100 delete_img" onclick="deleteUploadImgByStandard(this);" title="删除"></span>
                                                    <input type="hidden" class="upload-pic" name="goods_attr_pic[<?=$spec_key?>][<?=$v?>]" value="<?=$goods['goods_pic_data'][$v]?>">
                                                </span>
                                                </div>
                                            <?php else:?>
                                                <div class="chbox">
                                                    <div class="checkedBox">
                                                        <input type="checkbox" style="width:0;float:left;" checked="" class="checkbox check_item" value="<?=$v?>">
                                                        <input type="text" class="addIpnut" value="<?=$v?>" name="spec_details[<?=$spec_key?>][]" style="padding:8px;">
                                                        <i class="ace-icon fa fa-trash-o bigger-120 del"></i>
                                                    </div>
                                                    <span class="pop-img upload_standard_src" <?php if(isset($goods['goods_pic_data'][$v]) &&!empty($goods['goods_pic_data'][$v])):?>onmouseover="mouseOverImgShow(this);" onmouseout="mouseOutImgHide(this);"<?php endif;?>>
                                                    <input type="file" class="uploadImg"  onchange="ajaxGetUploadImgPath(this)">
                                                    <img src="<?php if(isset($goods['goods_pic_data'][$v]) &&!empty($goods['goods_pic_data'][$v])){echo '/uploads'.$goods['goods_pic_data'][$v];} else {?>/images/popimg_add.png<?php }?>" style="display:block;max-width: 100px;width:100%;">
                                                    <span class="ace-icon fa fa-trash-o bigger-100 delete_img" onclick="deleteUploadImgByStandard(this);" title="删除"></span>
                                                    <input type="hidden" class="upload-pic" name="goods_attr_pic[<?=$spec_key?>][<?=$v?>]" value="<?=$goods['goods_pic_data'][$v]?>">
                                                </span>
                                                </div>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                        <button type="button" style="margin-left: 10px;margin-top:15px;" class="btn btn-warning btn-xs add-value-btn" data-name="<?=$spec_key?>">添加规格值</button>
                                    </td>
                                </tr>
                                <?php $a++;?>
                            <?php endif;
                        endforeach;
                    endif; ?>
                    <!--规格结束-->
                </table>
            </div>
            <div class="add-spec">
                <?php $a = 0;?>
                <?php if (isset($goods['zong_spec_data']) && $goods['zong_spec_data']) :
                    foreach ($goods['zong_spec_data'] as $item) :?>
                    <?php $spec_key = $key;?>
                    <?php if(!isset($goods['spec_data_value'][$spec_key])):?>
                        <table class="table table-striped table-bordered table-hover">
                            <tr class="Father_Title">
                                <th width="90" class="spec">规格名</th>
                                <th>
                                    <div class="name">
                                        <input type="text" class="addIpnut" value="<?=$spec_key?>">
                                    </div>
                                    <i class="ace-icon fa fa-trash-o bigger-120 del"></i>
                                </th>
                            </tr>
                            <tr class="spec-value">
                                <td class="spec">规格值</td>
                                <td class="Father_Item Father_Item<?=$a;?>">
                                    <?php foreach ($item as $v) :?>
                                        <div class="chbox">
                                            <div class="checkedBox">
                                                <input style="width:0;float:left;" type="checkbox" checked="" class="checkbox check_item" value="<?=$v?>">
                                                <input type="text" class="addIpnut" value="<?=$v?>" name="spec_details[<?=$spec_key?>][]" style="padding:8px;">
                                                <i class="ace-icon fa fa-trash-o bigger-120 del"></i>
                                            </div>
                                            <span class="pop-img upload_standard_src" <?php if(isset($goods['goods_pic_data'][$v]) && !empty($goods['goods_pic_data'][$v])):?>onmouseover="mouseOverImgShow(this);" onmouseout="mouseOutImgHide(this);"<?php endif;?>>
                                                <input type="file" class="uploadImg"  onchange="ajaxGetUploadImgPath(this)">
                                                <img src="<?php if(isset($goods['goods_pic_data'][$v]) &&!empty($goods['goods_pic_data'][$v])){echo '/uploads'.$goods['goods_pic_data'][$v];} else {?>/images/popimg_add.png<?php }?>" style="display:block;max-width: 100px;width:100%;">
                                                <span class="ace-icon fa fa-trash-o bigger-100 delete_img" onclick="deleteUploadImgByStandard(this);" title="删除"></span>
                                                <input type="hidden" class="upload-pic" name="goods_attr_pic[<?=$spec_key?>][<?=$v?>]" value="<?=$goods['goods_pic_data'][$v]?>">
                                            </span>
                                        </div>
                                    <?php endforeach;?>
                                    <button type="button" style="margin-left: 10px;margin-top:15px;" class="btn btn-warning btn-xs add-value-btn" data-name="<?=$spec_key?>">添加规格值</button>
                                </td>
                            </tr>
                        </table>
                            <?php $a++;?>
                        <?php endif;
                    endforeach;
                endif; ?>
                <!--<button type="button" class="btn btn-warning btn-xs add-standard-name-btn">添加规格名</button>-->
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">生成规格明细</label>
        <div class="col-sm-8">
            <div class="spec-details"></div>
            <div class="control-hidden">
                <?php if (isset($goods['standard_data']) && $goods['standard_data']) :
                foreach ($goods['standard_data'] as $item) :?>
                    <input type="hidden" class="old_standard" name="standard_price_hidden[<?=$item['name']?>]" value="<?=$item['price']?>" />
                    <input type="hidden" class="old_standard" name="standard_del_price_hidden[<?=$item['name']?>]" value="<?=$item['market_price']?>" />
                    <input type="hidden" class="old_standard" name="standard_stock_hidden[<?=$item['name']?>]" value="<?=$item['stock']?>" />
                    <input type="hidden" class="old_standard" name="standard_weight_hidden[<?=$item['name']?>]" value="<?=$item['weight']?>" />
                    <input type="hidden" class="old_standard" name="standard_volume_hidden[<?=$item['name']?>]" value="<?=$item['volume']?>" />
                    <input type="hidden" class="old_standard" name="standard_sn_hidden[<?=$item['name']?>]" value="<?=$item['sn']?>" />
                    <input type="hidden" class="old_standard" name="standard_id_hidden[<?=$item['name']?>]" value="<?=$item['id']?>" />
                <?php endforeach;
                endif; ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">价格</label>
        <div class="col-sm-8">
            <input type="text" name="goods_price" class="col-xs-12 col-sm-10" value="<?=$model->price?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">划线价</label>
        <div class="col-sm-8">
            <input type="text" name="goods_del_price" class="col-xs-12 col-sm-10" value="<?=$model->market_price?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">库存</label>
        <div class="col-sm-8">
            <input type="text" name="goods_stock" class="col-xs-12 col-sm-10" value="<?=$model->stock?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">类目属性</label>
        <div class="col-sm-8">
            <span class="help-block">错误填写宝贝属性，可能会引起宝贝下架或搜索流量减少，影响您的正常销售，请认真准确填写！</span>
            <div class="goods_attr_content">
                <?php if (isset($goods['attr_data']) && $goods['attr_data']) :
                foreach ($goods['attr_data'] as $item) :?>
                    <div class="col-sm-6">
                        <div class="form-group sell-cat-prop-item">
                            <label class="col-sm-2 control-label no-padding-left"><?=$item['name']?>：</label>
                            <div class="col-sm-10">
                                <?php if($item['type'] == 1):?>
                                    <?php foreach ($item['value'] as $v) :?>
                                        <label class="cklabel">
                                            <span class="next-checkbox">
                                                <input type="radio" class="checkbox check_item"  value="<?=$v?>" <?php if(!empty($item['select_value']) && in_array($v,$item['select_value'])):?>checked="checked"<?php endif;?> name="goods_attr[<?=$item['id']?>][]">
                                            </span>
                                            <span class="next-checkbox-label"><?=$v?></span>
                                        </label>
                                    <?php endforeach;?>
                                <?php elseif($item['type'] == 2):?>
                                    <?php foreach ($item['value'] as $v) :?>
                                        <label class="cklabel">
                                            <span class="next-checkbox">
                                                <input type="checkbox" class="checkbox check_item"  value="<?=$v?>" <?php if(!empty($item['select_value']) && in_array($v,$item['select_value'])):?>checked="checked"<?php endif;?> name="goods_attr[<?=$item['id']?>][]">
                                            </span>
                                            <span class="next-checkbox-label"><?=$v?></span>
                                        </label>
                                    <?php endforeach;?>
                                <?php elseif($item['type'] == 3):?>
                                    <input type="text" class="layui-input" name="goods_attr[<?=$item['id']?>][]" value="<?php if(!empty($item['select_value'])):?><?=$item['select_value'][0]?><?php endif;?>">
                                <?php elseif($item['type'] == 4):?>
                                    <select name="goods_attr[<?=$item['id']?>][]">
                                        <option value="">请选择</option>
                                        <?php foreach ($item['value'] as $v) :?>
                                            <option value="<?=$v?>" <?php if(!empty($item['select_value']) && in_array($v,$item['select_value'])):?>selected="selected"<?php endif;?>><?=$v?></option>
                                        <?php endforeach;?>
                                    </select>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endforeach;
                endif; ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left">自定义属性</label>
        <div class="col-sm-8">
            <span class="help-block">店家自定义属性</span>
            <?php if (isset($goods['goods_attr_custom_data']) && $goods['goods_attr_custom_data']) :
            foreach ($goods['goods_attr_custom_data'] as $item) :?>
                <div class="form-group attribute-wrap">
                    <div class="col-sm-9">
                        <input type="text" placeholder="属性名" name="custom_attr_name[]" value="<?=$item['name']?>">
                        <input type="text" style="margin-left: 10px;" placeholder="属性值" name="custom_attr_value[]" value="<?=$item['value']?>">
                        <i style="cursor:pointer;margin-left: 10px;" class="ace-icon fa fa-trash-o bigger-120 del-btn-attr"></i>
                    </div>
                </div>
            <?php endforeach;
            endif;?>
            <button type="button" class="btn btn-warning btn-xs add-customize-property-btn" data-name="">添加自定义属性</button>
        </div>
    </div>
</div>