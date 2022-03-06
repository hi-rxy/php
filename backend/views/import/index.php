<?php

use yii\helpers\Url;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '数据还原';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?= $this->title?>",
            url: {
                downloadUrl: "<?=Url::toRoute(['db/download'])?>",
                importUrl: "<?=Url::toRoute(['db/import'])?>",
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/import/_index.js?v=<?= time() ?>"></script>
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
                        title: "名称",
                        data: "title",
                        sortable: false
                    },
                    {
                        title: "卷数",
                        data: "part",
                        sortable: false
                    },
                    {
                        title: "压缩",
                        data: "compress",
                        sortable: false
                    },
                    {
                        title: "数据大小",
                        data: "size",
                        sortable: false
                    },
                    {
                        title: "备份时间",
                        data: "format_time",
                        sortable: false
                    }
                ]
            }
        });

        $(function () {
            m.init();

            $(document).on('click', '.me-table-import-show-table', importFile);
            $(document).on('click', '.me-table-download-show-table', download);
        });
    </script>
<?php $this->endBlock(); ?>