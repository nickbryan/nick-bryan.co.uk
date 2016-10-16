<?php

namespace App;

use League\Flysystem\Filesystem;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;

class ContentParser
{
    /**
     * @var Document
     */
    private $content;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Filesystem $filesystem, Parser $parser)
    {
        $this->filesystem = $filesystem;
        $this->parser = $parser;
    }

    /**
     * @param string $filePath
     */
    public function parseFile($filePath)
    {
        $this->content = $this->parser->parse($this->filesystem->get($filePath)->read());
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content->getContent();
    }

    /**
     * @return array
     */
    public function getYAML()
    {
        return $this->content->getYAML();
    }
}
