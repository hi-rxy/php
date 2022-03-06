<?php

use jinxing\admin\widgets\MeTable;
// 定义标题和面包屑信息
$this->title = '订单管理';
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
						title: "订单id",
						data: "id",
						edit: {type: "hidden"},
						sortable: false
					},
					{
						title: "主订单id",
						data: "main_order_id",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "订单编号",
						data: "order_sn",
						edit: {type: "text", required: true, rangeLength: "[2, 200]"},
						sortable: false
					},
					{
						title: "用户id",
						data: "user_id",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "店铺id",
						data: "store_id",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "订单来源0表示pc1表示wap",
						data: "source",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "支付方式1表示支付宝2表示微信",
						data: "pay_type",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "支付宝或者微信订单号",
						data: "trade_no",
						edit: {type: "text", rangeLength: "[2, 200]"},
						sortable: false
					},
					{
						title: "记录自己生成微信订单号",
						data: "out_trade_no",
						edit: {type: "text", rangeLength: "[2, 200]"},
						sortable: false
					},
					{
						title: "退款时原来的订单状态",
						data: "old_order_status",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "物流费",
						data: "shipping_price",
						edit: {type: "text"},
						sortable: false
					},
					{
						title: "订单总价",
						data: "pay_amount",
						edit: {type: "text", required: true},
						sortable: false
					},
					{
						title: "实付订单",
						data: "real_amount",
						edit: {type: "text", required: true},
						sortable: false
					},
					{
						title: "订单创建时间",
						data: "created_time",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "订单付款时间",
						data: "pay_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单发货时间",
						data: "send_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单确认收货时间",
						data: "take_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单评价时间",
						data: "comment_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单取消时间",
						data: "cancel_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "取消原因",
						data: "cancel_reason",
						edit: {type: "text"},
						sortable: false
					},
					{
						title: "订单关闭时间",
						data: "close_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单删除时间",
						data: "delete_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单处理中的时间",
						data: "refund_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "超时未付款时间",
						data: "over_pay_time",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "订单方式0表示未付款1表示已付款2表示发货3确认收货4已评价5已取消6订单关闭7已删除8订单处理中9订单付款超时",
						data: "order_status",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "付款状态0表示未付款1表示已付款",
						data: "is_pay_status",
						edit: {type: "text", number: true},
						sortable: false
					},
					{
						title: "0表示未评价1已评价",
						data: "is_comment",
						edit: {type: "text", required: true, number: true},
						sortable: false
					},
					{
						title: "订单ip",
						data: "order_ip",
						edit: {type: "text", rangeLength: "[2, 100]"},
						sortable: false
					},
					{
						title: "订单留言",
						data: "order_post_script",
						edit: {type: "text", rangeLength: "[2, 200]"},
						sortable: false
					},
					{
						title: "收货人姓名",
						data: "consignee_name",
						edit: {type: "text", required: true, rangeLength: "[2, 20]"},
						sortable: false
					},
					{
						title: "收货人电话",
						data: "consignee_mobile",
						edit: {type: "text", required: true, rangeLength: "[2, 20]"},
						sortable: false
					},
					{
						title: "收货人省份",
						data: "consignee_province",
						edit: {type: "text", required: true, rangeLength: "[2, 50]"},
						sortable: false
					},
					{
						title: "收货人城市",
						data: "consignee_city",
						edit: {type: "text", required: true, rangeLength: "[2, 50]"},
						sortable: false
					},
					{
						title: "收货人地区",
						data: "consignee_district",
						edit: {type: "text", required: true, rangeLength: "[2, 50]"},
						sortable: false
					},
					{
						title: "收货详细地址",
						data: "consignee_address",
						edit: {type: "text", required: true, rangeLength: "[2, 200]"},
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