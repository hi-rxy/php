<?php
namespace api\controllers;

use yii\filters\auth\HttpBearerAuth;

class AuthController extends CommonController
{
    /**
     * @Notes: 验证授权
     * @Function: behaviors
     * @param $optional
     * @return array|array[]
     * @Author: 17908
     * @Time: 2022/3/6 0006 19:16
     */
    public function behaviors( $optional = [] )
    {
        $behaviors = parent::behaviors();
        $behaviors[ 'authenticator' ] = [
            'class' => HttpBearerAuth::className()
        ];
        return $behaviors;
    }

    /**
     * @Notes: 注销部分操作
     * @Function: actions
     * @return \string[][]
     * @Author: 17908
     * @Time: 2022/3/6 0006 19:16
     */
    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions[ 'index' ] ,
            $actions[ 'update' ] ,
            $actions[ 'create' ] ,
            $actions[ 'delete' ]
        );
        return $actions;
    }
}
