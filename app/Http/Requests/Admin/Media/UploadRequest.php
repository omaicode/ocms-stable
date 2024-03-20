<?php

namespace App\Http\Requests\Admin\Media;

use App\Http\Requests\BaseApiRequest;

class UploadRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'path' => 'required|string|max:255',
            'files' => 'required|array',
            'files.*' => 'required|file'
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
