<?php

namespace App;

use League\Flysystem\File;
use League\Flysystem\Filesystem;
use League\Flysystem\Handler;
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
     * @param File|Handler $file
     */
    public function parse(File $file)
    {
        $this->content = $this->parser->parse($file->read());
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
