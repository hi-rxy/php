<?php
namespace backend\controllers;

use common\models\Goods;
use backend\models\Order;
use common\models\Region;
use common\models\UserAddress;
use Yii;

/**
 * Class OrderController 订单管理 执行操作控制器
 * @package backend\controllers
 */
class OrderController extends Controller
{
    /** @var string pk 定义表使用的主键名称 */
    protected $pk = 'id';
    
    /** @var string sort 定义默认排序字段名称 */
    protected $sort = 'id';

    public $user_id = 1;
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Order';
    
    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     * 
     * @return array 
     */
    public function where()
    {
        return [
            [['id'], '='],
        ];
    }

    public function actionAjaxCity ()
    {
        if (Yii::$app->request->isAjax) {
            $list = Region::tableGetData(['pid' => (int)Yii::$app->request->post('region_pid',0)]);
            return $this->success($list);
        }
        return $this->returnJson();
    }

    public function actionBuy ()
    {
        $this->layout = "@backend/template/layouts/main";
        $this->viewPath = "@backend/template/";

        $goods_id     = Yii::$app->request->get('goods_id',0);
        $standard_id  = Yii::$app->request->get('standard_id',0);
        $goods_num    = Yii::$app->request->get('goods_num',0);
        $address_id   = Yii::$app->request->get('address_id','');

        $address      = Order::getUserAddress($this->user_id,$address_id);
        $result       = Order::getBuyData($goods_id,$standard_id,$goods_num);
        $model        = Goods::findOne($goods_id);
        $data         = Order::processCartData($result);
        $count        = $data[2];
        $count_status = $data[3];
        $count_status_1 = $data[4];
        return $this->render('order/index', [
            'model'     => $model,
            'address'   => $address,
            'result'    => $result,
            'page_data' => $data[0],
            'count_price' => $data[1],
            'count'     => $count,
            'count_status'      => $count_status,
            'count_status_1'    => $count_status_1,
        ]);
    }

    public function actionBuyDo ()
    {
        if (Yii::$app->request->isPost) {
            $goods_id     = Yii::$app->request->post('goods_id',0);
            $standard_id  = Yii::$app->request->post('standard_id',0);
            $goods_num    = Yii::$app->request->post('goods_num',0);
            $address_id   = Yii::$app->request->post('address_id','');
            $pay_type     = Yii::$app->request->post('pay_type',1);
            $result       = Order::getBuyData($goods_id,$standard_id,$goods_num);
            $cart_data    = Order::processCartData($result);
            //购物车
            $data         = $cart_data[0];
            //总价
            $order_price  = $cart_data[1];

            $user_address_one = UserAddress::getAddressDetails($address_id);

            if (empty($user_address_one)) {
                return $this->error(201,'用户地址不正确');
            }

            if (empty($data)) {
                return $this->error(201,'请选择产品');
            }

            //生成订单
            $this->makeOrder($data, $order_price, $pay_type, $user_address_one, 'cart');

        }
        return $this->returnJson();
    }

    /**
     * 生成订单
     * @param $data
     * @param $order_price
     * @param $pay_type
     * @param $user_address_one
     * @param string $type
     */
    private function makeOrder ($data, $order_price, $pay_type, $user_address_one, $type = "cart")
    {
        //主订单号
        $order_sn = create_sn();
        $csn = 0;
        $csn_new = 0;
        $cart_ids = array();
        $order_data = array();
        $order_goods = array();
        foreach ($data as $store_id => $store_one) {
            do {
                $csn = create_sn();
                if ($csn != $order_sn && $csn != $csn_new) {
                    $csn_new = $csn;
                    break;
                }
            } while (1);
            $order_post_script = input($store_id . "_order_post_script");
            $real_amount = $store_one['store']['total_price'];
            //订单数据
            $order_data[$store_id] = array(
                'order_sn' => $csn_new,
                'order_uid' => $this->uid,
                'order_source' => 0,
                'order_status' => 0,
                'pay_status' => 0,
                'pay_type' => $pay_type,
                'pay_amount' => $real_amount,
                'real_amount' => $real_amount,
                'create_time' => time(),
                'address_username' => $user_address_one['address_username'],
                'address_mobile' => $user_address_one['address_mobile'],
                'address_province' => $user_address_one['province_name'],
                'address_city' => $user_address_one['city_name'],
                'address_district' => $user_address_one['district_name'],
                'user_address' => $user_address_one['user_address'],
                'order_post_script' => $order_post_script,
                'store_id' => $store_id,
                'order_ip' => get_client_ip(),
            );
            //订单商品数据
            foreach ($store_one['child'] as $goods_one) {
                $order_goods[$store_id]['goods'][] = array(
                    'order_uid' => $this->uid,
                    'goods_id' => $goods_one['goods_id'],
                    'goods_name' => $goods_one['goods_name'],
                    'goods_sn' => $goods_one['goods_sn'],
                    'standard_id' => $goods_one['standard_id'],
                    'goods_del_price' => $goods_one['goods_del_price'],
                    'goods_price' => $goods_one['goods_price'],
                    'goods_num' => $goods_one['goods_num'],
                    'store_id' => $store_id,
                    'standard_name' => $goods_one['standard_name'],
                    'goods_thumb' => $goods_one['goods_thumb'],
                    'standard_attr' => !empty($goods_one['spec_data']) ? serialize($goods_one['spec_data']) : "",
                );
                if ($type == "cart") {
                    $cart_ids[] = $goods_one['cart_id'];
                }
            }
        }
        //主订单
        $main_data = array(
            'order_sn' => $order_sn,
            'order_uid' => $this->uid,
            'order_status' => 0,
            'pay_type' => $pay_type,
            'order_price' => $order_price,
        );
        $order_data = array(
            'main_data' => $main_data,
            'order_data' => $order_data,
            'goods_data' => $order_goods,
        );
        $add = $this->order->addData($order_data, $cart_ids);
        if (!$add) {
            $this->error('订单创建失败');
        } else {
            //支付宝支付
            if ($pay_type == 1) {
                $notify_url = url('NotifyUrl/alipay', '', false, config('site.WEB_MAIN_DOMAIN').config('cookie.domain'));
                $return_url = url('ReturnUrl/alipay', '', false, config('site.WEB_MAIN_DOMAIN').config('cookie.domain'));
                alipay($order_sn, $order_price, $notify_url, $return_url);
                //微信支付
            } elseif ($pay_type == 2) {
                $this->redirect(url('order/wxpay', array('order_sn' => $main_data['order_sn'])));
            }
        }
    }
}
