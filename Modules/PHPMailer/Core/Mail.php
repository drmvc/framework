<?php

namespace Modules\PHPMailer\Core;

/**
 * Custom class for PHPMailer to uniform sending emails.
 */
class Mail extends PHPMailer
{
    public $From     = 'my@mail.com';
    public $FromName = TITLE;
    public $Host     = 'smtp.mail.com';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;
    public $Username = 'client@hotmail.org';
    public $Password = 'YourPassword';
    public $SMTPSecure = 'tls';
    public $WordWrap = 75;

    /**
     * Subject
     *
     * @param  string $subjt The subject of the email
     */
    public function subject($subject)
    {
        $this->Subject = $subject;
    }

    /**
     * Body
     *
     * @param  string $body The content of the email
     */
    public function body($body)
    {
        $this->Body = $body;
    }

    /**
     * Send
     *
     * @return mixed - sends the email.
     */
    public function send()
    {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }
}
