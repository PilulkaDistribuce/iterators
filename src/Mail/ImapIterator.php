<?php

namespace Pilulka\Iterators\Mail;

use Pilulka\Iterators\Mail\Model\Attachment;
use Pilulka\Iterators\Mail\Model\Mail;

class ImapIterator implements \Iterator
{

    const SORT_ASC = 'asc';
    const SORT_DESC = 'desc';

    private $host;
    private $username;
    private $password;

    private $iterator;
    private $ids;
    private $connection;
    private $filter = 'ALL';
    private $sort = self::SORT_DESC;

    /**
     * ImapIterator constructor.
     * @param $host
     * @param $username
     * @param $password
     */
    public function __construct($host, $username, $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param mixed $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param $sort
     * @throws \InvalidArgumentException
     */
    public function setSort($sort)
    {
        if(!array_search($sort, [self::SORT_DESC, self::SORT_ASC])) {
            throw new \InvalidArgumentException(
                "Invalid sort type: `{$sort}`"
            );
        }
        $this->sort = $sort;
    }



    public function current()
    {
        $key = $this->iterator()->current();
        $header = imap_headerinfo($this->connection(), $key);
        return new Mail(
            $key,
            $header->fromaddress,
            $header->toaddress,
            $header->subject,
            $header->message_id,
            date('c', $header->udate),
            $this->getMailAttachments($key)
        );
    }

    public function next()
    {
        return $this->iterator()->next();
    }

    public function key()
    {
        return $this->iterator()->key();
    }

    public function valid()
    {
        return $this->iterator()->valid();
    }

    public function rewind()
    {
        return $this->iterator()->rewind();
    }

    private function connection()
    {
        if (!isset($this->connection)) {
            $this->connection = imap_open($this->host, $this->username, $this->password);
        }
        return $this->connection;
    }

    private function iterator()
    {
        if (!isset($this->iterator)) {
            $this->iterator = new \ArrayIterator((array)$this->ids());
        }
        return $this->iterator;
    }

    private function ids()
    {
        if (!isset($this->ids)) {
            $this->ids = imap_search(
                $this->connection(),
                $this->filter
            );
            if($this->sort == self::SORT_DESC) {
                rsort($this->ids);
            }
        }
        return $this->ids;
    }

    public function __destruct()
    {
        if ($this->connection) {
            imap_close($this->connection());
        }
    }

    /**
     * @param $key
     * @return array
     */
    private function getMailAttachments($key)
    {
        $structure = imap_fetchstructure($this->connection(), $key);
        if (isset($structure->parts) && count($structure->parts)) {
            for ($i = 0; $i < count($structure->parts); $i++) {
                $attachment = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachment['is_attachment'] = true;
                            $attachment['filename'] = $object->value;
                        }
                    }
                }

                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachment['is_attachment'] = true;
                            $attachment['name'] = $object->value;
                        }
                    }
                }

                if ($attachment['is_attachment']) {
                    $attachment['attachment'] = imap_fetchbody($this->connection(), $key, $i + 1);
                    if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachment['attachment'] = base64_decode($attachment['attachment']);
                    } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachment['attachment'] = quoted_printable_decode($attachment[$i]['attachment']);
                    }
                }

                yield new Attachment(
                    $attachment['is_attachment'],
                    $attachment['filename'],
                    $attachment['name'],
                    $attachment['attachment']
                );
            }
        }
    }



}

