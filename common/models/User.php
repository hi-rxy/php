<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

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
class User extends Authentication
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            ['user_card_status', 'safe'],
            // 用户状态
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            // 身份证审核状态
            ['user_card_status', 'default', 'value' => self::STATUS_CARD_NOT_ACTIVE],
            ['user_card_status', 'in', 'range' => [self::STATUS_CARD_NOT_ACTIVE, self::STATUS_CARD_ACTIVE , self::STATUS_CARD_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => '默认收货地址',
            'username' => '昵称',
            'truename' => '真实姓名',
            'birthday' => '生日',
            'avatar' => '用户头像',
            'sex' => '性别',
            'mobile' => '手机号码',
            'email' => '邮箱',
            'status' => '状态',
            'point' => '积分',
            'id_card_truename' => '身份证真实姓名',
            'id_card_code' => '身份证号码',
            'user_card_status' => '身份证审核状态',
            'pay_password_hash' => '支付密码',
            'question_one' => '问题1',
            'question_two' => '问题2',
            'answer_one' => '答案1',
            'answer_two' => '答案2',
            'auth_key' => '盐',
            'password_hash' => '登录密码',
            'password_reset_token' => '登录凭据',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'created_ip' => '创建IP',
            'login_at' => '登录时间',
            'login_ip' => '登录IP',
        ];
    }
}
