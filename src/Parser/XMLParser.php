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
        $i = 1;
        $numberOfElements = count($xmlElements);
        foreach ($xmlElements as $element) {
            $attributes = [];
            foreach ($element->attributes() as $key => $value) {
                $attributes[(string)$key] = (string)$value;
            }

            $content = StringHelper::removeWhitespace((string)$element);

            // Prettify verses
            if ($i === 1) {
                $content = StringHelper::uppercaseFirst($content);
            }
            if ($i === $numberOfElements) {
                $content = StringHelper::trailingCommaToPeriod($content);
            }

            $pieces[] = new ScripturePiece(
                $typeMap[$element->getName()],
                (int)$element->attributes()[$idAttributeKey],
                $content,
                $attributes
            );

            $i++;
        }

        return $pieces;
    }
}
