<?php

namespace Moehrenzahn\ScriptureKit\Renderer\Html;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Renderer\ScripturePieceRendererInterface;

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
            switch ($piece->getType()) {
                case ScripturePiece::TYPE_LINEBREAK:
                    $template = __DIR__ . '/../../Template/ScripturePiece/Linebreak.phtml';
                    break;
                case ScripturePiece::TYPE_CAPTION:
                    $template = __DIR__ . '/../../Template/ScripturePiece/Caption.phtml';
                    break;
                case ScripturePiece::TYPE_STYLED:
                    $template = __DIR__ . '/../../Template/ScripturePiece/Styled.phtml';
                    break;
                case ScripturePiece::TYPE_REF:
                case ScripturePiece::TYPE_SUPERSCRIPT:
                case ScripturePiece::TYPE_NOTE:
                    $template = $verseRequest->isShowAnnotations() ?
                        __DIR__ . '/../../Template/ScripturePiece/Annotation.phtml' : '';
                    break;
                case ScripturePiece::TYPE_CONTENT:
                    if (in_array($piece->getPieceId(), $verseRequest->getHighlightedVerses())) {
                        $template = __DIR__ . '/../../Template/ScripturePiece/Highlight.phtml';
                    } else {
                        $template = __DIR__ . '/../../Template/ScripturePiece/Default.phtml';
                    }
                    break;
                case ScripturePiece::TYPE_CHAPTER_TITLE:
                    $template = $verseRequest->isShowAnnotations() ? __DIR__ . '/../../Template/ScripturePiece/ChapterTitle.phtml' : '';
                    break;
                case ScripturePiece::TYPE_BOOK_TITLE:
                    $template = $verseRequest->isShowAnnotations() ? __DIR__ . '/../../Template/ScripturePiece/BookTitle.phtml' : '';
                    break;
                default:
                    $template = '';
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
