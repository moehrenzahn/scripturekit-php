<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Util\BibleBookNames;
use Moehrenzahn\ScriptureKit\Util\QuranChapterNames;
use Moehrenzahn\ScriptureKit\Util\TanakhBookNames;
use RuntimeException;

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
            switch ($verseRequest->getCollection()) {
                case VerseRequest::COLLECTION_TANAKH:
                    $bookName = TanakhBookNames::getBookName($book);
                    break;
                case VerseRequest::COLLECTION_NT:
                case VerseRequest::COLLECTION_OT:
                    $bookName = BibleBookNames::getBookName($book);
                    break;
            }
            if (!isset($bookName)) {
                throw new RuntimeException('This book is not available in this collection.');
            }

            $result .= $bookName;


            if ($withAltName) {
                if ($verseRequest->getCollection() === VerseRequest::COLLECTION_TANAKH) {
                    $altName = BibleBookNames::getBookName($verseRequest->getBookNumber());
                }
                if (isset($altName)) {
                    $result .= ' (' . $altName . ')';
                }
            }

            $result .= ' ';
        }

        if ($verseRequest->getCollection() === VerseRequest::COLLECTION_QURAN) {
            $chapterName = QuranChapterNames::getChapterName($verseRequest->getChapter());
            $result .= $chapterName . ' ';
            if ($withAltName) {
                $altName = 'Surah ' . $verseRequest->getChapter();
                $result .= '(' . $altName . ') ';
            }
        } else {
            $result .= $verseRequest->getChapter();
        }

        if (!empty($verseRequest->getVerses())) {
            if (!isset($chapterName)) {
                $result .= ':';
            }
            $result .= $this->verseRangeRenderer->render($verseRequest->getVerses());
        }

        return $result;
    }
}
