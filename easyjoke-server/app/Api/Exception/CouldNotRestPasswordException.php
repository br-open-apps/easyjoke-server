<?php

namespace App\Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CouldNotRestPasswordException extends HttpException
{
    public function __construct()
    {
        parent::__construct(500, "It is not possible to reset the password", null, [], 0);
    }
}