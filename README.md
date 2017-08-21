# Pilulka Iterators

This library allows you to iterate over logically iterable streams or resources.

There are implemented these resources.
 
## Curl Http Iterator

It allows you to iterate over given url mask until resource response code status
 differs from 200 Ok.
 
Example: 
```php
$mask = 'http://example.com/?p=%d';
$urlIterator = new UrlIterator($mask);
$i = 0;
foreach ($urlIterator as $pageContent) {
    printf(
        "Url: %s, length: %d\n",
        $urlIterator->getIterationUrl(),
        strlen($pageContent)
    );
    if($i++ > 3) break; // to simulate invalid response
}
```
Further usage can be found [here](./examples/url-iterator.php).

## Json Collection Iterator

It allows you ti iterate over very simple REST collections.

Example:

```php
use Pilulka\Iterators\Http;
$urlIterator = new Http\UrlIterator('http://private-62aa2-pilulkaiterator.apiary-mock.com/items');
$jsonIterator = new Http\JsonCollectionIterator($urlIterator);
$i = 0;
echo "Url: {$jsonIterator->getUrlIterator()->getIterationUrl()}\n";
foreach ($jsonIterator as $item) {
    echo var_export($item) . "\n";
    if($i++ > 2) break;
}
```

## Email IMAP iterator

Allows you to iterate over mail inbox.

Example:

```php
use Pilulka\Iterators\Mail\ImapIterator;
$cfg = [
    'host' => '<hostname>', // e.g. {imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX
    'username' => '<username>', // e.g. jon.doe@example.com
    'password' => '<password>',
    'filter' => '<filter>', // See: http://php.net/manual/en/function.imap-search.php
];
$iterator = new ImapIterator(
    $cfg['host'],
    $cfg['username'],
    $cfg['password']
);
$iterator->setFilter($cfg['filter']); // e.g. FROM "john@example.com"
$i = 0;
foreach ($iterator as $mail) {
    /** @var \Pilulka\Iterators\Mail\Model\Mail $mail */
    foreach ($mail->getAttachments() as $attachment) {
        if($attachment->isAttachment()) {
            printf(
                "File name: %s\nName:%s\nContent:\n%s",
                $attachment->getFilename(),
                $attachment->getName(),
                $attachment->getAttachment()
            );
        }
    }
    if($i++ > 1) break;
}
```

## Release notes

This package fulfill our company needs - you should consider this as an 
 experimental.
 
We will be pleasured for any feedback or provided pull request.
 