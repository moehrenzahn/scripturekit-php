<?php

namespace Moehrenzahn\ScriptureKit\Data;

/**
 * Class Version
 */
class Version
{
    public const TYPE_TANAKH = 0;
    public const TYPE_BIBLE = 1;
    public const TYPE_QURAN = 2;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var int
     */
    private $type;

    /**
     * @var int[]
     */
    private $availableCollections;

    /**
     * Version constructor.
     *
     * @param string $name
     * @param string $filePath
     * @param int    $type
     * @param int[]  $availableCollections
     */
    public function __construct(
        string $name,
        string $filePath,
        int $type,
        array $availableCollections
    ) {
        $this->name = $name;
        $this->filePath = $filePath;
        $this->type = $type;
        $this->availableCollections = $availableCollections;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int[]
     */
    public function getAvailableCollections(): array
    {
        return $this->availableCollections;
    }
}
