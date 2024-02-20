<?php

namespace DaadkrachtMarketing\DutchChamberOfCommerceApi\Exceptions;

use Illuminate\Http\Client\Response;
use Throwable;

class ApiHttpException extends BaseException
{
    public function __construct(string $message, int $code, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->message = $message;
        $this->code = $code;
    }

    public static function fromResponse(Response $response): ApiHttpException
    {
        return new self('An error occurred while communicating with the API', $response->status(), $response->toException());
    }

    public static function isException(Response $response): bool
    {
        return !($response->json()) && $response->status() >= 400 && $response->status() < 600;
    }
}
