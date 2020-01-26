<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\DetailedVersion;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Parser\ParserInterface;

class VersionRenderer
{
    /**
     * @var ParserInterface
     */
    private $parser;

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function render(Version $version): DetailedVersion
    {
        $filePath = $version->getFilePath();

        return new DetailedVersion(
            $version,
            $this->parser->loadTitle($filePath),
            strtolower($this->parser->loadLanguageCode($filePath)),
            $this->parser->loadVersionDetails($filePath)
        );
    }
}
