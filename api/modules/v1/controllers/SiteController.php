<?php

namespace api\modules\v1\controllers;

use api\models\v1\forms\RegisterForm;
use api\models\v1\forms\LoginForm;

class SiteController extends \api\base\controllers\CommonController
{
    public $modelClass = 'api\models\v1\User';

    /**
     * 登录
     * @return array
     */
    public function actionLogin()
    {
        $loginForm = new LoginForm();
        $loginForm->username = $this->request->post('username','');
        $loginForm->password = $this->request->post('password','');
        return $this->success([
            'access_token' => $loginForm->login()
        ]);
    }

    /**
     * 注册
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        $registerForm = new RegisterForm();
        $registerForm->code = $this->request->post('code','');
        $registerForm->name = $this->request->post('type','');
        $registerForm->password = $this->request->post('password','');
        $registerForm->username = $this->request->post($registerForm->name,'');
        $uid = $registerForm->register();
        return $this->success(['uid' => $uid]);
    }

    /**
     * 发送手机验证码或者邮箱验证码
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionSendCode()
    {
        $type = $this->request->post('type','');
        if (!in_array($type,['email','mobile'])) return $this->error('type 参数值错误');
        if ($type == 'email') {
            $options = [
                'email'     => $this->request->post('email',''),
                'subject'   => '账号验证码',
                'body'      => '您的验证码是：%s，请于3分钟内输入验证，如非本人操作，可不用理会！',
            ];
        } else {
            $options = [
                'phone'     => $this->request->post('phone',''),
                'templateId'=> '123456',
            ];
        }
        factory('api\\models\\',$type)->send($options);
        return $this->success([],  '发送成功,请注意邮箱查收');
    }
}
