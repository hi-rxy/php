<?php
namespace api\models\v1;

use common\helpers\Ucpaas;
use yii\base\Model;
use yii;

class Sms extends Model
{
    # 手机号码
    public $phone;
    # 验证码
    public $code;

    private $_user;
    private $_cache_prefix = 'yii2:register:';
    private $key;
    private $redis;

    public function rules()
    {
        return [
            [['phone', 'code'], 'required'],
            [['phone'], 'trim'],
            [['phone'], 'match', 'pattern' => '/^1[3|4|5|7|8][0-9]{9}$/'],
            ['phone', 'validateUserExist'],
            ['code', 'validateExpireTime'],
        ];
    }

    public function validateUserExist($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, '手机号码未注册');
            }
        }
    }

    public function validateExpireTime($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $data  = $this->redis->get(md5($this->key));
            if ($data) {
                list($phone, $code, $expire_in) = explode(':',$data);
                if ($phone && ($phone == $this->phone) && ($expire_in > time())) {
                    $this->addError($attribute,'请于3分钟之后再来发送');
                }
            }
        }
    }

    public function send($options = [])
    {
        $this->redis    = Yii::$app->redis;
        $this->code     = rand(100000, 999999);
        $this->phone    = $options['phone'];
        $this->key      = $this->_cache_prefix.'sms:'.$this->phone;
        if ($this->validate()) {
            $expire_in  = time() + 180;
            $this->redis->set(md5($this->key),implode(':',array($this->phone,$this->code,$expire_in)));
            $this->redis->expire($this->redis,$expire_in);

            $config['SMS_ACCOUNT_SID']  = Yii::$app->params['site']['SMS_ACCOUNT_SID'];
            $config['SMS_TOKEN']        = Yii::$app->params['site']['SMS_TOKEN'];
            $config['SMS_APP_ID']       = Yii::$app->params['site']['SMS_APP_ID'];
            $ucpaas = new Ucpaas($config);
            //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
            //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
            $res = $ucpaas->SendSms($options['templateId'], $this->phone, implode(',',[ $this->phone, $this->code ]));
            return json_decode($res, true);
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
