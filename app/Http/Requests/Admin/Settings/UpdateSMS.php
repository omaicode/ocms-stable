<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSMS extends FormRequest
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
            'ppurio__base_url' => 'required|string|url',
            'ppurio__account' => 'required|string|max:250',
            'ppurio__access_key' => 'required|string|max:250',
            'ppurio__phone_from' => 'required|digits_between:10,20|max:20',
            'ppurio__phone_to' => 'required|digits_between:10,20|max:20',
            'ppurio__default_message' => 'required|string',
        ];
    }
}
