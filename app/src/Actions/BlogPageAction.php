<?php

namespace App\Actions;

use App\ContentParser;
use League\Flysystem\FilesystemInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class BlogPageAction
{
    const MD_EXTENSION = '.md';

    /**
     * @var string
     */
    private $defaultPageTitle;

    /**
     * @var ContentParser
     */
    private $contentParser;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var Twig
     */
    private $view;

    public function __construct(Twig $view, FilesystemInterface $filesystem, ContentParser $contentParser)
    {
        $this->filesystem = $filesystem;
        $this->contentParser = $contentParser;
        $this->view = $view;

        $this->defaultPageTitle = 'Blog';
    }

    public function __invoke(Request $request, Response $response, $slug)
    {
        $file = str_replace('-', '_', $slug) . self::MD_EXTENSION;

        if ($this->filesystem->has($file)) {
            $this->contentParser->parse($this->filesystem->get($file));

            return $this->view->render($response, 'pages/about.twig', $this->buildPageVariables([
                'content' => $this->contentParser->getContent()
            ]));
        }

        return $response->withStatus(404);
    }

    private function buildPageVariables(array $data = [])
    {
        $defaults = [
            'page' => [
                'title' => $this->defaultPageTitle
            ]
        ];

        return array_merge($defaults, $this->contentParser->getYAML(), $data);
    }

}
