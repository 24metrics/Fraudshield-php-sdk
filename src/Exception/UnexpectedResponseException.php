<?php

namespace Fraudshield\Exception;


use Exception;

class UnexpectedResponseException extends Exception
{
    protected $headers;
    protected $info;
    protected $body;

    /**
     * UnexpectedResponseException constructor.
     * @param string $message
     * @param array $headers
     * @param array $info
     * @param string $body
     */
    public function __construct($message, $headers, $info, $body)
    {
        $this->message = $message;
        $this->headers = $headers;
        $this->info = $info;
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

}
