<?php

namespace Moehrenzahn\ScriptureKit\Data;

/**
 * Class DetailedVersion
 */
class DetailedVersion
{
    /**
     * @var Version
     */
    private $version;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $languageCode;

    /**
     * @var string[]
     */
    private $details;

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

    /**
     * @return Version
     */
    public function getVersion(): Version
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
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
