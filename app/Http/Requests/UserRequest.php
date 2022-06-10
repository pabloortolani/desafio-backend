<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'document' => 'required|string|max:18',
            'email' => 'required|email',
            'type' => 'required|string|in:'.User::COMUM.','.User::LOJISTA.",".
                strtolower(User::COMUM).",".strtolower(User::LOJISTA),
            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Obrigatório informar o nome do usuário.',
            'name.string' => 'Nome do usuário inválido.',
            'document.required' => 'Obrigatório informar o CPF ou CNPJ do usuário.',
            'email.required' => 'Obrigatório informar o e-mail do usuário.',
            'email.email' => 'E-mail inválido.',
            'type.required' => 'Obrigatório informar o tipo do usuário.',
            'type.in' => 'Tipo do usuário inválido.',
            'password.required' => 'Obrigatório informar uma senha para o usuário.'
        ];
    }
}
