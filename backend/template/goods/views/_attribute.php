<div class="clearfixRight">
    <!--规格属性-->
    <!--名称-->
    <div class="tb-detail-hd"><h1><?=$model->name?></h1></div>
    <div class="tb-detail-list">
        <!--价格-->
        <div class="tb-detail-price">
            <li class="price iteminfo_price">
                <dt>促销价</dt>
                <dd><em>¥</em><b class="sys_item_price" goods_price="<?=$details['goods_one']['goods_price']?>"><?=$details['goods_one']['goods_price']?></b> </dd>
            </li>
            <li class="price iteminfo_mktprice">
                <dt>原价</dt>
                <dd><em>¥</em><b class="sys_item_mktprice" goods_del_price="<?=$details['goods_one']['goods_del_price']?>"><?=$details['goods_one']['goods_del_price']?></b></dd>
            </li>
            <div class="clear"></div>
        </div>
        <!--地址-->
        <dl class="iteminfo_parameter freight">
            <dt class="tit">配送至</dt>
            <div class="distpicker"><input id="city-picker" class="form-control city-picker-input" readonly="" type="text" value=""></div>
        </dl>
        <div class="ship-price">快递: 0.00</div>
        <div class="clear"></div>
        <!--销量-->
        <ul class="tm-ind-panel">
            <li class="tm-ind-item tm-ind-sellCount canClick"><div class="tm-indcon"><span class="tm-label">月销量</span><span class="tm-count">1015</span></div></li>
            <li class="tm-ind-item tm-ind-sumCount canClick"><div class="tm-indcon"><span class="tm-label">累计销量</span><span class="tm-count"><?=$model->sales_volume?></span></div></li>
            <li class="tm-ind-item tm-ind-reviewCount canClick tm-line3"><div class="tm-indcon"><span class="tm-label">累计评价</span><span class="tm-count">640</span></div></li>
        </ul>
        <div class="clear"></div>
        <!--各种规格-->
        <dl class="iteminfo_parameter sys_item_specpara">
            <dt class="theme-login"><div class="cart-title"><span class="select-pop-data">点击选择规格</span><span class="am-icon-angle-right"></span></div></dt>
            <dd>
                <!--操作页面-->
                <div class="theme-popover-mask"></div>
                <div class="theme-popover">
                    <div class="theme-span"></div>
                    <div class="theme-poptit">
                        <a href="javascript:;" title="关闭" class="close">×</a>
                    </div>
                    <div class="theme-popbod dform">
                        <form class="theme-signin" name="loginform" action="" method="post">
                            <div class="theme-signin-left">
                                <?php foreach ($details['spec_data_value'] as $key => $specs):?>
                                    <div class="theme-options pic">
                                        <div class="cart-title"><?=$key?></div>
                                        <ul>
                                            <?php foreach ($specs as $spec):?>
                                                <li class="sku-line spec" goods_id="<?=$model->id?>" title="<?=$spec?>"><?php if(isset($details['goods_pic_data'][$spec]) &&!empty($details['goods_pic_data'][$spec])):?><img src="<?='/uploads'.$details['goods_pic_data'][$spec]['pic']?>" width="20px" height="20px" img-mid="<?='/uploads'.$details['goods_pic_data'][$spec]['pic']?>" img-big="<?='/uploads'.$details['goods_pic_data'][$spec]['pic']?>" alt="<?=$spec?>"><?php endif;?><?=$spec?><i></i>
                                                    <input type="hidden" name="spec[goods_attr_id]" value="<?=$details['goods_pic_data'][$spec]['id']?>" />
                                                </li>
                                            <?php endforeach;?>
                                        </ul>
                                    </div>
                                <?php endforeach;?>
                                <div class="theme-options">
                                    <div class="cart-title number">数量</div>
                                    <dd>
                                        <input id="min" class="am-btn am-btn-default min_goods_num" name="" type="button" value="-" />
                                        <input id="text_box" class="goods_num" name="goods_num" type="text" value="1" style="width:30px;" onKeyup="change_goods_quantity(this);" />
                                        <input id="add" class="am-btn am-btn-default add_goods_num" name="" type="button" value="+" />
                                        <span  class="tb-hidden">库存<span class="stock" goods_stock="<?=$model->stock?>"><?=$model->stock?></span>件</span>
                                    </dd>
                                </div>
                                <div class="clear"></div>
                                <div class="btn-op"><div class="btn am-btn am-btn-warning">确认</div><div class="btn close am-btn am-btn-warning">取消</div></div>
                                <div class="theme-signin-right">
                                    <!--<div class="img-info"><img src="/static/index/images/songzi.jpg" /></div>-->
                                    <div class="text-info">
                                        <span class="J_Price price-now" goods_price="<?=$details['goods_one']['goods_price']?>">¥<?=$details['goods_one']['goods_price']?></span>
                                        <span class="tb-hidden">库存<span class="stock" goods_stock="<?=$model->stock?>"><?=$model->stock?></span>件</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </dd>
        </dl>
        <div class="clear"></div>
        <div class="select-pop"></div>
        <!--活动	-->
        <div class="shopPromotion gold">
            <div class="hot">
                <dt class="tb-metatit">店铺优惠</dt>
                <div class="gold-list">
                    <p>购物满2件打8折，满3件7折<span>点击领券<i class="am-icon-sort-down"></i></span></p>
                </div>
            </div>
            <div class="clear"></div>
            <div class="coupon">
                <dt class="tb-metatit">优惠券</dt>
                <div class="gold-list">
                    <ul>
                        <li>125减5</li>
                        <li>198减10</li>
                        <li>298减20</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
