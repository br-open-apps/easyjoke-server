<?php

namespace App\Models;

/**
 * @property integer id
 * @property string title
 * @property string content
 */
class Joke extends ApiModel
{
    /**
     * The table joke are stored in.
     *
     * @var string
     */
    protected $table = 'jokes';

    protected $hidden = ['pivot', 'approved'];

    /**
     * The attributes that are mass assignable.
     * @return array
     */
    public function fillableConfig()
    {
        return array_merge(
            config('easyjoke.joke_fields'),
            config('easyjoke.joke_relations_fields')
        );
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_joke')
            ->withTimestamps();
    }
}
