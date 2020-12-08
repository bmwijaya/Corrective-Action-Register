<?php

namespace App\Http\Requests;

use App\Models\Source;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSourceRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('source_create');
    }

    public function rules()
    {
        return [
            'source' => [
                'string',
                'nullable',
            ],
        ];
    }
}
