<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'wallet_payer' => 'required|integer',
            'wallet_payee' => 'required|integer',
            'value' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'wallet_payer.required' => 'Obrigatório informar o ID da carteira pagadora.',
            'wallet_payer.integer' => 'ID da carteira pagadora inválido.',
            'wallet_payee.required' => 'Obrigatório informar o ID da carteira recebedora.',
            'wallet_payee.integer' => 'ID da carteira recebedora inválido.',
            'value.required' => 'Obrigatório informar o valor da transferência.',
            'value.numeric' => 'Valor da transferência inválido'
        ];
    }
}
