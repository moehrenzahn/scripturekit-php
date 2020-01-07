<?php

namespace Moehrenzahn\ScriptureKit\Util;


use RuntimeException;

class BibleBookNames
{
    const BOOK_NAMES = [
        1 => "Genesis",
        2 => "Exodus",
        3 => "Leviticus",
        4 => "Numbers",
        5 => "Deuteronomy",
        6 => "Joshua",
        7 => "Judges",
        9 => "1 Samuel",
        10 => "2 Samuel",
        11 => "1 Kings",
        12 => "2 Kings",
        13 => "1 Chronicles",
        14 => "2 Chronicles",
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
        40 => "Matthew",
        41 => "Mark",
        42 => "Luke",
        43 => "John",
        44 => "Acts",
        45 => "Romans",
        46 => "1 Corinthians",
        47 => "2 Corinthians",
        48 => "Galatians",
        49 => "Ephesians",
        50 => "Philippians",
        51 => "Colossians",
        52 => "1 Thessalonians",
        53 => "2 Thessalonians",
        54 => "1 Timothy",
        55 => "2 Timothy",
        56 => "Titus",
        57 => "Philemon",
        58 => "Hebrews",
        59 => "James",
        60 => "1 Peter",
        61 => "2 Peter",
        62 => "1 John",
        63 => "2 John",
        64 => "3 John",
        65 => "Jude",
        66 => "Revelations"
    ];

    /**
     * @param int $number
     * @return string
     */
    public static function getBookName(int $number): string
    {
        if (isset(self::BOOK_NAMES[$number])) {
            return self::BOOK_NAMES[$number];
        }
        throw new RuntimeException(sprintf('Book name for number %s could not be resolved.', $number));
    }
}
