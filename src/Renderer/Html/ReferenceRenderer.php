<?php

namespace Moehrenzahn\ScriptureKit\Renderer\Html;

use Moehrenzahn\ScriptureKit\Renderer\ReferenceRendererInterface;

class ReferenceRenderer extends \Moehrenzahn\ScriptureKit\Renderer\ReferenceRenderer implements ReferenceRendererInterface
{
    protected function renderAltName(string $altName)
    {
        return "<span class='name-alt'>($altName)</span>";
    }
}
