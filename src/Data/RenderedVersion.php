<?php

namespace Moehrenzahn\ScriptureKit\Data;

/**
 * Class RenderedVersion
 */
class RenderedVersion
{
    /**
     * @var Version
     */
    private $version;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int[]
     */
    private $collections;

    /**
     * @var string[]
     */
    private $details;

    /**
     * RenderedVersion constructor.
     *
     * @param Version  $version
     * @param string   $name
     * @param int[]    $collections
     * @param string[] $details
     */
    public function __construct(Version $version, string $name, array $collections, array $details)
    {
        $this->version = $version;
        $this->name = $name;
        $this->collections = $collections;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int[]
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     * @return string[]
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
