<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\RenderedVerse;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Util\BibleBookNames;
use Moehrenzahn\ScriptureKit\Util\QuranChapterNames;
use Moehrenzahn\ScriptureKit\Util\TanakhBookNames;

class VerseRenderer
{
    /**
     * @var ReferenceRendererInterface
     */
    private $referenceRenderer;

    /**
     * @var VerseTextRendererInterface
     */
    private $verseTextRenderer;

    public function __construct(
        ReferenceRendererInterface $referenceRenderer,
        VerseTextRendererInterface $verseTextRenderer
    ) {
        $this->referenceRenderer = $referenceRenderer;
        $this->verseTextRenderer = $verseTextRenderer;
    }

    public function render(VerseRequest $verseRequest, Version $version): RenderedVerse
    {
        $compactReference = $this->referenceRenderer->getShortReference($verseRequest);
        $reference = $this->referenceRenderer->getMediumReference($verseRequest);
        $fullReference = $this->referenceRenderer->getLongReference($verseRequest);

        $bookName = $this->getBookName($verseRequest, $version);
        $chapterName = $this->getChapterName($verseRequest, $version);
        $text = $this->verseTextRenderer->render($verseRequest, $version);

        return new RenderedVerse(
            $verseRequest,
            $version,
            $bookName,
            $chapterName,
            $text,
            $compactReference,
            $reference,
            $fullReference,
            []
        );
    }

    /**
     * Note: This will not add textOriginal or readingUrl keys.
     *
     * @param RenderedVerse $renderedVerse
     *
     * @return array
     */
    public function toJson(RenderedVerse $renderedVerse): array
    {
        $verse = $renderedVerse->getVerseRequest();

        return [
            'book' => $renderedVerse->getBookName(),
            'bookNumber' => $verse->getBookNumber(),
            'chapter' => $verse->getChapter(),
            'verses' => $verse->getVerses(),
            'text' => $renderedVerse->getText(),
            'version' => $renderedVerse->getVersion(),
            'collection' => $verse->getCollection(),
            'reference' => $renderedVerse->getFullReference(),
        ];
    }

    private function getBookName(VerseRequest $verseRequest, Version $version): string
    {
        $bookName = '';
        if ($version->getType() === Version::TYPE_TANAKH) {
            $bookName = TanakhBookNames::getBookName($verseRequest->getBookNumber());
        } else if ($version->getType() === Version::TYPE_BIBLE) {
            $bookName = BibleBookNames::getBookName($verseRequest->getBookNumber());
        }

        return $bookName;
    }

    private function getChapterName(VerseRequest $verseRequest, Version $version): string
    {
        $chapterName = '';
        if ($version->getType() === Version::TYPE_QURAN) {
            $chapterName = QuranChapterNames::getChapterName($verseRequest->getChapter());
        }

        return $chapterName;
    }
}
