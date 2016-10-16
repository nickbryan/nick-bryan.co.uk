<?php

namespace App;

use Mni\FrontYAML\Markdown\MarkdownParser;
use ParsedownExtra;

class ParsedownExtraParser implements MarkdownParser
{
    /**
     * @var ParsedownExtra
     */
    private $parser;

    public function __construct(ParsedownExtra $parser = null)
    {
        $this->parser = $parser ?: new ParsedownExtra();
    }

    /**
     * @param string $markdown
     * @return string
     */
    public function parse($markdown)
    {
        return $this->parser->parse($markdown);
    }
}
