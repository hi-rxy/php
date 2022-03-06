<?php
namespace api\models\v1;

use yii\base\Model;
use yii;

class Mail extends Model
{
    # 邮箱地址
    public $email;
    # 主题
    public $subject;
    # 内容
    public $body;
    # 验证码
    public $code;

    private $_user;
    private $_cache_prefix = 'yii2:register:';
    private $redis;
    private $key;

    public function rules()
    {
        return [
            [['email', 'body', 'subject'], 'required'],
            [['email', 'body', 'subject'], 'trim'],
            [['email'], 'email'],
            ['phone', 'validateUserExist'],
            ['code', 'validateExpireTime'],
        ];
    }

    public function validateUserExist($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, '邮箱未注册');
            }
        }
    }

    public function validateExpireTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $data  = $this->redis->get(md5($this->key));
            if ($data) {
                list($email, $code, $expire_in) = explode(':',$data);
                if ($email && ($email == $this->email) && ($expire_in > time())) {
                    $this->addError($attribute,'请于3分钟之后再来发送');
                }
            }
        }
    }

    public function send($options = [])
    {
        $this->email    = $options['email'];
        $this->body     = $options['body'];
        $this->subject  = $options['subject'];
        $this->redis    = Yii::$app->redis;
        $this->key      = $this->_cache_prefix.'email:'.$this->email;
        $this->code     = rand(100000, 999999);
        if ($this->validate()) {
            $expire_in  = time() + 180;
            $this->redis->set(md5($this->key),implode(':', [ $this->email,$this->code,$expire_in ]));
            $this->redis->expire($this->redis,$expire_in);

            $mail = Yii::$app->mailer->compose();
            $mail->setTo($this->email);//设置收件人的地址
            $mail->setSubject($this->subject);//设置邮件主题
            $mail->setHtmlBody($this->body);//设置邮件的内容的html
            return $mail->send();
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['mobile' => $this->phone]);
        }
        return $this->_user;
    }
}
