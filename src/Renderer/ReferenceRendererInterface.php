<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;

interface ReferenceRendererInterface
{
    public function getLongReference(VerseRequest $verseRequest): string;

    public function getMediumReference(VerseRequest $verseRequest): string;

    public function getShortReference(VerseRequest $verseRequest): string;

    public function getBookName(int $collection, ?int $bookNumber, bool $withAltName): string;

    public function getChapterName(int $collection, int $chapter, bool $withAltName): string;
}
