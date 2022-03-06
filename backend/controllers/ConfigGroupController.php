<?php
namespace backend\controllers;

use common\models\ConfigGroup;
//use jinxing\admin\behaviors\Logging;
use Yii;
//use yii\helpers\ArrayHelper;

/**
 * Class ConfigController 配置组管理 执行操作控制器
 * @package backend\controllers
 */
class ConfigGroupController extends Controller
{
        /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id'; 
    
        /**
     * @var string sort 定义默认排序字段名称
     */
    protected $sort = [
        'sort' => SORT_ASC
    ];
   
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\ConfigGroup';
    
    /**
     * 需要定义where 方法，确定前端查询字段对应的查询方式
     * 
     * @return array 
     */
    public function where()
    {
        return [];
    }

//    /**
//     * 行为
//     *
//     * @return array
//     */
//    public function behaviors()
//    {
//        return ArrayHelper::merge(
//            parent::behaviors(),
//            [
//                'logging' => [
//                    'class' => Logging::className(),
//                    'needLogActions' => [
//                        'create',
//                        'update',
//                        'delete',
//                        'delete-all',
//                        'editable',
//                        'upload',
//                        'update-single'
//                    ]
//                ]
//            ]
//        );
//    }

    /**
     * 前置操作 当执行这些 'create','update','delete','delete-all'操作 删除redis缓存
     * @param $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->request->isPost && in_array($action->id,['create','update','delete','delete-all'])) {
            if (Yii::$app->cache->exists($this->redisKey[0])) {
                Yii::$app->cache->delete($this->redisKey[0]);
            }
        }
        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index',[
            'arrStatus'    => ConfigGroup::getArrayStatus(),
            'arrColor'     => ConfigGroup::getStatusColor()
        ]);
    }

    /**
     * 修改单个值
     * @return mixed|string
     */
    public function actionUpdateSingle()
    {
        if (Yii::$app->request->isPost)
        {
            $post = Yii::$app->request->post();
            try {
                $model = ConfigGroup::findOne($post['id']);
                if (is_null($model)) throw new yii\base\Exception('参数错误');

                $func = function ($field,$value) {
                    switch ($field)
                    {
                        case 'sorts' :
                            $res = (int)$value;
                            break;
                        default :
                            $res = (int)$value ? ConfigGroup::STATUS_CLOSE : ConfigGroup::STATUS_OPEN;
                            break;
                    }
                    return $res;
                };

                $result = ConfigGroup::doUpdateField($model,$func,$post);
                if (is_object($result) && $result instanceof ConfigGroup) return $this->success($result,'保存成功');

                return $this->error(201,'更新失败');
            } catch (\yii\db\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            } catch (\yii\base\Exception $exception) {
                return $this->error(201,$exception->getMessage());
            }
        }

        return $this->returnJson();
    }
}
