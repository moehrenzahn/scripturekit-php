<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;

interface VerseTextRendererInterface
{
    /**
     * @param VerseRequest $verseRequest
     * @param Version      $version
     *
     * @return ScripturePiece[]
     */
    public function getPieces(VerseRequest $verseRequest, Version $version): array;

    /**
     * @param ScripturePiece[] $pieces
     * @param VerseRequest $verseRequest
     *
     * @return string
     */
    public function render(array $pieces, VerseRequest $verseRequest): string;
}
