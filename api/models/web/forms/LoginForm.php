<?php

namespace api\models\web\forms;

use api\models\web\AdminUser;

class LoginForm extends \yii\base\Model
{
    public $username;
    public $password;

    /**
     * @var $_user \api\models\web\AdminUser
     */
    private $_user;
    private $_cache_prefix = 'yii2:cms:';

    public function rules ()
    {
        return [
            [ [ 'username', 'password' ], 'required' ],
            [ 'password', 'validatePassword' ],
        ];
    }

    public function attributeLabels ()
    {
        return [
            'username' => '管理员账号',
            'password' => '管理员密码',
        ];
    }

    public function validatePassword ( $attribute, $params )
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
            if ($user->status != AdminUser::STATUS_ACTIVE) {
                $this->addError($attribute, '禁止登录');
            }
        }
    }

    public function login ()
    {
        if ($this->validate())
        {
            $data = [
                'auth_key' => $this->getUser()->auth_key,
                'expire_in' => time() + config('tokenExpire')
            ];
            # 生成token
            $key = md5($this->_cache_prefix . 'token:' . $this->getUser()->auth_key . ':' . time());
            $cache = \Yii::$app->cache;
            $cache->set($key, json_encode($data), config('tokenExpire'));

            # 更新信息
            $this->_user->last_time = time();
            $this->_user->last_ip = \Yii::$app->request->getUserIP();
            $this->_user->updated_at = time();
            $this->_user->updated_id = $this->_user->id;
            $this->_user->save();
            return $key;
        }
    }

    public function getUser ()
    {
        if (!$this->_user) {
            $this->_user = AdminUser::findByUsername($this->username);
        }
        return $this->_user;
    }
}
