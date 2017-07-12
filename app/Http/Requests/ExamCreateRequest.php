<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamCreateRequest extends FormRequest
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
        return [
            'org_id' => 'required|int|min:1',
            'func_id' => 'required|int|min:1',
            'position_id' => 'required|int|min:1',
            'user_id' => 'required|int|min:1',
            'chief_id' => 'required|int|min:1',
            'count' => 'required|int|min:1',
        ];
    }
}
