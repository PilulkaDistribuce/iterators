<?php
require __DIR__ . '/../vendor/autoload.php';

use Pilulka\Iterators\Http;

$urlIterator = new Http\UrlIterator('http://private-62aa2-pilulkaiterator.apiary-mock.com/items');
$jsonIterator = new Http\JsonCollectionIterator($urlIterator);
$i = 0;
echo "Url: {$jsonIterator->getUrlIterator()->getIterationUrl()}\n";
foreach ($jsonIterator as $item) {
    echo var_export($item) . "\n";
    if($i++ > 2) break;
}
