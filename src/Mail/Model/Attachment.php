<?php

namespace Pilulka\Iterators\Mail\Model;

class Attachment
{

    private $isAttachment;
    private $filename;
    private $name;
    private $attachment;

    /**
     * Attachment constructor.
     * @param $isAttachment
     * @param $filename
     * @param $name
     * @param $attachment
     */
    public function __construct($isAttachment, $filename, $name, $attachment)
    {
        $this->isAttachment = $isAttachment;
        $this->filename = $filename;
        $this->name = $name;
        $this->attachment = $attachment;
    }

    /**
     * @return mixed
     */
    public function isAttachment()
    {
        return $this->isAttachment;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

}

