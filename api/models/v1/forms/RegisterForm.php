<?php
namespace api\models\v1\forms;

use api\models\User;
use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    # 名称
    public $name;
    # 验证码
    public $code;
    # 用户名
    public $username;
    # 密码
    public $password;

    private $_user;
    private $_cache_prefix = 'yii2:register:';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code', 'username', 'password'], 'required'],
            [['name', 'code', 'username', 'password'], 'trim'],
            ['code', 'validateExpireTime'],
            ['name', 'IN', 'range' , ['email','mobile']]
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateExpireTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $redis = Yii::$app->redis;
            $key   = $this->_cache_prefix.$this->name.':'.$this->username;
            $data  = $redis->get(md5($key));
            if (!is_null($data)) {
                list($name, $code, $expire_in) = explode(':',$data);
                if ($name && ($name == $this->username) && ($expire_in > time())) {
                    if ($code != $this->code) {
                        $this->addError($attribute,'验证码不正确');
                    }
                }
            } else {
                $this->addError($attribute,'验证码已过期，请重新发送');
            }
        }
    }

    /**
     * @return false|string
     * @throws \yii\base\Exception
     */
    public function register()
    {
        if ($this->validate()) {
            $name = $this->name;
            $this->_user->point         = Yii::$app->params['site']['REG_POINT'];
            $this->_user->created_ip    = Yii::$app->request->userIP;
            $this->_user->password_hash = $this->password;
            $this->_user->$name         = $this->username;
            $this->_user->save();
            return $this->_user->attributes['id'];
        }
    }

    /**
     * @return \api\models\User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = (new User());
        }
        return $this->_user;
    }
}
