<?php

namespace App\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\NotFound;
use Slim\Views\Twig;

final class PageNotFoundAction extends NotFound
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var string
     */
    private $templateFile;

    public function __construct(Twig $view, $templateFile)
    {
        $this->view = $view;
        $this->templateFile = $templateFile;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        parent::__invoke($request, $response);

        $this->view->render($response, $this->templateFile);

        return $response->withStatus(404);
    }
}
