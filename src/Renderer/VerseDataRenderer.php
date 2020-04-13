<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\VerseData;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use RuntimeException;

class VerseDataRenderer
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

    public function render(VerseRequest $verseRequest, Version $version): VerseData
    {
        $errors = [];

        $compactReference = $this->referenceRenderer->getShortReference($verseRequest);
        $reference = $this->referenceRenderer->getMediumReference($verseRequest);
        $fullReference = $this->referenceRenderer->getLongReference($verseRequest);

        if (!$compactReference || !$fullReference || !$reference) {
            $errors[] = 'Verse reference not available';
        }
        $bookName = $this->referenceRenderer->getBookName(
            $verseRequest->getCollection(),
            $verseRequest->getBookNumber(),
            false
        );
        $chapterName = $this->referenceRenderer->getChapterName(
            $verseRequest->getCollection(),
            $verseRequest->getStartChapter(),
            false
        );

        try {
            $pieces = $this->verseTextRenderer->getPieces($verseRequest, $version);
        } catch (RuntimeException $e) {
            $pieces = [];
            $errors[] = $e->getMessage();
        }

        return new VerseData(
            $verseRequest,
            $version,
            $bookName,
            $chapterName,
            $pieces,
            $compactReference,
            $reference,
            $fullReference,
            $errors
        );
    }
}
