<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Renderer\ReferenceRendererInterface;
use RuntimeException;

class QuranParser implements ParserInterface
{
    const TYPE_MAP = [
        'Verse' => ScripturePiece::TYPE_CONTENT,
    ];

    /**
     * @var string[][]
     */
    private $hizb;

    private XMLParser $xmlParser;
    private ReferenceRendererInterface $referenceRenderer;

    public function __construct(XMLParser $xmlParser, ReferenceRendererInterface $referenceRenderer)
    {
        $this->xmlParser = $xmlParser;
        $this->hizb = json_decode(file_get_contents(__DIR__ . '/../../files/quran-hizb.json'), true);
        $this->referenceRenderer = $referenceRenderer;
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

        $piece = $this->xmlParser->convertNodes($xmlElements, 'VerseID', self::TYPE_MAP)[0];
        $piece->setPieceId("quran-{$piece->getType()}-$chapter-{$piece->getVerse()}");
        $piece->setChapter($chapter);

        return $piece;
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

        $pieces = $this->xmlParser->convertNodes($xmlElements, 'VerseID', self::TYPE_MAP);

        return $this->insertPieces($pieces, $chapter);
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
                    'Quran chapter %s is not included in this version.',
                    $chapter
                )
            );
        }

        $pieces = $this->xmlParser->convertNodes($xmlElements, 'VerseID', self::TYPE_MAP);

        return $this->insertPieces($pieces, $chapter);
    }

    /**
     * @param string $filePath
     * @param int|null $startBookNumber
     * @param int    $startChapter
     * @param int    $startVerse
     * @param int|null $endBookNumber
     * @param int    $endChapter
     * @param int    $endVerse
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
        $xml = $this->xmlParser->getXMLObject($filePath);

        $chapterRange = range($startChapter, $endChapter);
        $result = [];
        foreach ($chapterRange as $chapter) {
            $isFirstChapter = $chapter === $startChapter;
            $isLastChapter = $chapter === $endChapter;

            if ($isFirstChapter) {
                $verseRange = range($startVerse, 176);
            } elseif ($isLastChapter) {
                $verseRange = range(1, $endVerse);
            } else {
                $verseRange = range(1, 176);
            }

            $verseStatement = "@VerseID='" . implode("' or @VerseID='", $verseRange) . "'";
            $xmlElements = $xml->xpath("Chapter[@ChapterID='$chapter']/Verse[$verseStatement]");
            $pieces = $this->xmlParser->convertNodes($xmlElements, 'VerseID', self::TYPE_MAP);
            $result = array_merge(
                $result,
                $this->insertPieces($pieces, $chapter)
            );
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
     * @param ScripturePiece[] $pieces  Unfinished, raw XMLParser pieces
     * @param int              $chapter
     * @return ScripturePiece[]         Enriched pieces
     */
    private function insertPieces(array $pieces, int $chapter): array
    {
        $result = [];
        foreach ($pieces as $piece) {
            $piece->setPieceId("quran-{$piece->getType()}-$chapter-{$piece->getVerse()}");
            $piece->setChapter($chapter);

            if ($piece->getVerse() === 1) {
                $result[] = new ScripturePiece(
                    ScripturePiece::TYPE_CHAPTER_TITLE,
                    'quran-' . ScripturePiece::TYPE_CHAPTER_TITLE . "-quran-$chapter",
                    null,
                    $chapter,
                    null,
                    $this->referenceRenderer->getChapterName(VerseRequest::COLLECTION_QURAN, $chapter, true),
                    false,
                    []
                );
            }

            $hizb = $this->hizb[$chapter . ':' . $piece->getVerse()] ?? null;
            if ($hizb) {
                $result[] = new ScripturePiece(
                    ScripturePiece::TYPE_CAPTION,
                    'quran-' . ScripturePiece::TYPE_CAPTION . '-hizb-' . $hizb['id'],
                    null,
                    $chapter,
                    null,
                    $hizb['title'],
                    false,
                    []
                );
            }
            $result[] = $piece;
        }
        return $result;
    }
}
