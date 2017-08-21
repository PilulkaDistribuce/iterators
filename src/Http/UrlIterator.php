<?php

namespace Pilulka\Iterators\Http;

class UrlIterator implements \Iterator
{

    private $urlMask;
    private $hasAuth = false;
    private $username;
    private $password;

    private $response;
    private $responseCode = 200;

    private $requestCount = 0;

    private $urlClosure;

    /**
     * UrlIterator constructor.
     * @param $urlMask
     */
    public function __construct($urlMask)
    {
        $this->urlMask = $urlMask;
    }

    public function setUrlClosure(\Closure $closure)
    {
        $this->urlClosure = $closure;
    }

    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->hasAuth = true;
    }


    public function current()
    {
        $this->loadUrlContent();
        return $this->response;
    }

    public function next()
    {
        ++$this->requestCount;
    }

    public function key()
    {
        return $this->requestCount;
    }

    public function valid()
    {
        return $this->responseCode == 200;
    }

    public function rewind()
    {
        $this->requestCount = 0;
    }

    public function getIterationUrl()
    {
        if(isset($this->urlClosure)) {
            return call_user_func_array($this->urlClosure, [$this->urlMask, $this->requestCount]);
        }
        return sprintf($this->urlMask, $this->requestCount);
    }

    private function loadUrlContent()
    {
        $ch = curl_init($this->getIterationUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if($this->hasAuth) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        }
        $this->response = curl_exec($ch);
        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }


}