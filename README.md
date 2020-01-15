# ScriptureKit PHP

A PHP framework for working with scripture XML files
from the [Zefania Project](https://zefania-sharp.sourceforge.io/)
and [qurandatabase.org](http://qurandatabase.org).

## Installation

`composer require moehrenzahn/scripturekit`

## Usage

```php
$version = new \Moehrenzahn\ScriptureKit\Data\Version($filePath, $type, $availableCollections);

$verseRequestBuilder = new \Moehrenzahn\ScriptureKit\VerseRequestBuilder($chapterNumber, $verseNumbers, $collection);
$verseRequestBuilder->setBookNumber($bookNumber);
$verseRequestBuilder->setHighlightedVerses([2,3]);
$verseRequestBuilder->setReturnHtml(true);

$service = new \Moehrenzahn\ScriptureKit\Service($version);

$verse = $service->createVerse($verseRequestBuilder->build());

$detailedVersion = $service->createDetailedVersion();

echo $detailedVersion->getTitle();
echo $verse->getText();
```

## Testing

`make phpunit`

## Author

Max Melzer  
moehrenzahn.de  
<hi@moehrenzahn.de>
