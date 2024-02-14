<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions;

class ApiException extends BaseException
{
    public function __construct(string $message, string $code = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->message = $message;
        $this->code = $code;
    }

    public static function fromResponse($response): ApiException
    {
        return new self($response['fout'][0]['omschrijving'], $response['fout'][0]['code']);
    }

    public static function isException($response): bool
    {
        return isset($response['fout']);
    }
}
