<?php

namespace Moehrenzahn\ScriptureKit\Renderer\Html;

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
        $result = '';

        foreach ($pieces as $piece) {
            $template = '';
            if ($piece->getType() === ScripturePiece::TYPE_LINEBREAK) {
                $template = __DIR__ . '/../../Template/ScripturePiece/Linebreak.phtml';
            } else if ($piece->getType() === ScripturePiece::TYPE_CAPTION) {
                $template = __DIR__ . '/../../Template/ScripturePiece/Caption.phtml';
            } else if ($piece->getType() === ScripturePiece::TYPE_STYLED) {
                $template = __DIR__ . '/../../Template/ScripturePiece/Styled.phtml';
            } else if (in_array($piece->getType(), [ScripturePiece::TYPE_REF, ScripturePiece::TYPE_SUPERSCRIPT, ScripturePiece::TYPE_NOTE])) {
                if ($verseRequest->isShowAnnotations()) {
                    $template = __DIR__ . '/../../Template/ScripturePiece/Annotation.phtml';
                }
            } else if ($piece->getType() === ScripturePiece::TYPE_CONTENT) {
                if (in_array($piece->getPieceId(), $verseRequest->getHighlightedVerses())) {
                    $template = __DIR__ . '/../../Template/ScripturePiece/Highlight.phtml';
                } else {
                    $template = __DIR__ . '/../../Template/ScripturePiece/Default.phtml';
                }
            }
            if ($template) {
                $result .= $this->renderTemplate($template, $piece);
            }
        }

        if ($result) {
            $result =
                $this->renderTemplate(__DIR__ . '/../../Template/ScripturePiece/Start.phtml', null)
                . $result
                . $this->renderTemplate(__DIR__ . '/../../Template/ScripturePiece/End.phtml', null);
        }

        return trim($result);
    }

    private function renderTemplate(string $path, ?ScripturePiece $piece): string
    {
        ob_start();
        $view = $piece; // for usage in template
        require($path);
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
