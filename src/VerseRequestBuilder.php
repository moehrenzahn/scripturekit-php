<?php

namespace Moehrenzahn\ScriptureKit;

use Moehrenzahn\ScriptureKit\Data\VersePosition;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Util\BibleBookNames;
use Moehrenzahn\ScriptureKit\Util\QuranChapterNames;
use Moehrenzahn\ScriptureKit\Util\TanakhBookNames;
use RuntimeException;

/**
 * Class VerseRequestBuilder
 *
 * @api
 */
class VerseRequestBuilder
{
    /**
     * @var int|null
     */
    private $bookNumber;

    /**
     * @var int
     */
    private $startChapter;

    /**
     * @var int|null
     */
    private $endChapter;

    /**
     * @var ?int
     */
    private $startVerse;

    /**
     * @var ?int
     */
    private $endVerse;

    /**
     * @var int
     */
    private $collection;

    /**
     * @var bool
     */
    private $showAnnotations = true;

    /**
     * @var bool
     */
    private $showCaptions = true;

    /**
     * @var bool
     */
    private $inferLinebreaks = true;

    /**
     * @var VersePosition[]
     */
    private $highlightedVerses = [];

    /**
     * @var bool
     */
    private $returnHtml = false;

    /**
     * @var string
     */
    private $chapterVerseSeparator = ':';

    /**
     * @var string[]
     */
    private $tanachBookNames = TanakhBookNames::BOOK_NAMES;

    /**
     * @var string[]
     */
    private $bibleBookNames = BibleBookNames::BOOK_NAMES;

    /**
     * @var string[]
     */
    private $quranChapterNames = QuranChapterNames::CHAPTER_NAMES;

    /**
     * VerseRequestBuilder constructor.
     *
     * @param int      $startChapter
     * @param int      $startVerse
     * @param int      $collection
     * @param int|null $endVerse
     * @param int|null $endChapter
     */
    public function __construct(
        int $startChapter,
        int $collection,
        ?int $startVerse = null,
        ?int $endVerse = null,
        ?int $endChapter = null
    ) {
        if (
            !in_array(
                $collection,
                [
                    VerseRequest::COLLECTION_QURAN,
                    VerseRequest::COLLECTION_TANAKH,
                    VerseRequest::COLLECTION_OT,
                    VerseRequest::COLLECTION_NT,
                ]
            )
        ) {
            throw new RuntimeException('Invalid collection specified.');
        }

        $this->startChapter = $startChapter;
        $this->endChapter = $endChapter ?? $startChapter;
        $this->startVerse = $startVerse;
        $this->endVerse = $endVerse ?? $startVerse;
        $this->collection = $collection;
    }

    /**
     * @param int|null $book
     */
    public function setBookNumber(?int $book): void
    {
        if ($book !== null && $this->collection === VerseRequest::COLLECTION_QURAN) {
            throw new \RuntimeException('There are no book numbers in the Quran collection.');
        }
        $this->bookNumber = $book;
    }

    /**
     * @param bool $returnHtml
     */
    public function setReturnHtml(bool $returnHtml): void
    {
        $this->returnHtml = $returnHtml;
    }

    /**
     * @param bool $showAnnotations
     */
    public function setShowAnnotations(bool $showAnnotations): void
    {
        $this->showAnnotations = $showAnnotations;
    }

    /**
     * @param bool $showCaptions
     */
    public function setShowCaptions(bool $showCaptions): void
    {
        $this->showCaptions = $showCaptions;
    }

    /**
     * @param bool $inferLinebreaks
     */
    public function setInferLinebreaks(bool $inferLinebreaks): void
    {
        $this->inferLinebreaks = $inferLinebreaks;
    }

    /**
     * @param VersePosition[] $highlightedVerses
     */
    public function setHighlightedVerses(array $highlightedVerses): void
    {
        $this->highlightedVerses = $highlightedVerses;
    }

    /**
     * @param string[] $tanachBookNames
     */
    public function setTanakhBookNames(array $tanachBookNames): void
    {
        $this->tanachBookNames = $tanachBookNames;
    }

    /**
     * @param string[] $bibleBookNames
     */
    public function setBibleBookNames(array $bibleBookNames): void
    {
        $this->bibleBookNames = $bibleBookNames;
    }

    /**
     * @param string[] $quranChapterNames
     */
    public function setQuranChapterNames(array $quranChapterNames): void
    {
        $this->quranChapterNames = $quranChapterNames;
    }

    /**
     * @param string $chapterVerseSeparator
     */
    public function setChapterVerseSeparator(string $chapterVerseSeparator): void
    {
        $this->chapterVerseSeparator = $chapterVerseSeparator;
    }

    /**
     * Use this method to create the request after setting
     * all desired optional parameters via the setter methods.
     *
     * @return VerseRequest
     */
    public function build(): Data\VerseRequest
    {
        if ($this->collection !== VerseRequest::COLLECTION_QURAN && is_null($this->bookNumber)) {
            throw new RuntimeException('You must specify a book number for bible or tanakh verses.');
        }
        return new VerseRequest(
            $this->bookNumber,
            $this->startChapter,
            $this->endChapter,
            $this->collection,
            $this->startVerse,
            $this->endVerse,
            $this->showAnnotations,
            $this->showCaptions,
            $this->inferLinebreaks,
            $this->highlightedVerses,
            $this->returnHtml,
            $this->tanachBookNames,
            $this->bibleBookNames,
            $this->quranChapterNames,
            $this->chapterVerseSeparator
        );
    }

}
