<?php

namespace Moehrenzahn\ScriptureKit\Renderer\Html;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Renderer\ReferenceRendererInterface;
use Moehrenzahn\ScriptureKit\Renderer\VerseRangeRenderer;
use Moehrenzahn\ScriptureKit\Util\BibleBookNames;
use Moehrenzahn\ScriptureKit\Util\QuranChapterNames;

class ReferenceRenderer implements ReferenceRendererInterface
{
    /**
     * @var VerseRangeRenderer
     */
    private $verseRangeRenderer;

    public function __construct(VerseRangeRenderer $verseRangeRenderer)
    {
        $this->verseRangeRenderer = $verseRangeRenderer;
    }

    public function getLongReference(VerseRequest $verseRequest): string
    {
        return $this->buildReference($verseRequest, true);
    }

    public function getMediumReference(VerseRequest $verseRequest): string
    {
        return $this->buildReference($verseRequest, false);
    }

    public function getShortReference(VerseRequest $verseRequest): string
    {
        return $this->buildReference($verseRequest, false);
    }

    /**
     * @param VerseRequest $verseRequest
     * @param bool  $withAltName
     *
     * @return string
     */
    private function buildReference(VerseRequest $verseRequest, bool $withAltName): string
    {
        $result = '';

        if ($book = $verseRequest->getBookNumber()) {
            $result .= $book;

            if ($withAltName) {
                if ($verseRequest->getCollection() === VerseRequest::COLLECTION_TANAKH) {
                    $altName = BibleBookNames::getBookName($verseRequest->getBookNumber());
                }
                if (isset($altName)) {
                    $result .= " <span class='name-alt'>($altName)</span>";
                }
            }

            $result .= ' ';
        }

        if ($verseRequest->getCollection() === VerseRequest::COLLECTION_QURAN) {
            $chapterName = QuranChapterNames::getChapterName($verseRequest->getChapter());
            $result .= $chapterName . ' ';
            if ($withAltName) {
                $altName = 'Surah ' . $verseRequest->getChapter();
                $result .= "<span class='name-alt'>($altName)</span>";
            }
        } else {
            $result .= $verseRequest->getChapter();
        }

        if (!empty($this->verses)) {
            if (isset($chapterName)) {
                $result .= ' ';
            } else {
                $result .= ':';
            }
            $result .= $this->verseRangeRenderer->render($this->verses);
        }

        return $result;
    }
}
