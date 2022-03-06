<?php
namespace api\modules\web;

use api\models\web\AdminUser;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\web\controllers';

    public $admin = [];

    public $token = false;

    public function init()
    {
        $this->modules = [
            'class' => 'api\modules\web\Module'
        ];
        parent::init();
    }

    public function beforeAction($action)
    {
        $request = \Yii::$app->request;
        $this->token = $request->getHeaders()->get('x-token','');
        if ($this->token) {
            $this->admin = ArrayHelper::toArray(AdminUser::findIdentityByAccessToken($this->token));
        }
        return parent::beforeAction($action);
    }

    /**
     * @Notes:
     * @Function: getAdmin
     * @return array
     * @Author: 17908
     * @Time: 2022/2/25 0025 20:38
     */
    public function getAdmin ()
    {
        return $this->admin;
    }

    /**
     * @Notes:
     * @Function: getUserId
     * @return mixed
     * @Author: 17908
     * @Time: 2022/2/25 0025 20:38
     */
    public function getUserId ()
    {
        $admin = $this->getUser();
        return $admin['id'];
    }

    /**
     * @Notes:
     * @Function: getUser
     * @return array
     * @Author: 17908
     * @Time: 2022/2/25 0025 20:38
     */
    public function getUser ()
    {
        return $this->getAdmin();
    }
}
