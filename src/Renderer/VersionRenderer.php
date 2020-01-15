<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\DetailedVersion;
use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;
use Moehrenzahn\ScriptureKit\Parser\ParserInterface;
use Moehrenzahn\ScriptureKit\Util\BibleBookNames;
use Moehrenzahn\ScriptureKit\Util\QuranChapterNames;
use Moehrenzahn\ScriptureKit\Util\TanakhBookNames;

class VersionRenderer
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * VersionRenderer constructor.
     *
     * @param ParserInterface $parser
     */
    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function render(Version $version): DetailedVersion
    {
        $filePath = $version->getFilePath();

        return new DetailedVersion(
            $version,
            $this->parser->loadTitle($filePath),
            strtolower($this->parser->loadLanguageCode($filePath)),
            $this->parser->loadVersionDetails($filePath)
        );
    }

    private function getBookName(VerseRequest $verseRequest, Version $version): string
    {
        $bookName = '';
        if ($version->getType() === Version::TYPE_TANAKH) {
            $bookName = TanakhBookNames::getBookName($verseRequest->getBookNumber());
        } else if ($version->getType() === Version::TYPE_BIBLE) {
            $bookName = BibleBookNames::getBookName($verseRequest->getBookNumber());
        }

        return $bookName;
    }

    private function getChapterName(VerseRequest $verseRequest, Version $version): string
    {
        $chapterName = '';
        if ($version->getType() === Version::TYPE_QURAN) {
            $chapterName = QuranChapterNames::getChapterName($verseRequest->getChapter());
        }

        return $chapterName;
    }
}
