<?php
require_once 'vendor/autoload.php';
class Mail
{
    protected $smtp = array(
        'host' => 'mailcatcher',
        'port' => 1025,
    );
    public static function sendMail($to, $subject, $message, $from, $path)
    {

        $transport = (new Swift_SmtpTransport('mailcatcher', 1025))
            ->setUsername(null)
            ->setPassword(null);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
            ->setFrom([$from => 'Csaba'])
            ->setTo([$to => 'Csaba'])
            ->setBody($message)
            ->attach(Swift_Attachment::fromPath($path));

        $result = $mailer->send($message);
    }
}
