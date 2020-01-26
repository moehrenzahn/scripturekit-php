<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

class Names
{
    /**
     * @var string[]
     */
    private $tanakhBookNames;

    /**
     * @var string[]
     */
    private $bibleBookNames;

    /**
     * @var string[]
     */
    private $quranChapterNames;

    /**
     * Names constructor.
     *
     * @param string[] $tanakhBookNames
     * @param string[] $bibleBookNames
     * @param string[] $quranChapterNames
     */
    public function __construct(array $tanakhBookNames = [], array $bibleBookNames = [], array $quranChapterNames = [])
    {
        $this->tanakhBookNames = $tanakhBookNames;
        $this->bibleBookNames = $bibleBookNames;
        $this->quranChapterNames = $quranChapterNames;
    }

    /**
     * @param int $bookNumer
     *
     * @return string
     */
    public function getTanakhBookName(int $bookNumer): string
    {
        return $this->tanakhBookNames[$bookNumer] ?? '';
    }

    /**
     * @param int $bookNumer
     *
     * @return string
     */
    public function getBibleBookName(int $bookNumer): string
    {
        return $this->bibleBookNames[$bookNumer] ?? '';
    }

    /**
     * @param int $chapterNumber
     *
     * @return string
     */
    public function getQuranChapterName(int $chapterNumber): string
    {
        return $this->quranChapterNames[$chapterNumber] ?? '';
    }

    /**
     * @param string[] $tanakhBookNames
     */
    public function setTanakhBookNames(array $tanakhBookNames): void
    {
        $this->tanakhBookNames = $tanakhBookNames;
    }

    /**
     * @param string[] $bibleBookNames
     */
    public function setBibleBookNames(array $bibleBookNames): void
    {
        $this->bibleBookNames = $bibleBookNames;
    }

    /**
     * @param string[] $quranChapterNames
     */
    public function setQuranChapterNames(array $quranChapterNames): void
    {
        $this->quranChapterNames = $quranChapterNames;
    }
}
