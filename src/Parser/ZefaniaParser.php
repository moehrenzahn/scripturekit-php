<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use Moehrenzahn\ScriptureKit\Util\BibleBookNames;
use Moehrenzahn\ScriptureKit\Util\StringHelper;
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
        if (!$xmlElements[0]) {
            throw new RuntimeException(
                sprintf(
                    'Verse %s of %s %s is not included in this version.',
                    $verse,
                    BibleBookNames::getBookName($bookNumber),
                    $chapter
                )
            );
        }

        return $this->convertNodes($xmlElements)[0];
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
            throw new RuntimeException(
                sprintf(
                    '%s chapter %s is not included in this bible version.',
                    BibleBookNames::getBookName($bookNumber),
                    $chapter
                )
            );
        }

        return $this->convertNodes($xmlElements);
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
            throw new RuntimeException(
                sprintf(
                    '%s chapter %s is not included in this bible version.',
                    BibleBookNames::getBookName($bookNumber),
                    $chapter
                )
            );
        }

        return $this->convertNodes($xmlElements);
    }

    /**
     * @param string $filePath
     *
     * @return string[]
     */
    public function loadVersionDetails(string $filePath)
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
