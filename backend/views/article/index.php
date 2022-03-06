<?php

use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '文章管理';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?=$this->title?>",
            url: {
                createUrl: '<?=Url::toRoute(['create'])?>',
                updateUrl: '<?=Url::toRoute(['update'])?>',
                viewUrl: '<?=Url::toRoute(['view'])?>',
                commentUrl: '<?=Url::toRoute(['comment/create'])?>',
                childrenUrl: "<?=Url::toRoute(['children'])?>",
                updateSingleUrl: "<?=Url::toRoute(['update-single'])?>",
            },
            data: {
                category: <?= $category ?>,
                jsonArticleStatus: <?= Json::encode($aStatus) ?>,
                jsonArticleBoolStatus: <?= Json::encode($aBoolStatus) ?>,
                jsonArticleStatusColor: <?= Json::encode($aColorStatus) ?>,
            }
        }
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/article/_index.js?v=<?= time() ?>"></script>
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        $.extend(MeTables, {
            selectOptionsCreate: function (params) {
                return '<select ' + this.handleParams(params) + '><option value="0">顶级分类</option><?=$options?></select>';
            },
            selectOptionsSearchMiddleCreate: function (params) {
                delete params.type;
                params.id = "search-" + params.name;
                return '<label for="' + params.id + '"> ' + params.title + ': <select ' + this.handleParams(params) + '>' +
                    '<option value="0">顶级分类</option>' +
                    '<?=$options?>' +
                    '</select></label>';
            }
        });

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
                        title: "ID",
                        data: "id",
                        sortable: false
                    },
                    {
                        title: "分类",
                        data: "category_id",
                        search: {type: "selectOptions"},
                        createdCell: articleCategory
                    },
                    {
                        title: "标题",
                        data: "title",
                        search: {type: "text"},
                        sortable: false,
                        createdCell: articleTitle
                    },
                    {
                        title: "作者",
                        data: "author_name",
                        sortable: false
                    },
                    {
                        title: "头条",
                        data: "is_headline",
                        sortable: false,
                        createdCell: articleHeadLine
                    },
                    {
                        title: "推荐",
                        data: "is_recommend",
                        sortable: false,
                        createdCell: articleRecommend
                    },
                    {
                        title: "幻灯",
                        data: "is_slide_show",
                        sortable: false,
                        createdCell: articleSlide
                    },
                    {
                        title: "滚动",
                        data: "is_roll",
                        sortable: false,
                        createdCell: articleRoll
                    },
                    {
                        title: "加粗",
                        data: "is_bold",
                        sortable: false,
                        createdCell: articleBold
                    },
                    {
                        title: "特别推荐",
                        data: "is_special_recommend",
                        sortable: false,
                        createdCell: articleSpecial
                    },
                    {
                        title: "状态",
                        data: "status",
                        sortable: false,
                        createdCell: articleStatus
                    },
                    {
                        title: "排序",
                        data: "sorts",
                        sortable: false,
                        createdCell: updateSortsValue
                    },
                    {
                        title: "最后更新时间",
                        data: "updated_at",
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

        $(function () {
            m.init();
            m.publish = publish_article;

            $(document).on('change', '#show-table .sorts', _update_single_value);
            $(document).on('click', '#show-table .is_headline', _update_single_value);
            $(document).on('click', '#show-table .is_recommend', _update_single_value);
            $(document).on('click', '#show-table .is_slide_show', _update_single_value);
            $(document).on('click', '#show-table .is_special_recommend', _update_single_value);
            $(document).on('click', '#show-table .is_roll', _update_single_value);
            $(document).on('click', '#show-table .is_bold', _update_single_value);
            $(document).on('click', '#show-table .status', _update_single_value);
            $(document).on('click', '#show-table .title', show_article);
            $(document).on('click', '.me-table-article-show-table', update_article);
            $(document).on('click', '.me-table-comment-show-table', comment_article);
        });
    </script>
<?php $this->endBlock(); ?>