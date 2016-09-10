<?php

namespace App\Api\V1\Transformers;

use App\Models\Joke;
use League\Fractal\TransformerAbstract;

class JokeCollectionTransformer extends TransformerAbstract
{
    public function transform(Joke $joke)
    {
        return [
            'id' 	     => (int) $joke->id,
            'title'      => $joke->title,
            'content'    => $joke->content,
            'categories' => $joke->categories()->get()
        ];
    }
}