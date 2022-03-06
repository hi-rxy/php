<?php

namespace backend\controllers;

use backend\models\search\UserSearch;
//use jinxing\admin\behaviors\Logging;
use yii;
use backend\models\User;
//use jinxing\admin\helpers\Helper;
//use yii\helpers\ArrayHelper;

/**
 * Class UserController 用户信息
 * @package backend\controllers
 */
class UserController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\User';

    /**
     * @var UserSearch string 定义使用的查询model
     */
    public $modelSearchClass = null;

    /**
     * 定义查询字段对应处理表达式
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

    public function beforeAction($action)
    {
        if (is_null($this->modelSearchClass)) $this->modelSearchClass = new UserSearch();
        return parent::beforeAction($action);
    }

    /**
     * 查询条件
     * @param array|mixed $where
     * @return yii\db\ActiveQuery|yii\db\Query
     */
    protected function getQuery($where)
    {
        $get     = Yii::$app->request->get('filters', []);
        $post    = Yii::$app->request->post('filters', []);
        $filters = array_merge($get,$post);
        return $this->modelSearchClass->search($filters)->asArray();
    }

    /**
     * 首页显示
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'userStatus'      => User::getArrayStatus(),
            'userStatusColor' => User::getStatusColor(),
            'cardStatus'      => User::getCardArrayStatus(),
            'cardStatusColor' => User::getCardStatusColor(),
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

            if (!$model = $this->findOne($post)) return $this->returnJson();

            $func = function ($field,$value) {
                switch ($field)
                {
                    case 'status' :
                        $res = (int)$value ? User::STATUS_DELETED : User::STATUS_ACTIVE;
                        break;
                    case 'user_card_status' :
                        $res = (int)$value ? User::STATUS_CARD_NOT_ACTIVE : User::STATUS_CARD_ACTIVE;
                        break;
                }
                return $res;
            };

            $result = User::doUpdateField($model,$func,$post);
            if (is_object($result) && $result instanceof User) return $this->success($result,'保存成功');

            return $this->error(201,'更新失败');
        }

        return $this->returnJson();
    }
}

