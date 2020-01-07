<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;

interface ScripturePieceRendererInterface
{
    /**
     * @param ScripturePiece[] $pieces
     * @param VerseRequest $verseRequest
     *
     * @return string
     */
    public function render(array $pieces, VerseRequest $verseRequest): string;
}
