<?php

namespace Moehrenzahn\ScriptureKit\Parser;

use Moehrenzahn\ScriptureKit\Data\ScripturePiece;
use Moehrenzahn\ScriptureKit\Util\StringHelper;
use SimpleXMLElement;

/**
 * Class XMLParser
 *
 * @package Framework\Library\Parser
 */
class XMLParser
{
    /**
     * @var \SimpleXMLElement[]
     */
    private $XMLObjects;

    /**
     * @param string $filePath
     *
     * @return \SimpleXMLElement
     */
    public function getXMLObject(string $filePath): \SimpleXMLElement
    {
        if (!isset($this->XMLObjects[$filePath])) {
            $this->XMLObjects[$filePath] = $this->loadFile($filePath);
        }

        return $this->XMLObjects[$filePath];
    }

    /**
     * @param string $path
     * @return \SimpleXMLElement
     */
    private function loadFile(string $path)
    {
        return simplexml_load_file($path);
    }

    /**
     * @param SimpleXMLElement[] $xmlElements
     * @param string $idAttributeKey
     * @param string[] $typeMap
     *
     * @return ScripturePiece[]
     */
    public function convertNodes(array $xmlElements, string $idAttributeKey, array $typeMap): array
    {
        $pieces = [];
        foreach ($xmlElements as $element) {
            $attributes = [];
            foreach ($element->attributes() as $key => $value) {
                $attributes[(string)$key] = (string)$value;
            }

            $content = StringHelper::removeWhitespace((string)$element);

            // Prettify single verses
            if (count($xmlElements) === 1) {
                $content = StringHelper::uppercaseFirst($content);
                $content = StringHelper::trailingCommaToString($content);
            }

            $pieces[] = new ScripturePiece(
                $typeMap[$element->getName()],
                (string)$element->attributes()[$idAttributeKey],
                $content,
                $attributes
            );
        }

        return $pieces;
    }
}
