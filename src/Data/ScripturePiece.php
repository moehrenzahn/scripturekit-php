<?php

namespace Moehrenzahn\ScriptureKit\Data;

use JsonSerializable;
use RuntimeException;

/**
 * The equivalent to a scripture xml node.
 * Usually contains one verse, the pieceId is the verse number.
 *
 * @package Moehrenzahn\ScriptureKit\Data
 */
class ScripturePiece implements JsonSerializable
{
    public const TYPE_LINEBREAK = 'br';
    public const TYPE_STYLED = 'style';
    public const TYPE_NOTE = 'note';
    public const TYPE_REF = 'ref';
    public const TYPE_SUPERSCRIPT = 'sup';
    public const TYPE_GRAMMAR_NOTE = 'gram';
    public const TYPE_CAPTION = 'caption';
    public const TYPE_BOOK_TITLE = 'bookTitle';
    public const TYPE_CHAPTER_TITLE = 'chapterTitle';
    public const TYPE_CONTENT = 'content';
    private const TYPES = [
        self::TYPE_LINEBREAK,
        self::TYPE_STYLED,
        self::TYPE_NOTE,
        self::TYPE_REF,
        self::TYPE_SUPERSCRIPT,
        self::TYPE_GRAMMAR_NOTE,
        self::TYPE_CAPTION,
        self::TYPE_CONTENT,
        self::TYPE_BOOK_TITLE,
        self::TYPE_CHAPTER_TITLE,
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $pieceId;

    /**
     * @var int|null
     */
    private $bookNumber;

    /**
     * @var int|null
     */
    private $chapter;

    /**
     * @var int|null
     */
    private $verse;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool
     */
    private $highlighted;

    /**
     * @var string[]
     */
    private $attributes;

    /**
     * @param string[] $attributes
     */
    public function __construct(
        string $type,
        int $pieceId,
        ?int $bookNumber,
        ?int $chapter,
        ?int $verse,
        string $content,
        bool $highlighted,
        array $attributes
    ) {
        if (!in_array($type, self::TYPES)) {
            throw new RuntimeException("Unknown ScripturePiece type '$type' given");
        }
        $this->type = $type;
        $this->pieceId = $pieceId;
        $this->bookNumber = $bookNumber;
        $this->chapter = $chapter;
        $this->verse = $verse;
        $this->content = $content;
        $this->highlighted = $highlighted;
        $this->attributes = $attributes;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPieceId(): int
    {
        return $this->pieceId;
    }

    /**
     * @return int|null
     */
    public function getBookNumber(): ?int
    {
        return $this->bookNumber;
    }

    /**
     * @return int|null
     */
    public function getChapter(): ?int
    {
        return $this->chapter;
    }

    /**
     * @return int|null
     */
    public function getVerse(): ?int
    {
        return $this->verse;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isHighlighted(): bool
    {
        return $this->highlighted;
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setPieceId(int $pieceId): void
    {
        $this->pieceId = $pieceId;
    }

    public function setBookNumber(?int $bookNumber): void
    {
        $this->bookNumber = $bookNumber;
    }

    public function setChapter(?int $chapter): void
    {
        $this->chapter = $chapter;
    }

    public function setVerse(?int $verse): void
    {
        $this->verse = $verse;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setHighlighted(bool $highlighted): void
    {
        $this->highlighted = $highlighted;
    }

    /**
     * @param string[] $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'pieceId' => $this->getPieceId(),
            'content' => $this->getContent(),
            'bookNumber' => $this->getBookNumber(),
            'chapter' => $this->getChapter(),
            'verse' => $this->getVerse(),
            'highlighted' => $this->isHighlighted(),
            'attributes' => $this->getAttributes() ?: \json_decode('{}'),
        ];
    }

    public static function createLinebreak(): ScripturePiece
    {
        return new self(
            self::TYPE_LINEBREAK,
            0,
            null,
            null,
            null,
            '',
            false,
            []
        );
    }
}
