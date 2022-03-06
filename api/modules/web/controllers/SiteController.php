<?php
namespace api\modules\web\controllers;

use api\controllers\CommonController;
use api\models\web\forms\LoginForm;
use jinxing\admin\helpers\Helper;

class SiteController extends CommonController
{
    public $modelClass = 'api\models\web\AdminUser';

    /**
     * @Notes: 登录
     * @Function actionLogin
     * @return array
     * @author: Admin
     * @Time: 2022/2/21 8:58
     */
    public function actionLogin ()
    {
        $model = new LoginForm();
        $model->username = $this->request->post('username','');
        $model->password = $this->request->post('password','');
        $token = $model->login();
        if ($token) {
            return $this->success([
                'token' => $token
            ],'登录成功');
        }
        return $this->error(Helper::arrayToString($model->getErrors()));
    }
}
