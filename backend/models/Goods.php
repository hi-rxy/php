<?php

namespace backend\models;

use common\models\GoodsClass;
use common\models\GoodsContent;
use common\models\StoreClass;
use jinxing\admin\helpers\Helper;
use yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property int $id 商品id
 * @property int $class_id 商品分类id
 * @property int $store_id 店铺id
 * @property int $store_class_id 店铺分类id
 * @property string $name 商品名称
 * @property string $thumb 商品主图
 * @property string $keywords 商品关键词
 * @property string $price 价格
 * @property string $min_price 价格最小值
 * @property string $max_price 价格最大值
 * @property string $market_price 商品划线价
 * @property string $market_min_price 商品划线最低价
 * @property string $market_max_price 商品划线最高价
 * @property int $stock 商品库存
 * @property int $hits 商品点击数
 * @property int $collect 商品收藏数
 * @property int $sales_volume 商品销量
 * @property int $comment_volume 商品评论数
 * @property int $sort 商品排序
 * @property int $status 0未审核1表示已通过2表示下架
 * @property string $verify_idea 审核意见
 * @property int $created_at 添加时间
 * @property int $updated_at 更新时间
 */
class Goods extends \common\models\Goods
{
    /**
     * 添加
     * @param ActiveRecord $ar
     * @param array $options
     * @return string|void|ActiveRecord
     */
    public static function doCreate(ActiveRecord $ar , array $options = [])
    {
        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $isSpec = [];
            $specIds     = $options['spec_id'];
            if ($specIds) $isSpec = $specIds;
            # 1、商品表
            $goodsId = self::saveGoods($ar,$options['goods']);
            # 2、商品内容表
            self::saveGoodsContent($goodsId,$options['goods_content']);
            # 3、添加商品相册
            GoodsAlbum::saveGoodsAlbum($goodsId,$options['goods_gallery']);
            # 4、判断商品有没有规格
            if ($isSpec) 
            {
                # 5、商品自定义属性表
                $customize   = GoodsSpec::saveGoodsSpec($goodsId, $options['spec_details'], $options['goods_attr_pic']);
                # 6、商品属性表-规格
                $goods_attrs = GoodsAttr::saveGoodsAttrStandard($isSpec, $customize);
                # 7、商品规格表
                GoodsStandard::saveGoodsStandard($goodsId, $options['goods_standard'], $goods_attrs);
            }
            # 8、商品属性表-参数
            GoodsAttr::saveGoodsAttrParams($goodsId, $options['goods_attr']);
            # 9、自定义属性表
            GoodsAttrCustom::saveGoodsAttrCustom($goodsId, $options['custom_data']);
            # 10、更新商品价格和库存
            self::updateGoods($goodsId, $isSpec, $options['goods']);

            $transaction->commit();

            return $ar;
        } catch (yii\db\Exception $exception) {
            $transaction->rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * 修改
     * @param ActiveRecord $ar
     * @param array $options
     * @return string|void|ActiveRecord
     */
    public static function doUpdate(ActiveRecord $ar , array $options = [])
    {
        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $goodsId = $ar->id;
            $isSpec = [];
            $specIds     = $options['spec_id'];
            if ($specIds) $isSpec = $specIds;

            # 1、商品表
            self::saveGoods($ar,$options['goods']);
            # 2、商品内容表
            self::saveGoodsContent($goodsId,$options['goods_content']);
            # 3、添加商品相册
            GoodsAlbum::saveGoodsAlbum($goodsId,$options['goods_gallery']);
            # 4、判断商品有没有规格
            if ($isSpec) {
                # 5、商品自定义属性表
                $customize   = GoodsSpec::saveGoodsSpec($goodsId, $options['spec_details'], $options['goods_attr_pic']);
                # 6、商品属性表
                $goods_attrs = GoodsAttr::saveGoodsAttrStandard($isSpec, $customize, $goodsId);
                # 7、商品规格表
                GoodsStandard::saveGoodsStandard($goodsId, $options['goods_standard'], $goods_attrs);
            } else {
                GoodsSpec::updateAll(['status' => GoodsSpec::STATUS_CLOSE] , ['goods_id' => $goodsId]);
                GoodsAttr::updateAll(['status' => GoodsAttr::STATUS_CLOSE] , ['goods_id' => $goodsId]);
                GoodsStandard::updateAll(['status' => GoodsStandard::STATUS_CLOSE] , ['goods_id' => $goodsId]);
            }
            # 8、商品属性表-参数
            GoodsAttr::saveGoodsAttrParams($goodsId, $options['goods_attr']);
            # 9、自定义属性表
            GoodsAttrCustom::saveGoodsAttrCustom($goodsId, $options['custom_data']);
            # 10、更新商品价格和库存
            self::updateGoods($goodsId, $isSpec, $options['goods']);

            $transaction->commit();

            return $ar;
        } catch (yii\base\Exception $exception) {
            $transaction->rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * 保存商品信息
     * @param ActiveRecord $model
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    private static function saveGoods($model,array $data)
    {
        // 验证是否定义了创建对象的验证场景
        if (ArrayHelper::getValue($model->scenarios(), 'create')) $model->scenario = 'create';
        if ($model->load($data,'') && $model->save()) return $model->id;
        throw new Exception(Helper::arrayToString($model->getErrors()));
    }

    /**
     * 保存商品内容
     * @param $goodsId
     * @param array $data
     * @throws Exception
     */
    private static function saveGoodsContent($goodsId, array $data)
    {
        $model = GoodsContent::findOne(['goods_id' => $goodsId]);
        if (is_null($model)) $model = new GoodsContent();
        $model->setAttributes(['goods_id' => $goodsId, 'content' => $data['content'],]);
        if (!$model->save()) throw new Exception(Helper::arrayToString($model->getErrors()));
    }

    /**
     * 更新商品价格和库存
     * @param int $goodsId
     * @param array $spec_id
     * @param array $data
     * @throws Exception
     */
    private static function updateGoods($goodsId, array $spec_id, array $data)
    {
        if (!$spec_id) {
            $a['min_price'] = $data['price'];
            $a['max_price'] = $data['price'];
            $a['market_min_price'] = $data['market_price'];
            $a['market_max_price'] = $data['market_price'];
            $a['stock'] = $data['stock'];
        } else {
            # 当前价格的最大值和最小值 库存总和
            $a = GoodsStandard::getStandardByGid($goodsId);
        }

        # 更新价格和库存
        /** @var Goods $model */
        $model = self::findOne($goodsId);
        if ($model->load($a,'') && !$model->save()) throw new Exception(Helper::arrayToString($model->getErrors()));
    }

    /**
     * 重新组装数据
     * @param array $formData
     * @return mixed
     */
    public static function formatData($formData)
    {
        $class_id = array_values(array_reverse(array_filter($formData['class_id'])));
        $store_class_id = array_values(array_reverse(array_filter($formData['store_class_id'])));
        $data['goods'] = [
            'class_id' => $class_id[0],
            'store_class_id' => $store_class_id[0],
            'name' => trim($formData['title']),
            'keywords' => trim($formData['summary']),
            'store_id' => 1,
            'price' => trim($formData['goods_price']),
            'market_price' => trim($formData['goods_del_price']),
            'stock' => trim($formData['goods_stock']),
            'status' => 1
        ];
        #商品规格
        $data['spec_id'] = $formData['spec_id'];
        #商品规格明细
        $data['spec_details'] = $formData['spec_details'];
        #商品规格
        $data['goods_standard'] = [
            'standard_price' => $formData['standard_price'],
            'standard_stock' => $formData['standard_stock'],
            'standard_del_price' => $formData['standard_del_price'],
            'standard_sn' => $formData['standard_sn'],
            'standard_name' => $formData['standard_name'],
            'standard_weight' => $formData['standard_weight'],
            'standard_volume' => $formData['standard_volume'],
            'standard_id' => $formData['standard_id'],
        ];
        #商品类目属性表
        $data['goods_attr'] = $formData['goods_attr'];
        $data['goods_attr_pic'] = $formData['goods_attr_pic'];
        #商家自定义商品类目属性
        $custom_attr_name = $formData['custom_attr_name'];
        $custom_attr_value = $formData['custom_attr_value'];
        if ($custom_attr_name) {
            foreach ($custom_attr_name as $k => $val) {
                if (!empty($val)) {
                    $data['custom_data']['attr_name'][$k] = $val;
                    $data['custom_data']['attr_value'][$k] = $custom_attr_value[$k];
                }
            }
        } else {
            $data['custom_data'] = [];
        }
        #相册
        $goods_gallery = $formData['goods_gallery'];
        if (!empty($goods_gallery)) {
            foreach ($goods_gallery as $k => $v) {
                if (empty($v)) {
                    unset($goods_gallery[$k]);
                }
            }
            $goods_gallery = array_values($goods_gallery);
        }
        #主图
        if (!empty($goods_gallery[0])) $data['goods']['thumb'] = $goods_gallery[0];
        if (!empty($goods_gallery[1])) $data['goods_gallery']['photo_1'] = $goods_gallery[1];
        if (!empty($goods_gallery[2])) $data['goods_gallery']['photo_2'] = $goods_gallery[2];
        if (!empty($goods_gallery[3])) $data['goods_gallery']['photo_3'] = $goods_gallery[3];
        if (!empty($goods_gallery[4])) $data['goods_gallery']['photo_4'] = $goods_gallery[4];
        #商品内容表
        $data['goods_content'] = [
            'content' => $formData['content']
        ];
        return $data;
    }

    /**
     * 获取修改时的商品的信息
     * @return array
     */
    public static function getGoods (Goods $model)
    {
        $goodsClassOne  = $model->goodsClass;
        #商品父级分类
        $classIds       = array_reverse(GoodsClass::getClassId((int)$model->class_id));
        #店铺父级分类
        $storeClassIds  = array_reverse(StoreClass::getClassId((int)$model->store_class_id));

        #商品分类
        if ((int)$model->class_id) {
            foreach ($classIds as $k => $v) {
                $goods_temp  = GoodsClass::queryCondition()->andFilterWhere(['pid' => $v])->asArray()->all();
                if (!empty($goods_temp)) {
                    $arrGoods[$k] = $goods_temp;
                }
            }
            $class_data     = $arrGoods;
        } else {
            $class_data[]   = GoodsClass::queryCondition()->andFilterWhere(['pid' => 0])->asArray()->all();
            $classIds       = [];
        }

        #店铺分类
        if ((int)$model->store_class_id) {
            foreach ($storeClassIds as $k1 => $v1) {
                $store_temp  = StoreClass::queryCondition()->andFilterWhere(['pid' => $v1])->asArray()->all();
                if (!empty($store_temp)) {
                    $arrStore[$k1] = $store_temp;
                }
            }
            $store_class_data = $arrStore;
        } else {
            $store_class_data[] = StoreClass::queryCondition()->andFilterWhere(['pid' => 0])->asArray()->all();
            $storeClassIds = array();
        }

        #获取总后台的规格
        $spec_data      = Attr::queryCondition()->select(['id','name','value'])->where(['type_id' => $goodsClassOne->type_id])->indexBy('id')->asArray()->all();

        #总后台数据处理
        $spec_data_value = array();
        if (!empty($spec_data)) {
            foreach ($spec_data as $k => $v) {
                $spec_data_value[$v['name']] = explode(",", $v['value']);
            }
        }

        #当前商品拥有规格
        $goods_spec_data = GoodsSpec::queryCondition()->select(['id','name','value'])->where(['goods_id' => $model->id])->indexBy('id')->asArray()->all();
        if (!empty($goods_spec_data) && !empty($spec_data)) {
            $goods_spec_temp = array_merge($spec_data, $goods_spec_data);
        } elseif (!empty($goods_spec_data)) {
            $goods_spec_temp = $goods_spec_data;
        } elseif (!empty($spec_data)) {
            $goods_spec_temp = $spec_data;
        } else {
            $goods_spec_temp = array();
        }

        #总规格处理
        $zong_spec_data = array();
        if (!empty($goods_spec_temp)) {
            foreach ($goods_spec_temp as $k => $v) {
                $zong_spec_data[$v['name']][] = $v['value'];
            }
            foreach ($zong_spec_data as $k => $v) {
                $zong_spec_data[$k] = array_unique(explode(",", implode(",", $v)));
            }
        }

        #当前商品规格处理
        if (!empty($goods_spec_data)) {
            $arr = array();
            foreach ($goods_spec_data as $k => $v) {
                $arr[$v['name']] = explode(",", $v['value']);
            }
            $goods_spec_data = $arr;
        }

        #商品选中规格
        $standard_data = ArrayHelper::toArray($model->goodsStandard);
        $goods_attr_spec = GoodsAttr::queryCondition()->where(['goods_id' => $model->id, 'type' => 1])->indexBy('id')->asArray()->all();
        if (!empty($standard_data)) {
            unset($k,$v);
            foreach ($standard_data as $k => $v) {
                $standard_data[$k]['name'] = explode(",", $v['name']);
                if (!empty($standard_data[$k]['name'])) {
                    foreach ($standard_data[$k]['name'] as $k1 => $v1) {
                        $standard_data_str[$k1] = $goods_attr_spec[$v1]['value'];
                    }
                    $standard_data[$k]['name'] = implode(",", $standard_data_str);
                }
            }
        }

        #规格属性里面的图片数据
        $goods_pic_data = ArrayHelper::map($goods_attr_spec,'value','pic');

        #获取总后台的规格
        $attr_data      = Attr::queryCondition()->select(['id','name','value'])->where(['type_id' => $goodsClassOne->type_id,'style' => 0])->indexBy('id')->asArray()->all();

        #当前商品拥有属性
        $goods_attr_data = GoodsAttr::find()->where(['goods_id' => $model->id, 'type' => 0])->asArray()->all();
        if (!empty($goods_attr_data) && !empty($attr_data)) {
            foreach ($attr_data as $k => $v) {
                foreach ($goods_attr_data as $k1 => $v1) {
                    if ($v1['id'] == $v['id']) {
                        $attr_data[$k]['select_value'][] = $v1['value'];
                    }
                }
            }
        }
        if (!empty($attr_data)) {
            foreach ($attr_data as $k => $v) {
                $attr_data[$k]['value'] = explode(",", $attr_data[$k]['value']);
            }
        }

        #获取商品自定义属性
        $goods_attr_custom_data = GoodsAttrCustom::find()->where(['goods_id' => $model->id])->asArray()->all();

        #获取商品相册
        $gallery_one = GoodsAlbum::find()->where(['goods_id' => $model->id])->asArray()->one();
        $gallery_one = [
            $model->thumb,
            $gallery_one['photo_1'],
            $gallery_one['photo_2'],
            $gallery_one['photo_3'],
            $gallery_one['photo_4']
        ];
        $gallery_one = array_values(array_filter($gallery_one));

        #商品内容
        $content_one = GoodsContent::find()->where(['goods_id' => $model->id])->asArray()->one();

        return [
            'classIds'          => $classIds,
            'class_data'        => $class_data,
            'storeClassIds'     => $storeClassIds,
            'store_class_data'  => $store_class_data,
            'spec_data_value'   => $spec_data_value,
            'zong_spec_data'    => $zong_spec_data,
            'goods_spec_data'   => $goods_spec_data,
            'standard_data'     => $standard_data,
            'goods_pic_data'    => $goods_pic_data,
            'attr_data'         => $attr_data,
            'goods_attr_custom_data'    => $goods_attr_custom_data,
            'gallery_one'       => $gallery_one,
            'content_one'       => $content_one,
        ];
    }
}
