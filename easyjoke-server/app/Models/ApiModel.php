<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

abstract class ApiModel extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->fillable = $this->fillableConfig();

        parent::__construct($attributes);
    }

    /**
     * The attributes that are mass assignable.
     * @return array
     */
    abstract public function fillableConfig();

}