<?php

namespace App\Http\Requests\Dashboard;

use App\Entities\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
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
        if($this->rol == 'Administrador')
        {
          return [
              'nombre'       => 'required',
              'email'        => [
                  'required',
                  'email',
                  'string',
                  'max:255',
                  Rule::unique('users'),
              ],
              'password' => 'min:8',
              'password_confirmation' => 'required_with:password|same:password|min:8'
          ];

        }else
        {
          return [
              'nombre'       => 'required',
              'email'        => [
                  'required',
                  'email',
                  'string',
                  'max:255',
                  Rule::unique('users'),
              ],
          ];
        }
      }
      elseif ($this->method() == 'PUT')
      {
        $user = User::findOrFail($this->user_id);
        if(!empty($this->input('password')) && $this->rol == 'administrador')//si la contraseña no esta vacia
        {
          return [
              'nombre'       => 'required',
              'email'        => [
                'required',
                'email',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
              ],
              'password' => 'min:8',
              'password_confirmation' => 'required_with:password|same:password|min:8'
          ];
        }else {
          return [
              'nombre'       => 'required',
              'email'        => [
                  'required',
                  'email',
                  'string',
                  'max:255',
                  Rule::unique('users')->ignore($user->id),
              ],
          ];
        }

      }
    }
}
