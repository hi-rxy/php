<?php

use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '用户信息';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?= $this->title?>",
            url: {
                updateSingleUrl: "<?= Url::toRoute(['user/update-single'])?>",
            },
            data: {
                jsonUserStatus: <?= Json::encode($userStatus)?>,
                jsonUserStatusColor: <?= Json::encode($userStatusColor)?>,
                jsonIDCardStatus: <?= Json::encode($cardStatus)?>,
                jsonIDCardStatusColor: <?= Json::encode($cardStatusColor)?>,
            }
        };
    </script>
    <script src="/js/common/index.js?v=<?= time() ?>"></script>
    <script src="/js/Views/user/_index.js?v=<?= time() ?>"></script>
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            buttons: buttons(),
            number: false,
            table: {
                columns: [
                    {
                        title: "id",
                        data: "id",
                        edit: {type: "hidden"},
                        sortable: false
                    },
                    {
                        title: "用户名",
                        data: "username",
                        search: {name: "username"},
                        edit: {type: "text", required: true, rangeLength: "[2, 255]", autocomplete: "off"},
                        sortable: false
                    },
                    {
                        title: "密码",
                        data: "password",
                        hide: true,
                        edit: {type: "password", rangeLength: "[2, 20]", autocomplete: "new-password"},
                        sortable: false,
                        defaultContent: "",
                        view: false
                    },
                    {
                        title: "确认密码",
                        data: "rePassword",
                        hide: true,
                        edit: {
                            type: "password",
                            rangeLength: "[2, 20]",
                            equalTo: "input[name=password]:first"
                        },
                        sortable: false,
                        defaultContent: "",
                        view: false
                    },
                    {
                        title: "手机号码",
                        data: "mobile",
                        search: {name: "mobile"},
                        edit: {type: "text", required: true, mobile: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "会员邮箱",
                        data: "email",
                        search: {name: "email"},
                        edit: {type: "text", required: true, email: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "身份证号码",
                        data: "id_card_code",
                        edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                        sortable: false
                    },
                    {
                        title: "身份证审核状态",
                        data: "user_card_status",
                        value: config.data.jsonIDCardStatus,
                        search: {type: "select"},
                        edit: {type: "radio", number: true, default: 0},
                        sortable: false,
                        createdCell: cardStatus,
                    },
                    {
                        title: "用户状态",
                        data: "status",
                        value: config.data.jsonUserStatus,
                        search: {type: "select"},
                        edit: {type: "radio", number: true, default: 10},
                        createdCell: userStatus,
                        sortable: false
                    },
                    {
                        title: "注册时间",
                        data: "created_at",
                        defaultOrder: "desc",
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "最后登录时间",
                        data: "updated_at",
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "开始时间",
                        data: null,
                        search: {
                            type: "text",
                            name: "start_time",
                            class: "Wdate",
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

            $(document).on('click', '.user_card_status', _update_single_value);
            $(document).on('click', '.status', _update_single_value);
        });
    </script>
<?php $this->endBlock(); ?>