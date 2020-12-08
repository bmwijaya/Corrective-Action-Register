<?php

namespace App\Http\Requests;

use App\Models\Corrective;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCorrectiveRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('corrective_create');
    }

    public function rules()
    {
        return [
            'finding_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'action'       => [
                'string',
                'nullable',
            ],
            'target_date'  => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
