<?php

namespace App\Actions;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class BlogAction
{
    const TEMPLATE_PATH = 'pages/blog.twig';

    /**
     * @var Twig
     */
    private $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->view->render($response, self::TEMPLATE_PATH, ['content' => 'Blog index page.']);

        return $response;
    }
}
