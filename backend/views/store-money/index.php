<?php

use jinxing\admin\widgets\MeTable;
// 定义标题和面包屑信息
$this->title = '店铺金额';
?>
<?=MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var m = meTables({
        title: "<?=$this->title?>",
        number: false,
        
        table: {
            columns: [
                
				{
				title: "id",
				data: "id",
				edit: {type: "hidden"},
				sortable: false
				},
				{
				title: "订单id",
				data: "order_id",
				edit: {type: "text", number: true},
				sortable: false
				},
				{
				title: "订单编号",
				data: "order_sn",
				edit: {type: "text", rangeLength: "[2, 50]"},
				sortable: false
				},
				{
				title: "店铺id",
				data: "store_id",
				edit: {type: "text", required: true, number: true},
				sortable: false
				},
				{
				title: "金额",
				data: "store_money",
				edit: {type: "text"},
				sortable: false
				},
				{
				title: "交易类型1收入2提现",
				data: "money_type",
				edit: {type: "text", number: true},
				sortable: false
				},
				{
				title: "简短描述",
				data: "money_remark",
				edit: {type: "text", rangeLength: "[2, 120]"},
				sortable: false
				},
				{
				title: "处理状态1成功2待处理3拒绝",
				data: "money_status",
				edit: {type: "text", number: true},
				sortable: false
				},
				{
				title: "拒绝原因",
				data: "money_reason",
				edit: {type: "text", rangeLength: "[2, 120]"},
				sortable: false
				},
				{
				title: "添加时间",
				data: "created_time",
				edit: {type: "text", number: true},
				sortable: false
				}
            ]       
        }
    });
    
    /*$.extend(m, {
        // 显示的前置和后置操作
        beforeShow: function (data) {
            return true;
        },
        afterShow: function (data) {
            return true;
        },

        // 编辑的前置和后置操作
        beforeSave: function (data) {
            return true;
        },
        afterSave: function (data) {
            return true;
        }
    });*/

     $(function(){
         m.init();
     });
</script>
<?php $this->endBlock(); ?>