<?php

namespace App\Api\V1\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiController extends BaseController
{
    use Helpers;
}