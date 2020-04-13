<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\RenderedVerse;
use Moehrenzahn\ScriptureKit\Data\VerseData;

class VerseRenderer
{
    /**
     * @var VerseTextRendererInterface
     */
    private $verseTextRenderer;

    public function __construct(
        VerseTextRendererInterface $verseTextRenderer
    ) {
        $this->verseTextRenderer = $verseTextRenderer;
    }

    public function render(VerseData $verseData): RenderedVerse
    {
        $text = $this->verseTextRenderer->render($verseData->getPieces(), $verseData->getVerseRequest());

        return new RenderedVerse(
            $verseData->getVerseRequest(),
            $verseData->getVersion(),
            $verseData->getBookName(),
            $verseData->getChapterName(),
            $text,
            $verseData->getCompactReference(),
            $verseData->getReference(),
            $verseData->getFullReference(),
            $verseData->getErrors()
        );
    }
}
