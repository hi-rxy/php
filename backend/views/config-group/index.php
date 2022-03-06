<?php

use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '配置组';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            url: {
                configUrl: "<?=Url::toRoute(['config/index'])?>",
                updateSingleUrl: "<?=Url::toRoute(['update-single'])?>",
            },
            data: {
                jsonConfigStatus: <?=Json::encode($arrStatus)?>,
                jsonConfigColor: <?=Json::encode($arrColor)?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/config-group/_index.js?v=<?= time() ?>"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            number: false,
            buttons: buttons(),
            operations: {
                width: 'auto',
                buttons: operationBtn()
            },
            table: {
                columns: [
                    {
                        title: "id",
                        data: "id",
                        isHide: true,
                        edit: {type: "hidden"},
                        sortable: false
                    },
                    {
                        title: "组名(英文)",
                        data: "name",
                        edit: {type: "text", required: true},
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "组名(中文)",
                        data: "title",
                        edit: {type: "text", required: true, rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "状态",
                        data: "status",
                        value: config.data.jsonConfigStatus,
                        edit: {type: "radio", default: 1},
                        sortable: false,
                        createdCell: getStatusStr
                    },
                    {
                        title: "排序",
                        data: "sort",
                        edit: {type: "text", required: true, number: true, value: 1},
                        sortable: false,
                        createdCell: updateSortsValue
                    },
                    {
                        title: "添加时间",
                        data: "created_at",
                        sortable: false,
                        isHide: true,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "最后更新时间",
                        data: "updated_at",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    }
                ]
            }
        });

        $(function () {
            m.init();

            $(document).on('click', '.status', _update_single_value);
            $(document).on('change', '.sorts', _update_single_value);
            $(document).on('click', '.me-table-params-show-table', openFrame);
        });
    </script>
<?php $this->endBlock(); ?>