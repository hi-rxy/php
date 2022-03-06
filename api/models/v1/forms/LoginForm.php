<?php
namespace api\models\v1\forms;

use yii\base\Model;
use api\models\v1\User;

class LoginForm extends Model
{
    public $username;
    public $password;

    /**
     * @var $_user \api\models\v1\User
     */
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'trim'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '账号或者密码错误');
            }
        }
    }

    /**
     * @return string|void
     * @throws \yii\base\Exception
     */
    public function login()
    {
        if ($this->validate()) {
            //$this->_user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            $this->_user->updated_at = time();
            $this->_user->save();
            return $this->_user->password_reset_token;
        }
    }

    /**
     * @return \api\models\v1\User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['username' => $this->username]);
        }
        return $this->_user;
    }
}
