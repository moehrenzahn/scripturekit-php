<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Renderer\ScripturePieceRendererInterface;
use Moehrenzahn\ScriptureKit\Util\StringHelper;

class ScripturePieceRenderer implements ScripturePieceRendererInterface
{
    /**
     * @param ScripturePiece[] $pieces
     * @param VerseRequest $verseRequest
     *
     * @return string
     */
    public function render(array $pieces, VerseRequest $verseRequest): string
    {
        $result = [];

        foreach ($pieces as $piece) {
            if ($piece->getType() === ScripturePiece::TYPE_LINEBREAK) {
                $result[] = PHP_EOL . PHP_EOL;
            } else if ($piece->getType() === ScripturePiece::TYPE_CAPTION) {
                $result[] = PHP_EOL . PHP_EOL . $piece->getContent() . PHP_EOL . PHP_EOL;
            } else if (in_array($piece->getType(), [ScripturePiece::TYPE_STYLED, ScripturePiece::TYPE_CONTENT])) {
                $result[] = $piece->getContent();
            } else if (in_array($piece->getType(), [ScripturePiece::TYPE_REF, ScripturePiece::TYPE_SUPERSCRIPT, ScripturePiece::TYPE_NOTE])) {
                if ($verseRequest->isShowAnnotations()) {
                    $result[] = '(' . $piece->getContent() . ')';
                }
            }
        }

        return implode(' ', $result);
    }
}
