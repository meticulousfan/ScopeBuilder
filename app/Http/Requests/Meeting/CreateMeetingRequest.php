<?php

namespace App\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;

class CreateMeetingRequest extends FormRequest
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
            'transaction_id' => ['required'],
            'developer_id' => ['required'],
            'meeting_id' => [
                'required',
                //'exists:project,uuid'
            ],
            'duration' => ['required', 'integer', 'min:10'],
        ];
    }

}
