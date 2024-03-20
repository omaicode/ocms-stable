<?php
namespace App\Http\Requests\Admin\Ajax;

use App\Facades\Email;
use App\Http\Requests\BaseApiRequest;

class UpdateEmailTemplateRequest extends BaseApiRequest
{
    public function rules()
    {
        $templates = Email::all()->keys()->join(',');

        return [
            'template' => 'required|in:'.$templates,
            'content'  => 'required'
        ];
    }
}