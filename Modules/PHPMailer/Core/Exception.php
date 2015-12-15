<?php
/**
 * PHPMailer exception handler
 * @package PHPMailer
 * @date May 18 2015
 */

namespace Modules\PHPMailer\Core;

/**
 * Exceptions for PHPMailer
 */
class PHPMailerException extends \Exception
{
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        echo $errorMsg;
    }
}
