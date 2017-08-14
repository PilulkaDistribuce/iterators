<?php

namespace Pilulka\Iterators\Mail\Model;

class Mail
{

    private $id;
    private $from;
    private $to;
    private $subject;
    private $messageId;
    private $date;
    private $attachments;

    /**
     * Mail constructor.
     * @param $id
     * @param $from
     * @param $to
     * @param $subject
     * @param $messageId
     * @param $attachments
     */
    public function __construct($id, $from, $to, $subject, $messageId, $date, $attachments)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->messageId = $messageId;
        $this->date = $date;
        $this->attachments = $attachments;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

}

