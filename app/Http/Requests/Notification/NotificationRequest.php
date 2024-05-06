<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to entity this request.
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from_date'  => 'nullable|date_format:Y-m-d',
            'to_date'    => 'nullable|date_format:Y-m-d',
            'all'        => 'boolean|nullable',
            'search'     => 'string|nullable',
        ];
    }
}
