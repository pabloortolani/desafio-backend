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
}
