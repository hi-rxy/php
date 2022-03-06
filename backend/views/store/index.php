<?php

use yii\helpers\Url;
use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '店铺管理';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<?= PHP_EOL ?>
    <script type="text/javascript">
        var config = {
            title: "<?= $this->title?>",
            url: {
                getChinaCityUrl: "<?= Url::toRoute(['get-china-city'])?>",
            },
            data: {
                jsonStoreStatus: <?= Json::encode($arrStoreStatus)?>,
                jsonStoreStatusColor: <?= Json::encode($arrStoreStatusColor)?>,
                jsonStoreDomainStatus: <?= Json::encode($arrStoreDomainStatus)?>,
                jsonStoreDomainStatusColor: <?= Json::encode($arrStoreDomainStatusColor)?>,
                jsonChinaAddress: <?= Json::encode($arrChinaAddress)?>,
                jsonChinaCity: <?= Json::encode($arrChinaCity)?>,
                jsonChinaArea: <?= Json::encode($arrChinaArea)?>,
            }
        }
    </script>
    <script type="text/javascript" src="/js/common/index.js?v=<?= time() ?>"></script>
    <script type="text/javascript" src="/js/Views/store/_index.js?v=<?= time() ?>"></script>
    <script type="text/javascript">
        var m = meTables({
            title: config.title,
            fileSelector: ["#file_logo", "#file_id_card_front", "#file_id_card_side"],
            buttons: buttons(),
            number: false,
            table: {
                columns: [
                    {
                        title: "店铺ID",
                        data: "id",
                        edit: {type: "hidden"},
                        isHide: true,
                        sortable: false
                    },
                    {
                        title: "用户id",
                        data: "user_id",
                        isHide: true,
                        edit: {type: "hidden", required: true, number: true},
                        sortable: false
                    },
                    {
                        title: "店铺名称",
                        data: "name",
                        search: {type: "text"},
                        edit: {type: "text", required: true, rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "店铺logo",
                        data: "logo",
                        sortable: false,
                        edit: {
                            type: "file",
                            options: {
                                "id": "file_logo",
                                "name": "UploadForm[logo]",
                                "input-name": "logo",
                                "input-type": "ace_file",
                                "file-name": "logo"
                            }
                        },
                        createdCell: storeLogo
                    },
                    {
                        title: "店铺标题",
                        data: "title",
                        isHide: true,
                        view: false,
                        sortable: false
                    },
                    {
                        title: "店铺关键词",
                        data: "keywords",
                        isHide: true,
                        view: false,
                        sortable: false
                    },
                    {
                        title: "店铺描述",
                        data: "desc",
                        isHide: true,
                        view: false,
                        edit: {type: "text"},
                        sortable: false
                    },
                    {
                        title: "身份证正面",
                        data: "id_card_front",
                        isHide: true,
                        edit: {
                            type: "file",
                            options: {
                                "id": "file_id_card_front",
                                "name": "UploadForm[id_card_front]",
                                "input-name": "id_card_front",
                                "input-type": "ace_file",
                                "file-name": "id_card_front"
                            }
                        },
                        sortable: false,
                        createdCell: storeUserIDCardFront
                    },
                    {
                        title: "身份证反面",
                        data: "id_card_side",
                        isHide: true,
                        edit: {
                            type: "file",
                            options: {
                                "id": "file_id_card_side",
                                "name": "UploadForm[id_card_side]",
                                "input-name": "id_card_side",
                                "input-type": "ace_file",
                                "file-name": "id_card_side"
                            }
                        },
                        sortable: false,
                        createdCell: storeUserIDCardSide
                    },
                    {
                        title: "联系人",
                        data: "contact",
                        isHide: true,
                        edit: {type: "text", required: true, rangeLength: "[2, 50]"},
                        sortable: false
                    },
                    {
                        title: "联系电话",
                        data: "contact_mobile",
                        edit: {type: "text", required: true, mobile: true, rangeLength: "[2, 20]"},
                        sortable: false
                    },
                    {
                        title: "店铺收入",
                        data: "money",
                        isHide: true,
                        view: false,
                        sortable: false
                    },
                    {
                        title: "店铺状态",
                        data: "status",
                        value: config.data.jsonStoreStatus,
                        search: {type: "select"},
                        edit: {type: "radio", number: true, default: 0},
                        sortable: false,
                        view: false,
                        createdCell: storeStatus
                    },
                    {
                        title: "审核失败的原因",
                        data: "reason",
                        isHide: true,
                        view: false,
                        sortable: false
                    },
                    {
                        title: "省",
                        data: "province",
                        isHide: true,
                        view: false,
                        value: config.data.jsonChinaAddress,
                        edit: {type: "select", required: true},
                        sortable: false,
                    },
                    {
                        title: "市",
                        data: "city",
                        view: false,
                        isHide: true,
                        value: config.data.jsonChinaCity,
                        edit: {type: "select", required: true},
                        sortable: false
                    },
                    {
                        title: "区",
                        data: "district",
                        view: false,
                        isHide: true,
                        value: config.data.jsonChinaArea,
                        edit: {type: "select"},
                        sortable: false
                    },
                    {
                        title: "详细地址",
                        data: "user_address",
                        isHide: true,
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "店铺模板",
                        data: "template",
                        isHide: true,
                        view: false,
                        sortable: false
                    },
                    {
                        title: "二级域名",
                        data: "domain",
                        edit: {type: "text", rangeLength: "[2, 200]"},
                        sortable: false
                    },
                    {
                        title: "二级域名状态",
                        data: "is_open_store",
                        value: config.data.jsonStoreDomainStatus,
                        search: {type: "select"},
                        edit: {type: "radio", number: true, default: 0},
                        sortable: false,
                        view: false,
                        createdCell: storeDomainStatus
                    },
                    {
                        title: "注册时间",
                        data: "created_at",
                        sortable: false,
                        isHide: true,
                        createdCell: MeTables.dateTimeString
                    },
                    {
                        title: "最后更新时间",
                        data: "updated_time",
                        sortable: false,
                        createdCell: MeTables.dateTimeString
                    }
                ]
            }
        });

        var $file_logo = null;
        var $file_id_card_side = null;
        var $file_id_card_front = null;

        $.extend(m, {
            beforeShow: function (data) {
                $file_logo.ace_file_input("reset_input");
                $file_id_card_front.ace_file_input("reset_input");
                $file_id_card_side.ace_file_input("reset_input");

                // 修改复值
                if (this.action == "update") {
                    if (!empty(data.logo)) {
                        $file_logo.ace_file_input("show_file_list", [data.logo]);
                    }
                    if (!empty(data.id_card_front)) {
                        $file_id_card_front.ace_file_input("show_file_list", [data.id_card_front]);
                    }
                    if (!empty(data.id_card_side)) {
                        $file_id_card_side.ace_file_input("show_file_list", [data.id_card_side]);
                    }
                }
                return true;
            },
            afterShow: function (data) {
                var province = 0,
                    city = 0,
                    district = 0;

                if (this.action == "update") {
                    province = data.province;
                    city = data.city;
                    district = data.district;
                    getChinaCity(province, city, 'city');
                    getChinaCity(city, district, 'area');
                }

                $('select[name="province"] option[value="' + province + '"]').attr("selected", 'selected');
                $('select[name="city"] option[value="' + city + '"]').attr("selected", 'selected');
                $('select[name="district"] option[value="' + district + '"]').attr("selected", 'selected');

                return true;
            }
        });

        $(function () {
            m.init();
            $file_logo = $("#file_logo");
            $file_id_card_front = $("#file_id_card_front");
            $file_id_card_side = $("#file_id_card_side");

            $(document).on('change', 'select[name="province"]', _getChinaProvince);
            $(document).on('change', 'select[name="city"]', _getChinaDistrict);
        });
    </script>
<?php $this->endBlock(); ?>