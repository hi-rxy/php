<table class="table table-bordered table-striped">
    <thead class="thin-border-bottom">
    <tr>
        <th>参数名称</th>
        <th>参数类型</th>
        <th>显示方式</th>
        <th>参数值</th>
        <th>参数单位</th>
        <th>是否参与筛选</th>
        <th width="50">排序</th>
        <th><button type="button" class="btn btn-xs btn-primary add-attrs">添加</button></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $item) : ?>
        <tr>
            <td>
                <input type="text" value="<?= $item['name'] ?>" placeholder="请输入参数名称" name="attrs[name][]"
                       required>
                <input type="hidden" value="<?= $item['id'] ?>" name="attrs[attr_id][]">
            </td>
            <td>
                <select name="attrs[style][]">
                    <option <?php if ($item['style'] == 0): ?>selected<?php endif; ?> value="0">参数</option>
                    <option <?php if ($item['style'] == 1): ?>selected<?php endif; ?> value="1">规格</option>
                </select>
            </td>
            <td>
                <select name="attrs[type][]">
                    <option <?php if ($item['type'] == 1): ?>selected<?php endif; ?> value="1">单选框</option>
                    <option <?php if ($item['type'] == 2): ?>selected<?php endif; ?> value="2">复选框</option>
                    <option <?php if ($item['type'] == 3): ?>selected<?php endif; ?> value="3">输入框</option>
                    <option <?php if ($item['type'] == 4): ?>selected<?php endif; ?> value="4">下拉框</option>
                </select>
            </td>
            <td><input type="text" name="attrs[value][]" placeholder="请输入参数值" required value="<?= $item['value'] ?>"></td>
            <td><input type="text" name="attrs[unit][]" value="<?= $item['unit'] ?>" placeholder="请输入参数单位"></td>
            <td><input type="checkbox" name="attrs[search][]" value="1" <?php if ($item['search']) : ?>checked<?php endif; ?>></td>
            <td><input type="text" style="width: 50px" name="attrs[sort][]" placeholder="请输入排序" value="<?= $item['sort'] ?>"></td>
            <td><i style="padding-top: 5px;cursor: pointer;padding-left: 10px;" class="ace-icon fa fa-trash-o bigger-150 delete-row"></i></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>