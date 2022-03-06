<?php
namespace api\modules\v1\controllers;

use api\base\controllers\Auth;

/**
 * 用户控制器
 * Class UserController
 */
class UserController extends Auth
{
    /**
     * 指定ORM模型
     * @var string
     */
    public $modelClass = 'api\models\User';

    public function beforeAction($action)
    {
        $this->allowedApis = [
            'user/test',
        ];
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionTest ()
    {
        var_dump(\Yii::$app->meters->index());
    }
}