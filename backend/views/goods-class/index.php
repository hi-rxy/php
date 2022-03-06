<?php

use jinxing\admin\widgets\MeTable;
use yii\helpers\Json;
use yii\helpers\Url;

$request = Yii::$app->request;

// 定义标题和面包屑信息
$this->title = implode('>',$classifyName);
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?= $this->title?>",
            url: {
                childrenUrl: "<?=Url::toRoute(['children'])?>",
                updateSingleUrl: "<?=Url::toRoute(['update-single'])?>",
            },
            data: {
                jsonGoodsClass: <?=Json::encode($goodsClass)?>,
                jsonGoodsClassStatusColor: <?=Json::encode($arrStatusColor)?>,
                jsonGoodsClassStatus: <?=Json::encode($arrStatus)?>,
                jsonGoodsClassNavStatus: <?=Json::encode($arrNavStatus)?>,
                jsonGoodsType: <?=Json::encode($goodsType)?>,
                jsonParams: <?= Json::encode($request->get())?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/goods-class/_index.js?v=<?= time() ?>"></script>
    <script type="text/javascript">
        $.extend(MeTables, {
            selectOptionsCreate: function (params) {
                return '<select ' + this.handleParams(params) + '><option value="0">顶级分类</option><?=$options?></select>';
            },
            selectOptionsSearchMiddleCreate: function (params) {
                delete params.type;
                params.id = "search-" + params.name;
                return '<label for="' + params.id + '"> ' + params.title + ': <select ' + this.handleParams(params) + '>' +
                    '<option value="ALL">请选择</option>' +
                    '<option value="0">顶级分类</option>' +
                    '<?=$options?>' +
                    '</select></label>';
            }
        });

        var m = meTables({
            title: config.title,
            number: false,
            buttons: buttons(),
            params: config.data.jsonParams,
            operations: {
                width: '200px',
                buttons: operationBtn()
            },
            table: {
                columns: [
                    {
                        title: "编号",
                        data: "id",
                        edit: {type: "hidden"},
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "所属分类",
                        data: "pid",
                        edit: {type: "selectOptions", number: 1, id: "select-options"},
                        search: {type: "selectOptions"},
                        sortable: true,
                        isHide: true,
                        createdCell: parentStatus
                    },
                    {
                        title: "分类名称",
                        data: "name",
                        edit: {type: "text", required: true, rangeLength: "[2, 100]"},
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "分类关键词",
                        data: "keywords",
                        isHide: true,
                        edit: {type: "textarea", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "分类描述",
                        data: "desc",
                        isHide: true,
                        edit: {type: "textarea", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "商品类型",
                        data: "type_name",
                        //isHide: true,
                        value: config.data.jsonGoodsType,
                        edit: {type: "select", name: "type_id"},
                        sortable: false
                    },
                    {
                        title: "跳转地址",
                        data: "url",
                        isHide: true,
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "状态",
                        data: "status",
                        value: config.data.jsonGoodsClassStatus,
                        edit: {type: "radio", required: true, number: true, default: 1},
                        sortable: false,
                        createdCell: getGoodsClassStatusStr
                    },
                    {
                        title: "是否显示",
                        data: "is_nav",
                        value: config.data.jsonGoodsClassNavStatus,
                        edit: {type: "radio", required: true, number: true, default: 1},
                        sortable: false,
                        createdCell: getGoodsNavStatusStr
                    },
                    {
                        title: "排序",
                        data: "sort",
                        edit: {type: "text", required: true, number: true,value:1},
                        sortable: false,
                        createdCell: updateSortsValue
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
                if (this.action == 'update') {
                    $('select[name="type_id"] option[value="'+data.type_id+'"]').prop("selected",true);
                }
                return true;
            },

            // 编辑的前置和后置操作
            beforeSave: function (data) {
                return true;
            },
            afterSave: function (data) {
                return true;
            }
        });

        $(function () {
            m.init();

            $(document).on('change', '.sorts', _update_single_value);
            $(document).on('click', '.status', _update_single_value);
            $(document).on('click', '.is_nav', _update_single_value);
            $(document).on('click', '.me-table-children-show-table', _alert);
        });
    </script>
<?php $this->endBlock(); ?>