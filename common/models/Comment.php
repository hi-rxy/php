<?php
namespace common\models;

use common\services\Service;
use jinxing\admin\helpers\Helper;
use jinxing\admin\models\Admin;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id 自增id
 * @property int $article_id 文章id
 * @property int $user_id 用户id,游客为0
 * @property int $admin_id 管理员id,其他人员对其回复为0
 * @property int $reply_to 回复的评论id
 * @property string $nickname 昵称
 * @property string $email 邮箱
 * @property string $website_url 个人网址
 * @property string $content 回复内容
 * @property string $ip 回复ip
 * @property int $status 状态,0.未审核,1.已通过
 * @property int $created_at 创建时间
 * @property int $updated_at 最后修改时间
 */
class Comment extends Service
{
    const STATUS_PEND_REVIEW = 0; // 待审核
    const STATUS_PASS = 1; // 通过
    const STATUS_NOT_PASS = 2; // 未通过

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'user_id', 'admin_id', 'reply_to', 'status', 'created_at', 'updated_at'], 'integer'],
            // required
            [['content'], 'required'],
            // trim
            [['nickname','content'], 'trim'],
            [['nickname', 'email', 'website_url', 'content', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'user_id' => 'User ID',
            'admin_id' => 'Admin ID',
            'reply_to' => 'Reply To',
            'nickname' => 'Nickname',
            'email' => 'Email',
            'website_url' => 'Website Url',
            'content' => 'Content',
            'ip' => 'Ip',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 自动把时间戳填充指定的属性
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ]
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->status = 1;
            $this->admin_id = Yii::$app->user->id;
            return true;
        }
        return false;
    }

    /**
     * 状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_PEND_REVIEW => '待审核',
            self::STATUS_PASS => '通过',
            self::STATUS_NOT_PASS => '未通过',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     * @param int $intStatus 状态值
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_PEND_REVIEW => 'btn-primary',
            self::STATUS_PASS => 'btn-success',
            self::STATUS_NOT_PASS => 'btn-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 关联文章表
     * @return ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id'])->alias('article_');
    }

    /**
     * 关联用户表
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->alias('user_');
    }

    /**
     * 关联管理员表
     * @return ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admin::className(), ['id' => 'admin_id'])->alias('admin_');
    }

    /**
     * 获取昵称
     * @param $reply_to
     * @return array
     */
    private static function getNickname ($reply_to)
    {
        return self::find()->select(['nickname'])->where(['id' => $reply_to])->column();
    }

    /**
     * 添加
     * @param ActiveRecord $ar
     * @param array $options
     * @return string|void|ActiveRecord
     */
    public static function doCreate(ActiveRecord $ar , array $options = [])
    {
        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 验证是否定义了创建对象的验证场景
            if (ArrayHelper::getValue($ar->scenarios(), 'create')) {
                $ar->scenario = 'create';
            }

            if (!Article::find()->where(['id' => $options['articleId']])->count()) {
                throw new Exception('文章错误');
            }

            $comment['article_id']  = $options['articleId'];
            $comment['nickname']    = !$options['comment_id'] ? $options['name'] : rand(0000, 9999);
            $comment['content']     = $options['content'];
            $comment['reply_to']    = $options['comment_id'];

            $ar->load($comment,'');

            if (!$ar->save()) {
                throw new Exception(Helper::arrayToString($ar->getErrors()));
            }

            $transaction->commit();
            return $ar;
        } catch (yii\base\Exception $exception) {
            $transaction->rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * 格式数据
     * @param $data
     * @return array
     */
    public static function handleCommentData ($data)
    {
        $items = array();
        foreach ($data as $val) {
            $items[$val['id']] = [
                'id'            => $val['id'],
                'nickname'      => $val['nickname'],
                'reply_to'      => $val['reply_to'],
                'reply_name'    => $val['reply_to'] ? self::getNickname($val['reply_to'])[0] : '',
                'content'       => $val['content'],
                'created_at'    => Yii::$app->formatter->asRelativeTime($val['created_at']),
            ];
        }

        $tree = [];
        foreach ($items as $k => $val) {
            if (isset($items[$val['reply_to']])) {
                $items[$val['reply_to']]['child'][] = &$items[$k];
            } else {
                $tree[] = &$items[$k];
            }
        }
        return $tree;
    }
}
