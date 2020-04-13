<?php

namespace vendor\moehrenzahn\scripturekit\test;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Parser\QuranParser;
use Moehrenzahn\ScriptureKit\Parser\XMLParser;
use Moehrenzahn\ScriptureKit\Renderer\Html\ReferenceRenderer;
use Moehrenzahn\ScriptureKit\Renderer\Names;
use Moehrenzahn\ScriptureKit\Renderer\VerseRangeRenderer;
use PHPUnit\Framework\TestCase;

class QuranParserTest extends TestCase
{
    public function testLoadVerseRange()
    {
        $subject = new QuranParser(new XMLParser(), new ReferenceRenderer(new VerseRangeRenderer(), new Names()));

        $filePath =  __DIR__ . '/../files/A. J. Arberry.xml';
        $result = $subject->loadVerseRange(
            $filePath,
            0,
            3,
            171,
            0,
            4,
            24
        );

        self::assertNotEmpty($result);
        self::assertSame(ScripturePiece::TYPE_CAPTION, $result[0]->getType());
        self::assertSame(ScripturePiece::TYPE_CONTENT, $result[1]->getType());
    }
}
