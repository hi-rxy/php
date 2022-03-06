<?php

use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '主订单管理';
?>
<?= MeTable::widget() ?>
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
                        title: "订单编号",
                        data: "order_sn",
                        edit: {type: "text", required: true, rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "订单号",
                        data: "out_trade_no",
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "用户id",
                        data: "user_id",
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "支付方式1表示支付宝2表示微信",
                        data: "pay_type",
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "付款方式0表示未付款1表示已付款",
                        data: "order_status",
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "订单总价",
                        data: "order_price",
                        edit: {type: "text", required: true},
                        sortable: false
                    },
                    {
                        title: "支付宝或者微信订单号",
                        data: "trade_no",
                        edit: {type: "text", rangeLength: "[2, 200]"},
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

        $(function () {
            m.init();
        });
    </script>
<?php $this->endBlock(); ?>