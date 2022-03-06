<?php

use jinxing\admin\widgets\MeTable;
use yii\helpers\Url;
use yii\helpers\Json;

// 定义标题和面包屑信息
$this->title = '商品类型管理';
?>
<?= MeTable::widget(); ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?= $this->title?>",
            url: {
                attrUrl: "<?=Url::toRoute(['attrs/index'])?>",
                updateSingleUrl: "<?=Url::toRoute(['update-single'])?>",
            },
            data: {
                jsonTypeStatus: <?=Json::encode($arrGoodsTypeStatus)?>,
                jsonTypeStatusColor: <?=Json::encode($arrGoodsTypeColor)?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/goods-type/_index.js?v=<?= time() ?>"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            number: false,
            buttons: buttons(),
            operations: {
                width: '200px',
                buttons: operationBtn()
            },
            table: {
                columns: [
                    {
                        title: "编号",
                        data: "id",
                        isHide: true,
                        edit: {type: "hidden"},
                        sortable: false
                    },
                    {
                        title: "类型名称",
                        data: "name",
                        search: {type: "text"},
                        edit: {type: "text", required: true, rangeLength: "[2, 32]"},
                        sortable: false
                    },
                    {
                        title: "类型属性",
                        data: "attr_value",
                        sortable: false
                    },
                    {
                        title: "状态",
                        data: "status",
                        value: config.data.jsonTypeStatus,
                        search: {type: "select"},
                        edit: {type: "radio", default: 1},
                        sortable: false,
                        createdCell: getTypeStatusStr
                    }
                ]
            }
        });

        $(function () {
            m.init();

            $(document).on('click', '.status', _update_single_value);
            $(document).on('click', '.me-table-type-attrs-show-table', openFrame);
        });
    </script>
<?php $this->endBlock(); ?>