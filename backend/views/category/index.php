<?php

use yii\helpers\Url;
use yii\helpers\Json;
use common\models\Category;
use jinxing\admin\widgets\MeTable;

$request = Yii::$app->request;

// 定义标题和面包屑信息
$this->title = implode('>',$classify);;
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            url: {
                childrenUrl: '<?=Url::toRoute(['children'])?>',
                updateSortUrl: "<?=Url::toRoute(['update-sort'])?>",
            },
            data: {
                category: <?= $category ?>,
                params: <?= Json::encode($request->get())?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/category/_index.js?v=<?= time() ?>"></script>
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
            params: config.data.params,
            operations: {
                width: '200px',
                buttons: operationBtn()
            },
            table: {
                columns: [
                    {
                        data: "id",
                        edit: {type: "hidden"},
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "分类",
                        data: "parent_id",
                        isHide: true,
                        edit: {type: "selectOptions", number: 1, id: "select-options"},
                        search: {type: "selectOptions"},
                        createdCell: parentStatus
                    },
                    {
                        title: "名称",
                        data: "name",
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "别名",
                        data: "alias",
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "创建时间",
                        data: "created_at",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "最后更新",
                        data: "updated_at",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "排序",
                        data: "sort",
                        edit: {type: "text", required: true, number: true, value: 1},
                        sortable: false,
                        createdCell: updateSortsValue
                    }
                ]
            }
        });

        $.extend(m, {
            // 显示的前置和后置操作
            beforeShow: function (data) {
                $("#select-options option").prop("disabled", false);
                return true;
            },
            afterShow: function (data) {
                if (this.action === "update") {
                    // 自己不能选
                    $("#select-options option[value='" + data.id + "']").prop("disabled", true);
                    // 子类不能选
                    $("#select-options option[data-pid='" + data.id + "']").prop("disabled", true).each(function () {
                        $("#select-options option[data-pid='" + $(this).val() + "']").prop("disabled", true)
                    });
                }
                return true;
            }
        });

        $(function () {
            m.init();

            $(document).on('click', '.me-table-children-show-table', _alert);
            $(document).on('change', '.sorts', _update_sorts);
        });
    </script>
<?php $this->endBlock(); ?>