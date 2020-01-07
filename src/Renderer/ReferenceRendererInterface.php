<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;

interface ReferenceRendererInterface
{
    public function getLongReference(VerseRequest $verseRequest): string;

    public function getMediumReference(VerseRequest $verseRequest): string;

    public function getShortReference(VerseRequest $verseRequest): string;
}
