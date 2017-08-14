<?php

namespace Tests\Pilulka\Iterators\Mail;

use Pilulka\Iterators\Mail\ImapIterator;
use Pilulka\Iterators\Mail\Model\Attachment;
use Pilulka\Iterators\Mail\Model\Mail;

class ImapIteratorTest extends \PHPUnit_Framework_TestCase
{

    public function testIterateOverInbox()
    {
        $iterated = false;
        $i = 0;
        foreach ($this->iterator() as $mail) {
            $iterated = true;
            $this->assertInstanceOf(Mail::class, $mail);
            foreach ($mail->getAttachments() as $attachment) {
                $this->assertInstanceOf(Attachment::class, $attachment);
            }
            if($i++ > 1) break;
        }
        $this->assertTrue($iterated);
    }

    private function iterator()
    {
        $cfg = require __DIR__ . '/../config.php';
        $iterator = new ImapIterator(
            $cfg['imap']['host'],
            $cfg['imap']['username'],
            $cfg['imap']['password']
        );
        $iterator->setFilter($cfg['imap']['filter']);
        return $iterator;
    }

}

