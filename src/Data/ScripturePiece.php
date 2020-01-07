<?php

namespace Moehrenzahn\ScriptureKit\Data;

use RuntimeException;

/**
 * The equivalent to a scripture xml node.
 * Usually contains one verse, the pieceId is the verse number.
 *
 * @package Moehrenzahn\ScriptureKit\Data
 */
class ScripturePiece
{
    public const TYPE_LINEBREAK = 'br';
    public const TYPE_STYLED = 'style';
    public const TYPE_NOTE = 'note';
    public const TYPE_REF = 'ref';
    public const TYPE_SUPERSCRIPT = 'sup';
    public const TYPE_GRAMMAR_NOTE = 'gram';
    public const TYPE_CAPTION = 'caption';
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
     * @var string
     */
    private $content;

    /**
     * @var string[]
     */
    private $attributes;

    /**
     * ScripturePiece constructor.
     *
     * @param string   $type
     * @param int      $pieceId
     * @param string   $content
     * @param string[] $attributes
     */
    public function __construct(string $type, int $pieceId, string $content, array $attributes)
    {
        if (!in_array($type, self::TYPES)) {
            throw new RuntimeException("Unknown ScripturePart type '$type' given");
        }
        $this->type = $type;
        $this->pieceId = $pieceId;
        $this->content = $content;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPieceId(): int
    {
        return $this->pieceId;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
