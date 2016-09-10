<?php

namespace App\Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailNotFoundException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, "Email not found", null, [], 0);
    }
}