<?php
namespace backend\models\search;

use backend\models\GoodsSpec;
use common\models\Attr;
use common\models\Goods;
use common\models\GoodsAlbum;
use common\models\GoodsAttr;
use common\models\GoodsAttrCustom;
use common\models\GoodsClass;
use common\models\GoodsContent;
use common\models\Store;
use common\models\StoreClass;
use yii\helpers\ArrayHelper;

/**
 * 商品搜索模型
 * @package backend\models
 */
class GoodsSearch extends Goods
{
    public $filters = [];

    /**
     * 查询入口
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function search ($params)
    {
        $this->filters = $params;
        return $this->queryObject();
    }

    /**
     * 查询条件
     * @return string[]
     */
    private function queryCondition ()
    {
        $where   = ['and'];
        if (isset($this->filters['name']))        $where[] = ['like', 'goods_.name', $this->filters['name']];
        if (isset($this->filters['goods_id']))    $where[] = ['goods_.id' => $this->filters['goods_id']];
        if (isset($this->filters['store_id']))    $where[] = ['like', 'store_.name', $this->filters['store_id']];
        if (isset($this->filters['store_class_id'])) $where[] = ['like', 'store_class_.name', $this->filters['store_class_id']];
        if (isset($this->filters['class_id']))    $where[] = ['like', 'goods_class_.name', $this->filters['class_id']];
        if (isset($this->filters['status']) && $this->filters['status'] != 'All') $where[] = ['goods_.status' => $this->filters['status']];
        if (isset($this->filters['start_time']))  $where[] = ['>=','goods_.created_at',strtotime($this->filters['start_time'])];
        if (isset($this->filters['end_time']))    $where[] = ['<=','goods_.created_at',strtotime($this->filters['end_time'].' 23:59:59')];
        return $where;
    }

    /**
     * 查询对象
     * @return \yii\db\ActiveQuery
     */
    private function queryObject ()
    {
        $query = self::find();
        $query->alias('goods_');
        $query->filterWhere($this->queryCondition());
        return $query;
    }

    /**
     * 查询后的数据修改
     * @param $array
     * @return mixed
     */
    public function afterSearch (&$array)
    {
        foreach ($array as &$value) {
            $value['thumb'] = '/uploads'.$value['thumb'];
            $value['class_id'] = implode('>',array_reverse(GoodsClass::getClassName($value['class_id'])));
            $value['store_id'] = $value['store']['name'];
            $value['store_class_id'] = implode('>',array_reverse(StoreClass::getClassName($value['store_class_id'])));
        }
        unset($value);
        return $array;
    }

    public function getGoodsDetails (Goods $model)
    {
        $goods_id    = $model->id;
        $store_id    = $model->store_id;
        $goods_thumb = $model->thumb;

        # 销售价
        if ($model->min_price != $model->max_price) {
            $goods_one['goods_price'] = $model->min_price . "-" . $model->max_price;
        } else {
            $goods_one['goods_price'] = $model->min_price;
        }
        # 划线价
        if ($model->market_min_price != $model->market_max_price) {
            $goods_one['goods_del_price'] = $model->market_min_price . "-" . $model->market_max_price;
        } else {
            $goods_one['goods_del_price'] = $model->market_min_price;
        }

        # 当前商品的规格
        $spec_data          = GoodsSpec::find()->select(['id','name','value'])->where(['goods_id' => $goods_id, 'status' => 1])->indexBy('id')->asArray()->all();
        $spec_data_value    = [];
        if (!empty($spec_data)) {
            foreach ($spec_data as $k => $v) {
                $spec_data_value[$v['name']] = explode(",", $v['value']);
            }
        }
        # 规格属性里面的图片数据
        $goods_pic_data     = GoodsAttr::find()->where(['goods_id' => $goods_id, 'type' => 1, 'status' => 1])->select(['id','value','pic'])->indexBy('value')->asArray()->all();

        # 当前商品拥有属性
        $goods_attr_data    = GoodsAttr::find()->with(['attr'])->where(['goods_id' => $goods_id, 'type' => 0, 'status' => 1])->asArray()->all();
        $attr_name          = [];
        if (!empty($goods_attr_data)) {
            foreach ($goods_attr_data as $k => $v) {
                $attr_name[$v['attr']['name']][] = $v['value'];
            }
            if (!empty($attr_name)) {
                foreach ($attr_name as $k => $v) {
                    $attr_name[$k] = implode(',', $v);
                }
            }
        }
        # 获取商品自定义属性
        $goods_attr_custom_data = GoodsAttrCustom::find()->select(['name','value'])->where(['goods_id' => $goods_id])->indexBy('name')->asArray()->all();
        $goods_attr_custom_data = ArrayHelper::map($goods_attr_custom_data,'name','value');
        if ($goods_attr_custom_data) {
            $attr_name = array_merge($attr_name,$goods_attr_custom_data);
        }

        # 获取商品相册
        $gallery_one = GoodsAlbum::find()->where(['goods_id' => $goods_id])->asArray()->one();
        $gallery_one = [
            $goods_thumb,
            $gallery_one['photo_1'],
            $gallery_one['photo_2'],
            $gallery_one['photo_3'],
            $gallery_one['photo_4']
        ];
        $gallery_one = array_values(array_filter($gallery_one));

        # 商品内容
        $content_one = GoodsContent::find()->where(['goods_id' => $goods_id])->asArray()->one();

        # 店铺
        $store_one   = Store::findOne($store_id);

        return [
            'goods_one'         => $goods_one,
            'store_one'         => $store_one,
            'gallery_one'       => $gallery_one,
            'attr_name'         => $attr_name,
            'content_one'       => $content_one,
            'spec_data_value'   => $spec_data_value,
            'goods_pic_data'    => $goods_pic_data,
        ];
    }
}
