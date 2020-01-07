# ScriptureKit PHP

A PHP framework for working with scripture XML files
from the [Zefania Project](https://zefania-sharp.sourceforge.io/)
and [qurandatabase.org](http://qurandatabase.org).

## Installation

`composer require moehrenzahn/scripturekit`

## Usage

```php
$requestBuilder = new \Moehrenzahn\ScriptureKit\VerseRequestBuilder();
$request = $requestBuilder->build();
$verseFactory = new \Moehrenzahn\ScriptureKit\VerseFactory();
$verse = $verseFactory->createAndRender($request);

echo $verse->getText();
```

## Testing

`make phpunit`

## Author

Max Melzer  
moehrenzahn.de  
<hi@moehrenzahn.de>
