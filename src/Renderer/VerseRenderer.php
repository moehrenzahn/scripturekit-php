<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\RenderedVerse;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use RuntimeException;

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

    /**
     * @var Names
     */
    private $names;

    public function __construct(
        ReferenceRendererInterface $referenceRenderer,
        VerseTextRendererInterface $verseTextRenderer,
        Names $names
    ) {
        $this->referenceRenderer = $referenceRenderer;
        $this->verseTextRenderer = $verseTextRenderer;
        $this->names = $names;
    }

    public function render(VerseRequest $verseRequest, Version $version): RenderedVerse
    {
        $errors = [];

        $compactReference = $this->referenceRenderer->getShortReference($verseRequest);
        $reference = $this->referenceRenderer->getMediumReference($verseRequest);
        $fullReference = $this->referenceRenderer->getLongReference($verseRequest);

        if (!$compactReference || !$fullReference || !$reference) {
            $errors[] = 'Verse reference not available';
        }
        $bookName = $this->getBookName($verseRequest, $version);
        $chapterName = $this->getChapterName($verseRequest, $version);
        try {
            $text = $this->verseTextRenderer->render($verseRequest, $version);
        } catch (RuntimeException $e) {
            $text = '';
            $errors[] = $e->getMessage();
        }

        return new RenderedVerse(
            $verseRequest,
            $version,
            $bookName,
            $chapterName,
            $text,
            $compactReference,
            $reference,
            $fullReference,
            $errors
        );
    }

    private function getBookName(VerseRequest $verseRequest, Version $version): string
    {
        $bookName = '';
        if ($version->getType() === Version::TYPE_TANAKH) {
            $bookName = $this->names->getTanakhBookName($verseRequest->getBookNumber());
        } elseif ($version->getType() === Version::TYPE_BIBLE) {
            $bookName = $this->names->getBibleBookName($verseRequest->getBookNumber());
        }

        return $bookName;
    }

    private function getChapterName(VerseRequest $verseRequest, Version $version): string
    {
        $chapterName = '';
        if ($version->getType() === Version::TYPE_QURAN) {
            $chapterName = $this->names->getQuranChapterName($verseRequest->getChapter());
        }

        return $chapterName;
    }
}
