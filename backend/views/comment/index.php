<?php

use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '文章评论';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            data: {
                jsonCommentStatus: <?= Json::encode($arrStatus) ?>,
                jsonCommentStatusColor: <?= Json::encode($arrColorStatus) ?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/comment/_index.js?v=<?= time() ?>"></script>
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            number: false,
            buttons: buttons(),
            operations: null,
            table: {
                columns: [
                    {
                        title: "自增id",
                        data: "id",
                        edit: {type: "hidden"},
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "文章",
                        data: "article_title",
                        search: {type: "text"},
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "管理员id,其他人员对其回复为0",
                        data: "admin_id",
                        edit: {type: "text", required: true, number: true},
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "回复的评论id",
                        data: "reply_to",
                        isHide: true,
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "昵称",
                        data: "nickname",
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "邮箱",
                        data: "email",
                        isHide: true,
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "个人网址",
                        data: "website_url",
                        isHide: true,
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "回复内容",
                        data: "content",
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "回复ip",
                        data: "ip",
                        isHide: true,
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "状态",
                        data: "status",
                        edit: {type: "text", required: true, number: true},
                        sortable: false,
                        createdCell: articleCommentStatus
                    },
                    {
                        title: "用户类型",
                        data: "user_id",
                        isHide: true,
                        edit: {type: "text", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "创建时间",
                        data: "created_at",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "最后更新时间",
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
                            value: "<?=date('Y-m-d', getStartDayByMonth())?>",
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
                            value: "<?=date('Y-m-d', getEndDayByMonth())?>",
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

        $(function () {
            m.init();
        });
    </script>
<?php $this->endBlock(); ?>