<?php

namespace App\Pilulka\Iterators\Http;

class JsonCollectionIterator implements \Iterator
{

    /** @var UrlIterator */
    private $urlIterator;
    private $collection = [];
    private $responseIsInvalid = false;
    private $key = 0;

    /**
     * @param UrlIterator $urlIterator
     */
    public function __construct(UrlIterator $urlIterator)
    {
        $this->urlIterator = $urlIterator;
    }


    public function current()
    {
        return $this->collection[$this->key()];
    }

    public function next()
    {
        ++$this->key;
    }

    public function key()
    {
        return $this->key;
    }

    public function valid()
    {
        if ($this->key() == count($this->collection)) {
            $this->loadUrlIteratorData();
        }
        if($this->responseIsInvalid) {
            return false;
        }
        if ($this->urlIterator->valid()) {
            return true;
        }
        return false;
    }

    private function loadUrlIteratorData()
    {
        $content = $this->urlIterator->current();
        $items = json_decode($content, true);
        $this->key = 0;
        $this->collection = [];
        if (is_array($items) && count($items)) {
            $this->collection = array_values($items);
            $this->urlIterator->next();
        }
        if(empty($items)) {
            $this->responseIsInvalid = true;
        }
    }

    public function rewind()
    {
        $this->key = 0;
        $this->collection = [];
        $this->urlIterator->rewind();
    }


}