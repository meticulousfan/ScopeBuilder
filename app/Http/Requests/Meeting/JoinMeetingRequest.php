<?php

namespace App\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;

class JoinMeetingRequest extends FormRequest
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
			'meeting_id' => ['required'],
            'password' => ['nullable', 'min:2', 'max:64'],
            'full_name' => ['nullable'],
            'user_id' => ['required'],
            'client'=> ['required']
		];
    }

}