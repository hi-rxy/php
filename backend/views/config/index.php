<?php

use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '配置参数管理';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            url: {
                updateSingleUrl: "<?=Url::toRoute(['update-single'])?>",
            },
            data: {
                jsonConfigStatus: <?=Json::encode($arrStatus)?>,
                jsonConfigColor: <?=Json::encode($arrColor)?>,
                jsonFormTypes: <?=Json::encode($arrFormTypes)?>,
                jsonGroupId: <?=Yii::$app->request->get('id', 0)?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/config/_index.js?v=<?= time() ?>"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            number: false,
            buttons: buttons(),
            params: {
                groupId: config.data.jsonGroupId
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
                        title: "组id",
                        data: "group_id",
                        isHide: true,
                        edit: {type: "hidden"},
                        sortable: false
                    },
                    {
                        title: "参数英文名称",
                        data: "name",
                        edit: {type: "text", required: true},
                        sortable: false
                    },
                    {
                        title: "参数中文名称",
                        data: "title",
                        edit: {type: "text", required: true, rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "提示信息",
                        data: "message",
                        isHide: true,
                        edit: {type: "text", required: true, rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "显示类型",
                        isHide: true,
                        data: "type",
                        value: config.data.jsonFormTypes,
                        edit: {type: "radio", default: 0},
                        sortable: false,
                        createdCell: getTypes
                    },
                    {
                        title: "参数值",
                        isHide: true,
                        data: "value",
                        edit: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "配置参数",
                        isHide: true,
                        data: "info",
                        edit: {type: "text", rangeLength: "[2, 200]"},
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
                        edit: {type: "text", value: 1, number: true},
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


        $.extend(m, {
            afterShow: function (data) {
                $('input[name="group_id"]').val(config.data.jsonGroupId);
                return true;
            }
        });

        $(function () {
            m.init();

            $(document).on('click', '.status', _update_single_value);
            $(document).on('change', '.sorts', _update_single_value);
        });
    </script>
<?php $this->endBlock(); ?>