<?php
/**
 * Error - class for return errors
 */

namespace System\Core;

/**
 * Error class to generate 404 pages.
 */
class Error extends Controller
{
    /**
     * $error holder.
     *
     * @var string
     */
    private $error = null;

    /**
     * Save error to $this->error.
     *
     * @param string $error
     */
    public function __construct($error)
    {
        parent::__construct();
        $this->error = $error;
    }

    /**
     * Display errors
     *
     * @param  array  $error an error of errors
     * @param  string $class name of class to apply to div
     * @return string return the errors inside divs
     */
    public static function display($error, $class = 'alert alert-danger')
    {
        $row = null;

        if (is_array($error)) {
            // If array is not empty
            if (!empty($error)) {
                foreach ($error as $show_error) {
                    $row .= "<div class='$class'>$show_error</div>";
                }
                return $row;
            } else {
                // No errors
                return false;
            }
        } else {
            if (isset($error)) {
                return "<div class='$class'>$error</div>";
            } else {
                // No errors
                return false;
            }
        }
    }
}
