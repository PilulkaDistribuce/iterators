<?php
require __DIR__ . '/../vendor/autoload.php';

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
