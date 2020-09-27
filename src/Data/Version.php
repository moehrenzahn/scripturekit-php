<?php

namespace Moehrenzahn\ScriptureKit\Data;

use JsonSerializable;

/**
 * Class Version
 *
 * Representation of an XML Bible, Tanakh or Quran from
 * the Zefania XML project or qurandatabase.org.
 */
class Version implements JsonSerializable
{
    public const TYPE_TANAKH = 0;
    public const TYPE_TANAKH_SEFARIA = 3;
    public const TYPE_BIBLE = 1;
    public const TYPE_QURAN = 2;

    private string $id;
    private string $name;
    private string $filePath;
    private int $type;
    /** @var int[] */
    private array $availableCollections;

    /**
     * @param string $id                    Unique identifier of the version
     * @param string $name                  The title of the version
     * @param string $filePath              The path to the xml file with the version source
     * @param int    $type                  The type of version, see self::TYPE_*
     * @param int[] $availableCollections   The collections that are part of the version, see
     *                                      Moehrenzahn\ScriptureKit\Data\VerseRequest::COLLECTION_*
     */
    public function __construct(
        string $id,
        string $name,
        string $filePath,
        int $type,
        array $availableCollections
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->filePath = $filePath;
        $this->type = $type;
        $this->availableCollections = $availableCollections;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'filePath' => $this->getFilePath(),
            'type' => $this->getType(),
            'availableCollections' => $this->getAvailableCollections(),
        ];
    }
}
