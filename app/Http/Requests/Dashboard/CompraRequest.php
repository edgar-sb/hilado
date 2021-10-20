<?php

namespace App\Http\Requests\Dashboard;

use App\Entities\Compras\CompraEstatus;
use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
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
                'email' => 'required|email|max:255',
                'cve_doc' => 'required|string',
                'maniobras' => 'nullable|numeric|min:0|regex:/^[0-9]{0,}.[0-9]{0,2}$/i',
                'estadia' =>  'nullable|numeric|min:0|regex:/^[0-9]{0,}.[0-9]{0,2}$/i',
            ];
        } elseif ($this->method() == 'PUT') {
            $estatus = CompraEstatus::where('clave', $this->estatus)->first();
            if (!isset($estatus)) {
                $estatus = CompraEstatus::findOrFail($this->estatus);
            }
            if ($estatus->clave == "venta") {
                return  [
                    'maniobras' => 'nullable|numeric|min:0|regex:/^[0-9]{0,}.[0-9]{0,2}$/i',
                    'estadia' =>  'nullable|numeric|min:0|regex:/^[0-9]{0,}.[0-9]{0,2}$/i',
                ];
            }
            if ($estatus->clave == "validacion") {
                return  [
                    'comentarios' => 'nullable|string|required_if:rechazar,true',
                    'comentarios_parcial' => 'nullable|string',
                ];
            }
            if ($estatus->clave == "pago") {
                return  [
                    'fecha_pago' => 'required|date',
                ];
            }
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
            'email' => 'Correo electrónico del proveedor',
            'cve_doc' => 'clave de la orden de compra',
            'maniobras' => 'maniobras',
            'estadia' => 'estadía',
            'comentarios' => 'motivo de rechazo',
            'comentarios_parcial' => 'comentarios',
        ];
    }

    public function messages()
    {
        return [
            'regex' => 'El formato de :attribute es inválido (usar solo dos decimales).',
        ];
    }
}
