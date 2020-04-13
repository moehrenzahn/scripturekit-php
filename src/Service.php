<?php

namespace Moehrenzahn\ScriptureKit;

use Moehrenzahn\ScriptureKit\Data\RenderedVerse;
use Moehrenzahn\ScriptureKit\Data\DetailedVersion;
use Moehrenzahn\ScriptureKit\Data\VerseData;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Parser\ParserInterface;
use Moehrenzahn\ScriptureKit\Parser\QuranParser;
use Moehrenzahn\ScriptureKit\Parser\SefariaParser;
use Moehrenzahn\ScriptureKit\Parser\XMLParser;
use Moehrenzahn\ScriptureKit\Parser\ZefaniaParser;
use Moehrenzahn\ScriptureKit\Renderer\Html\ReferenceRenderer as HtmlReferenceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\Names;
use Moehrenzahn\ScriptureKit\Renderer\ReferenceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\ScripturePieceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\VerseDataRenderer;
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
     * @var ParserInterface
     */
    private $parser;

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

        switch ($version->getType()) {
            case Version::TYPE_QURAN:
                $this->parser = new QuranParser(new XMLParser(), new ReferenceRenderer(
                    new VerseRangeRenderer(),
                    $this->names
                ));
                break;
            case Version::TYPE_TANAKH_SEFARIA:
                $this->parser = new SefariaParser();
                break;
            default:
                $this->parser = new ZefaniaParser(new XMLParser());
        }

        $this->versionRenderer = new VersionRenderer($this->parser);

        $this->verseRenderer = new VerseRenderer(
            new VerseTextRenderer(
                $this->parser,
                new ScripturePieceRenderer()
            )
        );
        $this->verseHtmlRenderer = new VerseRenderer(
            new VerseTextRenderer(
                $this->parser,
                new Renderer\Html\ScripturePieceRenderer()
            )
        );
    }

    /**
     * @param VerseRequest $verseRequest Create via \Moehrenzahn\ScriptureKit\VerseRequestBuilder::build
     *
     * @return RenderedVerse
     */
    public function createVerse(VerseRequest $verseRequest): RenderedVerse
    {
        $verseData = $this->createVerseData($verseRequest);

        if ($verseRequest->isReturnHtml()) {
            return $this->verseHtmlRenderer->render($verseData);
        } else {
            return $this->verseRenderer->render($verseData);
        }
    }

    public function createVerseData(VerseRequest $verseRequest): VerseData
    {
        $this->names->setBibleBookNames($verseRequest->getBibleBookNames());
        $this->names->setTanakhBookNames($verseRequest->getTanachBookNames());
        $this->names->setQuranChapterNames($verseRequest->getQuranChapterNames());

        if ($verseRequest->isReturnHtml()) {
            $referenceRenderer = new HtmlReferenceRenderer(
                new VerseRangeRenderer(),
                $this->names
            );
        } else {
            $referenceRenderer = new ReferenceRenderer(
                new VerseRangeRenderer(),
                $this->names
            );
        }

        $renderer = new VerseDataRenderer(
            $referenceRenderer,
            new VerseTextRenderer(
                $this->parser,
                new ScripturePieceRenderer()
            ),
            $this->names
        );
        return $renderer->render(
            $verseRequest,
            $this->version
        );
    }

    /**
     * @return DetailedVersion
     */
    public function createDetailedVersion(): DetailedVersion
    {
        return $this->versionRenderer->render($this->version);
    }
}
