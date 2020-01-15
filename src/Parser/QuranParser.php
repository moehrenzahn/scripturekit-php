<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use RuntimeException;
use SimpleXMLElement;

/**
 * Class QuranParser
 *
 * @package Application\Model\Parser
 */
class QuranParser implements ParserInterface
{
    const TYPE_MAP = [
        'Verse' => ScripturePiece::TYPE_CONTENT,
    ];

    /**
     * @var XMLParser
     */
    private $xmlParser;

    /**
     * QuranParser constructor.
     *
     * @param XMLParser $xmlParser
     */
    public function __construct(XMLParser $xmlParser)
    {
        $this->xmlParser = $xmlParser;
    }

    public function loadVerseText(
        string $filePath,
        ?int $bookNumber,
        int $chapter,
        int $verse
    ): ScripturePiece {
        $xml = $this->xmlParser->getXMLObject($filePath);
        $xmlElements = $xml->xpath(
            "Chapter[@ChapterID='$chapter']/Verse[@VerseID='$verse']"
        );
        if (!$xmlElements[0]) {
            throw new RuntimeException(
                sprintf(
                    'Quran %s:%s is not included in this version.',
                    $chapter,
                    $verse
                )
            );
        }

        return $this->convertNodes($xmlElements)[0];
    }

    /**
     * @param string $filePath
     * @param int|null $bookNumber
     * @param int    $chapter
     * @param int[]  $verses
     *
     * @return ScripturePiece[]
     */
    public function loadVersesText(
        string $filePath,
        ?int $bookNumber,
        int $chapter,
        array $verses
    ): array {
        $xml = $this->xmlParser->getXMLObject($filePath);

        $verseStatement = "@VerseID='" . implode("' or @VerseID='", $verses) . "'";
        $xmlElements = $xml->xpath(
            "Chapter[@ChapterID='$chapter']/Verse[$verseStatement]"
        );
        if (!$xmlElements) {
            throw new RuntimeException(
                sprintf(
                    'Quran chapter %s is not included in this version.',
                    $chapter
                )
            );
        }

        return $this->convertNodes($xmlElements);
    }

    /**
     * @param string $filePath
     * @param int|null $bookNumber
     * @param int    $chapter
     *
     * @return ScripturePiece[]
     */
    public function loadChapterText(
        string $filePath,
        ?int $bookNumber,
        int $chapter
    ): array {
        $xml = $this->xmlParser->getXMLObject($filePath);
        $xmlElements = $xml->xpath(
            "Chapter[@ChapterID='$chapter']/descendant::*"
        );
        if (!$xmlElements) {
            throw new RuntimeException(
                sprintf(
                    'Quran chapter %s is not included in this bible version.',
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
    public function loadVersionDetails(string $filePath): array
    {
        $xml = $this->xmlParser->getXMLObject($filePath);
        $result = [
            'author'   => $xml['Writer'] ?? '',
            'language' => $xml['Language'] ?? '',
            'languageCode' => $xml['LanguageIsoCode'] ?? '',
        ];
        foreach ($result as &$item) {
            $item = trim((string)$item);
        }

        return array_filter($result);
    }

    public function loadTitle(string $filePath): string
    {
        $xml = $this->xmlParser->getXMLObject($filePath);

        return trim((string) ($xml['Writer'] ?? ''));
    }

    public function loadLanguageCode(string $filePath): string
    {
        $xml = $this->xmlParser->getXMLObject($filePath);

        return trim((string) ($xml['LanguageIsoCode'] ?? ''));
    }

    /**
     * @param SimpleXMLElement[] $xmlElements
     *
     * @return ScripturePiece[]
     */
    private function convertNodes(array $xmlElements): array
    {
        return $this->xmlParser->convertNodes($xmlElements, 'VerseID', self::TYPE_MAP);
    }
}
