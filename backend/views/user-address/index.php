<?php

use jinxing\admin\widgets\MeTable;
// 定义标题和面包屑信息
$this->title = '收货地址';
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
						title: "用户id",
						data: "user_id",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "收货人姓名",
						data: "consignee_username",
						edit: {type: "text", required: true, rangeLength: "[2, 50]"},
						sortable: false
					},
					{
						title: "省份",
						data: "consignee_province",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "城市",
						data: "consignee_city",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "地区",
						data: "consignee_district",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "收货详细地址",
						data: "consignee_address",
						edit: {type: "text", required: true, rangeLength: "[2, 200]"},
						sortable: false
					},
					{
						title: "手机号码",
						data: "consignee_mobile",
						edit: {type: "text", required: true, rangeLength: "[2, 20]"},
						sortable: false
					},
					{
						title: "是否设置为默认1表示是0表示否",
						data: "is_default",
						edit: {type: "text", required: true, number: true},
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