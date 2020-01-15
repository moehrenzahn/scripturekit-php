<?php

namespace Moehrenzahn\ScriptureKit;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;
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
    private $chapter;

    /**
     * @var int[]
     */
    private $verses = [];

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
    private $inferLinebreaks = true;

    /**
     * @var int[]
     */
    private $highlightedVerses = [];

    /**
     * @var bool
     */
    private $returnHtml = false;

    /**
     * VerseRequestBuilder constructor.
     *
     * @param int   $chapter
     * @param int[] $verses
     * @param int   $collection
     */
    public function __construct(
        int $chapter,
        array $verses,
        int $collection
    ) {
        if (!in_array($collection, [VerseRequest::COLLECTION_QURAN, VerseRequest::COLLECTION_TANAKH, VerseRequest::COLLECTION_OT, VerseRequest::COLLECTION_NT])) {
            throw new RuntimeException('Invalid collection specified.');
        }

        $this->chapter = $chapter;
        $this->verses = $verses;
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
     * @param bool $inferLinebreaks
     */
    public function setInferLinebreaks(bool $inferLinebreaks): void
    {
        $this->inferLinebreaks = $inferLinebreaks;
    }

    /**
     * @param int[] $highlightedVerses
     */
    public function setHighlightedVerses(array $highlightedVerses): void
    {
        $this->highlightedVerses = $highlightedVerses;
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
            $this->chapter,
            $this->collection,
            $this->verses,
            $this->showAnnotations,
            $this->inferLinebreaks,
            $this->highlightedVerses,
            $this->returnHtml
        );
    }
}
