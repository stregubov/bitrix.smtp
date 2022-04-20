# bitrix.smtp
SMTP отправка email

В первую очередь в php_interface/init.php определяем функцию custom_mail (почему именно custom_mail тут https://dev.1c-bitrix.ru/api_help/main/functions/other/bxmail.php). 
 Затем устанавливаем PHPMailer (https://github.com/PHPMailer/PHPMailer) через composer
 composer require phpmailer/phpmailer

Потом в bitrix/.settings.php дописываем параметры для отправителя

```php
...
'custom_smtp' => array(
    'value' => array(
        'host' => 'smtp.gmail.com',
        'username' => 'test@test.com',
        'password' => '111111',
        'debug' => false,
        'port' => 465,
        'sender' => 'my-sender'
    )
),
...
```

Сама функция custom_mail

```php
function custom_mail($to, $subject, $message, $headers = "", $params = ""){

    $arHeaders = explode(PHP_EOL, $headers);
    $arMail = explode(",", $to);

    $arMail = array_map(function ($item) {
        return trim($item);
    }, $arMail);

    $mail = new EmailMessage();
    $mail->setTo($arMail);
    $mail->setHeaders($arHeaders);
    $mail->setBody($message);
    $mail->setSubject($subject);

    $mailer = MailService::getInstance();
    return $mailer->sendMail($mail);
}

```
