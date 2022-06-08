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
}
