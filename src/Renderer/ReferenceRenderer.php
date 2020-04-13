<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;

class ReferenceRenderer implements ReferenceRendererInterface
{
    /**
     * @var VerseRangeRenderer
     */
    private $verseRangeRenderer;

    /**
     * @var Names
     */
    private $names;

    public function __construct(VerseRangeRenderer $verseRangeRenderer, Names $names)
    {
        $this->verseRangeRenderer = $verseRangeRenderer;
        $this->names = $names;
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
        if ($verseRequest->getStartChapter() === $verseRequest->getEndChapter()) {
            return $this->buildSimpleReference($verseRequest, $withAltName);
        } else {
            return  $this->buildMultiChapterReference($verseRequest, $withAltName);
        }
    }

    /**
     * @param VerseRequest $verseRequest
     * @param bool  $withAltName
     *
     * @return string
     */
    private function buildMultiChapterReference(VerseRequest $verseRequest, bool $withAltName): string
    {
        $withAltName = false; // Alt names never look good with multi chapter references

        $pieces = [];
        $collection = $verseRequest->getCollection();
        $separator = $collection === VerseRequest::COLLECTION_QURAN ? ' ' : $verseRequest->getChapterVerseSeparator();
        $bookName = $this->getBookName(
            $collection,
            $verseRequest->getBookNumber(),
            $withAltName
        );
        if ($bookName) {
            $pieces[] = $bookName;
        }


        $chapterVerse = $this->getChapterName($collection, $verseRequest->getStartChapter(), $withAltName);
        if ($verseRequest->getStartVerse()) {
            $chapterVerse .= $separator . $verseRequest->getStartVerse();
        }
        $pieces[] = $chapterVerse;
        $pieces[] = '–';

        $chapterVerse = $this->getChapterName($collection, $verseRequest->getEndChapter(), $withAltName);
        if ($verseRequest->getStartVerse()) {
            $chapterVerse .= $separator . $verseRequest->getEndVerse();
        }
        $pieces[] = $chapterVerse;

        return implode(' ', $pieces);
    }

    /**
     * @param VerseRequest $verseRequest
     * @param bool  $withAltName
     *
     * @return string
     */
    private function buildSimpleReference(VerseRequest $verseRequest, bool $withAltName): string
    {
        $result = '';

        $chapter = $verseRequest->getStartChapter();
        $collection = $verseRequest->getCollection();

        $bookName = $this->getBookName(
            $collection,
            $verseRequest->getBookNumber(),
            $withAltName
        );
        if ($bookName) {
            $result .= $bookName . ' ';
        }

        $result .= $this->getChapterName($collection, $chapter, $withAltName);

        if ($verseRequest->getStartVerse()) {
            $result .= $collection === VerseRequest::COLLECTION_QURAN ? ' ' : $verseRequest->getChapterVerseSeparator();
            $result .= $this->verseRangeRenderer->render(range(
                $verseRequest->getStartVerse(),
                $verseRequest->getEndVerse()
            ));
        }

        return $result;
    }

    public function getBookName(int $collection, ?int $bookNumber, bool $withAltName): string
    {
        switch ($collection) {
            case VerseRequest::COLLECTION_TANAKH:
                $bookName = $this->names->getTanakhBookName($bookNumber);
                break;
            case VerseRequest::COLLECTION_NT:
            case VerseRequest::COLLECTION_OT:
                $bookName = $this->names->getBibleBookName($bookNumber);
                break;
            default:
                $bookName = "";
        }
        if ($withAltName) {
            if ($collection === VerseRequest::COLLECTION_TANAKH) {
                $altName = $this->names->getBibleBookName($bookNumber);
            } else {
                $altName = "";
            }
            if ($altName) {
                $bookName .= ' ' . $this->renderAltName($altName);
            }
        }

        return trim($bookName);
    }

    public function getChapterName(int $collection, int $chapter, bool $withAltName): string
    {
        if ($collection !== VerseRequest::COLLECTION_QURAN) {
            return "$chapter";
        }
        $name = $this->names->getQuranChapterName($chapter);
        if ($withAltName) {
            $altName = $this->names->getQuranChapterName(0) . " $chapter";
            $name .= ' ' . $this->renderAltName($altName);
        }

        return $name;
    }

    protected function renderAltName(string $altName)
    {
        return "($altName)";
    }
}
