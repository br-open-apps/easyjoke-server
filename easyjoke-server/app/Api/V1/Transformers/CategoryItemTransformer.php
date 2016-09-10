<?php

namespace App\Api\V1\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryItemTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id'    => (int) $category->id,
            'name'  => $category->name
        ];
    }
}