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

    public function render(VerseRequest $verseRequest, Version $version): string
    {
        $verses = $verseRequest->getVerses();

        if (empty($verses)) {
            $pieces = $this->parser->loadChapterText($version->getFilePath(), $verseRequest->getBookNumber(), $verseRequest->getChapter());
        } elseif (count($verses) === 1) {
            $pieces = [
                $this->parser->loadVerseText($version->getFilePath(), $verseRequest->getBookNumber(), $verseRequest->getChapter(), $verses[0])
            ];
        } else {
            $pieces = $this->parser->loadVersesText($version->getFilePath(), $verseRequest->getBookNumber(), $verseRequest->getChapter(), $verses);
        }

        if ($verseRequest->isInferLinebreaks()) {
            $pieces = $this->addLineBreaks($pieces);
        }

        return $this->scripturePieceRenderer->render($pieces, $verseRequest);
    }

    /**
     * @param ScripturePiece[] $pieces
     * @return ScripturePiece[]
     */
    public function addLineBreaks(array $pieces): array
    {
        $result = [];

        $sentenceInProgress = true;
        while (!empty($pieces)) {
            /** @var ScripturePiece $piece */
            $piece = array_shift($pieces);
            if ($this->startsSentence($piece) && !$sentenceInProgress) {
                $result[] = new ScripturePiece(ScripturePiece::TYPE_LINEBREAK, '', '', []);
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
