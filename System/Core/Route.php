<?php namespace System\Core;

/**
 * Class Route for generate and analyze
 * @package System\Core
 */
class Route
{

    // Matches a URI group and captures the contents
    const REGEX_GROUP   = '\(((?:(?>[^()]+)|(?R))*)\)';

    // Defines the pattern of a <segment>
    const REGEX_KEY     = '<([a-zA-Z0-9_]++)>';

    // What can be part of a <segment> value
    //const REGEX_SEGMENT = '[^/.,;?\n]++';
    const REGEX_SEGMENT = '[^/,;?\n]++';

    // What must be escaped in the route regex
    const REGEX_ESCAPE  = '[.\\+*?[^\\]${}=!|]';

    /**
     * @var  array
     */
    protected static $_routes = array();

    /**
     * Returns the compiled regular expression for the route. This translates
     * keys and optional groups to a proper PCRE regular expression.
     */
    public static function compile($uri, array $regex = NULL)
    {
        // The URI should be considered literal except for keys and optional parts
        // Escape everything preg_quote would escape except for : ( ) < >
        $expression = preg_replace('#'.Route::REGEX_ESCAPE.'#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== FALSE)
        {
            // Make optional parts of the URI non-capturing and optional
            $expression = str_replace(array('(', ')'), array('(?:', ')?'), $expression);
        }

        // Insert default regex for keys
        $expression = str_replace(array('<', '>'), array('(?P<', '>'.Route::REGEX_SEGMENT.')'), $expression);

        if ($regex)
        {
            $search = $replace = array();
            foreach ($regex as $key => $value)
            {
                $search[]  = "<$key>".Route::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            // Replace the default regex with the user-specified regex
            $expression = str_replace($search, $replace, $expression);
        }

        return '#^'.$expression.'$#uD';
    }

    /**
     * Stores a named route and returns it. The "action" will always be set to
     * "index" if it is not defined.
     */
    public static function set($name, $uri = NULL, $regex = NULL)
    {
        return Route::$_routes[$name] = new Route($uri, $regex);
    }

    /**
     * Retrieves a named route.
     */
    public static function get($name)
    {
        if (isset(Route::$_routes[$name])) {
            return Route::$_routes[$name];
        } else {
            return false;
        }
    }

    /**
     * Retrieves all named routes.
     */
    public static function all()
    {
        return Route::$_routes;
    }

    protected $_defaults = array('controller' => 'Index', 'action' => 'index');

    /**
     * Creates a new route. Sets the URI and regular expressions for keys.
     * Routes should always be created with [Route::set] or they will not
     * be properly stored.
     */
    public function __construct($uri = NULL, $regex = NULL)
    {
        if ($uri === NULL)
        {
            // Assume the route is from cache
            return;
        }

        if ( ! empty($uri))
        {
            $this->_uri = $uri;
        }

        if ( ! empty($regex))
        {
            $this->_regex = $regex;
        }

        // Store the compiled regex locally
        $this->_route_regex = Route::compile($uri, $regex);
    }

    /**
     * Provides default values for keys when they are not present. The default
     * action will always be "index" unless it is overloaded here.
     *
     * @param array $defaults
     * @return $this|array
     */
    public function defaults(array $defaults = NULL)
    {
        if ($defaults === NULL) {
            return $this->_defaults;
        }

        $this->_defaults = $defaults;

        return $this;
    }

    /**
     * Tests if the route matches a given Request. A successful match will return
     * all of the routed parameters as an array. A failed match will return
     * boolean FALSE.
     */
    public function matches(Request $request)
    {
        // Get the URI from the Request
        $uri = trim($request->uri(), '/');
// 		$uri = $request->uri();

        if ( ! preg_match($this->_route_regex, $uri, $matches))
            return FALSE;

        $params = array();
        foreach ($matches as $key => $value)
        {
            if (is_int($key))
            {
                // Skip all unnamed keys
                continue;
            }

            // Set the value for all matched keys
            $params[$key] = $value;
// 			//echo $value;die();
        }

        foreach ($this->_defaults as $key => $value)
        {
            if ( ! isset($params[$key]) OR $params[$key] === '')
            {
                // Set default values for any key that was not matched
                $params[$key] = $value;
            }
        }

        if ( ! empty($params['controller']))
        {
            // PSR-0: Replace underscores with spaces, run ucwords, then replace underscore
            $params['controller'] = str_replace(' ', '_', ucwords(str_replace('_', ' ', $params['controller'])));
        }

        if ( ! empty($params['directory']))
        {
            // PSR-0: Replace underscores with spaces, run ucwords, then replace underscore
            $params['directory'] = str_replace(' ', '_', ucwords(str_replace('_', ' ', $params['directory'])));
        }

        return $params;
    }

    /**
     * Generates a URI for the current route based on the parameters given.
     *
     *     // Using the "default" route: "users/profile/10"
     *     $route->uri(array(
     *         'controller' => 'users',
     *         'action'     => 'profile',
     *         'id'         => '10'
     *     ));
     */
    public function uri(array $params = NULL)
    {
        $defaults = $this->_defaults;

        /**
         * Recursively compiles a portion of a URI specification by replacing
         * the specified parameters and any optional parameters that are needed.
         *
         * @param   string  $portion    Part of the URI specification
         * @param   boolean $required   Whether or not parameters are required (initially)
         * @return  array   Tuple of the compiled portion and whether or not it contained specified parameters
         */
        $compile = function ($portion, $required) use (&$compile, $defaults, $params)
        {
            $missing = array();

            $pattern = '#(?:'.Route::REGEX_KEY.'|'.Route::REGEX_GROUP.')#';
            $result = preg_replace_callback($pattern, function ($matches) use (&$compile, $defaults, &$missing, $params, &$required)
            {
                if ($matches[0][0] === '<')
                {
                    // Parameter, unwrapped
                    $param = $matches[1];

                    if (isset($params[$param]))
                    {
                        // This portion is required when a specified
                        // parameter does not match the default
                        $required = ($required OR ! isset($defaults[$param]) OR $params[$param] !== $defaults[$param]);

                        // Add specified parameter to this result
                        return $params[$param];
                    }

                    // Add default parameter to this result
                    if (isset($defaults[$param]))
                        return $defaults[$param];

                    // This portion is missing a parameter
                    $missing[] = $param;
                }
                else
                {
                    // Group, unwrapped
                    $result = $compile($matches[2], FALSE);

                    if ($result[1])
                    {
                        // This portion is required when it contains a group
                        // that is required
                        $required = TRUE;

                        // Add required groups to this result
                        return $result[0];
                    }

                    // Do not add optional groups to this result
                }
            }, $portion);

            return array($result, $required);
        };

        list($uri) = $compile($this->_uri, TRUE);

        // Trim all extra slashes from the URI
        $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));

        return $uri;
    }

}
