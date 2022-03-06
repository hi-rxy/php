<div id="base" class="tab-pane in active">
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">商品分类</label>
        <div class="col-sm-8">
            <?php if (!empty($goods['class_data'])) {foreach ($goods['class_data'] as $item) { ?>
            <select name="class_id[]" required="true" Onchange="getGoodsClassBySelect(this)" style="width: 200px">
                <option value="0">请选择分类</option>
                <?php foreach ($item as $v) { ?>
                    <option <?php if (in_array($v['id'],$goods['classIds'])) {?>selected<?php }?> value="<?= $v['id'] ?>">--<?= $v['name'] ?>--</option>
                <?php } ?>
            </select>
            <?php }} ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">店铺分类</label>
        <div class="col-sm-8">
            <?php if (!empty($goods['store_class_data'])) {foreach ($goods['store_class_data'] as $value) { ?>
            <select name="store_class_id[]" required="true" Onchange="getStoreClassBySelect(this)" style="width: 200px">
                <option value="0">请选择分类</option>
                <?php foreach ($value as $v) { ?>
                    <option <?php if (in_array($v['id'],$goods['storeClassIds'])) {?>selected<?php }?> value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                <?php } ?>
            </select>
            <?php }} ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">标题</label>
        <div class="col-sm-8">
            <input type="text" required="true" rangelength="[2, 100]" name="title" class="col-xs-12 col-sm-10" value="<?=$model->isNewRecord ? '' : $model->name?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-left required">概述</label>
        <div class="col-sm-8">
            <textarea class="col-xs-12 col-sm-10" name="summary" id="summary"><?=$model->isNewRecord ? '' : $model->keywords?></textarea>
        </div>
    </div>
    <div class="clearfix"></div>
</div>