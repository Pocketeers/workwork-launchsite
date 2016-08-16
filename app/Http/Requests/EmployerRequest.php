<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmployerRequest extends Request
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
            'business_name' => 'required',
            'business_category' => 'required',
            'business_contact' => 'required',
            'location' => 'required',
            'street' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'company_intro' => 'required',
        ];
    }
}