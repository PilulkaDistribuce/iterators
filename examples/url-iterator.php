<?php

require __DIR__ . '/../vendor/autoload.php';

use Pilulka\Iterators\Http\UrlIterator;

/**
 * Default behavior
 */

$mask = 'http://example.com/?p=%d';
$urlIterator = new UrlIterator($mask);
$i = 1;
foreach ($urlIterator as $pageContent) {
    printf(
        "Url: %s, length: %d\n",
        $urlIterator->getIterationUrl(),
        strlen($pageContent)
    );
    if($i++ > 2) break; // to simulate invalid response
}

/**
 * Specify custom url logic
 */
$i = 1;
$mask = 'http://example.com/?limit=%d&offset=%d';
$urlIterator = new UrlIterator($mask);
$limit = 100;
$urlIterator->setUrlClosure(function($mask, $page) use ($limit) {
    return sprintf($mask, $limit, $limit*$page);
});
foreach ($urlIterator as $pageContent) {
    printf(
        "Url: %s, length: %d\n",
        $urlIterator->getIterationUrl(),
        strlen($pageContent)
    );
    if($i++ > 2) break; // to simulate invalid response
}

/**
 * Define Http Auth creadentials
 */
$urlIterator = new UrlIterator($mask);
$urlIterator->setCredentials('<username>', '<password>');
// ...
