<?php
use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '商品管理';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?= $this->title?>",
            url: {
                postCreateUrl: "<?= Url::toRoute(['create'])?>",
                postUpdateUrl: "<?= Url::toRoute(['update'])?>",
                goodsAuditUrl: "<?= Url::toRoute(['audit'])?>",
                goodsViewUrl: "<?= Url::toRoute(['view'])?>",
            },
            data: {
                jsonGoodsStatus: <?=Json::encode($arrGoodsStatus)?>,
                jsonGoodsColor: <?=Json::encode($arrGoodsColor)?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/goods/_index.js?v=<?= time() ?>"></script>
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            number: false,
            buttons: buttons(),
            operations: {
                buttons: operationBtn()
            },
            table: {
                columns: [
                    {
                        title: "商品编号",
                        data: "id",
                        isHide: true,
                        edit: {type: "hidden"},
                        sortable: false
                    },
                    {
                        title: "名称",
                        data: "name",
                        edit: {type: "text", required: true, rangeLength: "[2, 200]"},
                        search: {type: "text"},
                        sortable: false,
                        createdCell: showGoodsView
                    },
                    {
                        title: "主图",
                        data: "thumb",
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        sortable: false,
                        createdCell: showGoodsThumb
                    },
                    {
                        title: "所属店铺",
                        data: "store_id",
                        edit: {type: "text", required: true, number: true},
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "店铺分类",
                        data: "store_class_id",
                        edit: {type: "text", required: true, number: true},
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "商品分类",
                        data: "class_id",
                        edit: {type: "text", required: true, number: true},
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "关键词",
                        data: "keywords",
                        isHide: true,
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        //search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "单价",
                        data: "price",
                        edit: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "最低价",
                        data: "min_price",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "最高价",
                        data: "max_price",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "市场价",
                        data: "market_price",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "市场最低价",
                        data: "market_min_price",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "市场最高价",
                        data: "market_max_price",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "库存",
                        data: "stock",
                        edit: {type: "text", number: true},
                        sortable: false
                    },
                    {
                        title: "点击数",
                        data: "hits",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "收藏数",
                        data: "collect",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "总销量",
                        data: "sales_volume",
                        sortable: false
                    },
                    {
                        title: "评论数",
                        data: "comment_volume",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "排序",
                        data: "sort",
                        isHide: true,
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "状态",
                        data: "status",
                        sortable: false,
                        search: {type: "select"},
                        value: config.data.jsonGoodsStatus,
                        createdCell: getGoodsStatusStr
                    },
                    {
                        title: "审核意见",
                        data: "verify_idea",
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "添加时间",
                        data: "created_at",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "更新时间",
                        data: "updated_at",
                        isHide: true,
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "开始时间",
                        data: null,
                        search: {
                            type: "text",
                            name: "start_time",
                            class: "Wdate",
                            value: "",
                            onclick: "WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})",
                            style: "height: 20px;background: #fff url(/js/My97DatePicker/skin/datePicker.gif) no-repeat right;"
                        },
                        isHide: true,
                        view: false,
                        sortable: false
                    },
                    {
                        title: "结束时间",
                        data: null,
                        search: {
                            type: "text",
                            name: "end_time",
                            class: "Wdate",
                            value: "",
                            onclick: "WdatePicker({el:this,dateFmt:'yyyy-MM-dd'})",
                            style: "height: 20px;background: #fff url(/js/My97DatePicker/skin/datePicker.gif) no-repeat right;"
                        },
                        isHide: true,
                        view: false,
                        sortable: false
                    }
                ]
            }
        });

        $.extend(m, {
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
            },
            createGoods : create,
            allAllow : allAllow,
            allDeny : allDeny
        });

        $(function () {
            m.init();

            $(document).on('click', '.me-table-update-goods-show-table', update);
            $(document).on('click', '.me-table-audit-goods-show-table', audit);
            $(document).on('click', '#show-table .name', details);
        });
    </script>
<?php $this->endBlock(); ?>