<?php
namespace backend\models;

use common\models\GoodsStandard;
use common\models\Goods;
use common\models\Region;
use common\models\Store;
use common\models\UserAddress;
use yii\base\Exception;

class Order extends \common\models\Order
{
    public static function getBuyData ($goods_id, $standard_id, $goods_num)
    {
        # 判断商品是否下架
        $goods_one = Goods::tableGetOne(['id' => $goods_id]);
        if (empty($goods_one)) throw new Exception('该商品已经下架');
        # 判断商品有没有规格
        if ($standard_id) {
            $standard_one = GoodsStandard::tableGetOne(['id' => $standard_id , 'goods_id' => $goods_id, 'status' => 1]);
            # 判断当前商品规格是否存在
            if (empty($standard_one)) throw new Exception('该商品的规格组合不存在');
            # 判断规格库存
            if ($standard_one['stock'] < $goods_num) throw new Exception('该商品的规格库存量不足');
        } else {
            if ($goods_one['stock'] < $goods_num) throw new Exception('该商品的库存量不足');
        }

        $data = Goods::getOneDetail($goods_id, $standard_id);
        $data['goods_price']    = sprintf('%.2f', $data['goods_price']);
        $data['goods_num']      = $goods_num;
        $data['cart_status']    = 1;
        $data['total_price']    = sprintf('%.2f', ($data['goods_price'] * $data['goods_num']));
        $result[$data['store_id']]['store']                 = Store::tableGetOne(['id' => $data['store_id']]);
        //$result[$data['store_id']]['store']['store_url']    = store_url($data['store_id']);
        $result[$data['store_id']]['child'][]               = $data;
        return $result;
    }

    public static function getUserAddress ($user_id, $address_id)
    {
        # 获取省份
        $provinceData = Region::tableGetData(['pid' => 1]);
        if ($address_id > 0 && is_mobile() == 1) {
            # 用户地址信息
            $user_address_data = UserAddress::tableGetData(['id' => $address_id, 'user_id' => $user_id]);
            $user_address_data[0]['is_default'] = 1;
            if (!empty($user_address_data)) {
                foreach ($user_address_data as $k => $v) {
                    $user_address_data[$k]['address_province']  = Region::tableGetDataColumn(['id' => $user_address_data[$k]['consignee_province']], 'name');
                    $user_address_data[$k]['address_city']      = Region::tableGetDataColumn(['id' => $user_address_data[$k]['consignee_city']], 'name');
                    $user_address_data[$k]['address_district']  = Region::tableGetDataColumn(['id' => $user_address_data[$k]['consignee_district']], 'name');
                }
            }
            # 获取用户的默认地址
            $user_default_data = UserAddress::tableGetOne(['id' => $address_id]);
        } else {
            # 用户地址信息
            $user_address_data = UserAddress::tableGetData(['user_id' => $user_id]);
            if (!empty($user_address_data)) {
                foreach ($user_address_data as $k => $v) {
                    $user_address_data[$k]['address_province']  = Region::tableGetDataColumn(['id' => $user_address_data[$k]['consignee_province']], 'name');
                    $user_address_data[$k]['address_city']      = Region::tableGetDataColumn(['id' => $user_address_data[$k]['consignee_city']], 'name');
                    $user_address_data[$k]['address_district']  = Region::tableGetDataColumn(['id' => $user_address_data[$k]['consignee_district']], 'name');
                }
            }
            # 获取用户的默认地址
            $user_default_data = UserAddress::tableGetOne(['is_default' => 1, 'user_id' => $user_id]);
            # 默认地址不为空
            if (!empty($user_default_data)) {
                $user_default_data[$k]['address_province']  = Region::tableGetDataColumn(['id' => $user_default_data[$k]['consignee_province']], 'name');
                $user_default_data[$k]['address_city']      = Region::tableGetDataColumn(['id' => $user_default_data[$k]['consignee_city']], 'name');
                $user_default_data[$k]['address_district']  = Region::tableGetDataColumn(['id' => $user_default_data[$k]['consignee_district']], 'name');
            }
            if (empty($user_default_data) && !empty($user_address_data)) {
                $user_default_data = $user_address_data[0];
            }
        }

        return [
            'province_data'     => $provinceData,
            'user_address_data' => $user_address_data,
            'user_default_data' => $user_default_data,
        ];
    }

    public static function processCartData ($cart)
    {
        if (empty($cart)) return false;
        //商品的数量
        $total_num = 0;
        $goods_total = 0;
        $total_num_status_1 = 0;
        $total_num_status = 0;
        foreach ($cart as $k => $v) {
            $cart[$k]['store']['cart_status_1'] = 0;
            $cart[$k]['store']['cart_status_total'] = 0;
            foreach ($v['child'] as $k1 => $v1) {
                if ($cart[$k]['child'][$k1]['cart_status'] == 0) {
                    $cart[$k]['store']['cart_status_total']++;
                    $total_num_status++;
                    $total_num++;
                    continue;
                } else {
                    $cart[$k]['store']['cart_status_total']++;
                    $cart[$k]['store']['cart_status_1']++;
                    $cart[$k]['store']['total_price'] += $cart[$k]['child'][$k1]['total_price'];
                    $total_num_status++;
                    $total_num++;
                    $total_num_status_1++;
                }
                $goods_total += $cart[$k]['child'][$k1]['total_price'];
            }
        }
        if (empty($goods_total)) {
            $goods_total = 0;
        } else {
            $goods_total = sprintf("%.2f", $goods_total);
        }
        return array($cart, $goods_total, $total_num, $total_num_status, $total_num_status_1);
    }
}