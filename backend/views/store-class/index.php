<?php

use yii\helpers\Json;
use yii\helpers\Url;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = implode('>',$classifyName);
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            url: {
                updateSingleUrl: "<?=Url::toRoute(['update-single'])?>",
                getParentsUrl: "<?=Url::toRoute(['get-parents-by-store-id'])?>",
            },
            data: {
                jsonStoreClassStatus: <?= Json::encode($arrStatus)?>,
                jsonStoreClassStatusColor: <?= Json::encode($arrStatusColor)?>,
                jsonStoreClassNavStatus: <?= Json::encode($arrNavStatus)?>,
                jsonStoreClassNavStatusColor: <?= Json::encode($arrNavStatusColor)?>,
                jsonStore: <?= Json::encode($arrStore)?>,
                jsonStoreClassParents: <?= Json::encode($arrStoreClassParents)?>,
                jsonParams: <?= Json::encode(Yii::$app->request->get())?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/store-class/_index.js?v=<?= time() ?>"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            number: false,
            params: config.data.jsonParams,
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
                        title: "店铺",
                        data: "store_id",
                        value: config.data.jsonStore,
                        search: {type: "select", required: true, defaultSelect: 0},
                        edit: {type: "select"},
                        sortable: false
                    },
                    {
                        title: "名称",
                        data: "name",
                        search: {type:"text"},
                        edit: {type: "text", required: true, rangeLength: "[2, 100]"},
                        sortable: false
                    },
                    {
                        title: "标题",
                        data: "title",
                        isHide: true,
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "关键词",
                        data: "keywords",
                        isHide: true,
                        edit: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "描述",
                        data: "desc",
                        isHide: true,
                        edit: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "所属父级",
                        data: "pid",
                        value: config.data.jsonStoreClassParents,
                        edit: {type: "select", required: true},
                        sortable: false
                    },
                    {
                        title: "排序",
                        data: "sort",
                        edit: {type: "text", required: true, number: true, value: 1},
                        sortable: false,
                        createdCell: updateSortsValue
                    },
                    {
                        title: "状态",
                        data: "status",
                        value: config.data.jsonStoreClassStatus,
                        edit: {type: "radio", required: true, default: 1},
                        sortable: false,
                        createdCell: storeClassStatus
                    },
                    {
                        title: "是否显示",
                        data: "is_nav",
                        value: config.data.jsonStoreClassNavStatus,
                        edit: {type: "radio", required: true, default: 1},
                        sortable: false,
                        createdCell: storeClassIsNav
                    },
                    {
                        title: "添加时间",
                        data: "created_at",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "添加者",
                        data: "create_user_id",
                        sortable: false
                    }
                ]
            }
        });

        $.extend(m, {
            afterShow: function (data) {
                if (this.action == 'update') {
                    $('select[name="store_id"] option[value="' + data.storeId + '"]').prop("selected", true);
                    if (!empty(data.parentsId)) {
                        AutoGetParentsByStoreId(data.storeId,data.parentsId);
                    } else {
                        $('select[name="pid"] option[value="0"]').prop("selected", true);
                    }
                }

                return true;
            }
        });


        $(function () {
            m.init();

            $(document).on('change', '.sorts', _update_single_value);
            $(document).on('click', '.is_nav', _update_single_value);
            $(document).on('click', '.status', _update_single_value);
            $(document).on('change','select[name="store_id"]',_getParents);
        });
    </script>
<?php $this->endBlock(); ?>