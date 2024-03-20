<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\MenuPositionEnum;

class StoreMenuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menu_id' => 'nullable|exists:appearance_menus,id',
            'position' => 'required|numeric|in:'.implode(',', MenuPositionEnum::getValues()),
            'name'  => 'required',
            'url'   => 'required',
            'order' => 'required|numeric|min:0|max:999',
            'active'=> 'nullable|accepted',
            'template' => 'nullable|string|max:250'
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
