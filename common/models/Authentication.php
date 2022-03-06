<?php
namespace common\models;

use common\services\Service;
use Yii;
use yii\web\IdentityInterface;

class Authentication extends Service implements IdentityInterface
{
    const STATUS_DELETED = 0; // 用户禁用状态
    const STATUS_ACTIVE = 10; // 用户正常状态
    const STATUS_CARD_ACTIVE = 1; // 身份证已审核
    const STATUS_CARD_NOT_ACTIVE = 0; // 用户正未审核
    const STATUS_CARD_DELETED = 2; // 用户正审核拒绝

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (!static::isPasswordResetTokenValid($token)) return false;
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) return false;
        $timestamp  = (int)substr($token, strrpos($token, '_') + 1);
        $expire     = Yii::$app->params['user.passwordResetTokenExpire'];
        return ($timestamp + $expire) >= time();
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
}