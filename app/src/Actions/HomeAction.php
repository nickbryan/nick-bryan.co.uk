<?php

namespace App\Actions;

use App\ContentParser;
use DateTime;
use League\Flysystem\FilesystemInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class HomeAction
{
    const CONTENT_FILE  = 'home.md';
    const TEMPLATE_PATH = 'pages/home.twig';

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var ContentParser
     */
    private $parser;

    /**
     * @var Twig
     */
    private $view;

    public function __construct(Twig $view, FilesystemInterface $filesystem, ContentParser $contentParser)
    {
        $this->view = $view;
        $this->parser = $contentParser;
        $this->filesystem = $filesystem;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->parser->parse($this->filesystem->get(self::CONTENT_FILE));

        $this->view->render($response, self::TEMPLATE_PATH, array_merge([
            'age' => $this->getAge(),
            'timeDeveloping' => $this->getTimeDeveloping(),
            'content' => $this->parser->getContent(),
            'onHomePage' => true
        ], $this->parser->getYAML()));

        return $response;
    }

    /**
     * @return int
     */
    private function getAge()
    {
        return DateTime::createFromFormat('d/m/Y', '05/03/1991')
            ->diff(new DateTime('now'))
            ->y;
    }

    /**
     * @return string
     */
    private function getTimeDeveloping()
    {
        return DateTime::createFromFormat('d/m/Y', '07/07/2014')
            ->diff(new DateTime('now'))
            ->format('%y year(s) %m month(s) and %d day(s)');
    }
}
