<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class ApiRequest extends FormRequest
{
    /**
     * @var string Ability policy name
     */
    protected $ability;

}