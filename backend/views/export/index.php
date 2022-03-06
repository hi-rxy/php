<?php

use yii\helpers\Url;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '数据备份';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            url: {
                optimizeUrl: "<?=Url::toRoute(['db/optimize'])?>",
                repairUrl: "<?=Url::toRoute(['db/repair'])?>",
                backupUrl: "<?=Url::toRoute(['db/backup'])?>",
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/export/_index.js?v=<?= time() ?>"></script>
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
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                columns: [
                    {
                        title: "表名",
                        data: "Name",
                        search: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "存储引擎",
                        data: "Engine",
                        sortable: false
                    },
                    {
                        title: "行格式",
                        data: "Row_format",
                        sortable: false
                    },
                    {
                        title: "表中的行数",
                        data: "Rows",
                        sortable: false
                    },
                    {
                        title: "整个表的数据量",
                        data: "Data_length",
                        sortable: false
                    },
                    {
                        title: "下一个自增的值",
                        data: "Auto_increment",
                        sortable: false
                    },
                    {
                        title: "创建时间",
                        data: "Create_time",
                        sortable: false,
                        isHide: true,
                    },
                    {
                        title: "最近更新时间",
                        data: "Update_time",
                        sortable: false,
                    },
                    {
                        title: "注释",
                        data: "Comment",
                        sortable: false,
                    },
                    {
                        title: "字符集",
                        data: "Collation",
                        sortable: false,
                    }
                ]
            }
        });

        $(function () {
            m.init();
            m.optimize = fOptimize;
            m.repair = fRepair;
            m.backup = fBackup;

            $(document).on('click', '.me-table-optimize-show-table', rowClickEvent);
            $(document).on('click', '.me-table-repair-show-table', rowClickEvent);
        });
    </script>
<?php $this->endBlock(); ?>