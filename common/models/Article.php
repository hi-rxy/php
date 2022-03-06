<?php

namespace common\models;

use common\services\Service;
use jinxing\admin\helpers\Helper;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property int $id 文章自增id
 * @property int $category_id 文章分类id
 * @property int $type 类型.0文章,1单页
 * @property string $title 文章标题
 * @property string $summary 文章概要
 * @property string $thumb 缩略图
 * @property string $seo_title seo标题
 * @property string $seo_keywords seo关键字
 * @property string $seo_description seo描述
 * @property int $status 状态.0草稿,1发布
 * @property int $sorts 排序
 * @property int $author_id 发布文章管理员id
 * @property string $author_name 发布文章管理员用户名
 * @property int $scan_count 浏览次数
 * @property int $comment_count 评论次数
 * @property int $can_comment 是否可评论.0否,1是
 * @property int $visibility 文章可见性.1.公开,2.评论可见,3.加密文章,4.登录可见
 * @property int $is_headline 头条.0否,1.是
 * @property int $is_recommend 推荐.0否,1.是
 * @property int $is_slide_show 幻灯.0否,1.是
 * @property int $is_special_recommend 特别推荐.0否,1.是
 * @property int $is_roll 滚动.0否,1.是
 * @property int $is_bold 加粗.0否,1.是
 * @property int $is_picture 图片.0否,1.是
 * @property string $template 文章模板
 * @property int $created_at 创建时间
 * @property int $updated_at 最后修改时间
 */
class Article extends Service
{
    const STATUS_NO = 0; // 否
    const STATUS_YES = 1; // 是

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'type', 'status', 'sorts', 'author_id', 'scan_count', 'comment_count', 'can_comment', 'visibility', 'is_headline', 'is_recommend', 'is_slide_show', 'is_special_recommend', 'is_roll', 'is_bold', 'is_picture', 'created_at', 'updated_at'], 'integer'],
            // required
            [['title'], 'required'],
            // scenarios 设置场景
            [['sorts'], 'integer', 'on' => ['sorts']],
            [['is_headline'], 'integer', 'on' => ['is_headline']],
            [['is_recommend'], 'integer', 'on' => ['is_recommend']],
            [['is_slide_show'], 'integer', 'on' => ['is_slide_show']],
            [['is_special_recommend'], 'integer', 'on' => ['is_special_recommend']],
            [['is_roll'], 'integer', 'on' => ['is_roll']],
            [['is_bold'], 'integer', 'on' => ['is_bold']],
            [['status'], 'integer', 'on' => ['status']],
            // trim
            [['title', 'seo_title', 'seo_keywords', 'seo_description', 'author_name'], 'trim'],
            [['title', 'summary', 'thumb', 'seo_title', 'seo_keywords', 'seo_description', 'author_name', 'template'], 'string', 'max' => 255],
            // status
            [['status', 'is_headline', 'is_picture', 'is_recommend', 'is_slide_show', 'is_special_recommend', 'is_roll', 'is_bold'], 'default', 'value' => self::STATUS_NO],
            [['status', 'is_headline', 'is_picture', 'is_recommend', 'is_slide_show', 'is_special_recommend', 'is_roll', 'is_bold'], 'in', 'range' => [self::STATUS_NO, self::STATUS_YES]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'type' => 'Type',
            'title' => 'Title',
            'summary' => 'Summary',
            'thumb' => 'Thumb',
            'seo_title' => 'Seo Title',
            'seo_keywords' => 'Seo Keywords',
            'seo_description' => 'Seo Description',
            'status' => 'Status',
            'sorts' => 'Sorts',
            'author_id' => 'Author ID',
            'author_name' => 'Author Name',
            'scan_count' => 'Scan Count',
            'comment_count' => 'Comment Count',
            'can_comment' => 'Can Comment',
            'visibility' => 'Visibility',
            'is_headline' => 'Is Headline',
            'is_recommend' => 'Is Recommend',
            'is_slide_show' => 'Is Slide Show',
            'is_special_recommend' => 'Is Special Recommend',
            'is_roll' => 'Is Roll',
            'is_bold' => 'Is Bold',
            'is_picture' => 'Is Picture',
            'template' => 'Template',
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

    /**
     * 删除文章内容
     */
    public function afterDelete()
    {
        ArticleContent::deleteAll(['article_id' => $this->id]);
        parent::afterDelete();
    }

    /**
     * 关联文章分类表
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->alias('category_');
    }

    /**
     * 关联文章内容表
     * @return ActiveQuery
     */
    public function getArticleContent()
    {
        return $this->hasOne(ArticleContent::className(), ['article_id' => 'id'])->alias('article_content_');
    }

    /**
     * 关联文章评论表
     * @return ActiveQuery
     */
    public function getComment()
    {
        return $this->hasMany(Comment::className(), ['article_id' => 'id'])->alias('comment_');
    }

    /**
     * 状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getBoolStatus($intStatus = null)
    {
        $array = [
            self::STATUS_NO => '否',
            self::STATUS_YES => '是'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 状态值
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_NO => '草稿',
            self::STATUS_YES => '发布'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     *
     * @param int $intStatus 状态值
     *
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_NO => 'btn-danger',
            self::STATUS_YES => 'btn-success'
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取实例
     * @param $id
     * @return mixed
     */
    public static function getInstance ($id)
    {
        return self::findModel(self::className(),$id);
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

            // 对model对象各个字段进行赋值
            $ar->load($options, '');

            // 判断修改返回数据
            if (!$ar->save()) {
                throw new Exception(Helper::arrayToString($ar->getErrors()));
            }

            // 保存文章内容
            $content = ArticleContent::findOne(['article_id' => $ar->id]);
            if (is_null($content)) $content = new ArticleContent();
            $content->article_id = $ar->id;
            $content->content = $options['content'];
            if (!$content->save()) {
                $transaction->rollBack();
                throw new Exception(Helper::arrayToString($content->getErrors()));
            }

            $transaction->commit();

            return $ar;
        } catch (yii\base\Exception $exception) {
            $transaction->rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * 修改
     * @param ActiveRecord $ar
     * @param array $options
     * @return string|void|ActiveRecord
     */
    public static function doUpdate(ActiveRecord $ar , array $options = [])
    {
        /** @var Transaction $transaction */
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 验证是否定义了创建对象的验证场景
            if (ArrayHelper::getValue($ar->scenarios(), 'update')) {
                $ar->scenario = 'update';
            }

            // 对model对象各个字段进行赋值
            $ar->load($options, '');

            // 判断修改返回数据
            if (!$ar->save()) {
                throw new Exception(Helper::arrayToString($ar->getErrors()));
            }

            // 保存文章内容
            $content = ArticleContent::findOne(['article_id' => $ar->id]);
            if (is_null($content)) $content = new ArticleContent();
            $content->article_id = $ar->id;
            $content->content = $options['content'];
            if (!$content->save()) {
                $transaction->rollBack();
                throw new Exception(Helper::arrayToString($content->getErrors()));
            }

            $transaction->commit();

            return $ar;
        } catch (yii\base\Exception $exception) {
            $transaction->rollBack();
            return $exception->getMessage();
        }
    }

    /**
     * 初始化字段
     * @param $post
     * @param $field
     * @return int
     */
    public static function initField ($post,$field)
    {
        return (isset($post[$field]) && $post[$field] === 'on') ? 1 : 0;
    }

    /**
     * 返回信息
     * @param $post
     * @return mixed
     */
    public static function formatData ($post)
    {
        // 初始化字段值
        $post['is_headline']    = self::initField($post, 'is_headline'); // 头条
        $post['is_recommend']   = self::initField($post, 'is_recommend'); // 推荐
        $post['is_slide_show']  = self::initField($post, 'is_slide_show'); // 幻灯
        $post['is_special_recommend'] = self::initField($post, 'is_special_recommend'); // 特别推荐
        $post['is_roll']        = self::initField($post, 'is_roll'); // 滚动
        $post['is_bold']        = self::initField($post, 'is_bold'); // 加粗
        $post['author_id']      = Yii::$app->user->id; // 文章发布人id
        $post['author_name']    = Yii::$app->user->identity->username; // 文章发布人姓名
        return $post;
    }
}
