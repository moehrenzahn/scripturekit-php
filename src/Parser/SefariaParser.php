<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use RuntimeException;
use Moehrenzahn\ScriptureKit\Data\ScripturePiece;

class SefariaParser implements ParserInterface
{
    private const BOOK_NUMBER_NAME = [
        1 => "Genesis",
        2 => "Exodus",
        3 => "Leviticus",
        4 => "Numbers",
        5 => "Deuteronomy",
        6 => "Joshua",
        7 => "Judges",
        9 => "II Samuel",
        10 => "II Samuel",
        11 => "I Kings",
        12 => "II Kings",
        13 => "I Chronicles",
        14 => "II Chronicles",
        18 => "Job",
        19 => "Psalms",
        20 => "Proverbs",
        21 => "Ecclesiastes",
        22 => "Song of Songs",
        23 => "Isaiah",
        24 => "Jeremiah",
        25 => "Lamentations",
        26 => "Ezekiel",
        28 => "Hosea",
        31 => "Obadiah",
        33 => "Micah",
        34 => "Nahum",
        35 => "Habakkuk",
        36 => "Zephaniah",
        38 => "Zechariah",
        39 => "Malachi",
    ];

    /**
     * @param string $filePath
     * @param int    $bookNumber
     * @param int    $chapter
     * @param int    $verse
     *
     * @return ScripturePiece
     */
    public function loadVerseText(
        string $filePath,
        int $bookNumber,
        int $chapter,
        int $verse
    ): ScripturePiece {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }
        $text = $json[self::BOOK_NUMBER_NAME[$bookNumber]]['text'][$chapter-1][$verse-1] ?? null;
        if (!$text) {
            throw new RuntimeException('Verse is not included in this version.');
        }

        return new ScripturePiece(ScripturePiece::TYPE_CONTENT, $verse, $text, []);
    }

    /**
     * @param string $filePath
     * @param int    $bookNumber
     * @param int    $chapter
     * @param array  $verses
     *
     * @return ScripturePiece[]
     */
    public function loadVersesText(
        string $filePath,
        int $bookNumber,
        int $chapter,
        array $verses
    ): array {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }
        $result = [];
        foreach ($verses as $verse) {
            $text = $json[self::BOOK_NUMBER_NAME[$bookNumber]]['text'][$chapter-1][$verse-1] ?? null;

            if (!$text) {
                throw new RuntimeException('Verse is not included in this version.');
            }

            $result[] = new ScripturePiece(ScripturePiece::TYPE_CONTENT, $verse, $text, []);
        }
        return $result;
    }

    /**
     * @param string $filePath
     * @param int    $bookNumber
     * @param int    $chapter
     *
     * @return ScripturePiece[]
     */
    public function loadChapterText(
        string $filePath,
        int $bookNumber,
        int $chapter
    ): array {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }
        $chapter = $json[self::BOOK_NUMBER_NAME[$bookNumber]]['text'][$chapter-1] ?? null;
        if (!$chapter) {
            throw new RuntimeException('Chapter is not included in this version.');
        }
        $result = [];
        foreach ($chapter as $key => $text) {
            $verse = $key + 1;
            if (!$text) {
                throw new RuntimeException('Verse is not included in this version.');
            }

            $result[] = new ScripturePiece(ScripturePiece::TYPE_CONTENT, $verse, $text, []);
        }
        return $result;
    }

    /**
     * @param string $filePath
     *
     * @return string[]
     */
    public function loadVersionDetails(string $filePath): array
    {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }

        $result = [
            'creator'     => "Sefaria",
            'language'    => $json['language'],
            'source'      => $json['versionSource'],
            'rights'      => $json['license'],
        ];
        foreach ($result as &$item) {
            $item = trim((string)$item);
        }

        return array_filter($result);
    }

    public function loadTitle(string $filePath): string
    {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }

        return $json['versionTitle'] ?? "";
    }


    public function loadLanguageCode(string $filePath): string
    {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }

        return $json['language'] ?? "";
    }
}
