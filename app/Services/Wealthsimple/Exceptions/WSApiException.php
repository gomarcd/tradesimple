<?php

namespace App\Services\Wealthsimple\Exceptions;

use Exception;

class WSApiException extends Exception
{
    public $response;
    public function __construct(string $message, int $code, $response = NULL) {
        parent::__construct($message, $code);
        $this->response = $response;
    }
}
