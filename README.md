# ScriptureKit PHP

A PHP framework for working with scripture XML files
from the [Zefania Project](https://zefania-sharp.sourceforge.io/)
and [qurandatabase.org](http://qurandatabase.org).

## Requirements

- PHP 7.2 or newer with SimpleXML and mbstring extensions
- Composer package manager 

## Installation

ScriptureKit can only be installed via the package manager [Composer](https://getcomposer.org):

`composer require moehrenzahn/scripturekit`

For more information about how to use Composer in your Project, see [the Composer documentation](https://getcomposer.org/doc/00-intro.md).

## Features

You can use ScriptureKit to:

- load the text of a chapter
- load the text of a verse
- load the text of a set of verses
- render a formatted reference string for a chapter, verse set, or verse
- load version details for a scripture file.

When loading verse text, there are the following options available:

- render as `HTML` or plain text
- highlight individual verses
- automatically add paragraph-breaks at the end of sentences
- include annotations (ZefaniaXML only)  

ScriptureKit is compatible with the following scripture files:

- [ZefaniaXML](https://zefania-sharp.sourceforge.io/) files (.xml) 
- `XML Format (One File-Whole Quran)` exported from [qurandatabase.org](http://qurandatabase.org). (.xml)

## Usage

### Instantiation

```php
$availableCollections = [
    Moehrenzahn\ScriptureKit\Data\VerseRequest::COLLECTION_NT,
    Moehrenzahn\ScriptureKit\Data\VerseRequest::COLLECTION_OT
];

$version = new \Moehrenzahn\ScriptureKit\Data\Version(
    'International Standard Version',     // Title of the version
    'xml/bibles/isv.xml', // Filesystem path to the xml file you want to load 
    \Moehrenzahn\ScriptureKit\Data\Version::TYPE_BIBLE, // The type of version (Zefania XML Tanakh, Zefania XML Bible, or Qurandatabase Quran)
    $availableCollections // List of Collections available in the version, see Moehrenzahn\ScriptureKit\Data\VerseRequest::COLLECTION_*
);
// Instantiate the ScriptureKit service
$service = new \Moehrenzahn\ScriptureKit\Service($version);
```

## Loading the text of a Verse

```php
$version = new \Moehrenzahn\ScriptureKit\Data\Version($filePath, $type, $availableCollections);
$service = new \Moehrenzahn\ScriptureKit\Service($version);

$verseRequestBuilder = new \Moehrenzahn\ScriptureKit\VerseRequestBuilder(
    3, // Number of the chapter to load,
    [16], // List of verses to load. Can be non-consecutive
    Moehrenzahn\ScriptureKit\Data\VerseRequest::COLLECTION_NT // Collection from which to load the verse (Tanakh, OT, NT, or Quran) 
);
// Number of the Book to load (if collection is not Quran). First book starts at 1 (Matthew)
$verseRequestBuilder->setBookNumber(4); 
// List of verses to render with highlight
$verseRequestBuilder->setHighlightedVerses([]); 
// Render as HTML instead of plain text
$verseRequestBuilder->setReturnHtml(true);
// See \Moehrenzahn\ScriptureKit\VerseRequestBuilder for more options
$verseRequestBuilder->set...();

$verse = $service->createVerse($verseRequestBuilder->build());

// Print verse text (as HTML)
echo $verse->getText();

// Print verse reference string ("New Testament, John 3:16")
echo $verse->getFullReference();
// Print a compact reference (John 3:16)
echo $verse->getCompactReference();
// See \Moehrenzahn\ScriptureKit\Data\RenderedVerse for more options
echo $verse->get...();
```

### Loading Version details

```php
$version = new \Moehrenzahn\ScriptureKit\Data\Version($filePath, $type, $availableCollections);
$service = new \Moehrenzahn\ScriptureKit\Service($version);

$detailedVersion = $service->createDetailedVersion();

echo $detailedVersion->getTitle();
echo $detailedVersion->getDetails()['language'];
```

## Internationalisation

You can internationalise the names for books and chapters, as well as the verse separator (default `:`).

Your configured strings will be used when generating verse references.

```php
$version = new \Moehrenzahn\ScriptureKit\Data\Version($filePath, $type, $availableCollections);
$service = new \Moehrenzahn\ScriptureKit\Service($version);
$requestBuilder = new \Moehrenzahn\ScriptureKit\VerseRequestBuilder($chapterNumber, $verses, $collection);

$requestBuilder->setChapterVerseSeparator(',');
$requestBuilder->setTanakhBookNames(\Moehrenzahn\ScriptureKit\Util\TanakhBookNames::BOOK_NAMES);
$requestBuilder->setBibleBookNames(\Moehrenzahn\ScriptureKit\Util\BibleBookNames::BOOK_NAMES);
$requestBuilder->setQuranChapterNames(\Moehrenzahn\ScriptureKit\Util\QuranChapterNames::CHAPTER_NAMES);
``` 

## Testing

`make phpunit`

## Author

Max Melzer  
moehrenzahn.de  
<hi@moehrenzahn.de>
