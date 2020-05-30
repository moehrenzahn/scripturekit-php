<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Parser\ParserInterface;
use Moehrenzahn\ScriptureKit\Util\StringHelper;

class VerseTextRenderer implements VerseTextRendererInterface
{
    private const GREEK_LETTERS = [
        'Α','α','ε','Η','η','Ι',
        'ι','Ο','ο','υ','Ω','ω',
    ];

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var ScripturePieceRendererInterface
     */
    private $scripturePieceRenderer;

    /**
     * VerseTextRenderer constructor.
     *
     * @param ParserInterface                 $parser
     * @param ScripturePieceRendererInterface $scripturePieceRenderer
     */
    public function __construct(ParserInterface $parser, ScripturePieceRendererInterface $scripturePieceRenderer)
    {
        $this->parser = $parser;
        $this->scripturePieceRenderer = $scripturePieceRenderer;
    }

    /**
     * Chooses the most performant way to get the requested text from the parser
     *
     * @param VerseRequest $verseRequest
     * @param Version      $version
     *
     * @return ScripturePiece[]
     */
    public function getPieces(VerseRequest $verseRequest, Version $version): array
    {
        $bookNumber = $verseRequest->getBookNumber();
        $startVerse = $verseRequest->getStartVerse();
        $endVerse = $verseRequest->getEndVerse();
        $startChapter = $verseRequest->getStartChapter();
        $endChapter = $verseRequest->getEndChapter();


        if ($startChapter === $endChapter) {
            $chapter = $startChapter;
            if ($startVerse) {
                $pieces = $this->parser->loadVersesText(
                    $version->getFilePath(),
                    $bookNumber,
                    $chapter,
                    range($startVerse, $endVerse ?? $startVerse)
                );
            } else {
                $pieces = $this->parser->loadChapterText(
                    $version->getFilePath(),
                    $bookNumber,
                    $chapter
                );
            }
        } else {
            if ($startVerse) {
                $pieces = $this->parser->loadVerseRange(
                    $version->getFilePath(),
                    $bookNumber,
                    $startChapter,
                    $startVerse ?? 1,
                    $bookNumber,
                    $endChapter,
                    $endVerse ? $endVerse : ($startVerse ? $startVerse : 200)
                );
            } else {
                $pieces = $this->parser->loadVerseRange(
                    $version->getFilePath(),
                    $bookNumber,
                    $startChapter,
                    1,
                    $bookNumber,
                    $endChapter,
                    200
                );
            }
        }

        if ($verseRequest->getHighlightedVerses()) {
            $pieces = $this->addHighlights($pieces, $verseRequest);
        }

        if ($verseRequest->isInferLinebreaks()) {
            $pieces = $this->addLineBreaks($pieces);
        }

        return $pieces;
    }

    /**
     * @param ScripturePiece[] $pieces
     * @param VerseRequest $verseRequest
     *
     * @return string
     */
    public function render(array $pieces, VerseRequest $verseRequest): string
    {
        return $this->scripturePieceRenderer->render($pieces, $verseRequest);
    }

    /**
     * @param ScripturePiece[] $pieces
     * @return ScripturePiece[]
     */
    private function addHighlights(array $pieces, VerseRequest $request): array
    {
        foreach ($pieces as $piece) {
            if (in_array($piece->getVerse(), $request->getHighlightedVerses())) {
                $piece->setHighlighted(true);
            }
        }

        return $pieces;
    }

    /**
     * @param ScripturePiece[] $pieces
     * @return ScripturePiece[]
     */
    private function addLineBreaks(array $pieces): array
    {
        $result = [];

        $sentenceInProgress = true;
        while (!empty($pieces)) {
            /** @var ScripturePiece $piece */
            $piece = array_shift($pieces);
            if ($this->startsSentence($piece) && !$sentenceInProgress) {
                $result[] = ScripturePiece::createLinebreak();
                $sentenceInProgress = true;
            }
            if ($this->endsSentence($piece)) {
                $sentenceInProgress = false;
            }
            $result[] = $piece;
        }

        return $result;
    }

    private function endsSentence(ScripturePiece $scripturePiece): bool
    {
        if (in_array($scripturePiece->getType(), [ScripturePiece::TYPE_STYLED, ScripturePiece::TYPE_CONTENT])) {
            $content = strip_tags($scripturePiece->getContent());
            $content = trim($content, '"\'/ “«');
            $content = str_replace(PHP_EOL, '', $content);
            return (
                StringHelper::endsWith($content, '.') ||
                StringHelper::endsWith($content, ':') ||
                StringHelper::endsWith($content, '?') ||
                StringHelper::endsWith($content, '׃') ||
                StringHelper::endsWith($content, '.') ||
                StringHelper::endsWith($content, '·') ||
                StringHelper::endsWith($content, '׃ ס') ||
                StringHelper::endsWith($content, '!') ||
                StringHelper::endsWith($content, 'ن')

            );
        } else {
            return false;
        }
    }

    private function startsSentence(ScripturePiece $scripturePiece): bool
    {
        if (in_array($scripturePiece->getType(), [ScripturePiece::TYPE_STYLED, ScripturePiece::TYPE_CONTENT])) {
            $content = strip_tags($scripturePiece->getContent());
            $content = trim($content, '\'"/ „»');

            $isGreek = false;
            foreach (self::GREEK_LETTERS as $letter) {
                if (StringHelper::contains($content, $letter)) {
                    $isGreek = true;
                    break;
                }
            }

            return (ucfirst($content) === $content) || $isGreek;
        } else {
            return false;
        }
    }
}
