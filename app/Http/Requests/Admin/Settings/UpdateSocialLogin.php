<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialLogin extends FormRequest
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
            'services__facebook__client_id' => 'nullable|string|max:100',
            'services__facebook__client_secret' => 'nullable|string|max:100',
            'services__google__client_id' => 'nullable|string|max:100',
            'services__google__client_secret' => 'nullable|string|max:100',
            'services__kakao__client_id' => 'nullable|string|max:100',
            'services__kakao__client_secret' => 'nullable|string|max:100',
            'services__naver__client_id' => 'nullable|string|max:100',
            'services__naver__client_secret' => 'nullable|string|max:100',
        ];
    }
}
