<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;

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
            } elseif ($piece->getType() === ScripturePiece::TYPE_CAPTION) {
                if ($verseRequest->isShowHeadings()) {
                    $result[] = PHP_EOL . PHP_EOL . $piece->getContent() . PHP_EOL . PHP_EOL;
                }
            } elseif (in_array($piece->getType(), [ScripturePiece::TYPE_STYLED, ScripturePiece::TYPE_CONTENT])) {
                $result[] = $piece->getContent();
            } elseif (in_array($piece->getType(), [ScripturePiece::TYPE_REF, ScripturePiece::TYPE_SUPERSCRIPT, ScripturePiece::TYPE_NOTE])) {
                if ($verseRequest->isShowAnnotations()) {
                    $result[] = '(' . $piece->getContent() . ')';
                }
            }
        }

        return str_replace( // get rid of spaces around linebreaks
            ' ' . PHP_EOL . PHP_EOL . ' ',
            PHP_EOL . PHP_EOL,
            implode(' ', $result)
        );
    }
}
