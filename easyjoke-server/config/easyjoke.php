<?php


return [
    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    |
    |
    */
    'category_fields' => [
        'name'
    ],

    'category_relations_fields' => [
    ],

    'category_validation' => [
        'name' => 'required|min:5',
    ],

    /*
    |--------------------------------------------------------------------------
    | Joke
    |--------------------------------------------------------------------------
    |
    |
    */
    'joke_fields' => [
        'title',
        'content'
    ],

    'joke_relations_fields' => [
        'approved'
    ],

    'joke_validation' => [
        'title' => 'required|min:5',
        'content' => 'required|min:5',
        'categories' => 'required|array',
        'categories.*.id' => 'required|exists:categories'
    ],
];