<?php

namespace Contact\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Zend\ServiceManager\ServiceManager;

class MailSender
{
    protected $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Sends the mail message.
     * @var $subject
     * @var $email
     * @var $message
     * @return boolean
     */
    public function sendMail($subject, $email, $message)
    {
        $config = $this->serviceManager->get('config');

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 1;
            $mail->isSMTP();
            $mail->Host = $config['smtp']['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp']['connection_config']['username'];
            $mail->Password = $config['smtp']['connection_config']['password'];
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('' . $email . '', $email);
            $mail->addAddress($config['smtp']['email'], 'Contact Form');

            $mail->isHTML(true);
            $mail->Subject = '' . $subject . '';
            $mail->Body = '' . $message . '';
            $mail->AltBody = '' . $message . '';

            $mail->send();
            $result = true;
        } catch (Exception $e) {

            $result = false;
        }
        return $result;

    }

}