<?php

namespace Moehrenzahn\ScriptureKit\Data;

/**
 * Class DetailedVersion
 */
class DetailedVersion
{
    private Version $version;
    private string $title;
    private string $languageCode;
    /**
     * @var string[]
     */
    private array $details;

    /**
     * RenderedVersion constructor.
     *
     * @param Version  $version
     * @param string   $title
     * @param string[] $details
     * @param string   $languageCode
     */
    public function __construct(Version $version, string $title, string $languageCode, array $details)
    {
        $this->version = $version;
        $this->title = $title;
        $this->languageCode = $languageCode;
        $this->details = $details;
    }

    public function getVersion(): Version
    {
        return $this->version;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    /**
     * @return string[]
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
