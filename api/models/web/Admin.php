<?php

namespace api\models\web;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $face
 * @property string $auth_key
 * @property array $role
 * @property integer $status
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_id
 * @property integer $created_id
 * @property integer $last_time
 * @property string $last_ip
 */
class Admin extends AdminUser
{
    public $password;
    public $rePassword;

    /**
     * @var integer 超级管理员ID
     */
    const SUPER_ADMIN_ID = 1;

    /**
     * @Notes: 获取状态说明信息
     * @Function: getArrayStatus
     * @param $intStatus
     * @return array|mixed
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:35
     */
    public static function getArrayStatus ( $intStatus = null )
    {
        $array = [
            self::STATUS_ACTIVE => Yii::t('admin', '启用'),
            self::STATUS_INACTIVE => Yii::t('admin', '禁用'),
        ];
        if ($intStatus !== null && isset($array[ $intStatus ])) $array = $array[ $intStatus ];
        return $array;
    }

    /**
     * @Notes: 获取状态值对应颜色信息
     * @Function: getStatusColor
     * @param $intStatus
     * @return string|string[]
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:34
     */
    public static function getStatusColor ( $intStatus = null )
    {
        $array = [
            self::STATUS_ACTIVE => 'enable',
            self::STATUS_INACTIVE => 'disable',
        ];
        if ($intStatus !== null && isset($array[ $intStatus ])) $array = $array[ $intStatus ];
        return $array;
    }

    /**
     * @param array $role
     */
    public function setRole ( $role )
    {
        $this->role = implode(',',$role);
    }

    /**
     * @Notes: 获取角色信息
     * @Function: getArrayRole
     * @param $user_id
     * @param $isDelete
     * @return array
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:33
     */
    public static function getArrayRole ( $user_id, $isDelete = true )
    {
        $auth = Yii::$app->authManager; // 权限对象
        $roles = $user_id == self::SUPER_ADMIN_ID ? $auth->getRoles() : $auth->getRolesByUser($user_id);
        if ($roles && $isDelete && isset($roles[ Auth::SUPER_ADMIN_NAME ])) unset($roles[ Auth::SUPER_ADMIN_NAME ]);
        return ArrayHelper::map($roles, 'name', 'description');
    }

    /**
     * @Notes: 验证规则
     * @Function: rules
     * @return array|array[]
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:31
     */
    public function rules ()
    {
        return [
            [ [ 'username', 'email' ], 'required' ],
            [ [ 'password', 'rePassword', 'role' ], 'required', 'on' => [ 'create' ] ],
            [ [ 'username', 'email', 'password', 'rePassword' ], 'trim' ],
            [ [ 'password', 'rePassword' ], 'string', 'min' => 6, 'max' => 30 ],
            // Unique
            [ [ 'username', 'email' ], 'unique' ],
            // Username
            [ 'username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/' ],
            [ 'username', 'string', 'min' => 3, 'max' => 30 ],
            // E-mail
            [ [ 'email' ], 'string', 'max' => 64 ],
            [ [ 'face', 'address' ], 'string', 'max' => 100 ],
            [ 'email', 'email' ],
            [ [ 'age', 'sex' ], 'integer' ],
            // Repassword
            [ 'rePassword', 'compare', 'compareAttribute' => 'password' ],
            //['status', 'default', 'value' => self::STATUS_ACTIVE],
            [ 'status', 'in', 'range' => [ self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED ] ],
            // Status
            //[ 'role', 'in', 'range' => array_keys(self::getArrayRole(Yii::$app->controller->module->getUserId(), false)) ],
            [ 'role', 'validateRoles'],
        ];
    }

    /**
     * @Notes: 验证场景
     * @Function: scenarios
     * @return \string[][]
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:31
     */
    public function scenarios ()
    {
        return [
            'default' => [ 'username', 'email', 'password', 'rePassword', 'status', 'role', 'face' ],
            'create' => [ 'username', 'email', 'password', 'rePassword', 'status', 'role', 'face' ],
            'update' => [ 'username', 'email','password', 'rePassword', 'status', 'role', 'face' ],
            'state' => [ 'status','id' ],
        ];
    }

    /**
     * @Notes: 验证角色
     * @Function: validateRoles
     * @param $attribute
     * @param $params
     * @return bool|void
     * @Author: 17908
     * @Time: 2022/2/27 0027 21:35
     */
    public function validateRoles($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // 可分配的角色集合
            $user_roles = array_keys(self::getArrayRole(Yii::$app->controller->module->getUserId(), false));
            // 等待分配的角色集合
            $roles = $this->role;
            // 出现差集，说明role不可分配
            $diff_role = array_diff($roles,$user_roles);
            if ($diff_role) {
                $this->addError($attribute, implode(',',$diff_role).'不可分配权限');
            }
        }
    }

    /**
     * @Notes: 字段信息
     * @Function: attributeLabels
     * @return array|string[]
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:31
     */
    public function attributeLabels ()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'face' => '头像信息',
                'last_time' => '上一次登录时间',
                'last_ip' => '上一次登录的IP',
                'password' => '密码',
                'repassword' => '确认密码',
            ]
        );
    }

    /**
     * @Notes: 新增或者修改了密码信息
     * @Function: beforeSave
     * @param $insert
     * @return bool
     * @throws \yii\base\Exception
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:30
     */
    public function beforeSave ( $insert )
    {
        if ($this->scenario != 'state') {
            $this->setRole($this->role);
        }
        if ($this->isNewRecord || $this->password ) {
            $this->setPassword($this->password);
            $this->generateAuthKey();
            $this->generatePasswordResetToken();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @Notes: 新增或者修改了角色信息
     * @Function: afterSave
     * @param $insert
     * @param $changedAttributes
     * @return void
     * @throws \Exception
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:29
     */
    public function afterSave ( $insert, $changedAttributes )
    {
        // 只有在新增或者修改了角色信息，那么才要修改角色信息
        if ($insert || !empty($changedAttributes['role']))
        {
            if (!$changedAttributes['role']) {
                $beforeUpdateRoles = [];
            } else {
                $beforeUpdateRoles = explode(',',$changedAttributes['role']);
            }
            $userSelectRoles = explode(',',$this->role);

            $addRoles = array_diff($userSelectRoles,$beforeUpdateRoles);
            $delRoles = array_diff($beforeUpdateRoles,$userSelectRoles);

            $auth = Yii::$app->authManager;
            // 修改了角色信息，删除之前的角色信息
            if ($delRoles) {
                foreach ($delRoles as $role) {
                    // 不删除超级管理员的角色
                    if ($this->id != Admin::SUPER_ADMIN_ID) $auth->revoke($auth->getRole($role), $this->id);
                }
            }

            if ($addRoles) {
                $isInsert = true;
                foreach ($addRoles as $role) {
                    // 没有存在这个角色才新增
                    if (in_array($this->id, $auth->getUserIdsByRole($role))) $isInsert = false;
                    // 添加角色
                    if ($isInsert) $auth->assign($auth->getRole($role), $this->id);
                }
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @Notes: 删除之前的处理-验证不能删除超级管理员和自己
     * @Function: beforeDelete
     * @return bool
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:29
     */
    public function beforeDelete ()
    {
        if ($this->id == self::SUPER_ADMIN_ID) {
            $this->addError('username', '不能删除超级管理员');
            return false;
        }

        if ($this->id == Yii::$app->controller->module->getUserId()) {
            $this->addError('username', '不能删除自己');
            return false;
        }

        return parent::beforeDelete();
    }

    /**
     * @Notes: 删除之后的处理删除缓存
     * @Function: afterDelete
     * @return void
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:30
     */
    public function afterDelete ()
    {
        // 移出权限信息
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete();
    }

    /**
     * @Notes: 获取管理员信息
     * @Function: getAdmins
     * @return array
     * @Author: 17908
     * @Time: 2022/2/24 0024 22:30
     */
    public static function getAdmins ()
    {
        return ArrayHelper::map(static::findAll([ 'status' => static::STATUS_ACTIVE ]), 'id', 'username');
    }
}
