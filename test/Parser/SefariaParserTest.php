<?php

namespace vendor\moehrenzahn\scripturekit\test;

use Moehrenzahn\ScriptureKit\Parser\SefariaParser;
use PHPUnit\Framework\TestCase;

class SefariaParserTest extends TestCase
{
    public function testLoadVerseRange()
    {
        $subject = new SefariaParser();

        $filePath =  __DIR__ . '/../files/New JPS Translation 1917.json';

        $result = $subject->loadVerseRange(
            $filePath,
            3,
            10,
            15,
            4,
            1,
            3
        );

        self::assertNotEmpty($result);
    }

    public function testLoadVerseText()
    {
        $subject = new SefariaParser();

        $filePath =  __DIR__ . '/../files/New JPS Translation 1917.json';

        $result = $subject->loadVerseText(
            $filePath,
            1,
            1,
            5
        );

        self::assertSame(
            'And God called the light Day, and the darkness He called Night. And there was evening and there was morning, one day.',
            $result->getContent()
        );
    }
}
