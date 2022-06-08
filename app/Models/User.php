<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    CONST COMUM = 'Comum';
    CONST LOJISTA = 'Lojista';
    CONST TYPES_USER_CAN_TRANSFER = [self::COMUM];

    protected $fillable = [
        'name',
        'email',
        'document',
        'type_id',
        'password'
    ];

    protected $hidden = [
        'password',
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    public function type()
    {
        return $this->belongsTo(UserTypes::class, 'type_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'id', 'user_id');
    }
}
