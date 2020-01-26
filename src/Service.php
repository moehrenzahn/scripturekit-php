<?php

namespace Moehrenzahn\ScriptureKit;

use Moehrenzahn\ScriptureKit\Data\RenderedVerse;
use Moehrenzahn\ScriptureKit\Data\DetailedVersion;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Parser\QuranParser;
use Moehrenzahn\ScriptureKit\Parser\XMLParser;
use Moehrenzahn\ScriptureKit\Parser\ZefaniaParser;
use Moehrenzahn\ScriptureKit\Renderer\Names;
use Moehrenzahn\ScriptureKit\Renderer\ReferenceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\ScripturePieceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseRangeRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseTextRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VersionRenderer;

/**
 * Class Service
 *
 * The main entry point to ScriptureKit.
 *
 * @api
 */
class Service
{
    /**
     * @var Version
     */
    private $version;

    /**
     * @var VersionRenderer
     */
    private $versionRenderer;

    /**
     * @var VerseRenderer
     */
    private $verseRenderer;

    /**
     * @var VerseRenderer
     */
    private $verseHtmlRenderer;

    /**
     * @var Names
     */
    private $names;

    /**
     * Service constructor.
     *
     * @param Version $version Create this object via `new Version(...)`
     *                         with the details of the xml version you want to use
     */
    public function __construct(Version $version)
    {
        $this->version = $version;

        $this->names = new Names();

        $verseRangeRenderer = new VerseRangeRenderer();

        if ($version->getType() === Version::TYPE_QURAN) {
            $parser = new QuranParser(new XMLParser());
        } else {
            $parser = new ZefaniaParser(new XMLParser());
        }

        $this->versionRenderer = new VersionRenderer($parser);

        $this->verseRenderer = new VerseRenderer(
            new ReferenceRenderer(
                $verseRangeRenderer,
                $this->names
            ),
            new VerseTextRenderer(
                $parser,
                new ScripturePieceRenderer()
            ),
            $this->names
        );
        $this->verseHtmlRenderer = new VerseRenderer(
            new Renderer\Html\ReferenceRenderer(
                $verseRangeRenderer,
                $this->names
            ),
            new VerseTextRenderer(
                $parser,
                new Renderer\Html\ScripturePieceRenderer()
            ),
            $this->names
        );
    }

    /**
     * @param VerseRequest $verseRequest Create via \Moehrenzahn\ScriptureKit\VerseRequestBuilder::build
     *
     * @return RenderedVerse
     */
    public function createVerse(VerseRequest $verseRequest): RenderedVerse
    {
        $this->names->setBibleBookNames($verseRequest->getBibleBookNames());
        $this->names->setTanakhBookNames($verseRequest->getTanachBookNames());
        $this->names->setQuranChapterNames($verseRequest->getQuranChapterNames());

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

    /**
     * @return DetailedVersion
     */
    public function createDetailedVersion(): DetailedVersion
    {
        return $this->versionRenderer->render($this->version);
    }
}
