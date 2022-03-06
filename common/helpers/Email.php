<?php
namespace common\helpers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Yii;

require __DIR__ . '/../../vendor/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../../vendor/phpmailer/src/SMTP.php';

/**
* SMTP邮件发送
*/
class Email
{
    public $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer();
        // 是否启用SMTP的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $this->mailer->SMTPDebug = Yii::$app->params['site']['EMAIL_SMTP_DEBUG'];
        // 使用SMTP方式发送邮件
        $this->mailer->IsSMTP();
    }

    /**
     * 返回当前实例化邮件类对象
     * @return PHPMailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * 加载配置
     */
    private function loadConfig()
    {
        /* Server Settings  */
        // 开启 SMTP 认证
        $this->mailer->SMTPAuth = Yii::$app->params['site']['EMAIL_SMTP_AUTH'] ? true : false;
        // 设置SMTP服务器
        $this->mailer->Host = Yii::$app->params['site']['EMAIL_HOST'];
        // 设置ssl连接smtp服务器的远程服务器端口号 可选465或587
        $this->mailer->Port = Yii::$app->params['site']['EMAIL_PORT'];
        // 设置启用加密，注意：必须打开 php_openssl 模块
        $this->mailer->SMTPSecure = Yii::$app->params['site']['EMAIL_SMTP_SECURE'];

        /* Account Settings */
        // SMTP 登录账号
        $this->mailer->Username = Yii::$app->params['site']['EMAIL_USERNAME'];
        // SMTP 登录密码
        $this->mailer->Password = Yii::$app->params['site']['EMAIL_PASSWORD'];
        // 发件人邮箱地址
        $this->mailer->From = Yii::$app->params['site']['EMAIL_SENDER_ADDRESS'];
        // 发件人昵称（任意内容）
        $this->mailer->FromName = Yii::$app->params['site']['EMAIL_SENDER_NAME'];

        /* Content Setting  */
        $this->mailer->IsHTML(false); // 邮件正文是否为 HTML
        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $this->mailer->CharSet = 'UTF-8';
    }

    /**
     * 附件
     * @param $path
     * @throws Exception
     */
    public function addAttachment($path)
    {
        $this->mailer->addAttachment($path);
    }

    /**
     * 发送邮件
     * @param $email
     * @param $title
     * @param $content
     * @return bool
     * @throws Exception
     */
    public function send($email, $title, $content)
    {
        $this->loadConfig();
        // 收件人邮箱
        $this->mailer->addAddress($email);
        // 设置邮件标题
        $this->mailer->Subject = $title;
        // 设置邮件正文
        $this->mailer->Body = $content;

        // 发送邮件
        return (bool)$this->mailer->send();
    }
}