<?php

namespace DrMVC\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Error
{
    public function action_index(Request $request, Response $response, array $args)
    {
        $body = $this->renderHtmlErrorMessage();
        $response->getBody()->write($body);
    }

    /**
     * Render HTML error page
     *
     * @return string
     */
    private function renderHtmlErrorMessage()
    {
        $title = 'DrMVC Application Error';
        $html = '<p>A website error has occurred. Sorry for the temporary inconvenience.</p>';
        $output = sprintf(
            "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
            "<title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana," .
            "sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{" .
            "display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>",
            $title,
            $title,
            $html
        );
        return $output;
    }
}
