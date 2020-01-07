<?php

namespace Moehrenzahn\ScriptureKit\Renderer;

use Moehrenzahn\ScriptureKit\Data\VerseRequest;
use Moehrenzahn\ScriptureKit\Data\Version;

interface VerseTextRendererInterface
{
    public function render(VerseRequest $verseRequest, Version $version): string;
}
