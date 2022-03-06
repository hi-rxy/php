<?php
// +----------------------------------------------------------------------
// | 邮件设置
// +----------------------------------------------------------------------
return [
    'class'             => 'yii\swiftmailer\Mailer',
    'viewPath'          => '@common/mail',
    'useFileTransport'  => false,
    'transport'         => [
        'class'         => 'Swift_SmtpTransport',
        'host'          => $params['EMAIL_HOST'],
        'username'      => $params['EMAIL_USERNAME'],
        'password'      => $params['EMAIL_PASSWORD'],
        'port'          => $params['EMAIL_PORT'],
        'encryption'    => $params['EMAIL_SMTP_SECURE']
    ],
    'messageConfig'     => [
        'charset'       => 'UTF-8',
        'from'          => [$params['EMAIL_SETFROM_ADDRESS'] => $params['EMAIL_SETFROM_NAME']]
    ],
];