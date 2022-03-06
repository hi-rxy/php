<?php

namespace api\models\web;

use Yii;
use api\models\BaseModel;
use yii\web\IdentityInterface;
use api\models\web\traits\AdminModelTrait;
use api\exceptions\TokenOverdueException;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_id
 * @property integer $created_at
 * @property integer $updated_id
 * @property integer $updated_at
 * @property string $face [varchar(100)]  管理员头像
 * @property int $last_time [int(11)]  上一次登录时间
 * @property string $last_ip [char(15)]  上一次登录的IP
 * @property string $address [varchar(100)]  地址信息
 */
class AdminUser extends BaseModel implements IdentityInterface
{
    use AdminModelTrait;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 20;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;

    /**
     * @Notes: 表
     * @Function tableName
     * @return string
     * @author: Admin
     * @Time: 2022/2/22 14:50
     */
    public static function tableName ()
    {
        return '{{%admin}}';
    }

    /**
     * @Notes: 定义规则
     * @Function rules
     * @return array[]
     * @author: Admin
     * @Time: 2022/2/22 14:51
     */
    public function rules ()
    {
        return [
            [ 'status', 'default', 'value' => self::STATUS_ACTIVE ],
            [ 'status', 'in', 'range' => [ self::STATUS_ACTIVE, self::STATUS_DELETED ] ],
        ];
    }

    /**
     * @Notes: 定义字段
     * @Function attributeLabels
     * @return string[]
     * @author: Admin
     * @Time: 2022/2/22 14:51
     */
    public function attributeLabels ()
    {
        return [
            'id' => 'Id',
            'username' => '管理员账号',
            'password' => '管理员密码',
            'email' => '管理员邮箱',
            'role' => '管理员角色',
            'auth_key' => '登录密钥',
            'password_hash' => '密码的哈希值',
            'password_reset_token' => '重新登录密钥',
            'status' => '状态',
            'create_time' => '创建时间',
            'create_id' => '创建用户',
            'update_time' => '修改时间',
            'update_id' => '修改用户',
        ];
    }

    /**
     * @Notes: 主键查找
     * @Function: findIdentity
     * @param $id
     * @return \api\models\web\AdminUser|\yii\web\IdentityInterface|null
     * @Author: 17908
     * @Time: 2022/3/6 0006 8:10
     */
    public static function findIdentity ( $id )
    {
        return static::findOne([ 'id' => $id, 'status' => self::STATUS_ACTIVE ]);
    }

    /**
     * @Notes: 根据token 获取用户信息
     * @Function: findIdentityByAccessToken
     * @param $token
     * @param $type
     * @return \api\models\web\AdminUser|\yii\web\IdentityInterface|null
     * @throws \api\exceptions\TokenOverdueException
     * @Author: 17908
     * @Time: 2022/3/6 0006 19:14
     */
    public static function findIdentityByAccessToken ( $token, $type = null )
    {
        return self::findByToken($token);
    }

    /**
     * @Notes: Finds user by username
     * @Function findByUsername
     * @param $username
     * @return AdminUser|null
     * @author: Admin
     * @Time: 2022/2/22 14:51
     */
    public static function findByUsername ( $username )
    {
        // 支持通过邮箱登录
        $column = strrpos($username, '@') === false ? 'username' : 'email';
        return static::findOne([ $column => $username, 'status' => self::STATUS_ACTIVE ]);
    }

    /**
     * @Notes: 根据token查找用户
     * @Function: findByToken
     * @param $cacheKey
     * @return \api\models\web\AdminUser
     * @throws \api\exceptions\TokenOverdueException
     * @Author: 17908
     * @Time: 2022/3/6 0006 8:03
     */
    private static function findByToken ($cacheKey)
    {
        $cache = json_decode(Yii::$app->cache->get($cacheKey), true);
        if (static::isLoginTokenValida($cache['auth_key'],$cache['expire_in'])) {
            return static::findOne([
                'auth_key' => $cache['auth_key'],
                'status' => self::STATUS_ACTIVE,
            ]);
        }
        throw new TokenOverdueException('出现错误');
    }

    /**
     * @Notes: 登录token验证
     * @Function: isLoginTokenValida
     * @param $auth_key
     * @param $expire_in
     * @return bool
     * @throws \api\exceptions\TokenOverdueException
     * @Author: 17908
     * @Time: 2022/3/6 0006 8:01
     */
    private static function isLoginTokenValida ($auth_key,$expire_in)
    {
        if ($auth_key && ( $expire_in - time() > config('tokenRefreshTime') )) return true;
        throw new TokenOverdueException('token 已过期');
    }

    /**
     * @Notes: Finds user by password reset token
     * @Function findByPasswordResetToken
     * @param $token
     * @return AdminUser|null
     * @author: Admin
     * @Time: 2022/2/22 14:51
     */
    public static function findByPasswordResetToken ( $token )
    {
        return !static::isPasswordResetTokenValid($token) ? null : static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @Notes: Finds out if password reset token is valid
     * @Function isPasswordResetTokenValid
     * @param $token
     * @return bool
     * @author: Admin
     * @Time: 2022/2/22 14:52
     */
    public static function isPasswordResetTokenValid ( $token )
    {
        if (empty($token)) return false;
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + config('user.passwordResetTokenExpire') >= time();
    }

    /**
     * @Notes: 主键
     * @Function: getId
     * @return array|int|mixed|string|null
     * @Author: 17908
     * @Time: 2022/3/6 0006 7:18
     */
    public function getId ()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @Notes: auth_key
     * @Function: getAuthKey
     * @return string
     * @Author: 17908
     * @Time: 2022/3/6 0006 7:19
     */
    public function getAuthKey ()
    {
        return $this->auth_key;
    }

    /**
     * @Notes: 验证auth_key
     * @Function: validateAuthKey
     * @param $authKey
     * @return bool
     * @Author: 17908
     * @Time: 2022/3/6 0006 7:19
     */
    public function validateAuthKey ( $authKey )
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @Notes: 验证密码
     * @Function validatePassword
     * @param $password
     * @return bool
     * @author: Admin
     * @Time: 2022/2/22 14:52
     */
    public function validatePassword ( $password )
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @Notes: 生成密码
     * @Function setPassword
     * @param $password
     * @throws \yii\base\Exception
     * @author: Admin
     * @Time: 2022/2/22 14:52
     */
    public function setPassword ( $password )
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @Notes: Generates "remember me" authentication key
     * @Function generateAuthKey
     * @throws \yii\base\Exception
     * @author: Admin
     * @Time: 2022/2/22 14:52
     */
    public function generateAuthKey ()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @Notes: Generates new password reset token
     * @Function generatePasswordResetToken
     * @throws \yii\base\Exception
     * @author: Admin
     * @Time: 2022/2/22 14:53
     */
    public function generatePasswordResetToken ()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @Notes: Removes password reset token
     * @Function removePasswordResetToken
     * @author: Admin
     * @Time: 2022/2/22 14:53
     */
    public function removePasswordResetToken ()
    {
        $this->password_reset_token = null;
    }
}
