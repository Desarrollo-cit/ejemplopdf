<?php

namespace Controllers;

use Exception;
use MVC\Router;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailController
{
    public static function email(Router $router)
    {
        $email = new PHPMailer(true);
        $email->SMTPOptions = [
            "ssl" => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];
        try {
            // $email->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $email->isSMTP();                                            //Send using SMTP
            $email->Host = $_ENV['MAIL_HOST'];                     //Set the SMTP server to send through
            $email->SMTPAuth = true;                                   //Enable SMTP authentication
            $email->Username = $_ENV['MAIL_USERNAME'];                     //SMTP username
            $email->Password = $_ENV['MAIL_PASSWORD'];                               //SMTP password
            $email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $email->Port = $_ENV['MAIL_PORT'];
            $email->CharSet = "UTF-8";
            $email->AddReplyTo($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $email->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $email->isHTML();
            $imagePath = __DIR__ . '/../public/images/cit.png';
            $email->AddEmbeddedImage($imagePath, 'hola', 'cit.png');
            $html = $router->load('email/saludo');
            $email->Body = $html;
            $email->Subject = "Prueba de correo";
            $email->addAddress('afuentesj1@miumg.edu.gt', 'ABNER FUENTES');

            $email->send();


            echo "correo enviado";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}