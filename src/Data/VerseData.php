<?php

namespace Moehrenzahn\ScriptureKit\Data;

class VerseData
{
    /**
     * @var VerseRequest
     */
    private $verseRequest;

    /**
     * @var Version
     */
    private $version;

    /**
     * @var string
     */
    private $bookName;

    /**
     * @var string
     */
    private $chapterName;

    /**
     * @var ScripturePiece[]
     */
    private $pieces;

    /**
     * @var string
     */
    private $compactReference;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $fullReference;

    /**
     * @var string[]
     */
    private $errors;

    /**
     * RenderedVerse constructor.
     *
     * @param VerseRequest $verseRequest
     * @param Version $version
     * @param string $bookName
     * @param string $chapterName
     * @param ScripturePiece[] $pieces
     * @param string $compactReference
     * @param string $reference
     * @param string $fullReference
     * @param string[] $errors
     */
    public function __construct(
        VerseRequest $verseRequest,
        Version $version,
        string $bookName,
        string $chapterName,
        array $pieces,
        string $compactReference,
        string $reference,
        string $fullReference,
        array $errors
    ) {
        $this->verseRequest = $verseRequest;
        $this->version = $version;
        $this->bookName = $bookName;
        $this->chapterName = $chapterName;
        $this->pieces = $pieces;
        $this->compactReference = $compactReference;
        $this->reference = $reference;
        $this->fullReference = $fullReference;
        $this->errors = $errors;
    }

    public function getVerseRequest(): VerseRequest
    {
        return $this->verseRequest;
    }

    public function getVersion(): Version
    {
        return $this->version;
    }

    public function getBookName(): string
    {
        return $this->bookName;
    }

    public function getChapterName(): string
    {
        return $this->chapterName;
    }

    /**
     * @return ScripturePiece[]
     */
    public function getPieces(): array
    {
        return $this->pieces;
    }

    public function getCompactReference(): string
    {
        return $this->compactReference;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getFullReference(): string
    {
        return $this->fullReference;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
