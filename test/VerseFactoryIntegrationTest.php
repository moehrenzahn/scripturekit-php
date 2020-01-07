<?php

namespace Moehrenzahn\ScriptureKit\Test;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\VerseFactory;
use Moehrenzahn\ScriptureKit\VerseRequestBuilder;
use PHPUnit\Framework\TestCase;

class VerseFactoryIntegrationTest extends TestCase
{
    public function provider(): array
    {
        return [
            'new testament text' => [
                'versionPath' => __DIR__ . '/files/World English Bible.xml',
                'versionType' => Version::TYPE_BIBLE,
                'collection' => VerseRequest::COLLECTION_NT,
                'book' => 45,
                'chapter' => 1,
                'verses' => [1,2],
                'highlightedVerses' => [2],
                'returnHtml' => false,
                'expectedText' => 'Paul, a servant of Jesus Christ, called to be an apostle, set apart for the gospel of God, which he promised before through his prophets in the holy scriptures,'
            ],
            'new testament html' => [
                'versionPath' => __DIR__ . '/files/World English Bible.xml',
                'versionType' => Version::TYPE_BIBLE,
                'collection' => VerseRequest::COLLECTION_NT,
                'book' => 45,
                'chapter' => 1,
                'verses' => [1,2],
                'highlightedVerses' => [2],
                'returnHtml' => true,
                'expectedText' => '<p>
<span
    class="1"
    data-versenumber="1">
    Paul, a servant of Jesus Christ, called to be an apostle, set apart for the gospel of God,</span>
<span
    class="highlight 2"
    data-versenumber="2">
    which he promised before through his prophets in the holy scriptures,</span>
</p>'
            ],
            'quran text' => [
                'versionPath' => __DIR__ . '/files/A. J. Arberry.xml',
                'versionType' => Version::TYPE_QURAN,
                'collection' => VerseRequest::COLLECTION_QURAN,
                'book' => null,
                'chapter' => 1,
                'verses' => [1,2],
                'highlightedVerses' => [2],
                'returnHtml' => false,
                'expectedText' => 'In the Name of God, the Merciful, the Compassionate Praise belongs to God, the Lord of all Being,'
            ],
            'quran html' => [
                'versionPath' => __DIR__ . '/files/A. J. Arberry.xml',
                'versionType' => Version::TYPE_QURAN,
                'collection' => VerseRequest::COLLECTION_QURAN,
                'book' => null,
                'chapter' => 1,
                'verses' => [1,2],
                'highlightedVerses' => [2],
                'returnHtml' => true,
                'expectedText' => '<p>
<span
    class="1"
    data-versenumber="1">
    In the Name of God, the Merciful, the Compassionate</span>
<span
    class="highlight 2"
    data-versenumber="2">
    Praise belongs to God, the Lord of all Being,</span>
</p>'
            ],
            'quran single verse text' => [
                'versionPath' => __DIR__ . '/files/A. J. Arberry.xml',
                'versionType' => Version::TYPE_QURAN,
                'collection' => VerseRequest::COLLECTION_QURAN,
                'book' => null,
                'chapter' => 1,
                'verses' => [2],
                'highlightedVerses' => [],
                'returnHtml' => false,
                'expectedText' => 'Praise belongs to God, the Lord of all Being.'
            ],
            'quran single verse html' => [
                'versionPath' => __DIR__ . '/files/A. J. Arberry.xml',
                'versionType' => Version::TYPE_QURAN,
                'collection' => VerseRequest::COLLECTION_QURAN,
                'book' => null,
                'chapter' => 1,
                'verses' => [2],
                'highlightedVerses' => [],
                'returnHtml' => true,
                'expectedText' => '<p>
<span
    class="2"
    data-versenumber="2">
    Praise belongs to God, the Lord of all Being.</span>
</p>'
            ],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCreate(string $versionPath, int $versionType, int $collection, ?int $book, int $chapter, array $verses, array $highlightedVerses, bool $returnHtml, $expectedText)
    {
        $versionName = 'Test Version Name';
        $versionAvailableCollections = [$collection];

        $version = new Version($versionName, $versionPath, $versionType, $versionAvailableCollections);
        $verseFactory = new VerseFactory($version);

        $verseRequestBuilder = new VerseRequestBuilder($chapter, $verses, $collection);
        $verseRequestBuilder->setBookNumber($book);
        $verseRequestBuilder->setHighlightedVerses($highlightedVerses);
        $verseRequestBuilder->setReturnHtml($returnHtml);

        $request = $verseRequestBuilder->build();

        $verse = $verseFactory->create($request);

        self::assertSame(
            $expectedText,
            $verse->getText()
        );
    }
}
