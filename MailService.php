class MailService
{

    private static ?MailService $instance = null;

    private PHPMailer $mailer;

    private ?string $senderName = null;

    // Конфигурируем базовые параметры в одиночном сервисе,
    // потому что Битрикс отправляет письма пачками и
    // нам незачем каждый раз пересоздавать подключение
    private function __construct()
    {
        $config = \Bitrix\Main\Config\Configuration::getInstance()->get('custom_smtp');
        if (empty($config)) {
            throw new \Exception('Не заданы настройки для SMTP подключения в файле .settings.php');
        }

        $this->mailer = new PHPMailer(true);
        $this->mailer->SMTPDebug = $config['debug'] ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = (int)$config['port'];
        $this->senderName = $config['sender'];
    }

    public function sendMail(EmailMessage $message): bool
    {
        try {

            $this->mailer->setFrom($message->getFrom(), $this->senderName);
            $to = $message->getTo();

            if (is_array($to)) {
                foreach ($to as $email) {
                    $this->mailer->addAddress($email);
                }
            } else {
                $this->mailer->addAddress($to);
            }

            $cc = $message->getCc();
            if ($cc) {
                $this->mailer->addCC($cc);
            }

            $bcc = $message->getBcc();
            if ($bcc) {
                $this->mailer->addBCC($bcc);
            }

            $replyTo = $message->getReplyTo();
            if ($replyTo) {
                $this->mailer->addReplyTo($replyTo);
            }

            $this->mailer->Subject = $message->getSubject();
            $this->mailer->Body = $message->getBody();
            $this->mailer->ContentType = $message->getContentType();

            $this->mailer->send();
            return true;
            //echo 'Message has been sent';
        } catch (\Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
            return false;
        }
    }

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __wakeup()
    {
    }

    public function __clone()
    {
    }
}
