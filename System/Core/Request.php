<?php
/**
 * Request - class for compare url with routes
 */

namespace System\Core;

class Request
{
    public static $initial;
    public static $current;

    /**
     * Detect current page url
     *
     * @param $uri
     * @return Request
     */
    public static function factory($uri)
    {
        // If this is the initial request
        if (!Request::$initial) {
            if ($uri === TRUE) {
                // Attempt to guess the proper URI
                $uri = Request::detect_uri();

                $dir = str_replace('/', '\/', DIR);
                $uri = preg_replace('/^' . $dir . '/', '/', $uri);
            }
            // Create the instance singleton
            Request::$initial = $request = new Request($uri);
        } else {
            $request = new Request($uri);
        }
        return $request;
    }

    /**
     * Automatically detects the URI of the main request using PATH_INFO,
     * REQUEST_URI, PHP_SELF or REDIRECT_URL.
     */
    public static function detect_uri()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            // PATH_INFO does not contain the docroot or index
            $uri = $_SERVER['PATH_INFO'];
        } else {
            // REQUEST_URI and PHP_SELF include the docroot and index
            if (isset($_SERVER['REQUEST_URI'])) {
                /**
                 * We use REQUEST_URI as the fallback value. The reason
                 * for this is we might have a malformed URL such as:
                 *
                 *  http://localhost/http://example.com/judge.php
                 *
                 * which parse_url can't handle. So rather than leave empty
                 * handed, we'll use this.
                 */
                $uri = $_SERVER['REQUEST_URI'];

                if ($request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
                    // Valid URL path found, set it.
                    $uri = $request_uri;
                }

                // Decode the request URI
                $uri = rawurldecode($uri);
            } elseif (isset($_SERVER['PHP_SELF'])) {
                $uri = $_SERVER['PHP_SELF'];
            } elseif (isset($_SERVER['REDIRECT_URL'])) {
                $uri = $_SERVER['REDIRECT_URL'];
            }

        }

        return $uri;
    }

    public function __construct($uri)
    {
        // Create a route
        $this->_route = new Route($uri);

        // Store the URI
        $this->_uri = $uri;

        // Get matches
        $result = $this->_route->matches($this);

        return $this;
    }

    protected $_external = FALSE;

    /**
     * Sets and gets the uri from the request.
     */
    public function uri($uri = NULL)
    {
        if ($uri === NULL) {
            // Act as a getter
            return empty($this->_uri) ? '/' : $this->_uri;
        }

        // Act as a setter
        $this->_uri = $uri;

        return $this;
    }

    /**
     * Processes the request, executing the controller action that handles this
     * request, determined by the [Route].
     *
     * 1. Before the controller action is called, the [Controller::before] method
     * will be called.
     * 2. Next the controller action will be called.
     * 3. After the controller action is called, the [Controller::after] method
     * will be called.
     *
     * By default, the output from the controller is captured and returned, and
     * no headers are sent.
     *
     *     $request->execute();
     */
    public function execute()
    {
        if (!$this->_external) {
            $processed = Request::process($this, $this->_routes);

            if ($processed) {
                // Store the matching route
                $this->_route = $processed['route'];
                $params = $processed['params'];

                // Is this route external?
                //$this->_external = $this->_route->is_external();
                $this->_external = FALSE;

                if (isset($params['directory'])) {
                    // Controllers are in a sub-directory
                    $this->_directory = $params['directory'];
                }

                // Store the controller
                $this->_controller = (isset($params['controller']))
                    ? $params['controller']
                    : Route::$default_controller;

                // Store the action
                $this->_action = (isset($params['action']))
                    ? $params['action']
                    : Route::$default_action;

                // These are accessible as public vars and can be overloaded
                unset($params['controller'], $params['action'], $params['directory']);

                // Params cannot be changed once matched
                $this->_params = $params;
            }
        }

        return $this;
    }

    /**
     * Process a request to find a matching route
     */
    public static function process(Request $request, $routes = NULL)
    {
        // Load routes
        $routes = (empty($routes)) ? Route::all() : $routes;
        $params = NULL;

        foreach ($routes as $name => $route) {
            // We found something suitable
            if ($params = $route->matches($request)) {
                return array(
                    'params' => $params,
                    'route' => $route,
                );
            }
        }

        return NULL;
    }

    /**
     * Render HTML
     */
    public function render()
    {
        $prefix = '\\Application\\Controllers\\';
        $controller = $prefix . ucfirst(strtolower($this->_controller));
        $action = 'action_' . $this->_action;

        // Filename for check
        $controller_file = DOCROOT . str_replace('\\', DIRECTORY_SEPARATOR, $controller . '.php');

        if (file_exists($controller_file)) {
            // echo "file exist\n";
            if (method_exists($controller, $action)) {
                // echo "method exist\n";
            } else {
                // echo "method not exist\n";
                $error = Route::get('error')->defaults();
                $controller = $prefix . $error['controller'];
                $action = 'action_' . $error['action'];
            }
        } else {
            // echo "file not exist\n";
            $error = Route::get('error')->defaults();
            $controller = $prefix . $error['controller'];
            $action = 'action_' . $error['action'];
        }

        // Create requested class
        $app = new $controller();
        // Send current request into controller
        $app->request = $this;
        // Run action and exec page generation
        $app->$action();
    }

    /**
     * Some parameters for method
     *
     * @param null $key
     * @param null $default
     * @return null
     */
    public function param($key = NULL, $default = NULL)
    {
        if ($key === NULL) {
            // Return the full array
            return $this->_params;
        }
        return isset($this->_params[$key]) ? $this->_params[$key] : $default;
    }
}

