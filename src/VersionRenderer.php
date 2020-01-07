<?php

namespace Moehrenzahn\ScriptureKit;

use Moehrenzahn\ScriptureKit\Data\RenderedVersion;
use Moehrenzahn\ScriptureKit\Data\Version;

class VersionRenderer
{
    public function toObject(Version $version): RenderedVersion
    {
        return new RenderedVersion();
    }

    /**
     * @param Version $version
     *
     * @return array
     */
    public function toJson(Version $version): array
    {
        $renderedVersion = $this->toObject($version);

        return [
            'id' => $version->getName(),
            'name' => $version->getName(),
            'language' => $renderedVersion->getLanguage(),
            'details' => $renderedVersion->getDetails(),
        ];
    }
}
