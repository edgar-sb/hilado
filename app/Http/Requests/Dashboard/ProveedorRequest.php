<?php

namespace App\Http\Requests\Dashboard;

use App\Entities\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
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
        if($this->method() == 'POST') {
            return [
                'nombre'       => 'required',
                'razon_social' => 'required',
                'rfc'          => 'required',
                'dias_credito' => 'nullable|integer|min:0|max:2147483647',
                'email'        => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users'),
                ],
                'emails'       => 'nullable|array',
                'emails.*'     => 'required|email|max:255',
            ];
        }
        elseif ($this->method() == 'PUT')
        {
            $user = User::findOrFail($this->user_id);
            return [
                'nombre'       => 'required',
                'razon_social' => 'required',
                'rfc'          => 'required',
                'dias_credito' => 'nullable|integer|min:0|max:2147483647',
                'email'        => [
                    'required',
                    'email',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'emails'       => 'nullable|array',
                'emails.*'     => 'required|email|max:255',
            ];
        }

        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'nombre'       => 'nombre',
            'razon_social' => 'razón social',
            'rfc'          => 'RFC',
            'email'        => 'email',
            'dias_credito' => 'días de crédito',
            'emails'       => 'emails adicionales',
            'emails.*'     => 'Email adicional',
        ];
    }
}
