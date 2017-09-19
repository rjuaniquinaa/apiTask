<?php

namespace App\Exceptions;


class ValidationException extends \RuntimeException
{
    protected $messages;

    public function __construct(array $messages)
    {
        $this->messages = array_values($messages);
    }

    public function getMessages()
    {
        return $this->messages;
    }
}