<?php

namespace DrMVC\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Error
{
    public function action_index(Request $request, Response $response, array $args)
    {
        $error = "error triggered\n";
        $error .= print_r($args, true) . "\n\n";
        $error .= print_r($request, true) . "\n";

        $response->getBody()->write($error);
    }
}
