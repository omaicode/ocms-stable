<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBackend extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'app__name'      => 'required|alpha_dash|alpha_num',
            'app__language'  => 'required|in:en,vi',
            'app__timezone'  => 'required',
            'app__debug'     => 'nullable|in:on,off',
            'app__cache'     => 'nullable|in:on,off',
            'app__logo'      => 'nullable|file|image|max:3072',
            'app__favicon'   => 'nullable|file|image|max:3072',
            'app__notification_method' => 'required|in:email,sms'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
