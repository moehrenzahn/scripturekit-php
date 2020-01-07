<?php

namespace Moehrenzahn\ScriptureKit;

use Moehrenzahn\ScriptureKit\Data\RenderedVerse;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Parser\QuranParser;
use Moehrenzahn\ScriptureKit\Parser\XMLParser;
use Moehrenzahn\ScriptureKit\Parser\ZefaniaParser;
use Moehrenzahn\ScriptureKit\Renderer\ReferenceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\ScripturePieceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseRangeRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseTextRenderer;

/**
 * Class VerseFactory
 *
 * @api
 */
class VerseFactory
{
    /**
     * @var Version
     */
    private $version;

    /**
     * @var VerseRenderer
     */
    private $verseRenderer;

    /**
     * @var VerseRenderer
     */
    private $verseHtmlRenderer;

    public function __construct(Version $version)
    {
        $this->version = $version;

        $verseRangeRenderer = new VerseRangeRenderer();

        if ($version->getType() === Version::TYPE_QURAN) {
            $parser = new QuranParser(new XMLParser());
        } else {
            $parser = new ZefaniaParser(new XMLParser());
        }

        $this->verseRenderer = new VerseRenderer(
            new ReferenceRenderer(
                $verseRangeRenderer
            ),
            new VerseTextRenderer(
                $parser,
                new ScripturePieceRenderer()
            )
        );
        $this->verseHtmlRenderer = new VerseRenderer(
            new Renderer\Html\ReferenceRenderer(
                $verseRangeRenderer
            ),
            new VerseTextRenderer(
                $parser,
                new Renderer\Html\ScripturePieceRenderer()
            )
        );
    }

    public function create(VerseRequest $verseRequest): RenderedVerse
    {
        if ($verseRequest->isReturnHtml()) {
            return $this->verseHtmlRenderer->render(
                $verseRequest,
                $this->version
            );
        } else {
            return $this->verseRenderer->render(
                $verseRequest,
                $this->version
            );
        }
    }
}
