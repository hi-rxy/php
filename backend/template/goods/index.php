<?php

use yii\helpers\Url;

list(, $url) = Yii::$app->assetManager->publish((new \backend\template\assets\AppAsset())->sourcePath);
$depends = ['depends' => 'backend\template\assets\AppAsset'];
$this->registerCssFile($url . '/css/city-picker.css', $depends);
$this->registerCssFile($url . '/css/option.css', $depends);
$this->registerCssFile($url . '/css/item.css', $depends);
$this->registerJsFile($url . '/extends/js/city-picker.data.js', $depends);
$this->registerJsFile($url . '/extends/js/city-picker.js', $depends);
$this->registerJsFile($url . '/extends/js/jquery.imagezoom.min.js', $depends);
$this->registerJsFile($url . '/extends/js/quick_links.js', $depends);
$this->registerJsFile($url . '/extends/js/jquery.fly.min.js', $depends);
$this->registerJsFile($url . '/js/item.js', $depends);
$this->title = $model->name;
?>
<script type="text/javascript">
    var uid = 1;
    var getGoodsStandard = '<?=Url::to(['goods/get-standard'])?>';
    var checkGoodsStock = '<?=Url::to(['goods/check-stock'])?>';
    var buyGoods = '<?=Url::to(['order/buy'])?>';
</script>
<div class="listMain">
    <div class="scoll">
        <section class="slider">
            <div class="flexslider">
                <ul class="slides">
                    <?php if (!empty($details['gallery_one'])) :foreach ($details['gallery_one'] as $item) :?>
                    <li>
                        <img src="<?='/uploads'.$item?>" title="pic" />
                    </li>
                    <?php endforeach;endif;?>
                </ul>
            </div>
        </section>
    </div>
    <!--手机版主图切换结束-->
    <!--放大镜-->
    <div class="item-inform">
        <?=$this->render('views/_zoom',compact('model','details'));?>
        <?=$this->render('views/_attribute',compact('model','details'));?>
        <div class="pay">
            <div class="pay-opt">
                <a href="/"><span class="am-icon-home am-icon-fw">首页</span></a>
                <a><span class="am-icon-heart am-icon-fw">收藏</span></a>
                <a title="客服" href="javascript:;"><span><i class="am-icon am-icon-whatsapp online hover"></i>客服</span></a>
            </div>
            <ul>
                <li>
                    <div class="clearfix tb-btn tb-btn-buy">
                        <a id="LikBuy" title="点此按钮到下一步确认购买信息" href="javascript:;" onclick="goods_buy(this,<?=$model->id?>)">立即购买</a>
                    </div>
                </li>
                <li>
                    <div class="clearfix tb-btn tb-btn-basket">
                        <a id="LikBasket" title="加入购物车" goods_id="<?=$model->id?>" href="javascript:;"><i></i>加入购物车</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="clear"></div>
<!-- introduce-->
<div class="introduce">
    <div class="browse">
        <?=$this->render('views/_side_shop_info',compact('model','details'));?>
        <?=$this->render('views/_side_goods_list',compact('model','details'));?>
    </div>
    <div class="introduceMain">
        <div class="am-tabs" data-am-tabs>
            <ul class="am-avg-sm-3 am-tabs-nav am-nav am-nav-tabs">
                <li class="am-active"><a href="javascript:;"><span class="index-needs-dt-txt">宝贝详情</span></a></li>
                <li goods_id="<?=$model->id?>"><a href="javascript:;"><span class="index-needs-dt-txt">全部评价</span></a></li>
                <li><a href="javascript:;"><span class="index-needs-dt-txt">猜你喜欢</span></a></li>
            </ul>

            <div class="am-tabs-bd">
                <div class="am-tab-panel am-fade am-in am-active">
                    <?=$this->render('views/_panel_goods_views',compact('details'));?>
                </div>
                <div class="am-tab-panel am-fade">
                    <?=$this->render('views/_panel_goods_comment',compact('model','details'));?>
                </div>
                <div class="am-tab-panel am-fade">
                    <?=$this->render('views/_panel_like',compact('model','details'));?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<input type="hidden" name="goods_id" value="<?=$model->id?>" />
<input type="hidden" name="store_id" value="<?=$model->store_id?>" />