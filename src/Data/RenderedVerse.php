<?php

namespace Moehrenzahn\ScriptureKit\Data;

/**
 * Class RenderedVerse
 */
class RenderedVerse
{
    private VerseRequest $verseRequest;
    private Version $version;
    private string $bookName;
    private string $chapterName;
    private string $text;
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
     * @param string $text
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
        string $text,
        string $compactReference,
        string $reference,
        string $fullReference,
        array $errors
    ) {
        $this->verseRequest = $verseRequest;
        $this->version = $version;
        $this->bookName = $bookName;
        $this->chapterName = $chapterName;
        $this->text = $text;
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

    public function getText(): string
    {
        return $this->text;
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
