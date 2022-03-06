<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property int $address_id 默认收货地址
 * @property string $username
 * @property string $truename 真实姓名
 * @property int $birthday 生日
 * @property string $avatar 用户头像
 * @property int $sex 性别
 * @property string $mobile 手机号码
 * @property string $email
 * @property int $status
 * @property int $point 积分
 * @property string $id_card_truename 身份证真实姓名
 * @property string $id_card_code 身份证号码
 * @property int $user_card_status 身份证审核状态
 * @property string $pay_password_hash 支付密码
 * @property int $question_one 问题1
 * @property int $question_two 问题2
 * @property string $answer_one 答案1
 * @property string $answer_two 答案2
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property int $created_at
 * @property int $updated_at
 * @property string $created_ip 注册IP
 * @property int $login_at 登录时间
 * @property string $login_ip 登录IP
 */
class User extends \common\models\User
{
    /**
     * @var string 定义密码
     */
    public $password;

    /**
     * @var string 定义确认密码
     */
    public $rePassword;

    /**
     * 定义验证规则
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'status', 'user_card_status'], 'required'],
            [['password', 'rePassword'], 'required', 'on' => ['create']],
            // trim
            [['username', 'email', 'id_card_code', 'mobile', 'password', 'rePassword'], 'trim'],
            [['password', 'rePassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['mobile'], 'unique', 'message' => '手机号码已存在'],
            [['email'], 'unique', 'message' => '邮箱已存在'],
            [['username'], 'unique', 'message' => '用户名已存在'],
            [['id_card_code'], 'unique', 'message' => '身份证号码已存在'],
            // Username
            //['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            // E-mail
            [['email'], 'string', 'max' => 100],
            ['email', 'email'],
            // mobile
            [['mobile'], 'string', 'max' => 11],
            // rePassword
            ['rePassword', 'compare', 'compareAttribute' => 'password'],
            //['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            // 身份证审核状态
            ['user_card_status', 'default', 'value' => self::STATUS_CARD_NOT_ACTIVE],
            ['user_card_status', 'in', 'range' => [self::STATUS_CARD_NOT_ACTIVE, self::STATUS_CARD_ACTIVE , self::STATUS_CARD_DELETED]],
        ];
    }

    /**
     * 获取字段信息
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge(
            $labels,
            [
                'password'   => '密码',
                'rePassword' => '确认密码',
            ]
        );
    }

    /**
     * 获取状态说明信息
     * @param  int $intStatus 状态
     * @return array|string
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE  => Yii::t('admin', '正常'),
            self::STATUS_DELETED => Yii::t('admin', '禁用'),
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取用户状态值对应的颜色信息
     * @param  int $intStatus 状态值
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE  => 'btn-success',
            self::STATUS_DELETED => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取身份证审核状态值说明信息
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getCardArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_CARD_NOT_ACTIVE => '未审核',
            self::STATUS_CARD_ACTIVE  => '已审核',
            self::STATUS_CARD_DELETED  => '审核拒绝',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取身份证审核状态对应的颜色信息
     * @param  int $intStatus 状态值
     * @return array|string
     */
    public static function getCardStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_CARD_NOT_ACTIVE  => 'btn-grey',
            self::STATUS_CARD_ACTIVE => 'btn-success',
            self::STATUS_CARD_DELETED => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * beforeSave() 新增之前的处理
     * @param  bool $insert 是否是新增数据
     * @return bool 处理是否成功
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // 新增记录和修改了密码
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password)) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }
            return true;
        }
        return false;
    }
}
