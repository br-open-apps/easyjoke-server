<?php

namespace App\Models;

/**
 * @property integer id
 * @property string name
 */
class Category extends ApiModel
{
    /**
     * The table category are stored in.
     *
     * @var string
     */
    protected $table = 'categories';

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     * @return array
     */
    public function fillableConfig()
    {
        return array_merge(
            config('easyjoke.category_fields')
        );
    }

    public function jokes() {
        return $this->belongsToMany(Joke::class, 'category_joke')
            ->withTimestamps();
    }
}
