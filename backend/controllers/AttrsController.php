<?php

namespace backend\controllers;

use backend\models\Attr;
use common\models\GoodsType;
use yii;

/**
 * Class AttrsController 商品属性管理 执行操作控制器
 * @package backend\controllers
 */
class AttrsController extends Controller
{
    /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id';

    /**
     * @var string sort 定义默认排序字段名称
     */
    protected $sort = 'id';

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Attr';

    /**
     * 商品属性页面
     * @return string
     */
    public function actionIndex()
    {
        $typeId = Yii::$app->request->get('id', 0);
        return $this->render('_form', [
            'data' => GoodsType::getInstance($typeId),
            'rows' => Attr::getAttrTypeList($typeId)
        ]);
    }

    /**
     * 重写更新方法
     * @return mixed|string
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isPost)
        {
            $flag   = false;
            $post   = Yii::$app->request->post();
            $attrId = isset($post['attrs']['attr_id']) ? $post['attrs']['attr_id'] : [];

            if (empty($attrId)) return $this->error();

            $result             = Attr::handlerPostAttrs($post);

            # 查询数据库中已保存的属性ID
            $savedPropertyIds   = Attr::getIdsByTypeId($post['typeId']);

            # 比较两个数组 是否存在交集 有则更新
            $samePropertyIds    = array_intersect($savedPropertyIds, $attrId);

            # 比较两个数组 是否存在差集 有则删除
            $diffPropertyIds    = array_diff($savedPropertyIds, $attrId);

            # 执行新增属性值
            if ($result[0])
            {
                $flag = true;
                Attr::batchInsertAttrs($result[0]);
            }

            # 执行修改属性值
            if ($samePropertyIds)
            {
                $flag = true;
                Attr::batchUpdateAttrs($result[1]);
            }

            # 执行批量删除
            if ($diffPropertyIds)
            {
                $flag = true;
                Attr::batchDeleteAttrs($diffPropertyIds);
            }

            if (!$flag) return $this->error(201,'保存失败');

            return $this->success([], '保存成功');
        }
        return $this->error();
    }
}
