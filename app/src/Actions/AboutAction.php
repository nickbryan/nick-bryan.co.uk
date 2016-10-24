<?php

namespace App\Actions;

use App\ContentParser;
use App\Treehouse;
use League\Flysystem\FilesystemInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

final class AboutAction
{
    const CONTENT_FILE  = 'about.md';
    const TEMPLATE_PATH = 'pages/about.twig';

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var ContentParser
     */
    private $parser;

    /**
     * @var Treehouse
     */
    private $treehouse;

    /**
     * @var Twig
     */
    private $view;

    public function __construct(Twig $view, FilesystemInterface $filesystem, ContentParser $contentParser, Treehouse $treehouse)
    {
        $this->view = $view;
        $this->parser = $contentParser;
        $this->treehouse = $treehouse;
        $this->filesystem = $filesystem;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->parser->parse($this->filesystem->get(self::CONTENT_FILE));

        $this->view->render($response, self::TEMPLATE_PATH, array_merge([
            'content'   => $this->parser->getContent(),
            'treehouse' => $this->treehouse->getData(true)
        ], $this->parser->getYAML()));

        return $response;
    }
}
