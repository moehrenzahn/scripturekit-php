<?php

namespace Moehrenzahn\ScriptureKit\Test;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Service;
use Moehrenzahn\ScriptureKit\VerseRequestBuilder;
use PHPUnit\Framework\TestCase;

class ServiceIntegrationTest extends TestCase
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
                'expectedLongReference' => 'Romans 1:1–2',
                'expectedText' => 'Paul, a servant of Jesus Christ, called to be an apostle, set apart for the gospel of God, which he promised before through his prophets in the holy scriptures.'
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
                'expectedLongReference' => 'Romans 1:1–2',
                'expectedText' => '<p>
<span
    class="1"
    data-versenumber="1">
    Paul, a servant of Jesus Christ, called to be an apostle, set apart for the gospel of God,</span>
<span
    class="highlight 2"
    data-versenumber="2">
    which he promised before through his prophets in the holy scriptures.</span>
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
                'expectedLongReference' => 'Al-Fatihah (Surah 1) 1–2',
                'expectedText' => 'In the Name of God, the Merciful, the Compassionate Praise belongs to God, the Lord of all Being.'
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
                'expectedLongReference' => 'Al-Fatihah <span class=\'name-alt\'>(Surah 1)</span> 1–2',
                'expectedText' => '<p>
<span
    class="1"
    data-versenumber="1">
    In the Name of God, the Merciful, the Compassionate</span>
<span
    class="highlight 2"
    data-versenumber="2">
    Praise belongs to God, the Lord of all Being.</span>
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
                'expectedLongReference' => 'Al-Fatihah (Surah 1) 2',
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
                'expectedLongReference' => 'Al-Fatihah <span class=\'name-alt\'>(Surah 1)</span> 2',
                'expectedText' => '<p>
<span
    class="2"
    data-versenumber="2">
    Praise belongs to God, the Lord of all Being.</span>
</p>'
            ],
        ];
    }

    public function versionDataProvider(): array
    {
        return [
            'zefania' => [
                'versionPath' => __DIR__ . '/files/World English Bible.xml',
                'versionType' => Version::TYPE_BIBLE,
                'expectedTitle' => 'World English Bible',
                'expectedLanguageCode' => 'eng',

            ],
            'qurandatabase' => [
                'versionPath' => __DIR__ . '/files/A. J. Arberry.xml',
                'versionType' => Version::TYPE_QURAN,
                'expectedTitle' => 'A. J. Arberry',
                'expectedLanguageCode' => 'eng',
            ]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCreateVerse(
        string $versionPath,
        int $versionType,
        int $collection,
        ?int $book,
        int $chapter,
        array $verses,
        array $highlightedVerses,
        bool $returnHtml,
        string $expectedLongReference,
        string $expectedText
    ) {
        $versionName = 'Test Version Name';
        $versionAvailableCollections = [$collection];

        $version = new Version($versionName, $versionPath, $versionType, $versionAvailableCollections);
        $subject = new Service($version);

        $verseRequestBuilder = new VerseRequestBuilder($chapter, $verses, $collection);
        $verseRequestBuilder->setBookNumber($book);
        $verseRequestBuilder->setHighlightedVerses($highlightedVerses);
        $verseRequestBuilder->setReturnHtml($returnHtml);

        $request = $verseRequestBuilder->build();

        $verse = $subject->createVerse($request);

        self::assertSame(
            $expectedText,
            $verse->getText()
        );

        self::assertSame(
            $expectedLongReference,
            $verse->getFullReference()
        );
    }

    /**
     * @dataProvider versionDataProvider
     */
    public function testCreateVersion(string $versionPath, int $versionType, string $expectedTitle, string $expectedLanguageCode)
    {
        $versionName = 'Test Version Name';
        $versionAvailableCollections = [];

        $version = new Version($versionName, $versionPath, $versionType, $versionAvailableCollections);

        $subject = new Service($version);

        $detailedVersion = $subject->createDetailedVersion();

        self::assertSame(
            $expectedTitle,
            $detailedVersion->getTitle()
        );
        self::assertSame(
            $expectedLanguageCode,
            $detailedVersion->getLanguageCode()
        );
    }
}
