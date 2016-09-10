<?php

namespace App\Api\V1\Requests\Joke;

use App\Api\V1\Requests\ApiRequest;

class JokeRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return config('easyjoke.joke_validation');
    }

    /**
     * Overrides parent function to append custom validation.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        $validator->after(function ()
        {
            $this->afterRequest();
        });

        return $validator;
    }

    /**
     * Override categories to array of integer
     */
    function afterRequest()
    {
        if ($categories = $this->request->get('categories')) {
            $array_categories = [];
            foreach ($categories as $key => $value) {
                $array_categories[] = $value['id'];
            }
            $this->merge([
                "categories" => $array_categories
            ]);
        }
    }


}
