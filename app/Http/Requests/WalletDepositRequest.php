<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletDepositRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'wallet_id' => 'required|integer',
            'value' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'wallet_id.required' => 'Obrigatório informar o ID da carteira',
            'wallet_id.integer' => 'ID da carteira inválido.',
            'value.required' => 'Obrigatório informar o valor do depósito.',
            'value.numeric' => 'Valor de depósito inválido'
        ];
    }
}
