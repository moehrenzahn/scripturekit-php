<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use RuntimeException;
use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use SimpleXMLElement;

class ZefaniaParser implements ParserInterface
{
    /**
     * @var XMLParser
     */
    private $xmlParser;

    private const TYPE_MAP = [
        'VERS' => ScripturePiece::TYPE_CONTENT,
        'BR' => ScripturePiece::TYPE_LINEBREAK,
        'STYLE' => ScripturePiece::TYPE_STYLED,
        'GRAM' => ScripturePiece::TYPE_GRAMMAR_NOTE,
        'NOTE' => ScripturePiece::TYPE_NOTE,
        'DIV' => ScripturePiece::TYPE_NOTE,
        'SUP' => ScripturePiece::TYPE_SUPERSCRIPT,
        'XREF' => ScripturePiece::TYPE_REF,
        'CAPTION' => ScripturePiece::TYPE_CAPTION,
    ];

    /**
     * QuranParser constructor.
     *
     * @param XMLParser $xmlParser
     */
    public function __construct(XMLParser $xmlParser)
    {
        $this->xmlParser = $xmlParser;
    }

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
        $xml = $this->xmlParser->getXMLObject($filePath);
        $xmlElements = $xml->xpath(
            "BIBLEBOOK[@bnumber='$bookNumber']/CHAPTER[@cnumber='$chapter']/VERS[@vnumber='$verse']"
        );
        if (!isset($xmlElements[0])) {
            throw new RuntimeException('Verse is not included in this version.');
        }

        $piece = $this->convertNodes($xmlElements)[0];
        $piece->setPieceId("zefania-{$piece->getType()}-$bookNumber-$chapter-{$piece->getVerse()}");
        $piece->setBookNumber($bookNumber);
        $piece->setChapter($chapter);

        return $piece;
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
        $xml = $this->xmlParser->getXMLObject($filePath);

        $verseStatement = "@vnumber='" . implode("' or @vnumber='", $verses) . "'";
        $xmlElements = $xml->xpath(
            "BIBLEBOOK[@bnumber='$bookNumber']/CHAPTER[@cnumber='$chapter']/VERS[$verseStatement]"
        );
        if (!$xmlElements) {
            throw new RuntimeException('Verses are not included in this version.');
        }

        $pieces = $this->convertNodes($xmlElements);

        foreach ($pieces as $piece) {
            $piece->setPieceId("zefania-{$piece->getType()}-$bookNumber-$chapter-{$piece->getVerse()}");
            $piece->setBookNumber($bookNumber);
            $piece->setChapter($chapter);
        }

        return $pieces;
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
        $xml = $this->xmlParser->getXMLObject($filePath);
        $xmlElements = $xml->xpath(
            "BIBLEBOOK[@bnumber='$bookNumber']/CHAPTER[@cnumber='$chapter']/descendant::*"
        );
        if (!$xmlElements) {
            throw new RuntimeException('Chapter is not included in this version.');
        }

        $pieces = $this->convertNodes($xmlElements);

        foreach ($pieces as $piece) {
            $piece->setPieceId("zefania-{$piece->getType()}-$bookNumber-$chapter-{$piece->getVerse()}");
            $piece->setBookNumber($bookNumber);
            $piece->setChapter($chapter);
        }

        return $pieces;
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
        /** @TODO Needs to be implemented  */
        throw new RuntimeException('Not implemented yet.');
    }

    /**
     * @param string $filePath
     *
     * @return string[]
     */
    public function loadVersionDetails(string $filePath): array
    {
        $xml = $this->xmlParser->getXMLObject($filePath);
        $result = [
            'creator'     => $xml->INFORMATION->creator[0],
            'description' => $xml->INFORMATION->description[0],
            'publisher'   => $xml->INFORMATION->publisher[0],
            'contributor' => $xml->INFORMATION->contributor[0],
            'source'      => $xml->INFORMATION->source[0],
            'rights'      => $xml->INFORMATION->rights[0],
        ];
        foreach ($result as &$item) {
            $item = trim((string)$item);
        }

        return array_filter($result);
    }

    public function loadTitle(string $filePath): string
    {
        $xml = $this->xmlParser->getXMLObject($filePath);

        return trim((string) ($xml->INFORMATION->title[0] ?? ''));
    }


    public function loadLanguageCode(string $filePath): string
    {
        $xml = $this->xmlParser->getXMLObject($filePath);

        return trim((string) ($xml->INFORMATION->language[0] ?? ''));
    }

    /**
     * @param SimpleXMLElement[] $xmlElements
     *
     * @return ScripturePiece[]
     */
    private function convertNodes(array $xmlElements): array
    {
        return $this->xmlParser->convertNodes($xmlElements, 'vnumber', self::TYPE_MAP);
    }
}
