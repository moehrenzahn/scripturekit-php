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
        8 => "Ruth",
        9 => "II Samuel",
        10 => "II Samuel",
        11 => "I Kings",
        12 => "II Kings",
        13 => "I Chronicles",
        14 => "II Chronicles",
        15 => "Ezra",
        16 => "Nehemia",
        17 => "Esther",
        18 => "Job",
        19 => "Psalms",
        20 => "Proverbs",
        21 => "Ecclesiastes",
        22 => "Song of Songs",
        23 => "Isaiah",
        24 => "Jeremiah",
        25 => "Lamentations",
        26 => "Ezekiel",
        27 => "Daniel",
        28 => "Hosea",
        29 => "Joel",
        30 => "Amos",
        31 => "Obadiah",
        32 => "Jonah",
        33 => "Micah",
        34 => "Nahum",
        35 => "Habakkuk",
        36 => "Zephaniah",
        37 => "Haggai",
        38 => "Zechariah",
        39 => "Malachi",
    ];

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

        return new ScripturePiece(
            ScripturePiece::TYPE_CONTENT,
            "sefaria-" . ScripturePiece::TYPE_CONTENT . "$bookNumber-$chapter-$verse",
            $bookNumber,
            $chapter,
            $verse,
            $text,
            false,
            []
        );
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
                if ($result) {
                    // previous verses were found, we are probably just at the end of the chapter
                    break;
                } else {
                    throw new RuntimeException('Verse is not included in this version.');
                }
            }

            $result[] = new ScripturePiece(
                ScripturePiece::TYPE_CONTENT,
                "sefaria-" . ScripturePiece::TYPE_CONTENT . "$bookNumber-$chapter-$verse",
                $bookNumber,
                $chapter,
                $verse,
                $text,
                false,
                []
            );
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
        $chapterData = $json[self::BOOK_NUMBER_NAME[$bookNumber]]['text'][$chapter-1] ?? null;
        if (!$chapterData) {
            throw new RuntimeException('Chapter is not included in this version.');
        }
        $result = [];
        foreach ($chapterData as $key => $text) {
            $verse = $key + 1;
            if (!$text) {
                throw new RuntimeException('Verse is not included in this version.');
            }

            $result[] = new ScripturePiece(
                ScripturePiece::TYPE_CONTENT,
                "sefaria-" . ScripturePiece::TYPE_CONTENT . "$bookNumber-$chapter-$verse",
                $bookNumber,
                $chapter,
                $verse,
                $text,
                false,
                []
            );
        }
        return $result;
    }

    /**
     * @param string $filePath
     * @param int|null $startBookNumber
     * @param int    $startChapter
     * @param int    $startVerse
     * @param int|null $endBookNumber
     * @param int    $endChapter
     * @param int    $endVerse
     * @throws RuntimeException
     *
     * @return ScripturePiece[]
     */
    public function loadVerseRange(
        string $filePath,
        ?int $startBookNumber,
        int $startChapter,
        int $startVerse,
        ?int $endBookNumber,
        int $endChapter,
        int $endVerse
    ): array {
        $json = json_decode(file_get_contents($filePath), true);
        if (!$json) {
            throw new RuntimeException("Could not parse version file at '$filePath'.");
        }

        $bookRange = range($startBookNumber, $endBookNumber);

        $result = [];

        foreach ($bookRange as $bookNumber) {
            $isFirstBook = $bookNumber === $startBookNumber;
            $isLastBook = $bookNumber === $endBookNumber;
            if ($isFirstBook) {
                $chapterRange = range($startChapter, 200);
            } elseif ($isLastBook) {
                $chapterRange = range(1, $endChapter);
            } else {
                $chapterRange = range(1, 200);
            }

            foreach ($chapterRange as $chapter) {
                $isFirstChapter = $isFirstBook && $chapter === $startChapter;
                $isLastChapter = $isLastBook && $chapter === $endChapter;

                if ($isFirstChapter) {
                    $verseRange = range($startVerse, 200);
                } elseif ($isLastChapter) {
                    $verseRange = range(1, $endVerse);
                } else {
                    $verseRange = range(1, 200);
                }

                foreach ($verseRange as $verse) {
                    $text = $json[self::BOOK_NUMBER_NAME[$bookNumber]]['text'][$chapter-1][$verse-1] ?? null;
                    if (!$text) {
                        break;
                    }

                    $result[] = new ScripturePiece(
                        ScripturePiece::TYPE_CONTENT,
                        "sefaria-" . ScripturePiece::TYPE_CONTENT . "$bookNumber-$chapter-$verse",
                        $bookNumber,
                        $chapter,
                        $verse,
                        $text,
                        false,
                        []
                    );
                }
            }
        }

        if (empty($result)) {
            throw new RuntimeException('Verses are not included in this version.');
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
