<?php

namespace Moehrenzahn\ScriptureKit\Data;

use JsonSerializable;

/**
 * Class VerseData
 *
 * Represents a set of verses including all information needed to render the verses and their reference
 */
class VerseData implements JsonSerializable
{
    private VerseRequest $verseRequest;
    private Version $version;
    private string $bookName;
    private string $chapterName;
    /**
     * @var ScripturePiece[]
     */
    private array $pieces;
    private string $compactReference;
    private string $reference;
    private string $fullReference;
    /**
     * @var string[]
     */
    private array $errors;

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

    public function jsonSerialize(): array
    {
        return [
            'verseRequest' => $this->getVerseRequest(),
            'version' => $this->getVersion(),
            'bookName' => $this->getBookName(),
            'chapterName' => $this->getChapterName(),
            'pieces' => $this->getPieces(),
            'compactReference' => $this->getCompactReference(),
            'reference' => $this->getReference(),
            'fullReference' => $this->getFullReference(),
            'errors' => $this->getErrors(),
        ];
    }
}
