<?php

namespace DrMVC\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Error
{
    public function action_index(Request $request, Response $response, array $args)
    {
        $error = 'error triggered';
        $response->getBody()->write($error);
    }
}
