<div class="concent">
    <div id="payTable">
        <h3>确认订单信息</h3>
        <div class="cart-table-th">
            <div class="wp">
                <div class="th th-item"><div class="td-inner">商品信息</div></div>
                <div class="th th-price"><div class="td-inner">单价</div></div>
                <div class="th th-amount"><div class="td-inner">数量</div></div>
                <div class="th th-sum"><div class="td-inner">小计金额</div></div>
            </div>
        </div>
        <div class="clear"></div>
        <?php if (!empty($page_data)) :foreach ($page_data as $item) :?>
            <div class="item-list">
                <!--店铺订单开始-->
                <div class="store-order">
                    <!--店铺信息开始-->
                    <div class="bundle-hd">
                        <div class="bd-promos">
                            <div class="act-promo">
                                <a href="<?=$item['store']['store_url']?>" target="_blank"><?=$item['store']['name']?></a> <a href="javascript:;"><i class="am-icon-whatsapp online hover"></i></a>
                            </div>
                            <div class="bd-has-promo">已享优惠:<span class="bd-has-promo-content">省￥19.50</span>&nbsp;&nbsp;</div>
                        </div>
                    </div>
                    <!--店铺信息结束-->
                    <!--商品订单列表开始-->
                    <?php if (!empty($item['child'])) :foreach ($item['child'] as $value) :?>
                        <div class="bundle">
                            <div class="bundle-main">
                                <ul class="item-content clearfix">
                                    <div class="pay-phone">
                                        <li class="td td-item">
                                            <div class="item-pic">
                                                <a href="<?=$value['url']?>" class="J_MakePoint" target="_blank">
                                                    <img src="<?=$value['goods_thumb']?>" class="itempic J_ItemImg"></a>
                                            </div>
                                            <div class="item-info">
                                                <div class="item-basic-info">
                                                    <a href="<?=$value['url']?>" class="item-title J_MakePoint" target="_blank"><?=$value['name']?></a>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="td td-info">
                                            <?php if(!empty($value['spec_data'])) :?>
                                                <div class="item-props">
                                                    <?php foreach ($value['spec_data'] as $spec) :?>
                                                        <span class="sku-line" attr_value="<?=$spec['value']?>"><?=$spec['name']?>：<?=$spec['value']?></span>
                                                    <?php endforeach;?>
                                                </div>
                                            <?php endif;?>
                                        </li>
                                        <li class="td td-price">
                                            <div class="item-price price-promo-promo">
                                                <div class="price-content">
                                                    <em class="J_Price price-now"><?=$value['goods_price']?></em>
                                                </div>
                                            </div>
                                        </li>
                                    </div>
                                    <li class="td td-amount">
                                        <div class="amount-wrapper ">
                                            <div class="item-amount ">
                                                <span class="phone-title">购买数量</span>
                                                <div class="sl">
                                                    <span class="buy-num">× <?=$value['goods_num']?></span>
                                                    <input type="hidden" name="goods_num" value="<?=$value['goods_num']?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="td td-sum">
                                        <div class="td-inner">
                                            <input type="hidden" name="goods_id" value="<?=$spec['id']?>" />
                                            <input type="hidden" name="standard_id" value="<?=$value['standard_id']?>" />
                                            <em tabindex="0" class="J_ItemSum number"><?=$value['total_price']?></em>
                                        </div>
                                    </li>

                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>
                    <?php endforeach;endif;?>
                    <!--商品订单列表结束-->
                    <div class="clear"></div>
                    <div class="pay-total">
                        <!--留言-->
                        <div class="order-extra">
                            <div class="order-user-info">
                                <div  class="memo">
                                    <label>买家留言：</label>
                                    <input type="text" title="选填,对本次交易的说明（建议填写已经和卖家达成一致的说明）" placeholder="选填,建议填写和卖家达成一致的说明" name="<?=$item['store']['id']?>_order_post_script" class="memo-input J_MakePoint c2c-text-default memo-close">
                                    <div class="msg hidden J-msg"><p class="error">最多输入500个字符</p></div>
                                </div>
                            </div>

                        </div>
                        <div class="clear"></div>
                    </div>
                    <!--含运费小计 -->
                    <div class="buy-point-discharge ">
                        <p class="price g_price ">合计（含运费） <span>¥</span><em class="pay-sum"><?=0;//$item['store']['total_price']?></em></p>
                    </div>
                    <!--信息 -->
                </div>
                <!--店铺订单结束-->
            </div>
        <?php endforeach;endif;?>

        <div class="order-go clearfix">
            <div class="pay-confirm clearfix">
                <div class="box">
                    <div tabindex="0" id="holyshit267" class="realPay"><em class="t">实付款：</em>
                        <span class="price g_price "><span>¥</span> <em class="style-large-bold-red " id="J_ActualFee"><?=$count_price?></em></span>
                    </div>
                    <div id="holyshit268" class="pay-address">
                        <p class="buy-footer-address">
                            <span class="buy-line-title buy-line-title-type">寄送至：</span>
                            <span class="buy--address-detail">
                                <span class="province"><?=$address['user_default_data']['address_province']?></span>
                                <span class="city"><?=$address['user_default_data']['address_city']?></span>
                                <span class="dist"><?=$address['user_default_data']['address_province']?></span>
                                <span class="street"><?=$address['user_default_data']['consignee_address']?></span>
                            </span>
                        </p>
                        <p class="buy-footer-address">
                            <span class="buy-line-title">收货人：</span>
                            <span class="buy-address-detail">
                                <span class="buy-user"><?=$address['user_default_data']['consignee_username']?></span>
                                <span class="buy-phone"><?=$address['user_default_data']['consignee_mobile']?></span>
                            </span>
                            <input type="hidden" name="address_id" value="<?=$address['user_default_data']['id']?>" />
                        </p>
                    </div>
                </div>
                <div id="holyshit269" class="submitOrder">
                    <div class="go-btn-wrap">
                        <a href="{:url('Cart/index')}" class="go-cart"><i class="am-icon-chevron-left"></i>返回购物车</a>
                        <input type="submit" class="btn-go"  title="点击此按钮，提交订单" value="提交订单" />
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>