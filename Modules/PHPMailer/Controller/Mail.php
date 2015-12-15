<?php

namespace Application\Controllers;

use Modules\PHPMailer\Core\Mail as Mail_Core;
use System\Core\Controller;

/**
 * Class Mail - example controller for your application
 *
 * @package Application\Controllers
 */
class Mail extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Example mail action
     */
    public function action_mail()
    {
        // If all values is set
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
            $mail = new Mail_Core();

            // To user
            $mail->addAddress('client@email.com');

            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            $body = null;
            $body .= "From: $name<br/>\n";
            $body .= "Email: <a href='mailto:$email'>$email</a><br/>\n";
            $body .= "Message: <br/>\n$message<br/>\n";

            // Message
            $mail->subject(TITLE . ' - Message from site');
            $mail->body($body);
            $mail->isHTML(true);

            // Send
            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }
        }
    }
}
